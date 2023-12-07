<?php
    require 'functions/redirect.php';

    session_start();

    if ( isset($_SESSION["login"]) ) {
        header("Location: dashboard");
        exit();
    } elseif(isset($_COOKIE['login'])) {
        header("Location: dashboard");
        exit();
    } else {
        header("Location: login");
        exit();
    }
?>