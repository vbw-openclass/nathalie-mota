<?php
    // Fonction pour enregistrer les menus de navigation
    function register_my_menus() {
        register_nav_menus( array(
            'header' => 'Menu principal',      // Menu de navigation de l'en-tête
            'footer' => 'Menu pied de page',  // Menu de navigation du pied de page
        ) );
    }
    add_action( 'init', 'register_my_menus' );  // Action pour enregistrer les menus lors de l'initialisation de WordPress

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

    // Fonction pour charger plus de photos en utilisant AJAX
    function load_more_photos() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;  // Récupère le numéro de page à partir des données POST ou défini à 1 par défaut
        $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';  // Récupère la catégorie à partir des données POST
        $format = isset($_POST['format']) ? $_POST['format'] : '';  // Récupère le format à partir des données POST
        $annee = isset($_POST['annee']) ? $_POST['annee'] : 'ASC';  // Récupère l'année à partir des données POST ou défini à 'ASC' par défaut
        
        $args_photos = array(
            'post_type' => 'photos',
            'posts_per_page' => 12,
            'paged' => $page,
            'orderby' => 'date',
            'order' => $annee ? $annee : 'ASC',  // Ordre de tri, par défaut 'ASC'
            'tax_query' => array(
                'relation' => 'AND',
            )
        );

        // Si une catégorie est sélectionnée, ajoute-la à la requête
        if (!empty($categorie)) {
            array_push($args_photos['tax_query'], array(
                'taxonomy' => 'categorie',
                'field' => 'term_id',
                'terms' => $categorie,
            ));
        }

        // Si un format est sélectionné, ajoute-le à la requête
        if (!empty($format)) {
            array_push($args_photos['tax_query'], array(
                'taxonomy' => 'format',
                'field' => 'term_id',
                'terms' => $format,
            ));
        }

        $catalogue_photos = new WP_Query($args_photos);  // Effectue la requête WP_Query avec les paramètres spécifiés

        if ($catalogue_photos->have_posts()) {
            while ($catalogue_photos->have_posts()) {
                $catalogue_photos->the_post();
                get_template_part('template-parts/photo-block');  // Inclut le modèle 'photo-block' pour afficher chaque photo
            }
            wp_reset_postdata();
        }
        die();  // Arrête l'exécution de la requête AJAX
    }

    add_action('wp_ajax_load_more_photos', 'load_more_photos');         // Si l'utilisateur est connecté
    add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');  // Si l'utilisateur n'est pas connecté

    // Fonction pour charger toutes les photos pour une lightbox
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

        echo json_encode($all_images);  // Retourne les données au format JSON
        wp_die();  // Arrête l'exécution de la requête AJAX
    }

    add_action('wp_ajax_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');         // Si l'utilisateur est connecté
    add_action('wp_ajax_nopriv_load_all_photos_for_lightbox', 'load_all_photos_for_lightbox');  // Si l'utilisateur n'est pas connecté
?>