<?php
# ./config.php

// Hata gösterimi (Canlı ortamda kapat)
error_reporting(E_ALL);
ini_set('display_errors', '1');


// Oturum başlatma (güvenli cookie ile)
session_set_cookie_params([
    'lifetime' => 0, // tarayıcı kapanınca silinsin
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

//SEO header html ayarları
define('SITE_NAME', 'Haysaf.com');
define('DESCRIPTION', 'Ticaretteki desteğiniz');
define('KEYWORDS', 'Online servisler, ticaret, destek');
define('CANONICAL', 'https://haysaf.com/');
define('OG_DESCRIPTION', 'Haysaf.com, ticaretteki desteğiniz. Online servislerimizle işinizi büyütün ve başarıya ulaşın.');
define('OG_IMAGE', '/assets/images/og.png');

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Veritabanı bağlantısı (MariaDB)
define('DB_HOST', 'mysql');
define('DB_NAME', 'haysaf');
define('DB_USER', 'appuser');
define('DB_PASS', 'apppass');
define('DB_CHARSET', 'utf8mb4');
// Genel ayarlar
define('APP_NAME', 'tercih.in');
define('APP_ENV', 'local');
define('APP_DEBUG', true);
// CSRF token oluştur
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

///REDIS AYARLARI
define('REDIS_HOST', 'redis');
define('REDIS_PORT', 6379);
define('REDIS_PASSWORD', null); // Şifre yoksa null bırakın
define('REDIS_LOGIN_ATTEMPT_INTERVAL', 600); // 10 dakika (600 saniye)
define('REDIS_MAX_LOGIN_ATTEMPTS', 5); // 5 başarısız giriş denemesi


//SONRADAN DEĞİŞTİRİLECEKLER
define( 'LGS_TARIHI', '2026-06-14');