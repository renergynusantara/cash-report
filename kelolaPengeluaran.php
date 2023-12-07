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

    if (isset($_GET['filter_tgl'])) {
        $date = $_GET['filter_tgl'];
        $queryPengeluaran = "SELECT * FROM pengeluaran
                            JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                            WHERE tanggal = '$date' AND id_user = '$id'
                            ORDER BY tanggal DESC
                            ";
    } else {
        $date = date("Y-m-d");
        $queryPengeluaran = "SELECT * FROM pengeluaran
                            JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                            WHERE id_user = '$id'
                            ORDER BY tanggal DESC
                            ";
    }

    if (isset($_GET['search_query'])) {
        $searchQuery = $_GET['search_query'];
        $queryPengeluaran = "SELECT * FROM pengeluaran
                            JOIN kategori_pengeluaran ON pengeluaran.id_kategori = kategori_pengeluaran.id_kategori
                            WHERE (tanggal LIKE '%$searchQuery%' OR deskripsi LIKE '%$searchQuery%' OR
                                    nama_kategori LIKE '%$searchQuery%' OR jumlah LIKE '%$searchQuery%') AND
                                    id_user = '$id'
                            ORDER BY tanggal DESC
                            ";
    }

    $pengeluaran = query($queryPengeluaran);
    $kategoriPengeluaran = query("SELECT * FROM kategori_pengeluaran");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Dashboard | Renus Money Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
                        <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <div class="container mt-4 border rounded-4 shadow">
        <div class="row">
            <div class="col">
                <div class="m-3">
                    <form action="kelolaPengeluaran" method="GET">
                        <label for="filter" class="mb-2">Pilih tanggal</label>
                        <input type="date"x class="form-control" id="filter" name="filter_tgl" onchange="submit()">
                    </form>
                </div>
                <div class="m-3">
                    <form action="kelolaPengeluaran" method="GET" id="search-form">
                        <div class="input-group mb-3 w-25">
                            <input type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="search-bar" name="search_query">
                            <span class="input-group-text" id="search-bar" onclick="$('#search-form').submit()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="m-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        Tambah Data
                    </button>
                </div>
                <div class="p-3">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Deskripsi Pengeluaran</th>
                            <th scope="col">Kategori Pengeluaran</th>
                            <th scope="col">Jumlah Pengeluaran</th>
                            <th scope="col">Aksi</th>
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
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                data-tgl="<?= $p['tanggal'] ?>" data-deskripsi="<?= $p['deskripsi'] ?>"
                                                data-jumlah="<?= $p['jumlah'] ?>" data-id-pengeluaran="<?= $p['id_pengeluaran'] ?>">
                                            Edit
                                        </button>
                                        <form action="functions/hapusData" method="POST">
                                            <input type="hidden" name="nama_table" value="pengeluaran">
                                            <input type="hidden" name="id_pengeluaran" value="<?= $p['id_pengeluaran'] ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                $index++;
                                $totalPengeluaran += $p['jumlah'];
                            ?>
                            <?php endforeach; ?>
                            <?php if (count($pengeluaran) > 0) : ?>
                            <tr>
                                <td colspan="4">Total Pengeluaran</td>
                                <td> <?= str_replace(',', '.', number_format($totalPengeluaran)); ?> </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk tambah data -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTambahLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="functions/tambahData" method="POST">
                    <input type="hidden" name="nama_table" value="pengeluaran">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" value="<?= $date ?>" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control filter" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="id_kategori">
                                <?php foreach($kategoriPengeluaran as $kategori) : ?>
                                <option value="<?= $kategori['id_kategori'] ?>"> <?= $kategori['nama_kategori'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number"  class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambahPengeluaran" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal untuk edit data -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel">Edit Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="functions/editData" method="POST">
                    <input type="hidden" name="nama_table" value="pengeluaran">
                    <input type="hidden" name="id_pengeluaran">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control filter" id="deskripsi" name="deskripsi" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="id_kategori">
                                <?php foreach($kategoriPengeluaran as $kategori) : ?>
                                <option value="<?= $kategori['id_kategori'] ?>"> <?= $kategori['nama_kategori'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number"  class="form-control" id="jumlah" name="jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" name="editPengeluaran" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // JS untuk isi data modal edit
        $('#modalEdit').on('show.bs.modal', function(e) {
            var tgl = $(e.relatedTarget).data('tgl');
            var deskripsi = $(e.relatedTarget).data('deskripsi');
            var jumlah = $(e.relatedTarget).data('jumlah');
            var idPengeluaran = $(e.relatedTarget).data('id-pengeluaran');

            $(e.currentTarget).find('input[name="tanggal"]').val(tgl);
            $(e.currentTarget).find('input[name="deskripsi"]').val(deskripsi);
            $(e.currentTarget).find('input[name="jumlah"]').val(jumlah);
            $(e.currentTarget).find('input[name="id_pengeluaran"]').val(idPengeluaran);
        });
    </script>
</body>
</html>

<?php
    if(isset($_SESSION["success_msg"])){
        $msg = $_SESSION["success_msg"];
        echo "
            <script>
                swal('Success','$msg','success');
            </script>
        ";

        unset($_SESSION["success_msg"]);
    }

    if(isset($_SESSION["error_msg"])){
        $msg = $_SESSION["error_msg"];
        echo "
            <script>
                swal('Error','$msg','error');
            </script>
        ";

        unset($_SESSION["error_msg"]);
    }
?>