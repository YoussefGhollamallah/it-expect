
<?php if(isset($_SESSION["user"])) :?>
    <p>Bonjour <?php echo $_SESSION["user"]["firstname"] . " " . $_SESSION["user"]["lastname"]; ?></p>
<?php endif; ?>

<h2>Films populaires</h2>
<section>
    <div class="swiper-container" id="movie">
    </div>
</section>

<h2>Séries populaires</h2>
<section>
    <div class="swiper-container" id="tv">
    </div>
</section>

<script src="<?php echo ASSETS; ?>js/index.js"></script>
