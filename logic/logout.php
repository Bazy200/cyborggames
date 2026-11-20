<?php

    session_start();
    require_once __DIR__ . "/utility.php";

    unset($_SESSION['korisnik']);
    redirect("../index.php");
?>
