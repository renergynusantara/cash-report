<?php

function customRedirect($newURL) {
    header('Location: ' . $newURL);
    exit();
}

?>