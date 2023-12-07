<?php 
    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');
    if (mysqli_connect_error() == true) {
        die('Gagal terhubung ke database');
        return false;
    } else {
        return true;
    }
    
    function query($query) {
        global $db;
        $result = mysqli_query($db, $query);
        $rows = [];
        while( $row = mysqli_fetch_assoc($result) ) {
            $rows[] = $row;
        }
        return $rows;
    }
?>