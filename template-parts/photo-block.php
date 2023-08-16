<div class="container-photo-block__apparente">
    <?php
        $categories = get_the_terms(get_the_ID(), 'categorie');
        if ($categories && !is_wp_error($categories)) {
            $category_ids = wp_list_pluck($categories, 'term_id');

            $args = array(
                'post_type' => 'photos',
                'posts_per_page' => 2,
                'orderby' => 'rand',
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'categorie',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    ),
                ),
            );

            $related_photos = new WP_Query($args);

            if ($related_photos->have_posts()) {
                while ($related_photos->have_posts()) {
                    $related_photos->the_post();
                    $photo_retrieval = get_field('photos');
                    ?>
                    <div>
                        <div class="img-apparente">
                            <img src="<?php echo $photo_retrieval; ?>" alt="<?php the_title_attribute(); ?>">
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            }
        }
    ?>
</div>