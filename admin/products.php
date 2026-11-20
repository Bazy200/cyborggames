<?php
    session_start();
    include "logic/utility.php";
    autorizacija("../login.php", "admin");

    include "config/conn.php";
    include "logic/functions.php";

    $filteri = [
        'proizvodjac' => isset($_GET['proizvodjac']) ? (int) $_GET['proizvodjac'] : 0,
        'platforma'   => isset($_GET['platforma'])   ? (int) $_GET['platforma']   : 0,
        'zanr'        => isset($_GET['zanr'])         ? (int) $_GET['zanr']        : 0,
    ];
    $sort = $_GET['sort'] ?? 'naziv-asc';

    $proizvodi    = dohvatiProizvode($filteri, $sort);
    $proizvodjaci = dohvatiSve("proizvodjac");
    $platforme    = dohvatiSve("platforma");
    $zanrovi      = dohvatiSve("zanr");

    $sortOpcije = [
        'naziv-asc'   => 'Name A-Z',
        'naziv-desc'  => 'Name Z-A',
        'cena-asc'    => 'Price ascending',
        'cena-desc'   => 'Price descending',
        'new-arrival' => 'Newest first',
    ];

    $pageTitle = "Products";
    include "includes/head.php";
    include "includes/header.php";
?>
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div>
                <h1 class="admin-heading"><em>Manage</em> products</h1>
                <p class="admin-subtitle">Total shown: <?= count($proizvodi) ?></p>
            </div>
            <a href="add-product.php" class="btn-pink">+ New product</a>
        </div>

        <?php if (isset($_SESSION['poruka-uspeh'])): ?>
            <div class="admin-alert admin-alert-success"><?= e($_SESSION['poruka-uspeh']) ?></div>
            <?php unset($_SESSION['poruka-uspeh']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['poruka-greska'])): ?>
            <div class="admin-alert admin-alert-danger"><?= e($_SESSION['poruka-greska']) ?></div>
            <?php unset($_SESSION['poruka-greska']); ?>
        <?php endif; ?>

        <form method="get" action="products.php" class="filter-bar admin-form">
            <div class="filter-item">
                <label for="proizvodjac">Publisher</label>
                <select name="proizvodjac" id="proizvodjac" class="form-select">
                    <option value="0">All</option>
                    <?php foreach ($proizvodjaci as $p): ?>
                        <option value="<?= $p->id ?>" <?= $filteri['proizvodjac'] == $p->id ? 'selected' : '' ?>><?= e($p->naziv) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="platforma">Platform</label>
                <select name="platforma" id="platforma" class="form-select">
                    <option value="0">All</option>
                    <?php foreach ($platforme as $p): ?>
                        <option value="<?= $p->id ?>" <?= $filteri['platforma'] == $p->id ? 'selected' : '' ?>><?= e($p->naziv) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="zanr">Genre</label>
                <select name="zanr" id="zanr" class="form-select">
                    <option value="0">All</option>
                    <?php foreach ($zanrovi as $z): ?>
                        <option value="<?= $z->id ?>" <?= $filteri['zanr'] == $z->id ? 'selected' : '' ?>><?= e($z->naziv) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="sort">Sort</label>
                <select name="sort" id="sort" class="form-select">
                    <?php foreach ($sortOpcije as $vr => $naziv): ?>
                        <option value="<?= $vr ?>" <?= $sort === $vr ? 'selected' : '' ?>><?= $naziv ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <button type="submit" class="btn-pink">Apply</button>
            </div>
            <div class="filter-item">
                <a href="products.php" class="btn-outline-pink">Reset</a>
            </div>
        </form>

        <div class="admin-card" style="padding:14px 18px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Publisher</th>
                        <th>Platform</th>
                        <th>Genre</th>
                        <th>Price</th>
                        <th>New</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($proizvodi) === 0): ?>
                        <tr class="empty-row"><td colspan="9">No products for the selected filters.</td></tr>
                    <?php else: ?>
                        <?php $rb = 1; foreach ($proizvodi as $pr): ?>
                            <tr>
                                <td><?= $rb++ ?></td>
                                <td>
                                    <?php if ($pr->slika_src): ?>
                                        <img src="../<?= e($pr->slika_src) ?>" alt="<?= e($pr->slika_alt) ?>" class="thumb"
                                             onerror="this.style.display='none'">
                                    <?php endif; ?>
                                </td>
                                <td><?= e($pr->naziv) ?></td>
                                <td><?= e($pr->proizvodjac_naziv) ?></td>
                                <td><?= e($pr->platforma_naziv) ?></td>
                                <td><?= e($pr->zanr_naziv) ?></td>
                                <td>
                                    <?php if ($pr->cena_nova !== null): ?>
                                        <span class="cena-nova"><?= number_format($pr->cena_nova, 0, ',', '.') ?> RSD</span>
                                        <?php if ($pr->cena_stara !== null): ?>
                                            <br><span class="cena-stara"><?= number_format($pr->cena_stara, 0, ',', '.') ?> RSD</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="cena-nova"><?= number_format($pr->cena_stara, 0, ',', '.') ?> RSD</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($pr->new_arrival): ?>
                                        <span class="badge-new">New</span>
                                    <?php else: ?>
                                        <span class="badge-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" style="white-space:nowrap;">
                                    <a href="edit-product.php?id=<?= (int) $pr->id ?>" class="btn-sm-ico btn-edit" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="logic/delete-product.php?id=<?= (int) $pr->id ?>" class="btn-sm-ico btn-del" title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this product?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php include "includes/footer.php"; ?>
