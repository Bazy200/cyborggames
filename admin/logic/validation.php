<?php

    function validirajProizvod(array $data): array {
        $errors = [];

        $naziv = trim($data['naziv'] ?? "");
        if (mb_strlen($naziv) < 2 || mb_strlen($naziv) > 150) {
            $errors[] = "Name must be between 2 and 150 characters.";
        }

        if ((int)($data['proizvodjac_id'] ?? 0) <= 0) {
            $errors[] = "Please select a publisher.";
        }
        if ((int)($data['platforma_id'] ?? 0) <= 0) {
            $errors[] = "Please select a platform.";
        }
        if ((int)($data['zanr_id'] ?? 0) <= 0) {
            $errors[] = "Please select a genre.";
        }

        $cenaStara = $data['cena_stara'] ?? "";
        $cenaNova  = $data['cena_nova'] ?? "";

        if ($cenaStara !== "" && (!ctype_digit((string)$cenaStara) || (int)$cenaStara < 0)) {
            $errors[] = "Old price must be a positive whole number.";
        }
        if ($cenaNova !== "" && (!ctype_digit((string)$cenaNova) || (int)$cenaNova < 0)) {
            $errors[] = "New price must be a positive whole number.";
        }
        if ($cenaStara === "" && $cenaNova === "") {
            $errors[] = "Enter at least one price (old or new).";
        }

        $src = trim($data['slika_src'] ?? "");
        if ($src === "") {
            $errors[] = "Enter an image path.";
        }

        return $errors;
    }
?>
