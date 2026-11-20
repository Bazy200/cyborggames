<?php

    session_start();
    require_once __DIR__ . "/../config/conn.php";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/utility.php";

    if (!isset($_POST['btnKupi'])) {
        redirect("../checkout.php");
    }

    $kupac = [
        'ime'            => trim($_POST['ime'] ?? ""),
        'prezime'        => trim($_POST['prezime'] ?? ""),
        'email'          => trim($_POST['email'] ?? ""),
        'nacin_placanja' => $_POST['nacin_placanja'] ?? "",

        'korisnik_id'    => isset($_SESSION['korisnik']) ? $_SESSION['korisnik']->id : null,
    ];

    $korpa = json_decode($_POST['korpa'] ?? "[]", true);
    if (!is_array($korpa)) {
        $korpa = [];
    }

    $rezultat = kreirajPorudzbinu($kupac, $korpa);

    if ($rezultat['success']) {
        redirect("../kupovina-uspeh.php?id=" . $rezultat['id']);
    } else {
        $_SESSION['kupovina-greske'] = $rezultat['errors'];
        redirect("../checkout.php");
    }
?>
