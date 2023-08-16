<?php
    get_header();
?>


<main class="main-site"> 

<?php

    $args = array(
        'post_type' => 'photos', 
        'p' =>get_the_ID(), 
    );

    $photo_request = new WP_Query($args);


    if ($photo_request->have_posts()) {
        while ($photo_request->have_posts()) {
            $photo_request->the_post();

            get_template_part('template-parts/content-single-photos');
        }

        wp_reset_postdata();
    } else {
        echo 'Aucune photo trouvée.';
    }

?>

</main>


<?php
    get_footer();
?>