<?php /* Template Name: Startseite */ ?>
<?php get_header(); ?>
    <div id="content" class="category">
        <div id="text">
            <h1 class="title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) { ?>
                <div class="abstract">
                    <?php echo the_excerpt() ?>
                </div>
            <?php } ?>
            <div class="text">
                <?php the_content(null, has_excerpt()); ?>
            </div>
            <div class="posts">

                <?php

                // define query arguments
                $args = array(
                    'posts_per_page' => 4, // your 'x' goes here
                    'nopaging' => true
                    // possibly more arguments here
                );

                // set up new query
                $tyler_query = new WP_Query($args);

                // loop through found posts
                while ($tyler_query->have_posts()) : $tyler_query->the_post(); ?>
                    <div class="post">
                        <h2 class="title"><?php the_title(); ?></h2>
                        <p class="meta">
                            <span class="date"><?php the_time('d.m.Y'); ?></span>
                            <span class="breadcrumbs">
                                    <?php
                                    if (function_exists('nav_breadcrumb')) nav_breadcrumb();
                                    ?>
                                </span>
                        </p>
                        <div class="abstract">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">mehr lesen</a>
                    </div>
                <?php endwhile;

                // reset post data
                wp_reset_postdata();

                ?>

            </div>
        </div>
        <div id="page-img">
            <?php the_post_thumbnail(); ?>
        </div>
    </div>
    <div class="fill-area"></div>
<?php get_footer(); ?>