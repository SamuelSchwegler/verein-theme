<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <meta name="theme-color" content="#ffffff">
    <link href="<?php echo get_theme_mod('google_font_url', 'https://fonts.googleapis.com/css2?family=Assistant:wght@600;700&display=swap'); ?> " rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" rel="stylesheet" type="text/css">
    <?php wp_head(); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/public/js/script.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/public/js/defer.js" defer></script>
    <style>
        body {
            font-family: <?php echo get_theme_mod('body_font', "'Assistant', sans-serif"); ?>;
            font-weight: <?php echo get_theme_mod('body_font_weight', 600); ?>;
        }

        h1, h2, h3, h4 {
            font-family: <?php echo get_theme_mod('heading_font', "'Assistant', sans-serif"); ?>;
            font-weight: <?php echo get_theme_mod('heading_font_weight', 600); ?>;
        }
    </style>
</head>
<body>
<div id="header">
    <div class="container">
        <div class="logo">
            <?php if (function_exists('the_custom_logo')) {
                the_custom_logo();
            } ?>
        </div>
        <?php require('includes/nav.php'); ?>
        <button class="menu-toggle"></button>
    </div>
    <?php require('includes/mobile-nav.php'); ?>
</div>
