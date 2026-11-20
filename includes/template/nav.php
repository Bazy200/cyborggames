<?php

    $navStavke = dohvatiNav();
    $trenutna  = basename($_SERVER['PHP_SELF']);
?>

<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">

                    <a href="index.php" class="logo">
                        <img src="assets/images/logo.png" alt="logo">
                    </a>

                    <ul class="nav">
                        <?php foreach ($navStavke as $stavka): ?>
                            <li>
                                <a href="<?= e($stavka->href) ?>"
                                   class="<?= ($trenutna == $stavka->href) ? 'active' : '' ?>">
                                    <?= e($stavka->label) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>

                        <li>
                            <a href="checkout.php">
                                <i class="fa" style="font-size:24px">&#xf07a;</i>
                                <span class="badge badge-warning lblCartCount"> </span>
                            </a>
                        </li>

                        <?php if (isset($_SESSION['korisnik']) && $_SESSION['korisnik']->uloga == "admin"): ?>
                            <li><a href="admin/index.php">Admin</a></li>
                            <li>
                                <a href="admin/logic/logout.php">
                                    Odjava (<?= e($_SESSION['korisnik']->ime) ?>)
                                </a>
                            </li>
                        <?php else: ?>
                            <li><a href="admin/login.php">Prijava</a></li>
                        <?php endif; ?>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>

                </nav>
            </div>
        </div>
    </div>
</header>

