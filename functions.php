<?php
add_theme_support('post-thumbnails', array('post', 'page'));
add_post_type_support('page', 'excerpt');

include('resources/functions/menu-walker.php');
?>