<?php

    function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    function autorizacija($url, $uloga = null) {
        if (!isset($_SESSION['korisnik'])) {
            redirect($url);
        }
        if ($uloga !== null && $_SESSION['korisnik']->uloga != $uloga) {
            redirect($url);
        }
    }

    function e($vrednost) {
        return htmlspecialchars($vrednost ?? "", ENT_QUOTES, "UTF-8");
    }
?>
