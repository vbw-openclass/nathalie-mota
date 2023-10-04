<?php

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

?>