<?php

    session_start();
    require_once __DIR__ . "/../config/conn.php";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/utility.php";

    if (!isset($_POST['btnLogin'])) {
        redirect("../login.php");
    }

    $email   = trim($_POST['tbEmail'] ?? "");
    $lozinka = $_POST['tbLozinka'] ?? "";

    $korisnik = logovanje($email, md5($lozinka));

    if ($korisnik) {
        $_SESSION['korisnik'] = $korisnik;
        redirect("../index.php");
    } else {
        $_SESSION['greska-logovanje'] = "Wrong email or password.";
        $_SESSION['login-email'] = $email;
        redirect("../login.php");
    }
?>
