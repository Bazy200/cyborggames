<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    $filteri = [
        'zanr'        => $_GET['zanr']        ?? "",
        'proizvodjac' => $_GET['proizvodjac'] ?? "",
        'platforma'   => $_GET['platforma']   ?? "",
    ];
    $sort = $_GET['sort'] ?? "naziv-asc";

    $proizvodi = dohvatiProizvode($filteri, $sort);

    $zanrovi      = dohvatiSve("zanr");
    $proizvodjaci = dohvatiSve("proizvodjac");
    $platforme    = dohvatiSve("platforma");

    $opcijeSort = [
        'naziv-asc'   => "Name A-Z",
        'naziv-desc'  => "Name Z-A",
        'cena-asc'    => "Price ASC",
        'cena-desc'   => "Price DESC",
        'new-arrival' => "New arrivals",
    ];

    $pageTitle = "Shop";
    require_once "includes/template/head.php";
?>

    <main class="container mt-2">
        <h1 class="text-center m-15">Games</h1>

        <div class="row pt-10">

            <div class="col-lg-3">
                <form method="get" action="shop.php" class="p-3 filter-box">
                    <div class="form-group mb-3">
                        <label class="form-label text-light">Sortiranje</label>
                        <select name="sort" class="form-select">
                            <?php foreach ($opcijeSort as $vr => $tekst): ?>
                                <option value="<?= e($vr) ?>" <?= ($sort == $vr) ? 'selected' : '' ?>><?= e($tekst) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-light">Zanr</label>
                        <select name="zanr" class="form-select">
                            <option value="">Svi</option>
                            <?php foreach ($zanrovi as $z): ?>
                                <option value="<?= (int)$z->id ?>" <?= ($filteri['zanr'] == $z->id) ? 'selected' : '' ?>><?= e($z->naziv) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-light">Proizvodjac</label>
                        <select name="proizvodjac" class="form-select">
                            <option value="">Svi</option>
                            <?php foreach ($proizvodjaci as $pr): ?>
                                <option value="<?= (int)$pr->id ?>" <?= ($filteri['proizvodjac'] == $pr->id) ? 'selected' : '' ?>><?= e($pr->naziv) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-light">Platforma</label>
                        <select name="platforma" class="form-select">
                            <option value="">Sve</option>
                            <?php foreach ($platforme as $pl): ?>
                                <option value="<?= (int)$pl->id ?>" <?= ($filteri['platforma'] == $pl->id) ? 'selected' : '' ?>><?= e($pl->naziv) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 mb-2">Primeni</button>
                    <a href="shop.php" class="btn btn-outline-light w-100">Ponisti</a>
                </form>
            </div>

            <div class="col-lg-9">
                <?php if (count($proizvodi) == 0): ?>
                    <div class="alert alert-danger" role="alert">Nema igara za izabrane filtere.</div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($proizvodi as $p): ?>
                            <div class="col-auto">
                                <div class="card m-3" style="width: 15rem;">
                                    <img src="<?= e($p->slika_src) ?>" class="card-img-top pt-3" alt="<?= e($p->slika_alt) ?>">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-dark"><?= e($p->naziv) ?></h5>
                                        <p class="m-0"><?= e($p->proizvodjac_naziv) ?></p>
                                        <p><?= e($p->platforma_naziv) ?></p>
                                        <p><?= e(prikazCena($p)) ?> RSD</p>
                                        <p><?= $p->new_arrival ? "Yes" : "No" ?></p>
                                        <button class="btn btn-danger btn-cart-add" data-id="<?= (int)$p->id ?>">Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php
    require_once "includes/template/footer.php";
?>
