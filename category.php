<?php get_header(); ?>
    <div class="container">
        <h2 class="page-title"><?php single_cat_title(); ?></h2>
        <div class="post-boxes row">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="post-box col <?php if(get_theme_mod('show_footer_blogpost', true)) { echo('with-img'); } ?>">
                    <?php if (get_theme_mod('show_footer_blogpost', true) && has_post_thumbnail()) { ?>
                        <div class="post-box-image-container">
                            <a class="link" href="<?php the_permalink(); ?>">
                                <?php if (wp_is_mobile()) {
                                    the_post_thumbnail('card-medium');
                                } else {
                                    the_post_thumbnail('card');
                                } ?>
                            </a>
                        </div>
                    <?php } ?>
                    <p class="blog-info"><?php the_time('d.m.Y'); ?></p>
                    <h4 class="post-box-title">
                        <a class="link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <?php the_excerpt(); ?>
                    <a class="post-box-action button" href="<?php the_permalink(); ?>">weiter lesen</a>
                </div>
            <?php endwhile; endif; ?>
        </div>
        <?php the_posts_pagination([
            'screen_reader_text' => 'weitere Beiträge anzeigen',
            'aria_label' => 'Beiträge',
            'next_text' => '>',
            'prev_text' => '<'
        ]); ?>
    </div>
    </div><!-- main -->
<?php get_footer(null, ['show_post' => false]); ?>