<?php

 debug('post', $_POST);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid method');
}

// CSRF kontrolü
if (!SECURITY::checkCsrfToken()) {
    http_response_code(403);
    exit('CSRF validation failed');
}

// POST verilerini sanitize et
$post = SECURITY::sanitize_post($_POST);

// Gerekli alanların kontrolü
if (empty($post['email']) || empty($post['password1']) || empty($post['password2'])) {
    http_response_code(400);
    exit('Missing required fields');
}

// Email sanitize & validate
$email = SECURITY::sanitize_email($post['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email');
}

// Parola kontrolü
$p1 = $post['password1'];
$p2 = $post['password2'];
if ($p1 !== $p2) {
    http_response_code(400);
    exit('Passwords do not match');
}
if (strlen($p1) < 6) {
    http_response_code(400);
    exit('Password must be at least 6 characters');
}

// Auth nesnesi
$auth = new Auth();

// Register işlemi
$success = $auth->register($email, $p1, $post['name'] ?? '');

if (!$success) {
    http_response_code(409);
    exit('Email already registered or registration failed');
}

http_response_code(201);
echo 'Registration successful';