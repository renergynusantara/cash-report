<?php
    require 'functions/redirect.php';

    session_start();

    if ( isset($_SESSION["login"]) ) {
        // header("Location: dashboard");
        // exit();
        customRedirect("dashboard");
    } elseif(isset($_COOKIE['login'])) {
        // header("Location: dashboard");
        // exit();
        customRedirect("dashboard");
    } else {
        // header("Location: login");
        // exit();
        customRedirect("login");
    }
?>