<?php
    session_start();
    require_once "config/conn.php";
    require_once "logic/functions.php";
    require_once "logic/utility.php";

    $pageTitle = "Home";
    require_once "includes/template/head.php";
?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-content">

                    <div class="main-banner">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="header-text">
                                    <h6>Welcome To Cyborg</h6>
                                    <h4><em>Browse</em> Our Popular Games Here</h4>
                                    <div class="main-button">
                                        <a href="shop.php">Browse Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="most-popular">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="heading-section">
                                    <h4><em>Latest</em> in store</h4>
                                </div>
                                <div class="row">
                                    <?php

                                        $najnoviji = dohvatiProizvode([], "new-arrival");
                                        foreach (array_slice($najnoviji, 0, 4) as $p):
                                    ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="item">
                                                <img src="<?= e($p->slika_src) ?>" alt="<?= e($p->slika_alt) ?>">
                                                <h4><?= e($p->naziv) ?><br><span><?= e($p->zanr_naziv) ?></span></h4>
                                                <ul>
                                                    <li><i class="fa fa-tag"></i> <?= e(prikazCena($p)) ?> RSD</li>
                                                    <li><i class="fa fa-desktop"></i> <?= e($p->platforma_naziv) ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <div class="col-lg-12">
                                        <div class="main-button">
                                            <a href="shop.php">Discover More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gaming-library">
                        <div class="col-lg-12">
                            <div class="heading-section">
                                <h4><em>Your Gaming</em> Library</h4>
                            </div>
                            <div class="item">
                                <ul>
                                    <li><img src="assets/images/game-01.jpg" alt="" class="templatemo-item"></li>
                                    <li><h4>Dota 2</h4><span>Sandbox</span></li>
                                    <li><h4>Date Added</h4><span>24/08/2036</span></li>
                                    <li><h4>Hours Played</h4><span>634 H 22 Mins</span></li>
                                    <li><h4>Currently</h4><span>Downloaded</span></li>
                                    <li><div class="main-border-button border-no-active"><a href="#">Donwloaded</a></div></li>
                                </ul>
                            </div>
                            <div class="item">
                                <ul>
                                    <li><img src="assets/images/game-02.jpg" alt="" class="templatemo-item"></li>
                                    <li><h4>Fortnite</h4><span>Sandbox</span></li>
                                    <li><h4>Date Added</h4><span>22/06/2036</span></li>
                                    <li><h4>Hours Played</h4><span>740 H 52 Mins</span></li>
                                    <li><h4>Currently</h4><span>Downloaded</span></li>
                                    <li><div class="main-border-button"><a href="#">Donwload</a></div></li>
                                </ul>
                            </div>
                            <div class="item last-item">
                                <ul>
                                    <li><img src="assets/images/game-03.jpg" alt="" class="templatemo-item"></li>
                                    <li><h4>CS-GO</h4><span>Sandbox</span></li>
                                    <li><h4>Date Added</h4><span>21/04/2036</span></li>
                                    <li><h4>Hours Played</h4><span>892 H 14 Mins</span></li>
                                    <li><h4>Currently</h4><span>Downloaded</span></li>
                                    <li><div class="main-border-button border-no-active"><a href="#">Donwloaded</a></div></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="main-button">
                                <a href="shop.php">View Your Library</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php
    require_once "includes/template/footer.php";
?>
