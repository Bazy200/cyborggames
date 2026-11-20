<?php

    function validirajProizvod(array $data): array {
        $errors = [];

        $naziv = trim($data['naziv'] ?? "");
        if (mb_strlen($naziv) < 2 || mb_strlen($naziv) > 150) {
            $errors[] = "Naziv mora imati izmedju 2 i 150 karaktera.";
        }

        if ((int)($data['proizvodjac_id'] ?? 0) <= 0) {
            $errors[] = "Izaberite proizvodjaca.";
        }
        if ((int)($data['platforma_id'] ?? 0) <= 0) {
            $errors[] = "Izaberite platformu.";
        }
        if ((int)($data['zanr_id'] ?? 0) <= 0) {
            $errors[] = "Izaberite zanr.";
        }

        $cenaStara = $data['cena_stara'] ?? "";
        $cenaNova  = $data['cena_nova'] ?? "";

        if ($cenaStara !== "" && (!ctype_digit((string)$cenaStara) || (int)$cenaStara < 0)) {
            $errors[] = "Stara cena mora biti pozitivan ceo broj.";
        }
        if ($cenaNova !== "" && (!ctype_digit((string)$cenaNova) || (int)$cenaNova < 0)) {
            $errors[] = "Nova cena mora biti pozitivan ceo broj.";
        }
        if ($cenaStara === "" && $cenaNova === "") {
            $errors[] = "Unesite bar jednu cenu (staru ili novu).";
        }

        $src = trim($data['slika_src'] ?? "");
        if ($src === "") {
            $errors[] = "Unesite putanju do slike.";
        }

        return $errors;
    }
?>
