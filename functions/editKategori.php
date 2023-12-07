<?php
    session_start();

    if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SESSION['role'] == 'user')) {
        header('Location: ../restricted');
        exit;
    }

    $idKategori = $_POST["id_kategori"];
    $namaKategori = htmlspecialchars($_POST["nama_kategori"]);
    $table = htmlspecialchars($_POST["nama_table"]);

    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if ($table == "pengeluaran") {
        $query = "
                    UPDATE kategori_pengeluaran 
                    SET nama_kategori = '$namaKategori'
                    WHERE id_kategori = '$idKategori'
                ";
    } elseif ($table == "pemasukan") {
        $query = "
                    UPDATE kategori_pemasukan
                    SET nama_kategori = '$namaKategori'
                    WHERE id_kategori = '$idKategori'
                ";
    }
    
    if (mysqli_query($db, $query)) {
        $_SESSION["success_msg"] = "Kategori berhasil diperbarui!";
    } else {
        $_SESSION["error_msg"] = "Kategori tidak berhasil diperbarui!";
    }

    if ($table == "pengeluaran") {
        header("Location: ../kelolaKategoriPengeluaran");
    } elseif ($table == "pemasukan") {
        header("Location: ../kelolaKategoriPemasukan");
    }
?>