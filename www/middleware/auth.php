<?php

$auth = new Auth();

// Oturum yoksa ama remember cookie varsa → auto login dene
if (!$auth->check()) {
    $auth->autoLogin();
}

// // Hâlâ login değilse → login sayfasına at
// if (!$auth->check()) {
//     header('Location: login');
//     exit;
// }
