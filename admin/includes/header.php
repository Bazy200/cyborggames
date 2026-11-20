<body>

    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">

                        <a href="index.php" class="logo">
                            <img src="../assets/images/logo.png" alt="Cyborg">
                        </a>

                        <ul class="nav admin-nav">
                            <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Dashboard</a></li>
                            <li><a href="products.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['products.php','add-product.php','edit-product.php']) ? 'active' : '' ?>">Proizvodi</a></li>
                            <li><a href="../index.php">Sajt</a></li>
                            <?php if (isset($_SESSION['korisnik'])): ?>
                                <li>
                                    <a href="logic/logout.php" class="admin-logout">
                                        <i class="fa fa-sign-out"></i> Odjava
                                        (<?= e($_SESSION['korisnik']->ime) ?>)
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <a class='menu-trigger'><span>Menu</span></a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-main">
