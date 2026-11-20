<?php

    function polje($kljuc, $old, $proizvod) {
        if ($old !== null && isset($old[$kljuc])) return $old[$kljuc];
        if ($proizvod !== null && isset($proizvod->$kljuc)) return $proizvod->$kljuc;
        return "";
    }
    $idProizvoda = $proizvod !== null ? (int) $proizvod->id : 0;
    $cur = function($kljuc) use ($old, $proizvod) { return polje($kljuc, $old, $proizvod); };
?>
<form action="logic/save-product.php" method="post" class="admin-form">
    <input type="hidden" name="id" value="<?= $idProizvoda ?>">

    <div class="row g-3">
        <div class="col-md-8">
            <label for="naziv" class="form-label">Game name</label>
            <input type="text" id="naziv" name="naziv" class="form-control admin-input"
                   value="<?= e($cur('naziv')) ?>" required>
        </div>

        <div class="col-md-4">
            <label for="slika_alt" class="form-label">Image alt text</label>
            <input type="text" id="slika_alt" name="slika_alt" class="form-control admin-input"
                   value="<?= e($cur('slika_alt')) ?>">
        </div>

        <div class="col-12">
            <label for="slika_src" class="form-label">Image path (e.g. assets/images/mgsv.jpg)</label>
            <input type="text" id="slika_src" name="slika_src" class="form-control admin-input"
                   value="<?= e($cur('slika_src')) ?>" required>
        </div>

        <div class="col-md-4">
            <label for="proizvodjac_id" class="form-label">Publisher</label>
            <select name="proizvodjac_id" id="proizvodjac_id" class="form-select">
                <option value="0">Select</option>
                <?php foreach ($proizvodjaci as $p): ?>
                    <option value="<?= $p->id ?>" <?= (int)$cur('proizvodjac_id') === (int)$p->id ? 'selected' : '' ?>><?= e($p->naziv) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="platforma_id" class="form-label">Platform</label>
            <select name="platforma_id" id="platforma_id" class="form-select">
                <option value="0">Select</option>
                <?php foreach ($platforme as $p): ?>
                    <option value="<?= $p->id ?>" <?= (int)$cur('platforma_id') === (int)$p->id ? 'selected' : '' ?>><?= e($p->naziv) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="zanr_id" class="form-label">Genre</label>
            <select name="zanr_id" id="zanr_id" class="form-select">
                <option value="0">Select</option>
                <?php foreach ($zanrovi as $z): ?>
                    <option value="<?= $z->id ?>" <?= (int)$cur('zanr_id') === (int)$z->id ? 'selected' : '' ?>><?= e($z->naziv) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="cena_stara" class="form-label">Old price (RSD)</label>
            <input type="number" min="0" id="cena_stara" name="cena_stara" class="form-control admin-input"
                   value="<?= e($cur('cena_stara')) ?>">
        </div>

        <div class="col-md-6">
            <label for="cena_nova" class="form-label">New price / sale (RSD)</label>
            <input type="number" min="0" id="cena_nova" name="cena_nova" class="form-control admin-input"
                   value="<?= e($cur('cena_nova')) ?>">
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="new_arrival" name="new_arrival" value="1"
                       <?= (int)$cur('new_arrival') === 1 ? 'checked' : '' ?>>
                <label class="form-check-label text-muted-cy" for="new_arrival">Mark as new arrival</label>
            </div>
        </div>

        <div class="col-12" style="margin-top:10px;">
            <button type="submit" name="btnSave" class="btn-pink"><?= $idProizvoda > 0 ? 'Save changes' : 'Add product' ?></button>
            <a href="products.php" class="btn-outline-pink">Cancel</a>
        </div>
    </div>
</form>
