<?php
    session_start();

    if ( isset($_SESSION["login"]) ) {
        header("Location: dashboard.php", true, 301);
        exit;
    } elseif(isset($_COOKIE['login'])) {
        header("Location: dashboard.php", true, 301);
        exit;
    } else {
        header("Location: login.php", true, 301);
    }
?>