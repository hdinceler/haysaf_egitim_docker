<?php
declare(strict_types=1);
require_once __DIR__ . '/../autoload.php';

// JSON response header
header('Content-Type: application/json');

// POST verilerini al
$input = json_decode(file_get_contents('php://input'), true);
$email = strtolower(trim($input['email'] ?? ''));
$password = $input['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email ve şifre gerekli']);
    exit;
}

// Brute-force / rate limit kontrolü
$RedisClient = new RedisClient();
SECURITY::checkLoginAttempts($RedisClient, $email);

// Kullanıcıyı DB'den al
$user = DB::row(
    "SELECT id, password FROM uyeler WHERE email = ?",
    [$email]
);

// Şifreyi kontrol et
if (!$user || !password_verify($password, $user['password'])) {
    // Başarısız deneme
    SECURITY::clearLoginAttempts($RedisClient, $email); // istersen burayı hit ile değiştir
    echo json_encode(['success' => false, 'message' => 'Hatalı giriş']);
    exit;
}

// Başarılı login
SECURITY::clearLoginAttempts($RedisClient, $email);

// Session başlat ve login state
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['logged_in'] = true;
$_SESSION['login_at'] = time();

// Başarılı yanıt
echo json_encode(['success' => true, 'message' => 'Giriş başarılı']);