<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| SECURITY Class – İçindekiler
|--------------------------------------------------------------------------
|
| HEADERS
| - setHeaders()          : Güvenlik HTTP header’larını ayarlar
|
| CSRF
| - generateCsrfToken()  : CSRF token üretir
| - checkCsrfToken()     : CSRF doğrulaması yapar
|
| RATE LIMIT
| - checkRateLimit()     : Dosya tabanlı IP rate-limit kontrolü
|
| SANITIZE
| - sanitize()           : Input temizleme (escape YOK)
| - sanitize_post()      : POST verisi için sanitize
| - sanitize_get()       : GET verisi için sanitize
|
*/

final class SECURITY
{
    /**
     * Dahili gizli anahtar
     * @var string
     */
    private const SECRET_KEY = 'supersecretkey';

    /**
     * IP başına maksimum istek sayısı
     * @var int
     */
    private const RATE_LIMIT = 100;

    /**
     * Rate-limit geçici dosya yolu
     * @var string
     */
    private const RATE_FILE = __DIR__ . '/rate_limit.tmp';

    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    /**
     * Temel güvenlik HTTP header’larını ayarlar.
     *
     * @return void
     */
    public static function setHeaders(): void
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header(
            "Content-Security-Policy:
            default-src 'self';
            script-src 'self';
            style-src 'self';
            img-src 'self' data:;
            object-src 'none';
            base-uri 'self';
            frame-ancestors 'self'"
        );
    }

    /**
     * CSRF token üretir ve session’da saklar.
     *
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF token doğrulaması yapar.
     * Sadece POST isteklerinde kontrol edilir.
     *
     * @return bool
     */
    public static function checkCsrfToken(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }

        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            return false;
        }

        unset($_SESSION['csrf_token']);
        return true;
    }
        /**
     * Login brute-force kontrolü (Redis)
     *
     * @param RedisClient $redis
     * @param string $email
     * @return void
     */
    public static function checkLoginAttempts(
            RedisClient $redis,
            string $email
        ): void {
            $ipKey    = 'login:ip:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
            $userKey  = 'login:user:' . strtolower($email);

            $ipAttempts   = $redis->incr($ipKey);
            $userAttempts = $redis->incr($userKey);

            if ($ipAttempts === 1) {
                $redis->expire($ipKey, REDIS_LOGIN_ATTEMPT_INTERVAL);   // 10 dk
            }
            if ($userAttempts === 1) {
                $redis->expire($userKey, REDIS_LOGIN_ATTEMPT_INTERVAL); // 10 dk
            }

            if ($ipAttempts > 50 || $userAttempts > REDIS_MAX_LOGIN_ATTEMPTS) {
                http_response_code(429);
                exit('Çok fazla giriş denemesi, 10 dk bekleyin');
            }
        }
    public static function clearLoginAttempts(
        RedisClient $redis,
        string $email
    ): void {
        $redis->delete('login:ip:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $redis->delete('login:user:' . strtolower($email));
    }
    /**
     * Dosya tabanlı IP rate-limit kontrolü yapar.
     * Limit aşılırsa 429 döner ve script sonlandırılır.
     *
     * @return void
     */
    public static function checkRateLimit(): void
    {
        $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $now  = time();
        $rates = file_exists(self::RATE_FILE)
            ? @unserialize(file_get_contents(self::RATE_FILE)) ?: []
            : [];

        foreach ($rates as $k => $times) {
            $rates[$k] = array_filter($times, fn ($t) => $t > $now - 5);
            if (!$rates[$k]) {
                unset($rates[$k]);
            }
        }

        $rates[$ip][] = $now;

        if (count($rates[$ip]) > self::RATE_LIMIT) {
            http_response_code(429);
            exit('Too Many Requests');
        }

        file_put_contents(self::RATE_FILE, serialize($rates), LOCK_EX);
    }

    /**
     * Input verisini temizler.
     * - Escape yapmaz
     * - Boş stringleri opsiyonel olarak null’a çevirir
     *
     * @param array $input
     * @param bool  $emptyToNull
     * @return array
     */
    public static function sanitize(array $input, bool $emptyToNull = true): array
    {
        $out = [];

        foreach ($input as $k => $v) {
            if (is_array($v)) {
                $out[$k] = self::sanitize($v, $emptyToNull);
                continue;
            }

            if (is_string($v)) {
                $v = trim($v);
                $out[$k] = ($emptyToNull && $v === '') ? null : $v;
                continue;
            }

            $out[$k] = $v;
        }

        return $out;
    }
    /** email sanitize */
    public static function sanitize_email(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    /**
     * POST verisi için sanitize wrapper.
     *
     * @param array $post
     * @return array
     */
    public static function sanitize_post(array $post): array
    {
        return self::sanitize($post, true);
    }

    /**
     * GET verisi için sanitize wrapper.
     *
     * @param array $get
     * @return array
     */
    public static function sanitize_get(array $get): array
    {
        return self::sanitize($get, false);
    }
}