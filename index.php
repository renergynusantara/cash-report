<?php
    session_start();

    if ( isset($_SESSION["login"]) ) {
        header("Location: dashboard.php");
        exit;
    } elseif(isset($_COOKIE['login'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php");
    }
?>