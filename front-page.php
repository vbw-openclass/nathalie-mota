<?php get_header(); ?>

<body>
    <main>
    
    <!-- hero -->
    <section class="container-hero">
        <?php
            $args = array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'posts_per_page' => 1,
                'orderby' => 'rand',
            );

            $images = get_posts($args);

            foreach ($images as $image) {
                $image_url = wp_get_attachment_image_url($image->ID, 'full');
                $image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
        ?>
            <img class="container-hero__img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
            <?php } ?>
            <h2 class="container-hero__titre"> Photographe Event </h2>
    </section>

    <!-- filtre -->
    <section class="filter-container">
        <select id="filtre-categorie" class="custom-select2-behavior" data-placeholder="CATÉGORIES">
            <option value="">CATÉGORIES</option>
        </select>

        <select id="filtre-format" class="custom-select2-behavior" data-placeholder="FORMATS">
            <option value="">FORMATS</option>
        </select>

        <select id="filtre-date" class="custom-select2-behavior" data-placeholder="TRIER PAR">
            <option value="">TRIER PAR</option>
            <option value="ASC">Du plus ancien au plus récent</option>
            <option value="DESC">Du plus récent au plus ancien</option>
        </select>
    </section>

    <!-- Catalogue -->
    <section class="container-photo testo">
        <div class="container-photo-apparente" data-page="1">
            <?php

            //* Nouvelle instance de WP_Query pour récupérer les 12 premiers posts pour le catalogue photo
            $pagin = get_query_var('paged') ? get_query_var('paged') : 1;
            $args_photos = array(
                'post_type' => 'photos',
                'posts_per_page' => 12,
                'orderby' => 'date',
                'order' => 'ASC', 
            );

            $catalogue_photos = new WP_Query($args_photos);

            if ($catalogue_photos->have_posts()) {
                while ($catalogue_photos->have_posts()) {
                    $catalogue_photos->the_post();

                    // structure du catalogue
                    get_template_part('template-parts/photo-block');
                }
                wp_reset_postdata();
            }
            ?>
        </div>

        <div id="load-more-container">
            <button id="load-more-btn" class="container-photo__all-btn">Charger plus</button>
        </div>
    </section>

    </main>

<?php get_footer(); ?>

</body>

</html>