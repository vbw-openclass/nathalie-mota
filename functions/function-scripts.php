<?php
    // Fonction pour charger les styles et les scripts du thème
    function theme_script() {
        wp_enqueue_style( 'style', get_stylesheet_uri() );  // Charge la feuille de style principale
        wp_enqueue_script('lightbox', get_template_directory_uri() . '/js/lightbox.js', array('jquery'), '1.0', true);  // Charge un script nommé 'lightbox' avec dépendance à jQuery
        wp_localize_script('lightbox', 'adminAjax', array('ajax_url' => admin_url('admin-ajax.php')));  // Crée une variable JavaScript 'adminAjax' pour stocker l'URL de l'API Ajax
        wp_enqueue_script('script-js', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);  // Charge un autre script nommé 'script-js' avec dépendance à jQuery
        wp_localize_script( 'script-js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );  // Crée une variable JavaScript 'my_ajax_object' pour stocker l'URL de l'API Ajax
        wp_enqueue_script('pagination-ajax', get_template_directory_uri() . '/js/pagination-ajax.js', array('jquery'), '1.0', true);  // Charge un script nommé 'pagination-ajax' avec dépendance à jQuery
        wp_localize_script('pagination-ajax', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));  // Crée une variable JavaScript 'myAjax' pour stocker l'URL de l'API Ajax
        add_theme_support( 'post-thumbnails' );  // Active la prise en charge des images à la une pour les articles
        wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.3.0', true);
        
    }
    add_action('wp_enqueue_scripts', 'theme_script', 'after_setup_theme');  // Action pour charger les styles et les scripts du thème lors de l'affichage de la page

?>