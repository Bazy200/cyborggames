<?php

    session_start();
    include "utility.php";
    autorizacija("../login.php", "admin");

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        redirect("../products.php");
    }

    include "../config/conn.php";
    include "functions.php";

    try {
        obrisiProizvod($id);
        $_SESSION['poruka-uspeh'] = "Proizvod je obrisan.";
    } catch (PDOException $e) {
        $_SESSION['poruka-greska'] = "Brisanje nije uspelo: " . $e->getMessage();
    }

    redirect("../products.php");
?>
