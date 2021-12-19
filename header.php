<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="<?php if (function_exists('get_template_directory_uri')) {
        echo get_template_directory_uri() . '/';
    } ?>style.css">
    <meta charset="utf-8">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/public/js/script.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/public/js/defer.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>
<div id="page">
    <button class="menu-toggle"></button>
    <div id="navigation-bar">
        <div id="logo-area">
            <?php if (function_exists('the_custom_logo')) {
                the_custom_logo();
            } ?>
        </div>
        <div class="mobile-menu">
            <?php
            wp_nav_menu([
                'menu' => 'main',
                'container_id' => 'main-menu-mobile',
                'walker' => new CSS_Menu_Maker_Walker()
            ]);
            ?>
            <div id="mobile-search-form" class="search-form-outer">
                <div class="search-form-wrapper">
                    <form method="get" id="searchform-mobile" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="text" class="form-control" style="color:white;" value="<?php echo wp_specialchars($s, 1); ?>" placeholder="Suchen..." name="s" id="s-mobile" />
                    </form>
                    <div class="search-form-icon"><a id="search-icon-wrapper"><i class="fas fa-search"></i></a></div>
                </div>
            </div>
        </div>
        <div class="hide-mobile">
            <?php
            wp_nav_menu([
                'menu' => 'main',
                'container_id' => 'menu-area',
                'walker' => new CSS_Menu_Maker_Walker()
            ]);
            ?>
        </div>
        <div class="fill-area hide-mobile">
        </div>
        <div class="events hide-mobile">
            <h5>Nächste Termine</h5>
            <?php
            if (function_exists('calendar_events')) {
                $events = calendar_events();
                foreach (calendar_events() as $event) {
                    echo '<div class="event">';
                    echo '<div class="symbol"><img src="'.$event['img_src'].'"></div>';
                    echo '<div class="info">' . $event['title'] . '<br>'.$event['date'].'<br>' . $event['location'] . '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    <div class="shadow-element hide-mobile"></div>
    <div id="overlay hide-mobile"></div>
    <div id="main">