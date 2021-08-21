<?php get_header(); ?>
<div class="container">
    <div class="img-section">
        <?php if (has_post_thumbnail()) {
            if (wp_is_mobile()) {
                the_post_thumbnail('banner-image', ['class' => 'main-img']);
            } else {
                the_post_thumbnail('side-image', ['class' => 'main-img']);
            }
        } ?>
    </div>
    <div class="text-container" id="page-content">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h2 class="page-title"><?php the_title(); ?></h2>
            <?php the_date('d.m.Y'); ?>
            <div class="abstract">
                <?php echo get_extended(get_post()->post_content)['main'] ?>
            </div>
            <div class="text">
                <?php the_content(null, true); ?>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div>
<?php get_footer(); ?>
