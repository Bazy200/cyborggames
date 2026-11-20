<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    if (isset($_SESSION['korisnik'])) {
        redirect("index.php");
    }

    $greska = $_SESSION['greska-logovanje'] ?? null;
    $staryEmail = $_SESSION['login-email'] ?? "";
    unset($_SESSION['greska-logovanje'], $_SESSION['login-email']);

    $pageTitle = "Login";
    require_once "includes/template/head.php";
?>

    <main class="container" style="min-height:60vh;">
        <div class="row justify-content-center pt-10">
            <div class="col-lg-5 col-md-7">
                <div class="card p-4 mt-5">
                    <div class="card-body">
                        <h1 class="text-center text-dark mb-4">Login</h1>

                        <?php if ($greska): ?>
                            <div class="alert alert-danger"><?= e($greska) ?></div>
                        <?php endif; ?>

                        <form action="logic/login.php" method="post">
                            <div class="mb-3">
                                <label for="tbEmail" class="form-label text-dark">Email</label>
                                <input type="email" id="tbEmail" name="tbEmail" class="form-control"
                                       value="<?= e($staryEmail) ?>" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-4">
                                <label for="tbLozinka" class="form-label text-dark">Password</label>
                                <input type="password" id="tbLozinka" name="tbLozinka" class="form-control" required>
                            </div>
                            <button type="submit" name="btnLogin" class="btn btn-danger w-100 mb-3">Login</button>
                        </form>

                        <p class="text-center text-dark m-0">
                            Don't have an account? <a href="register.php">Register here</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
    require_once "includes/template/footer.php";
?>
