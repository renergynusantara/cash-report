<?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        header('Location: ../kelolaPengeluaran');
        exit;
    }

    session_start();

    $tanggal = htmlspecialchars($_POST["tanggal"]);
    $deskripsi = htmlspecialchars($_POST["deskripsi"]);
    $idKategori = htmlspecialchars($_POST["id_kategori"]);
    $jumlah = htmlspecialchars($_POST["jumlah"]);
    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if ($table == "pengeluaran") {
        $idPengeluaran = $_POST['id_pengeluaran'];
        $query = "
                    UPDATE pengeluaran 
                    SET tanggal = '$tanggal', deskripsi = '$deskripsi' , id_kategori = '$idKategori' , jumlah = '$jumlah'
                    WHERE id_pengeluaran = '$idPengeluaran'
                ";
    } elseif ($table == "pemasukan") {
        $idPemasukan = $_POST['id_pemasukan'];
        $query = "
                    UPDATE pemasukan 
                    SET tanggal = '$tanggal', deskripsi = '$deskripsi' , id_kategori = '$idKategori' , jumlah = '$jumlah'
                    WHERE id_pemasukan = '$idPemasukan'
                ";
    }
    
    if (mysqli_query($db, $query)) {
        $_SESSION["success_msg"] = "Data berhasil diperbarui!";
    } else {
        $_SESSION["error_msg"] = "Data tidak berhasil diperbarui!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaPemasukan");
    }
?>