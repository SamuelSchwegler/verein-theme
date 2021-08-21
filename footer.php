<?php
    $show_post = (!isset($args['show_post']) || $args['show_post']) && get_theme_mod('show_footer_blogpost', true);
?>

<div id="footer" class="<?php if($show_post) { echo('with-post'); } ?>">
    <?php
    if ($show_post) {
        require('includes/footer-post.php');
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <a href="https://correct.ch">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/logo-correct-white-<?php echo substr(get_theme_mod('accent_color', ''), 1); ?>.png"
                         alt="" class="logo">
                </a>
            </div>
            <div id="social-wrapper-desktop" class="col">
                <?php require('includes/social-links.php'); ?>
            </div>
            <div class="col">
                <h4>Links</h4>
                <?php echo get_theme_mod('footer_links_block'); ?>
                <p>
                    <a class="link" href="/impressum">Impressum</a>
                </p>
            </div>
            <div class="col">
                <h4>Kontakt</h4>
                <?php echo get_theme_mod('footer_adresse_block'); ?>
            </div>
        </div>
    </div>
    <div id="social-wrapper-mobile">
        <?php require('includes/social-links.php'); ?>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
