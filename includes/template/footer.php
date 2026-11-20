
    <div class="footer_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <h3 class="useful_text">About</h3>
                    <p class="footer_text text-light">CyborgGaming prodavnica video igara. Pregledaj, filtriraj i poruci omiljene naslove.</p>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h3 class="useful_text">Menu</h3>
                    <div class="footer_menu">
                        <ul class="navbar-nav ml-auto">
                            <?php foreach (dohvatiNav() as $stavka): ?>
                                <li><a href="<?= e($stavka->href) ?>" class="text-light"><?= e($stavka->label) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h1 class="useful_text">Useful Links</h1>
                    <a href="shop.php" class="color"><i class="fa fa-gamepad"></i><span class="px-2">Prodavnica</span></a>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <h1 class="useful_text">Contact Us</h1>
                    <div class="location_text">
                        <ul>
                            <li><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i><span class="px-2">Address : France,Paris</span></a></li>
                            <li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i><span class="px-2">Call : +01 1234567890</span></a></li>
                            <li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i><span class="px-2">Email : demo@gmail.com</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/tabs.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/my.js"></script>
</body>

</html>
