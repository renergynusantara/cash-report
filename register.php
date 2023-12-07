<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/rns.png">
    <title>Register | Renus Money Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
    <div class="vh-100 vw-100 d-flex align-items-center justify-content-center">
        <div class="w-50 h-100 p-5 d-flex flex-column justify-content-center align-items-center">
            <div class="w-75 border rounded-4 shadow">
                <h3 class="p-3 m-0 rounded-top-4 text-center" style="background: #73c2fb">
                Renus Money Manager
                </h3>
                <div class="p-3">
                    <form action="functions/loginRegister" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" class="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="konfirmasi-password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="konfirmasi-password" name="konfirmasi_password" class="password" required>
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <a href="login" class="me-4">Sudah punya akun?</a>
                            <button type="submit" name="register" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-50 h-100">
            <img src="img/panel.jpg" class="img-fluid h-100">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

<?php
    session_start();

    if ( isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
        $id = $_COOKIE['id'];
        $key = $_COOKIE['key'];

        global $db;
        $result = mysqli_query($db, "SELECT username FROM users WHERE id_user = $id");
        $row = mysqli_fetch_assoc($result);

        if ( $key === hash('sha256', $row['username']) ) {
            $_SESSION['login'] = true;
        }
    }

    if ( isset($_SESSION["login"])) {
        ($_SESSION["role"] == 'user') ? header("Location: dashboard") : header("Location: administrator");
        exit;
    } elseif(isset($_COOKIE['login'])) {
        ($_COOKIE["role"] == 'user') ? header("Location: dashboard") : header("Location: administrator");
        exit;
    }

    if(isset($_SESSION["register_error"])){
        $msg = $_SESSION["register_error"];
        echo "
            <script>
                swal('Error','$msg','error');
            </script>
        ";
    }

    session_unset();
?>