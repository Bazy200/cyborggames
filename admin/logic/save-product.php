<?php

    session_start();
    include "utility.php";
    autorizacija("../../login.php", "admin");

    if (!isset($_POST['btnSave'])) {
        redirect("../products.php");
    }

    include "../config/conn.php";
    include "functions.php";
    include "validation.php";

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $podaci = [
        'naziv'          => trim($_POST['naziv'] ?? ""),
        'proizvodjac_id' => $_POST['proizvodjac_id'] ?? 0,
        'platforma_id'   => $_POST['platforma_id'] ?? 0,
        'zanr_id'        => $_POST['zanr_id'] ?? 0,
        'slika_src'      => trim($_POST['slika_src'] ?? ""),
        'slika_alt'      => trim($_POST['slika_alt'] ?? ""),
        'cena_stara'     => trim($_POST['cena_stara'] ?? ""),
        'cena_nova'      => trim($_POST['cena_nova'] ?? ""),
        'new_arrival'    => isset($_POST['new_arrival']) ? 1 : 0,
    ];

    $errors = validirajProizvod($podaci);

    if (count($errors) > 0) {

        $_SESSION['form-errors'] = $errors;
        $_SESSION['form-old']    = $podaci;
        if ($id > 0) {
            redirect("../edit-product.php?id=" . $id);
        }
        redirect("../add-product.php");
    }

    try {
        if ($id > 0) {
            izmeniProizvod($id, $podaci);
            $_SESSION['poruka-uspeh'] = "Product updated successfully.";
        } else {
            upisProizvoda($podaci);
            $_SESSION['poruka-uspeh'] = "Product added successfully.";
        }
    } catch (PDOException $e) {
        $_SESSION['poruka-greska'] = "Database error: " . $e->getMessage();
    }

    redirect("../products.php");
?>
