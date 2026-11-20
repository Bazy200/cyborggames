<?php

    session_start();
    include "utility.php";

    unset($_SESSION['korisnik']);
    redirect("../../index.php");
?>
