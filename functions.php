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
        wp_localize_script( 'script-js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script('pagination-ajax', get_template_directory_uri() . '/js/pagination-ajax.js', array('jquery'), '1.0', true);
        wp_localize_script('pagination-ajax', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
        wp_enqueue_script('lightbox', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), '1.0', true);
        add_theme_support( 'post-thumbnails' );
    }

    add_action('wp_enqueue_scripts', 'theme_script', 'after_setup_theme');
    
    function load_more_photos() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
        $format = isset($_POST['format']) ? $_POST['format'] : '';
        $annee = isset($_POST['annee']) ? $_POST['annee'] : 'ASC';
    
        $args_photos = array(
            'post_type' => 'photos',
            'posts_per_page' => 12,
            'paged' => $page,
            'orderby' => 'date',
            'order' => $annee ? $annee : 'ASC', // ou 'ASC', selon ce que vous voulez par défaut
            'tax_query' => array(
                'relation' => 'AND',
            )
        );
    
        if (!empty($categorie)) {
            array_push($args_photos['tax_query'], array(
                'taxonomy' => 'categorie',
                'field' => 'term_id',
                'terms' => $categorie,
            ));
        }
    
        if (!empty($format)) {
            array_push($args_photos['tax_query'], array(
                'taxonomy' => 'format',
                'field' => 'term_id',
                'terms' => $format,
            ));
        }
    
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

    function load_all_photos_for_lightbox() {
        $args = array(
            'post_type' => 'photos',
            'posts_per_page' => -1,
        );
    
        $query = new WP_Query($args);
        $all_images = [];
    
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $image_data = array(
                    'url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                    'reference' => get_field('reference'),
                    'category' => implode(', ', wp_list_pluck(get_the_terms(get_the_ID(), 'categorie'), 'name')),
                );
                
                $all_images[] = $image_data;
            }
        }
    
        echo json_encode($all_images);
        wp_die();
    }
    
    add_action('wp_ajax_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');
    add_action('wp_ajax_nopriv_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');

?>