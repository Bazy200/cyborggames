<?php

    session_start();
    include "utility.php";

    if (isset($_POST['btnLogin'])) {
        $email   = trim($_POST['tbEmail'] ?? "");
        $lozinka = $_POST['tbLozinka'] ?? "";

        include "../config/conn.php";
        include "functions.php";

        $ulogovanKorisnik = logovanje($email, md5($lozinka));

        if ($ulogovanKorisnik && $ulogovanKorisnik->uloga == "admin") {
            $_SESSION['korisnik'] = $ulogovanKorisnik;
            redirect("../index.php");
        } else if ($ulogovanKorisnik) {

            $_SESSION['greska-logovanje'] = "Nemate administratorska prava.";
            redirect("../login.php");
        } else {
            $_SESSION['greska-logovanje'] = "Pogresan email ili lozinka.";
            redirect("../login.php");
        }
    } else {
        redirect("../login.php");
    }
?>
