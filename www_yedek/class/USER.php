<?php
declare(strict_types=1);

final class User {
    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    // Kullanıcı kaydı
    public static function register(string $name, string $email, string $password, string $role = 'user'): bool {
        if (self::exists($email)) return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);

        DB::create('users', [
            'name' => htmlspecialchars(trim($name), ENT_QUOTES, 'UTF-8'),
            'email' => strtolower(trim($email)),
            'password' => $hash,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    // Login
    public static function login(string $email, string $password, bool $remember = false): bool {
        $user = DB::read('users', ['email' => strtolower(trim($email))]);
        if (!$user) return false;

        $user = $user[0];
        if (!password_verify($password, $user['password'])) return false;

        // Oturumu başlat
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['isLogged'] = true;

        // "Beni hatırla" aktifse güvenli cookie oluştur
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + 60 * 60 * 24 * 30; // 30 gün

            setcookie('remember_token', $token, [
                'expires' => $expires,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            DB::update('users', ['remember_token' => $token, 'remember_expire' => date('Y-m-d H:i:s', $expires)], ['id' => $user['id']]);
        }

        return true;
    }

    // "Beni hatırla" ile otomatik login
    public static function autoLogin(): bool {
        if (!isset($_COOKIE['remember_token'])) return false;

        $user = DB::read('users', ['remember_token' => $_COOKIE['remember_token']]);
        if (!$user) return false;

        $user = $user[0];
        if (strtotime($user['remember_expire']) < time()) return false;

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['isLogged'] = true;

        return true;
    }

    // Çıkış yap
    public static function logout(): void {
        if (isset($_SESSION)) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }

        setcookie('remember_token', '', time() - 3600, '/');
    }

    // Giriş kontrolü
    public static function isLogged(): bool {
        return isset($_SESSION['isLogged']) && $_SESSION['isLogged'] === true;
    }

    // CSRF token üret ve doğrula
    public static function csrfToken(string $action): string {
        if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = [];
        $token = bin2hex(random_bytes(16));
        $_SESSION['csrf'][$action] = $token;
        return $token;
    }

    public static function verifyCsrf(string $action, string $token): bool {
        return isset($_SESSION['csrf'][$action]) && hash_equals($_SESSION['csrf'][$action], $token);
    }

    // XSS koruması için güvenli output
    public static function e(string $text): string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    // Kullanıcı var mı
    public static function exists(string $email): bool {
        $user = DB::read('users', ['email' => strtolower(trim($email))]);
        return !empty($user);
    }

    // Kullanıcı bilgisi
    public static function current(): ?array {
        if (!self::isLogged()) return null;
        return DB::read('users', ['id' => $_SESSION['user_id']])[0] ?? null;
    }
}
