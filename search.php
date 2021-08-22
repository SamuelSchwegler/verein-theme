<?php get_header(); ?>
<div class="container" id="search-page">
    <h2 class="page-title">
        Suche
    </h2>
    <div class="text">
        <?php
        $default_posts_per_page = get_option('posts_per_page');
        if (have_posts()) { ?>
            <p class="text-center">Ihre Suche für <strong><?php echo wp_specialchars($s, 1); ?></strong> ergab folgende
                Resultate:</p>
            <div class="post-boxes row">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="post-box col">
                        <?php if (has_post_thumbnail()) { ?>
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
                        <p class="blog-info">
                            <?php if (get_post_type() === 'post') {
                                the_author(); ?> | <?php the_time('d.m.Y');
                            } else { ?>
                                Seite
                            <?php } ?>
                        </p>
                        <h4 class="post-box-title">
                            <a class="link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <?php the_excerpt(); ?>
                        <a class="post-box-action button" href="<?php the_permalink(); ?>">weiter lesen</a>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            if ($count_posts >= $default_posts_per_page) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-center"><?php next_posts_link('Mehr Suchresultate') ?></p>
                    </div>
                </div>
            <?php }
        } else { ?>
            <p class="text-center">Wir konnten für Ihre Suche <strong><?php echo wp_specialchars($s, 1); ?></strong>
                keine Ergebnisse finden.</p>
            <div class="search-form-outer active center">
                <div class="search-form-wrapper">
                    <form method="get" id="searchform-page" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="text" class="form-control" value="<?php echo wp_specialchars($s, 1); ?>"
                               placeholder="Suchen..." name="s" id="s" required/>
                    </form>
                </div>
                <div class="search-form-icon no-action">
                    <button form="searchform-page" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php get_footer(null, ['show_post' => false]); ?>
