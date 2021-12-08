<?php get_header(); ?>
    <div id="content" class="category">
        <div id="text">
            <h1 class="title"><?php single_cat_title(); ?></h1>
            <p class="abstract">
                <?php category_description(); ?>
            </p>
            <div class="posts">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
    <div class="fill-area"></div>
<?php get_footer(); ?>