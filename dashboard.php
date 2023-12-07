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

    $tglAwalMinggu = (date('D') != 'Mon') ? date('Y-m-d', strtotime('last Monday')) : date('Y-m-d');
    $tglAkhirMinggu = (date('D') != 'Sun') ? date('Y-m-d', strtotime('next Sunday')) : date('Y-m-d');

    $queryPengeluaran = "SELECT tanggal, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                            WHERE tanggal >= '$tglAwalMinggu' AND tanggal <= '$tglAkhirMinggu' AND id_user = '$id'
                            GROUP BY tanggal
                        ";
    $queryPemasukan = "SELECT tanggal, SUM(jumlah) AS total_pemasukan FROM pemasukan
                        WHERE tanggal >= '$tglAwalMinggu' AND tanggal <= '$tglAkhirMinggu' AND id_user = '$id'
                        GROUP BY tanggal
                    ";

    $ringkasanPengeluaranMingguan = query($queryPengeluaran);
    $ringkasanPemasukanMingguan = query($queryPemasukan);

    $tglAwalBulan = date('Y-m-01');
    $tglAkhirBulan = date('Y-m-t');

    $queryPengeluaran = "SELECT nama_kategori, SUM(jumlah) AS total_pengeluaran FROM pengeluaran
                            JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                            WHERE tanggal >= '$tglAwalBulan' AND tanggal <= '$tglAkhirBulan' AND id_user = '$id'
                            GROUP BY nama_kategori
                        ";
    $queryPemasukan = "SELECT nama_kategori, SUM(jumlah) AS total_pemasukan FROM pemasukan
                        JOIN kategori_pemasukan ON pemasukan.id_kategori = kategori_pemasukan.id_kategori
                        WHERE tanggal >= '$tglAwalBulan' AND tanggal <= '$tglAkhirBulan' AND id_user = '$id'
                        GROUP BY nama_kategori
                    ";

    $ringkasanPengeluaranBulanan = query($queryPengeluaran);
    $ringkasanPemasukanBulanan = query($queryPemasukan);
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
                        <a class="nav-link active" aria-current="page" href="dashboard">Dashboard</a>
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
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <div class="container my-4">
        <div class="row">
            <div class="col border rounded-4 shadow me-3">
                <h3 class="text-center m-3 pb-3 border-bottom">
                    Ringkasan Pengeluaran Minggu Ini
                </h3>
                <?php if (count($ringkasanPengeluaranMingguan) > 0) : ?>
                <canvas id="bar-chart-pengeluaran" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di minggu ini.</div>
                <?php endif; ?>
            </div>
            <div class="col border rounded-4 shadow ms-3">
                <h3 class="text-center m-3 pb-3 border-bottom">
                    Ringkasan Pemasukan Minggu Ini
                </h3>
                <?php if (count($ringkasanPemasukanMingguan) > 0) : ?>
                <canvas id="bar-chart-pemasukan" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di minggu ini.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col border rounded-4 shadow me-3">
                <h3 class="text-center m-3 pb-3 border-bottom">
                    Ringkasan Pengeluaran Bulan Ini
                </h3>
                <?php if (count($ringkasanPengeluaranBulanan) > 0) : ?>
                <canvas id="pie-chart-pengeluaran" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di bulan ini.</div>
                <?php endif; ?>
            </div>
            <div class="col border rounded-4 shadow ms-3">
                <h3 class="text-center m-3 pb-3 border-bottom">
                    Ringkasan Pemasukan Bulan Ini
                </h3>
                <?php if (count($ringkasanPemasukanBulanan) > 0) : ?>
                <canvas id="pie-chart-pemasukan" class="mb-3"></canvas>
                <?php else : ?>
                <div class="text-center mb-3">Belum ada pengeluaran di bulan ini.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var data = [
            <?php foreach($ringkasanPengeluaranMingguan as $data) : ?>
                { tanggal: "<?= $data['tanggal'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
            <?php endforeach; ?>
        ];

        barChartPengeluaran = new Chart(
            document.getElementById('bar-chart-pengeluaran'),
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

        data = [
            <?php foreach($ringkasanPemasukanMingguan as $data) : ?>
                { tanggal: "<?= $data['tanggal'] ?>", totalPemasukan: <?= $data['total_pemasukan'] ?> },
            <?php endforeach; ?>
        ];

        barChartPemasukan = new Chart(
            document.getElementById('bar-chart-pemasukan'),
            {
                type: 'bar',
                data: {
                    labels: data.map(row => row.tanggal),
                    datasets: [
                        {
                            label: 'Total Pemasukan',
                            data: data.map(row => row.totalPemasukan),
                            backgroundColor: 'rgb(255, 205, 86)'
                        }
                    ]                    
                }
            }
        );
    </script>
    <script type="text/javascript">
        var data = [
            <?php foreach($ringkasanPengeluaranBulanan as $data) : ?>
                { nama_kategori: "<?= $data['nama_kategori'] ?>", totalPengeluaran: <?= $data['total_pengeluaran'] ?> },
            <?php endforeach; ?>
        ];

        pieChartPengeluaran = new Chart(
            document.getElementById('pie-chart-pengeluaran'),
            {
                type: 'pie',
                data: {
                    labels: data.map(row => row.nama_kategori),
                    datasets: [
                        {
                            label: 'Total Pengeluaran',
                            data: data.map(row => row.totalPengeluaran),
                            backgroundColor: function(context){
                                var colors = [
                                    'rgb(255, 99, 132)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 205, 86)',
                                    'rgb(75, 192, 192)',
                                    'rgb(54, 162, 235)',
                                    'rgb(153, 102, 255)',
                                    'rgb(201, 203, 207)'
                                ];

                                return colors[context.dataIndex % 7];
                            }
                        }
                    ]
                }
            }
        );

        var data = [
            <?php foreach($ringkasanPemasukanBulanan as $data) : ?>
                { nama_kategori: "<?= $data['nama_kategori'] ?>", totalPemasukan: <?= $data['total_pemasukan'] ?> },
            <?php endforeach; ?>
        ];

        pieChartPemasukan = new Chart(
            document.getElementById('pie-chart-pemasukan'),
            {
                type: 'pie',
                data: {
                    labels: data.map(row => row.nama_kategori),
                    datasets: [
                        {
                            label: 'Total Pemasukan',
                            data: data.map(row => row.totalPemasukan),
                            backgroundColor: function(context){
                                var colors = [
                                    'rgb(153, 102, 255)',
                                    'rgb(54, 162, 235)',
                                    'rgb(75, 192, 192)',
                                    'rgb(255, 205, 86)',
                                    'rgb(255, 159, 64)',
                                    'rgb(255, 99, 132)'
                                ];

                                return colors[context.dataIndex % 7];
                            }
                        }
                    ]
                }
            }
        );
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>