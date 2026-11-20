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

    function dohvatiNav() {
        global $conn;
        return $conn->query("SELECT * FROM nav ORDER BY redosled ASC")->fetchAll();
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

    function prikazCena($proizvod) {
        if ($proizvod->cena_nova !== null) return $proizvod->cena_nova;
        return $proizvod->cena_stara;
    }

    function validirajKupca(array $d): array {
        $errors = [];
        $reIme = "/^[A-ZŠĐŽČĆ][a-zšđžčć]{1,19}$/u";

        if (!preg_match($reIme, trim($d['ime'] ?? ""))) {
            $errors[] = "Ime nije u dobrom formatu (npr. Pera).";
        }
        if (!preg_match($reIme, trim($d['prezime'] ?? ""))) {
            $errors[] = "Prezime nije u dobrom formatu (npr. Peric).";
        }
        if (!filter_var(trim($d['email'] ?? ""), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email nije u dobrom formatu.";
        }
        if (!in_array($d['nacin_placanja'] ?? "", ["1", "2"], true)) {
            $errors[] = "Izaberite nacin placanja.";
        }
        return $errors;
    }

    function kreirajPorudzbinu(array $kupac, array $korpa): array {
        global $conn;

        $greske = validirajKupca($kupac);

        $stavke = [];
        $ukupno = 0;
        foreach ($korpa as $red) {
            $pid = (int) ($red['id'] ?? 0);
            $kol = (int) ($red['quantity'] ?? 0);
            if ($pid <= 0 || $kol <= 0) {
                continue;
            }
            $proizvod = dohvatiProizvod($pid);
            if (!$proizvod) {
                $greske[] = "Proizvod (id $pid) ne postoji.";
                continue;
            }
            $cena = (int) prikazCena($proizvod);
            $stavke[] = ['proizvod_id' => $pid, 'kolicina' => $kol, 'cena' => $cena];
            $ukupno += $cena * $kol;
        }

        if (count($stavke) === 0) {
            $greske[] = "Korpa je prazna.";
        }
        if (count($greske) > 0) {
            return ['success' => false, 'errors' => $greske, 'id' => null];
        }

        $nacin = $kupac['nacin_placanja'] == "1" ? "Kartica" : "Gotovina";
        try {
            $conn->beginTransaction();

            $iskaz = $conn->prepare(
                "INSERT INTO porudzbina (ime, prezime, email, nacin_placanja, korisnik_id, ukupno)
                 VALUES (:ime, :prezime, :email, :nacin, :korisnik, :ukupno)"
            );
            $iskaz->execute([
                ':ime'      => trim($kupac['ime']),
                ':prezime'  => trim($kupac['prezime']),
                ':email'    => trim($kupac['email']),
                ':nacin'    => $nacin,
                ':korisnik' => !empty($kupac['korisnik_id']) ? (int) $kupac['korisnik_id'] : null,
                ':ukupno'   => $ukupno,
            ]);
            $porId = (int) $conn->lastInsertId();

            $iskazStavka = $conn->prepare(
                "INSERT INTO stavka_porudzbine (porudzbina_id, proizvod_id, kolicina, cena)
                 VALUES (:por, :proiz, :kol, :cena)"
            );
            foreach ($stavke as $s) {
                $iskazStavka->execute([
                    ':por'   => $porId,
                    ':proiz' => $s['proizvod_id'],
                    ':kol'   => $s['kolicina'],
                    ':cena'  => $s['cena'],
                ]);
            }

            $conn->commit();
            return ['success' => true, 'errors' => [], 'id' => $porId, 'ukupno' => $ukupno];
        } catch (PDOException $e) {
            $conn->rollBack();
            return ['success' => false, 'errors' => ["Greska u bazi: " . $e->getMessage()], 'id' => null];
        }
    }
?>
