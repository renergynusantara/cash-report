<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        header('Location: ../kelolaPengeluaran');
        exit;
    }

    session_start();

    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if ($table == "pengeluaran") {
        $idPengeluaran = $_POST['id_pengeluaran'];
        $query = "DELETE FROM pengeluaran WHERE id_pengeluaran = '$idPengeluaran'";
    } elseif ($table == "pemasukan") {
        $idPemasukan = $_POST['id_pemasukan'];
        $query = "DELETE FROM pemasukan WHERE id_pemasukan = '$idPemasukan'";
    }
    
    if (mysqli_query($db, $query)) {
        $_SESSION["success_msg"] = "Data pengeluaran berhasil dihapus!";
    } else {
        $_SESSION["error_msg"] = "Data pengeluaran tidak berhasil dihapus!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaPemasukan");
    }
?>