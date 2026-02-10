<?php 
final class Auth {

    public function register(string $name, string $email, string $password, string $role = 'user'): bool {
        if ($this->exists($email)) return false;

        DB::add('uyeler', [
            'name' => trim($name),
            'email' => strtolower(trim($email)),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'added' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function login(string $email, string $password, bool $remember = false): bool {
        $user = DB::read('uyeler', ['email' => strtolower(trim($email))])[0] ?? null;
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['role'] = $user['role'];

        if ($remember) {
            $raw = bin2hex(random_bytes(32));
            $hash = hash('sha256', $raw);

            setcookie('remember', $raw, [
                'expires' => time() + 2592000,
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Strict'
            ]);

            DB::update('uyeler', [
                'remember_token' => $hash,
                'remember_expire' => date('Y-m-d H:i:s', time() + 2592000)
            ], ['id' => $user['id']]);
        }

        return true;
    }

    public function autoLogin(): bool {
        if (empty($_COOKIE['remember'])) return false;

        $hash = hash('sha256', $_COOKIE['remember']);
        $user = DB::read('uyeler', ['remember_token' => $hash])[0] ?? null;

        if (!$user || strtotime($user['remember_expire']) < time()) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['uid'] = (int)$user['id'];
        $_SESSION['role'] = $user['role'];

        return true;
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        setcookie('remember', '', time() - 3600, '/');
    }

    public function check(): bool {
        return isset($_SESSION['uid']);
    }

    public function id(): ?int {
        return $_SESSION['uid'] ?? null;
    }

    private function exists(string $email): bool {
        return !empty(DB::read('uyeler', ['email' => strtolower($email)]));
    }
}
