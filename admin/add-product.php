<?php
    session_start();
    include "logic/utility.php";
    autorizacija("login.php", "admin");

    include "config/conn.php";
    include "logic/functions.php";

    $proizvodjaci = dohvatiSve("proizvodjac");
    $platforme    = dohvatiSve("platforma");
    $zanrovi      = dohvatiSve("zanr");

    $proizvod = null;
    $old      = $_SESSION['form-old']    ?? null;
    $errors   = $_SESSION['form-errors'] ?? [];
    unset($_SESSION['form-old'], $_SESSION['form-errors']);

    $pageTitle = "Novi proizvod";
    include "includes/head.php";
    include "includes/header.php";
?>
    <div class="container">
        <h1 class="admin-heading"><em>Novi</em> proizvod</h1>
        <p class="admin-subtitle">Dodajte novu igru u ponudu.</p>

        <?php if (count($errors) > 0): ?>
            <div class="admin-alert admin-alert-warning">
                <ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <?php include "includes/product-form.php"; ?>
        </div>
    </div>
<?php include "includes/footer.php"; ?>
