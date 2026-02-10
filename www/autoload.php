<?php
declare(strict_types=1);

// -------------------------------------------------
// CONFIG
// -------------------------------------------------
require_once __DIR__ . '/config.php';

// -------------------------------------------------
// SESSION (sıra KESİNLİKLE korunur)
// -------------------------------------------------
session_start(); // sırayı bozma

// -------------------------------------------------
// AUTOLOAD
// -------------------------------------------------
spl_autoload_register(function (string $class) {
    $file = __DIR__ . '/class/' . $class . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
$auth=new Auth();
///----------------------------------------
/// Tüm Middleware ler
require_once __DIR__ . '/middleware/auth.php';

// -------------------------------------------------
// GLOBAL INPUT MIDDLEWARE
// -------------------------------------------------

// POST sanitize
if (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && !empty($_POST)
    && class_exists('SECURITY')
) {
    $_POST = SECURITY::sanitize_post($_POST);
}

// GET sanitize
if (
    !empty($_GET)
    && class_exists('SECURITY')
) {
    $_GET = SECURITY::sanitize_get($_GET);
}

// -------------------------------------------------
// DEBUG HELPER
// -------------------------------------------------
function debug(string $header, $obj, bool $stop = false): void
{
    
        echo '<pre style="background:black;padding:10px;border:1px solid #ccc;">';
        echo '<h3>' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . ':</h3>';
        var_dump($obj);
        echo '</pre>';
   

    if ($stop) {
        exit;
    }
}
// session_destroy();
// -------------------------------------------------
// HELPERS
// -------------------------------------------------
require_once __DIR__ . '/helpers/minify.php';
