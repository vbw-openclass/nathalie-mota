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
        wp_enqueue_script('pagination-ajax', get_template_directory_uri() . '/js/pagination-ajax.js', array('jquery'), '1.0', true);
        wp_localize_script('pagination-ajax', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
        add_theme_support( 'post-thumbnails' );
    }

    add_action('wp_enqueue_scripts', 'theme_script', 'after_setup_theme');
    
    function load_more_photos() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 2;
        $args_photos = array(
            'post_type' => 'photos',
            'posts_per_page' => 12,
            'paged' => $page,
            'orderby' => 'date',
            'order' => 'ASC', 
        );
    
        $catalogue_photos = new WP_Query($args_photos);
    
        if ($catalogue_photos->have_posts()) {
            while ($catalogue_photos->have_posts()) {
                $catalogue_photos->the_post();
                get_template_part('template-parts/photo-block');
            }
            wp_reset_postdata();
        }
        die();
    }
    
    add_action('wp_ajax_load_more_photos', 'load_more_photos');         // Si l'utilisateur est connecté
    add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');  // Si l'utilisateur n'est pas connecté

?>