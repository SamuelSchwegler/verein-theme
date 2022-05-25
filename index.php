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
        </div>
        <div id="page-img">
            <?php the_post_thumbnail(); ?>
        </div>
    </div>
    <div class="fill-area"></div>
<?php get_footer(); ?>
