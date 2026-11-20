<?php

    session_start();
    require_once __DIR__ . "/../config/conn.php";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/utility.php";

    if (!isset($_POST['btnRegister'])) {
        redirect("../register.php");
    }

    $podaci = [
        'ime'      => trim($_POST['ime'] ?? ""),
        'prezime'  => trim($_POST['prezime'] ?? ""),
        'email'    => trim($_POST['email'] ?? ""),
        'lozinka'  => $_POST['lozinka'] ?? "",
        'lozinka2' => $_POST['lozinka2'] ?? "",
    ];

    $errors = validirajRegistraciju($podaci);

    if (count($errors) === 0 && emailPostoji($podaci['email'])) {
        $errors[] = "An account with this email already exists.";
    }

    if (count($errors) > 0) {
        $_SESSION['reg-errors'] = $errors;
        $_SESSION['reg-old'] = [
            'ime'     => $podaci['ime'],
            'prezime' => $podaci['prezime'],
            'email'   => $podaci['email'],
        ];
        redirect("../register.php");
    }

    try {
        registracija($podaci['ime'], $podaci['prezime'], $podaci['email'], $podaci['lozinka']);
        // Auto-login the new user.
        $_SESSION['korisnik'] = logovanje($podaci['email'], md5($podaci['lozinka']));
        redirect("../index.php");
    } catch (PDOException $e) {
        // Catches the UNIQUE(email) constraint as a safety net.
        $_SESSION['reg-errors'] = ["Registration failed. This email may already be in use."];
        $_SESSION['reg-old'] = [
            'ime'     => $podaci['ime'],
            'prezime' => $podaci['prezime'],
            'email'   => $podaci['email'],
        ];
        redirect("../register.php");
    }
?>
