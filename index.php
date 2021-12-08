<?php get_header(); ?>
<div id="content">
    <div id="text">
        <h1 class="title"><?php the_title(); ?></h1>
        <?php if(has_excerpt()) { ?>
            <div class="abstract">
                <?php echo the_excerpt() ?>
            </div>
        <?php } ?>
        <div class="text">
            <?php the_content(null, has_excerpt()); ?>
        </div>
        <div class="posts">
            <div class="post">
                <h2 class="title">Korbball</h2>
                <p class="abstract">
                    Ballgefühl, Geschicklichkeit, Ausdauer, Kraft und und mit so Einigem mehr trainieren wir mit viel
                    Ehrgeiz und Freude.
                    Das ganze Jahr über sind wir aktiv, im Sommer auf dem Rasen und im Winter in der Sporthalle.
                </p>
                <a href="category.php" class="read-more">mehr lesen</a>
            </div>
        </div>
    </div>
    <div id="page-img">
        <?php the_post_thumbnail(); ?>
    </div>
</div>
<div class="fill-area"></div>
<?php get_footer(); ?>
