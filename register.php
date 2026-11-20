<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    if (isset($_SESSION['korisnik'])) {
        redirect("index.php");
    }

    $errors = $_SESSION['reg-errors'] ?? [];
    $old    = $_SESSION['reg-old']    ?? [];
    unset($_SESSION['reg-errors'], $_SESSION['reg-old']);

    $val = function ($k) use ($old) { return isset($old[$k]) ? $old[$k] : ""; };

    $pageTitle = "Register";
    require_once "includes/template/head.php";
?>

    <main class="container" style="min-height:60vh;">
        <div class="row justify-content-center pt-10">
            <div class="col-lg-6 col-md-8">
                <div class="card p-4 mt-5">
                    <div class="card-body">
                        <h1 class="text-center text-dark mb-4">Create an account</h1>

                        <?php if (count($errors) > 0): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= e($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="logic/register.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ime" class="form-label text-dark">First name</label>
                                    <input type="text" id="ime" name="ime" class="form-control"
                                           value="<?= e($val('ime')) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="prezime" class="form-label text-dark">Last name</label>
                                    <input type="text" id="prezime" name="prezime" class="form-control"
                                           value="<?= e($val('prezime')) ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-dark">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?= e($val('email')) ?>" placeholder="you@example.com" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="lozinka" class="form-label text-dark">Password</label>
                                    <input type="password" id="lozinka" name="lozinka" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="lozinka2" class="form-label text-dark">Confirm password</label>
                                    <input type="password" id="lozinka2" name="lozinka2" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" name="btnRegister" class="btn btn-danger w-100 mb-3">Register</button>
                        </form>

                        <p class="text-center text-dark m-0">
                            Already have an account? <a href="login.php">Login here</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
    require_once "includes/template/footer.php";
?>
