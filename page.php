<?php get_header(); ?>
    <div class="container">
        <?php if (has_post_thumbnail() || !wp_is_mobile()) { ?>
            <div class="img-section">
                <?php if (has_post_thumbnail()) {
                    if (wp_is_mobile()) {
                        the_post_thumbnail('banner-image', ['class' => 'main-img']);
                    } else {
                        the_post_thumbnail('side-image', ['class' => 'main-img']);
                    }
                } ?>
            </div>
        <?php } ?>
        <div class="text-container" id="page-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php
                if (is_page() && $post->post_parent) {
                    $children = get_children(['post_parent' => $post->post_parent]);
                    //var_dump($children);
                }
                ?>
                <h2 class="page-title">
                    <?php echo get_the_title(); ?>
                </h2>
                <?php if(has_excerpt()) { ?>
                <div class="abstract">
                    <?php echo get_extended(get_post()->post_content)['main'] ?>
                </div>
                <?php } ?>
                <div class="text">
                    <?php the_content(null, true); ?>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
<?php get_footer(); ?>