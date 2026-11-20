<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    $pageTitle = "Korpa";
    require_once "includes/template/head.php";
?>

    <?php if (!empty($_SESSION['kupovina-greske'])): ?>
        <div class="container mt-4">
            <div class="alert alert-danger">
                <strong>Kupovina nije uspela:</strong>
                <ul class="mb-0">
                    <?php foreach ($_SESSION['kupovina-greske'] as $greska): ?>
                        <li><?= e($greska) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php unset($_SESSION['kupovina-greske']); ?>
    <?php endif; ?>

    <div class="ads-grid py-sm-5 py-4">
        <div class="container" id="content"></div>
    </div>

<?php
    require_once "includes/template/footer.php";
?>
