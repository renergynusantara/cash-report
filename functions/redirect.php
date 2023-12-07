<?php

function customRedirect($newURL) {
    header('Location: ' . $newURL . ".php");
    exit();
}

?>