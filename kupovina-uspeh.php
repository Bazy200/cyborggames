<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $porudzbina = null;
    $stavke = [];
    if ($id > 0) {
        $iskaz = $conn->prepare("SELECT * FROM porudzbina WHERE id = :id");
        $iskaz->bindValue(":id", $id, PDO::PARAM_INT);
        $iskaz->execute();
        $porudzbina = $iskaz->fetch();

        if ($porudzbina) {
            $iskaz = $conn->prepare(
                "SELECT s.kolicina, s.cena, p.naziv
                 FROM stavka_porudzbine s
                 JOIN proizvod p ON s.proizvod_id = p.id
                 WHERE s.porudzbina_id = :id"
            );
            $iskaz->bindValue(":id", $id, PDO::PARAM_INT);
            $iskaz->execute();
            $stavke = $iskaz->fetchAll();
        }
    }

    $pageTitle = "Kupovina uspesna";
    require_once "includes/template/head.php";
?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($porudzbina): ?>
                    <div class="p-5 bg-white rounded shadow-sm text-center">
                        <h1 class="text-success mb-3"><i class="fa fa-check-circle"></i> Hvala na kupovini!</h1>
                        <p class="lead">Porudzbina <strong>#<?= (int)$porudzbina->id ?></strong> je uspesno primljena.</p>
                        <p>Kupac: <strong><?= e($porudzbina->ime) ?> <?= e($porudzbina->prezime) ?></strong>
                           &middot; <?= e($porudzbina->email) ?>
                           &middot; Placanje: <?= e($porudzbina->nacin_placanja) ?></p>

                        <table class="table mt-4">
                            <thead>
                                <tr><th>Igra</th><th>Kolicina</th><th>Cena</th><th>Ukupno</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stavke as $s): ?>
                                    <tr>
                                        <td><?= e($s->naziv) ?></td>
                                        <td><?= (int)$s->kolicina ?></td>
                                        <td><?= (int)$s->cena ?> RSD</td>
                                        <td><?= (int)$s->cena * (int)$s->kolicina ?> RSD</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr><th colspan="3" class="text-end">Ukupno:</th><th><?= (int)$porudzbina->ukupno ?> RSD</th></tr>
                            </tfoot>
                        </table>

                        <a href="shop.php" class="btn btn-danger mt-3">Nastavi kupovinu</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger text-center">Porudzbina nije pronadjena.</div>
                    <div class="text-center"><a href="shop.php" class="btn btn-danger">Nazad u prodavnicu</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        localStorage.removeItem("productsCart");
        document.querySelectorAll(".lblCartCount").forEach(function (el) { el.innerHTML = ""; });
    </script>

<?php
    require_once "includes/template/footer.php";
?>
