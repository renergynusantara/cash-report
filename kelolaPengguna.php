<?php
    require 'functions/query.php'; 
    session_start();
    
    if(isset($_COOKIE['login'])) {
        if ($_COOKIE['level'] == 'admin') {
            $_SESSION['login'] = true;
            $username = $_COOKIE['login'];
        } 
        
        elseif ($_COOKIE['level'] == 'user') {
            $_SESSION['login'] = true;
            header('Location: dashboard');
        }
    } 

    elseif ($_SESSION['role'] == 'admin') {
        $username = $_SESSION['user'];
    } 
    
    else {
        if ($_SESSION['role'] == 'user') {
            header('Location: dashboard');
            exit;
        }
    }

    if(empty($_SESSION['login'])) {
        header('Location: login');
        exit;
    } 

    $users = query("SELECT * FROM users WHERE role = 'user'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Kelola Pengguna | Renus Money Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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
                        <a class="nav-link active" aria-current="page" href="dashboard">Kelola Pengguna</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Kategori
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kelolaKategoriPengeluaran">Kategori Pengeluaran</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="kelolaKategoriPemasukan">Kategori Pemasukan</a></li>
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
                <div class="p-3">
                    <table class="table">
                        <thead>
                            <tr class="table-primary">
                            <th scope="col">No.</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1 ?>
                            <?php foreach($users as $user) : ?>
                            <tr>
                                <th scope="row"> <?= $index ?> </th>
                                <td> <?= $user['email'] ?> </td>
                                <td> <?= $user['username'] ?> </td>
                                <td> <?= $user['status'] ?> </td>
                                <td>
                                    <form action="functions/editUser" method="POST">
                                        <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                                        <?php if ($user['status'] == 'aktif') : ?>
                                        <button type="submit" name="nonaktifkanPengguna" class="btn btn-danger">Nonaktifkan</button>
                                        <?php elseif ($user['status'] == 'tidak aktif') : ?>
                                        <button type="submit" name="aktifkanPengguna" class="btn btn-primary">Aktifkan</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                            <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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