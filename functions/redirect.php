<?php

function customRedirect($url) {
    header('Location: ' . $url);
    exit();
}

?>