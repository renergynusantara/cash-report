<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SESSION['role'] == 'user')) {
        header('Location: ../restricted');
        exit;
    }

    $idUser = $_POST["id_user"];
    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if (isset($_POST['nonaktifkanPengguna'])) {
        nonaktifkanPengguna($idUser);
    } else if (isset($_POST['aktifkanPengguna'])) {
        aktifkanPengguna($idUser);
    }
    
    function nonaktifkanPengguna($idUser){
        global $db;

        $query = "
                UPDATE users 
                SET status = 'tidak aktif'
                WHERE id_user = '$idUser'
            ";
    
        if (mysqli_query($db, $query)) {
            $_SESSION["success_msg"] = "Pengguna berhasil dinonaktifkan!";
        } else {
            $_SESSION["error_msg"] = "Pengguna tidak berhasil dinonaktifkan!";
        }

        header("Location: ../kelolaPengguna");
    }

    function aktifkanPengguna($idUser){
        global $db;

        $query = "
                UPDATE users 
                SET status = 'aktif'
                WHERE id_user = '$idUser'
            ";
    
        if (mysqli_query($db, $query)) {
            $_SESSION["success_msg"] = "Pengguna berhasil diaktifkan!";
        } else {
            $_SESSION["error_msg"] = "Pengguna tidak berhasil diaktifkan!";
        }

        header("Location: ../kelolaPengguna");
    }
?>