<?php
    session_start();
    include "logic/utility.php";
    autorizacija("../login.php", "admin");

    include "config/conn.php";
    include "logic/functions.php";

    $brProizvoda   = count(dohvatiProizvode());
    $brProizvodjaca = count(dohvatiSve("proizvodjac"));
    $brPlatformi   = count(dohvatiSve("platforma"));
    $brZanrova     = count(dohvatiSve("zanr"));

    $pageTitle = "Dashboard";
    include "includes/head.php";
    include "includes/header.php";
?>
    <div class="container">
        <h1 class="admin-heading"><em>Dash</em>board</h1>
        <p class="admin-subtitle">Welcome, <?= e($_SESSION['korisnik']->ime) ?> <?= e($_SESSION['korisnik']->prezime) ?>.</p>

        <?php if (isset($_SESSION['poruka-uspeh'])): ?>
            <div class="admin-alert admin-alert-success"><?= e($_SESSION['poruka-uspeh']) ?></div>
            <?php unset($_SESSION['poruka-uspeh']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['poruka-greska'])): ?>
            <div class="admin-alert admin-alert-danger"><?= e($_SESSION['poruka-greska']) ?></div>
            <?php unset($_SESSION['poruka-greska']); ?>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-ico"><i class="fa fa-gamepad"></i></div>
                <div class="stat-num"><?= $brProizvoda ?></div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-card">
                <div class="stat-ico"><i class="fa fa-building"></i></div>
                <div class="stat-num"><?= $brProizvodjaca ?></div>
                <div class="stat-label">Publishers</div>
            </div>
            <div class="stat-card">
                <div class="stat-ico"><i class="fa fa-desktop"></i></div>
                <div class="stat-num"><?= $brPlatformi ?></div>
                <div class="stat-label">Platforms</div>
            </div>
            <div class="stat-card">
                <div class="stat-ico"><i class="fa fa-tags"></i></div>
                <div class="stat-num"><?= $brZanrova ?></div>
                <div class="stat-label">Genres</div>
            </div>
        </div>

        <div class="admin-card" style="margin-top:26px;">
            <h4>Quick actions</h4>
            <a href="products.php" class="btn-outline-pink">View products</a>
            <a href="add-product.php" class="btn-pink">+ New product</a>
        </div>
    </div>
<?php include "includes/footer.php"; ?>
