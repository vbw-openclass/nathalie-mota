<?php get_header(); ?>

<body>
    <main>
        
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


    </main>

<?php get_footer(); ?>

</body>

</html>