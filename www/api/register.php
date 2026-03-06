<?php
declare(strict_types=1);
require_once __DIR__ . '/../autoload.php';

header('Content-Type: application/json');

// POST (JSON) verisini al
$input = json_decode(file_get_contents('php://input'), true);

$email     = strtolower(trim($input['email'] ?? ''));
$password1 = $input['password1'] ?? '';
$password2 = $input['password2'] ?? '';

// 1️⃣ Zorunlu alan kontrolü
if (!$email || !$password1 || !$password2) {
    echo json_encode([
        'success' => false,
        'message' => 'Email ve şifre alanları zorunludur'
    ]);
    exit;
}

// 2️⃣ Şifreler eşleşiyor mu?
if ($password1 !== $password2) {
    echo json_encode([
        'success' => false,
        'message' => 'Şifreler uyuşmuyor'
    ]);
    exit;
}

// 3️⃣ Basit şifre politikası (opsiyonel ama önerilir)
if (strlen($password1) < 8) {
    echo json_encode([
        'success' => false,
        'message' => 'Şifre en az 8 karakter olmalıdır'
    ]);
    exit;
}

// 4️⃣ Register işlemi
$auth = new Auth();

$ok = $auth->register(
    $email,
    $password1
);

// 5️⃣ Sonuç
if (!$ok) {
    echo json_encode([
        'success' => false,
        'message' => 'Kayıt başarısız (email kullanılıyor olabilir)'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Kayıt başarılı'
]);