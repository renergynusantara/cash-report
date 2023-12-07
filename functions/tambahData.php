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
    $idUser = $_SESSION['id'];
    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if ($table == "pengeluaran") {
        $query = "INSERT INTO pengeluaran VALUES ('', '$tanggal', '$deskripsi', '$idKategori', '$jumlah', '$idUser')";
    } elseif ($table == "pemasukan") {
        $query = "INSERT INTO pemasukan VALUES ('', '$tanggal', '$deskripsi', '$idKategori', '$jumlah', '$idUser')";
    }
    
    if (mysqli_query($db, $query)) {
        $_SESSION["success_msg"] = "Data berhasil ditambahkan!";
    } else {
        $_SESSION["error_msg"] = "Data tidak berhasil ditambahkan!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaPemasukan");
    }
?>