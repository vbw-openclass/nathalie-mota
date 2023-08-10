<?php
    function register_my_menus() {
        register_nav_menus( array(
            'header' => 'Menu principal',
            'footer' => 'Menu pied de page',
        ) );
    }
    add_action( 'init', 'register_my_menus' );


    function theme_script() {
        
        wp_enqueue_style( 'style', get_stylesheet_uri() );
        wp_enqueue_script('script-js', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);
        add_theme_support( 'post-thumbnails' );
    }

    add_action('wp_enqueue_scripts', 'theme_script', 'add_theme_support');
    
    

?>