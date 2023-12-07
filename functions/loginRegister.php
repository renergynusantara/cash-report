<?php
    session_start();
    $db = mysqli_connect('localhost', 'u749483805_cash', 'Ramadani1234?', 'u749483805_cash');

    if (isset($_POST["login"])) {
        login($_POST);
    } else if (isset($_POST["register"])) {
        register($_POST);
    }

    function register($dataRegister)
    {
        global $db;

        $email = htmlspecialchars(stripcslashes($dataRegister['email']));
        $username = htmlspecialchars(stripcslashes($dataRegister['username']));
        $password = mysqli_real_escape_string($db, htmlspecialchars($dataRegister['password']));
        $konfirmasiPassword = mysqli_real_escape_string($db, htmlspecialchars($dataRegister['konfirmasi_password']));

        $cekUser = mysqli_query($db, "SELECT email, username FROM users WHERE email = '$email' OR username = '$username'");

        if (mysqli_num_rows($cekUser) > 0) {
            $_SESSION["register_error"] = "Email / username sudah terpakai!";
            header("Location: ../register");
        }

        if ($password != $passwordConfirm) {
            $_SESSION["register_error"] = "Password konfirmasi harus sama!";
            header("Location: ../register");
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        $sukses = mysqli_query($db, "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')");

        if ($sukses > 0) {
            $_SESSION["register_success"] = "Akun sudah berhasil didaftarkan!";
            header("Location: ../login");
        } else {
            $_SESSION["register_error"] = "Akun gagal didaftarkan!";
            header("Location: ../register");
        }

        return mysqli_affected_rows($db);
    }

    function login($dataLogin)
    {
        global $db;

        $email = $dataLogin['identifier'];
        $username = $dataLogin['identifier'];
        $password = $dataLogin['password'];

        $cekUser = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");

        if (mysqli_num_rows($cekUser) === 1) {
            $hasil = mysqli_fetch_assoc($cekUser);

            if (password_verify($password, $hasil["password"])) {
                if ($hasil['status'] == 'aktif') {
                    $_SESSION['user'] = $hasil['username'];
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $hasil['id_user'];

                    if ($hasil['role'] == 'admin') {
                        $_SESSION['role'] = 'admin';
                        header('Location: ../kelolaPengguna');
                    } elseif ($hasil['role'] == 'user') {
                        $_SESSION['role'] = 'user';
                        header('Location: ../dashboard');
                    }

                    if (isset($_POST['rememberme'])) {
                        setcookie('login', $hasil['username'], time() + 3600);
                        setcookie('role', $hasil['role'], time() + 3600);
                        setcookie('id', $hasil['id_user'], time() + 3600);
                        setcookie('key', hash('sha256', $hasil['username']), time() + 3600);
                    }
                } elseif ($hasil['status'] == 'tidak aktif') {
                    $_SESSION["login_error"] = "Akun anda dinonaktifkan oleh admin!";
                    header('Location: ../login');
                }
            } else {
                $_SESSION["login_error"] = "Username / password salah!";
                header('Location: ../login');
            }
        } else {
            $_SESSION["login_error"] = "Username / password salah!";
            header('Location: ../login');
        }

        return mysqli_affected_rows($db);
    }
?>