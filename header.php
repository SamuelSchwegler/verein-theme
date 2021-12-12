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
    <script type="text/javascript" src="<?php if (function_exists('get_template_directory_uri')) {
        echo get_template_directory_uri() . '/';
    } ?>resources/js/menu-bar.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap" rel="stylesheet">
</head>
<body>
<div id="page">
    <div id="navigation-bar">
        <div id="logo-area">
            <?php if (function_exists('the_custom_logo')) {
                the_custom_logo();
            } ?>
        </div>
        <?php
        wp_nav_menu([
            'menu' => 'main',
            'container_id' => 'menu-area',
            'walker' => new CSS_Menu_Maker_Walker()
        ]);
        ?>
        <div class="fill-area">
        </div>
        <div class="events">
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
    <div class="shadow-element"></div>
    <div id="overlay"></div>
    <div id="main">