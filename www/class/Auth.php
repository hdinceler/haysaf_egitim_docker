<?php
declare(strict_types=1);

final class Auth
{
    private const REMEMBER_TTL      = 2592000; // 30 gün
    private const MAX_LOGIN_ATTEMPT = 5;
    private const LOCK_SECONDS      = 600;

    /* =====================================================
     * REGISTER
     * ===================================================== */
    /**
     * Yeni kullanıcı kaydı oluşturur
     */
    public function register(
        string $email,
        string $password,
        string $name = '',
        string $role = 'user'
    ): bool {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->exists($email)) {
            return false;
        }

        $id = DB::add('uyeler', [
            'name'      => $name,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'role'      => $role,
            'added_at'  => date('Y-m-d H:i:s')
        ]);

        return $id > 0;
    }

    /* =====================================================
     * LOGIN
     * ===================================================== */
    /**
     * Kullanıcı giriş işlemini yapar
     */
    public function login(
        string $email,
        string $password,
        bool $remember = false
    ): bool {
        if (!SECURITY::checkCsrfToken()) {
            return false;
        }

        $email = strtolower(trim($email));
        $key   = 'login:' . sha1($email . $this->clientIp());

        if ($this->isLocked($key)) {
            return false;
        }

        $user = DB::row(
            'SELECT id,password,role FROM uyeler WHERE email = ? LIMIT 1',
            [$email]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            $this->hit($key);
            return false;
        }

        $this->clearAttempts($key);

        session_regenerate_id(true);
        $_SESSION['uid']  = (int)$user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fp']   = $this->fingerprint();

        if ($remember) {
            $this->setRememberToken((int)$user['id']);
        }

        return true;
    }
     /* =====================================================
     * LOGIN REQUIRE / ROLE CHECK
     * ===================================================== */
    
    /**
     * Sayfaya erişim için login kontrolü yapar.
     * @param array|null $roles : izin verilen roller, null ise sadece login kontrolü
     * @return void
     */
    public static function requireLogin(?array $roles = null): void
    {
        session_start();

        $auth = new self();

        if (!$auth->isLogged()) {
            http_response_code(401);
            exit(json_encode(['success' => false, 'message' => 'Yetkisiz erişim']));
        }

        // Eğer roller verilmişse kontrol et
        if ($roles !== null) {
            $userRole = $_SESSION['role'] ?? null;
            if (!in_array($userRole, $roles, true)) {
                http_response_code(403);
                exit(json_encode(['success' => false, 'message' => 'Yetkisiz rol']));
            }
        }
    }
    /**
     * Kullanıcıyı cookie üzerinden otomatik login eder
     */
    public function autoLogin(): bool
    {
        if (empty($_COOKIE['remember'])) {
            return false;
        }

        $hash = hash('sha256', $_COOKIE['remember']);

        $user = DB::row(
            'SELECT id, role
             FROM uyeler
             WHERE remember_token = ?
               AND remember_expire > NOW()
             LIMIT 1',
            [$hash]
        );

        if (!$user) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['uid']  = (int)$user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fp']   = $this->fingerprint();

        return true;
    }

    /* =====================================================
     * SESSION / LOGIN STATUS
     * ===================================================== */
    /**
     * Kullanıcının oturumunun geçerli olup olmadığını kontrol eder
     */
    public function check(): bool
    {
        return isset($_SESSION['uid'], $_SESSION['fp'])
            && hash_equals(
                $_SESSION['fp']['ua'],
                $this->fingerprint()['ua']
            );
    }

    /**
     * Kullanıcı login ise true döner, değilse false
     */
    public function isLogged(): bool
    {
        return $this->check();
    }

    /**
     * Aktif kullanıcı id’sini döner
     */
    public function id(): ?int
    {
        return $_SESSION['uid'] ?? null;
    }

    /**
     * Kullanıcıyı logout eder
     */
    public function logout(): void
    {
        if (!empty($_COOKIE['remember'])) {
            DB::exec(
                'UPDATE uyeler
                 SET remember_token = NULL,
                     remember_expire = NULL
                 WHERE remember_token = ?',
                [hash('sha256', $_COOKIE['remember'])]
            );
        }

        $_SESSION = [];
        session_destroy();

        setcookie('remember', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    /* =====================================================
     * HELPERS / INTERNAL
     * ===================================================== */
    /**
     * Email’in sistemde var olup olmadığını kontrol eder
     */
    private function exists(string $email): bool
    {
        return DB::value(
            'SELECT 1 FROM uyeler WHERE email = ? LIMIT 1',
            [$email]
        ) !== null;
    }

    /**
     * Remember-me token oluşturur ve cookie’ye yazar
     */
    private function setRememberToken(int $userId): void
    {
        $raw  = bin2hex(random_bytes(32));
        $hash = hash('sha256', $raw);

        DB::exec(
            'UPDATE uyeler
             SET remember_token = ?,
                 remember_expire = ?
             WHERE id = ?',
            [
                $hash,
                date('Y-m-d H:i:s', time() + self::REMEMBER_TTL),
                $userId
            ]
        );

        setcookie('remember', $raw, [
            'expires'  => time() + self::REMEMBER_TTL,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    /**
     * Kullanıcının browser fingerprint’ini oluşturur
     */
    private function fingerprint(): array
    {
        return [
            'ua' => hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? '')
        ];
    }

    /**
     * Brute-force için Redis sayacı artırır
     */
    private function hit(string $key): void
    {
        $count = Redis::incr($key);
        if ($count === 1) {
            Redis::set($key, 1, self::LOCK_SECONDS);
        }
    }

    /**
     * Kullanıcının login kilitli olup olmadığını kontrol eder
     */
    private function isLocked(string $key): bool
    {
        return (int)Redis::get($key) >= self::MAX_LOGIN_ATTEMPT;
    }

    /**
     * Brute-force sayacını sıfırlar
     */
    private function clearAttempts(string $key): void
    {
        Redis::del($key);
    }

    /**
     * Kullanıcının IP adresini alır
     */
    private function clientIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }
}