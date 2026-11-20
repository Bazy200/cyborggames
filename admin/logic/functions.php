<?php

    function logovanje($email, $lozinka) {
        global $conn;
        $upit = "SELECT k.id, k.ime, k.prezime, k.email, u.naziv AS uloga
                 FROM korisnik k
                 JOIN uloga u ON k.uloga_id = u.id
                 WHERE k.email = :email AND k.lozinka = :loz";

        $iskaz = $conn->prepare($upit);
        $iskaz->bindParam(":email", $email);
        $iskaz->bindParam(":loz", $lozinka);
        $iskaz->execute();

        return $iskaz->fetch();
    }

    function dohvatiSve($nazivTabele) {
        global $conn;

        $upit = "SELECT * FROM $nazivTabele ORDER BY naziv ASC";
        return $conn->query($upit)->fetchAll();
    }

    function dohvatiProizvode(array $filteri = [], $sort = "naziv-asc") {
        global $conn;

        $upit = "SELECT p.*, pr.naziv AS proizvodjac_naziv,
                        pl.naziv AS platforma_naziv, z.naziv AS zanr_naziv
                 FROM proizvod p
                 JOIN proizvodjac pr ON p.proizvodjac_id = pr.id
                 JOIN platforma   pl ON p.platforma_id   = pl.id
                 JOIN zanr        z  ON p.zanr_id         = z.id";

        $uslovi = [];
        $parametri = [];

        if (!empty($filteri['proizvodjac'])) {
            $uslovi[] = "p.proizvodjac_id = :proizvodjac";
            $parametri[':proizvodjac'] = (int) $filteri['proizvodjac'];
        }
        if (!empty($filteri['platforma'])) {
            $uslovi[] = "p.platforma_id = :platforma";
            $parametri[':platforma'] = (int) $filteri['platforma'];
        }
        if (!empty($filteri['zanr'])) {
            $uslovi[] = "p.zanr_id = :zanr";
            $parametri[':zanr'] = (int) $filteri['zanr'];
        }

        if (count($uslovi) > 0) {
            $upit .= " WHERE " . implode(" AND ", $uslovi);
        }

        $mapaSort = [
            'naziv-asc'   => "p.naziv ASC",
            'naziv-desc'  => "p.naziv DESC",
            'cena-asc'    => "COALESCE(p.cena_nova, p.cena_stara) ASC",
            'cena-desc'   => "COALESCE(p.cena_nova, p.cena_stara) DESC",
            'new-arrival' => "p.new_arrival DESC, p.id DESC",
        ];
        $orderBy = $mapaSort[$sort] ?? $mapaSort['naziv-asc'];
        $upit .= " ORDER BY " . $orderBy;

        $iskaz = $conn->prepare($upit);
        $iskaz->execute($parametri);
        return $iskaz->fetchAll();
    }

    function dohvatiProizvod($id) {
        global $conn;
        $iskaz = $conn->prepare("SELECT * FROM proizvod WHERE id = :id");
        $iskaz->bindValue(":id", (int) $id, PDO::PARAM_INT);
        $iskaz->execute();
        return $iskaz->fetch();
    }

    function upisProizvoda(array $d) {
        global $conn;
        $upit = "INSERT INTO proizvod
                    (naziv, proizvodjac_id, platforma_id, zanr_id, slika_src, slika_alt, cena_stara, cena_nova, new_arrival)
                 VALUES
                    (:naziv, :proizvodjac, :platforma, :zanr, :src, :alt, :cstara, :cnova, :new)";
        $iskaz = $conn->prepare($upit);
        $iskaz->execute(vratiParametre($d));
        return $conn->lastInsertId();
    }

    function izmeniProizvod($id, array $d) {
        global $conn;
        $upit = "UPDATE proizvod SET
                    naziv = :naziv, proizvodjac_id = :proizvodjac, platforma_id = :platforma,
                    zanr_id = :zanr, slika_src = :src, slika_alt = :alt,
                    cena_stara = :cstara, cena_nova = :cnova, new_arrival = :new
                 WHERE id = :id";
        $iskaz = $conn->prepare($upit);
        $params = vratiParametre($d);
        $params[':id'] = (int) $id;
        return $iskaz->execute($params);
    }

    function obrisiProizvod($id) {
        global $conn;
        $iskaz = $conn->prepare("DELETE FROM proizvod WHERE id = :id");
        $iskaz->bindValue(":id", (int) $id, PDO::PARAM_INT);
        return $iskaz->execute();
    }

    function vratiParametre(array $d) {
        return [
            ':naziv'       => $d['naziv'],
            ':proizvodjac' => (int) $d['proizvodjac_id'],
            ':platforma'   => (int) $d['platforma_id'],
            ':zanr'        => (int) $d['zanr_id'],
            ':src'         => $d['slika_src'],
            ':alt'         => $d['slika_alt'],
            ':cstara'      => $d['cena_stara'] !== "" ? (int) $d['cena_stara'] : null,
            ':cnova'       => $d['cena_nova']  !== "" ? (int) $d['cena_nova']  : null,
            ':new'         => !empty($d['new_arrival']) ? 1 : 0,
        ];
    }

    function prikazCena($proizvod) {
        if ($proizvod->cena_nova !== null) return $proizvod->cena_nova;
        return $proizvod->cena_stara;
    }
?>
