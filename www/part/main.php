<?php 
 var_dump($auth);

    if(isset($_GET["job"]) && strlen($_GET["job"])):
        require_once "./modul/". $_GET["job"] ."/index.php";
    else: 
        require_once "./modul/giris/index.php";
    endif; 
 
?>