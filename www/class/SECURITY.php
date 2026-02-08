<?php
declare(strict_types=1);

final class SECURITY {
    private const SECRET_KEY = 'supersecretkey';
    private const RATE_LIMIT = 100; // 5 saniyede max 100 istek
    private const RATE_FILE  = __DIR__ . '/rate_limit.tmp';

    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    // ---------- HTTP Güvenlik Başlıkları ----------
    public static function setHeaders(): void {
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

    // ---------- Rate Limit ----------
    public static function checkRateLimit(): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $now = time();
        $rates = [];

        if (file_exists(self::RATE_FILE)) {
            $rates = @unserialize(file_get_contents(self::RATE_FILE)) ?: [];
        }

        // Eski kayıtları temizle (5 saniye öncesine kadar tut)
        foreach ($rates as $key => $timestamps) {
            $rates[$key] = array_filter($timestamps, fn($t) => $t > $now - 5);
            if (empty($rates[$key])) unset($rates[$key]);
        }

        $rates[$ip][] = $now;

        if (count($rates[$ip]) > self::RATE_LIMIT) {
            http_response_code(429);
            exit(json_encode(['error' => 'Too Many Requests']));
        }

        file_put_contents(self::RATE_FILE, serialize($rates), LOCK_EX);
    }

    // ---------- Girdi Temizleme ----------
private static function normalize(array $input, bool $emptyToNull): array
{
    $out = [];

    foreach ($input as $k => $v) {

        if (is_array($v)) {
            $out[$k] = self::normalize($v, $emptyToNull);
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


public static function sanitize_post(array $post): array
{
    return self::normalize($post, true);
}


public static function sanitize_get(array $get): array
{
    return self::normalize($get, false);
}

// ---------- Genel Sanitization (DB / POST / GET ortak) ----------
public static function sanitize(array $input, bool $emptyToNull = true): array
{
    $out = [];

    foreach ($input as $key => $value) {

        // anahtar güvenliği
        $key = is_string($key)
            ? preg_replace('/[^a-zA-Z0-9_]/', '', $key)
            : $key;

        if (is_array($value)) {
            $out[$key] = self::sanitize($value, $emptyToNull);
            continue;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($emptyToNull && $value === '') {
                $out[$key] = null;
                continue;
            }

            // XSS-safe ama veri bozmayan
            $out[$key] = htmlspecialchars(
                $value,
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8',
                false
            );
            continue;
        }

        // int, float, bool, null olduğu gibi
        $out[$key] = $value;
    }

    return $out;
}

    // ---------- Basit JWT benzeri Token ----------
    public static function generateToken(int $userId, string $role, int $expiry = 3600): string {
        $payload = ['userId' => $userId, 'role' => $role, 'exp' => time() + $expiry];
        $payloadEncoded = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', $payloadEncoded, self::SECRET_KEY);
        return $payloadEncoded . '.' . $signature;
    }

    public static function validateToken(string $token): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 2) return null;

        [$payloadEncoded, $signature] = $parts;
        $expected = hash_hmac('sha256', $payloadEncoded, self::SECRET_KEY);
        if (!hash_equals($expected, $signature)) return null;

        $payload = json_decode(base64_decode($payloadEncoded), true);
        if (!isset($payload['exp']) || $payload['exp'] < time()) return null;
        return $payload;
    }

    // ---------- Yetki kontrolü ----------
    public static function requireRole(string $role, array $user): void {
        if (($user['role'] ?? '') !== $role) {
            http_response_code(403);
            exit(json_encode(['error' => 'Forbidden']));
        }
    }
}
