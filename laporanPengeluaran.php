<?php
    require 'functions/query.php';
    session_start();
    
    if(isset($_COOKIE['login'])) {
        if ($_COOKIE['level'] == 'user') {
            $_SESSION['login'] = true;
            $username = $_COOKIE['login'];
            $id = $_COOKIE['id'];
        } 
        
        elseif ($_COOKIE['level'] == 'admin') {
            $_SESSION['login'] = true;
            header('Location: kelolaPengguna');
        }
    } 

    elseif ($_SESSION['role'] == 'user') {
        $username = $_SESSION['user'];
        $id = $_SESSION['id'];
    } 
    
    else {
        if ($_SESSION['role'] == 'admin') {
            header('Location: kelolaPengguna');
            exit;
        }
    }

    if(empty($_SESSION['login'])) {
        header('Location: login');
        exit;
    } 

    $kategoriPengeluaran = query("SELECT * FROM kategori_pengeluaran");
    $pengeluaran = [];

    if (isset($_GET["tampilkanLaporan"])) {
        if (($_GET['filter_kategori'] != NULL) && ($_GET['filter_tgl_awal'] != NULL) && ($_GET['filter_tgl_akhir'] != NULL)) {
            $idKategori = $_GET['filter_kategori'];
            $tglAwal = $_GET['filter_tgl_awal'];
            $tglAkhir = $_GET['filter_tgl_akhir'];
    
            $queryPengeluaran = "
                                    SELECT * FROM pengeluaran
                                    JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                                    WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                                    AND pengeluaran.id_kategori = '$idKategori' AND id_user = '$id'
                                ";
            $pengeluaran = query($queryPengeluaran);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Dashboard | Renus Money Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white shadow sticky-top">
        <div class="container-fluid">
            <img src="img/rns.png" width="35px">
            <div class="ms-2 navbar-brand fw-bold">Renus Money Manager</div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kelolaPengeluaran">Kelola Pengeluaran</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="kelolaPemasukan">Kelola Pemasukan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="laporanPengeluaran">Laporan Pengeluaran</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="laporanPemasukan">Laporan Pemasukan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" style="background: #73c2fb" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Welcome, <?= $username ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="functions/logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
                <form action="laporanPengeluaran" method="GET">
                    <div class="m-3">
                        <label for="filterKategori" class="mb-2">Pilih kategori</label>
                        <select class="form-select" id="filterKategori" name="filter_kategori">
                            <?php foreach($kategoriPengeluaran as $kategori) : ?>
                            <option value="<?= $kategori['id_kategori'] ?>"> <?= $kategori['nama_kategori'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="ms-3 my-1 d-inline-flex align-items-center">
                        <label for="filterTglAwal" class="me-2 text-nowrap">Pilih tanggal</label>
                        <input type="date" class="form-control" id="filterTglAwal" name="filter_tgl_awal">

                        <label for="filterTglAkhir" class="mx-2">sampai</label>
                        <input type="date" class="form-control" id="filterTglAkhir" name="filter_tgl_akhir">
                    </div>
                    <div class="m-3">
                        <button type="submit" name="tampilkanLaporan" class="btn btn-primary">
                            Tampilkan Laporan
                        </button>
                    </div>
                </form>
                <div class="m-3">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Deskripsi Pengeluaran</th>
                            <th scope="col">Kategori Pengeluaran</th>
                            <th scope="col">Jumlah Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $index = 1;
                                $totalPengeluaran = 0;
                            ?>
                            <?php foreach($pengeluaran as $p) : ?>
                            <tr>
                                <th scope="row"> <?= $index ?> </th>
                                <td> <?= $p['tanggal'] ?> </td>
                                <td> <?= $p['deskripsi'] ?> </td>
                                <td> <?= $p['nama_kategori'] ?> </td>
                                <td> <?= str_replace(',', '.', number_format($p['jumlah'])); ?> </td>
                            </tr>
                            <?php 
                                $totalPengeluaran += $p['jumlah'];
                                $index++;
                            ?>
                            <?php endforeach; ?>
                            <?php if (isset($_GET['tampilkanLaporan'])) : ?>
                            <tr>
                                <td colspan="4">Total Pengeluaran</td>
                                <td> <?= str_replace(',', '.', number_format($totalPengeluaran)); ?> </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($_GET['tampilkanLaporan'])) : ?>
                <div class="m-3">
                    <form action="functions/exportExcel" method="POST">
                        <input type="hidden" name="id_user" value="<?= $id ?>">
                        <input type="hidden" name="id_kategori" value="<?= $idKategori ?>">
                        <input type="hidden" name="tgl_awal" value="<?= $tglAwal ?>">
                        <input type="hidden" name="tgl_akhir" value="<?= $tglAkhir ?>">
                        <input type="hidden" name="tipe_laporan" value="Pengeluaran">
                        <button type="submit" name="exportExcel" class="btn btn-success">Export to Excel</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['tampilkanLaporan'])) : ?>
    <div class="container my-4 border rounded-4 shadow">
        <div class="row m-3 pb-3 border-bottom">
            <div class="col">
                <h3 class="text-center">
                    Ringkasan Laporan
                </h3>
            </div>
        </div>
        <div class="row m-3">
            <div class="col">
                <canvas id="bar-chart"></canvas>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script type="text/javascript">
        <?php
            $query = "SELECT tanggal, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                        WHERE tanggal >= '$tglAwal' AND tanggal <= '$tglAkhir'
                        AND id_kategori = '$idKategori' AND id_user = '$id'
                        GROUP BY tanggal
                    ";
            $dataRingkasan = query($query);
        ?>

        const data = [
            <?php foreach($dataRingkasan as $data) : ?>
                { tanggal: "<?= $data['tanggal'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
            <?php endforeach; ?>
        ];

        new Chart(
            document.getElementById('bar-chart'),
            {
                type: 'bar',
                data: {
                    labels: data.map(row => row.tanggal),
                    datasets: [
                        {
                            label: 'Total Pengeluaran',
                            data: data.map(row => row.totalPengeluaran),
                            backgroundColor: 'rgb(255, 99, 132)'
                        }
                    ]
                }
            }
        );
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>