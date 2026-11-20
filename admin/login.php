<?php
    session_start();
    include "logic/utility.php";

    if (isset($_SESSION['korisnik']) && $_SESSION['korisnik']->uloga == "admin") {
        redirect("index.php");
    }

    $pageTitle = "Prijava";
    include "includes/head.php";
?>
<body>
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row"><div class="col-12">
                <nav class="main-nav">
                    <a href="../index.php" class="logo"><img src="../assets/images/logo.png" alt="Cyborg"></a>
                </nav>
            </div></div>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <div class="login-wrap">
                <div class="login-card">
                    <div class="login-logo"><img src="../assets/images/logo.png" alt="Cyborg"></div>
                    <h1>Admin prijava</h1>
                    <p class="login-hint">Pristup kontrolnoj tabli CyborgGames</p>

                    <?php if (isset($_SESSION['greska-logovanje'])): ?>
                        <div class="admin-alert admin-alert-danger">
                            <?= e($_SESSION['greska-logovanje']) ?>
                        </div>
                        <?php unset($_SESSION['greska-logovanje']); ?>
                    <?php endif; ?>

                    <form action="logic/login.php" method="post" class="admin-form">
                        <div class="mb-3">
                            <label for="tbEmail" class="form-label">Email</label>
                            <input type="email" id="tbEmail" name="tbEmail" class="form-control admin-input" placeholder="admin@cyborg.rs" required>
                        </div>
                        <div class="mb-4">
                            <label for="tbLozinka" class="form-label">Lozinka</label>
                            <input type="password" id="tbLozinka" name="tbLozinka" class="form-control admin-input" required>
                        </div>
                        <button type="submit" name="btnLogin" class="btn-pink">Prijavi se</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php include "includes/footer.php"; ?>
