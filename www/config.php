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
define('SITE_NAME', 'Tercih.in');
define('DESCRIPTION', 'Dersleri bizimle ilerlet');
define('KEYWORDS', 'soru çöz, test çöz,puan hesapla,sıralama ölç,tercih yap');
define('CANONICAL', 'https://www.tercih.in/');
define('OG_DESCRIPTION', 'Tercih.in ile derslerinizde kolay ilerleyin');
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

//SONRADAN DEĞİŞTİRİLECEKLER
define( 'LGS_TARIHI', '2026-06-14');