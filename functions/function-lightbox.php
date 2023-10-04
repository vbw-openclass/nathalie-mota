<?php
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