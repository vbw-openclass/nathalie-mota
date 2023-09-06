<?php 
    $thumbnail_url = get_the_post_thumbnail_url();
?>

<div class="img-apparente">
    <img src="<?php echo $thumbnail_url;?>" alt="<?php the_title_attribute(); ?>">
    <div class="overlay">
        <a href="<?php the_permalink(); ?>"> 
            <img src="<?php echo get_theme_file_uri() .'/assets/images/eye.png';?>" class="eye-icon" alt="Voir les infos">
        </a>
        <img src="<?php echo get_theme_file_uri() .'/assets/images/fullscreen.png';?>" class="fullscreen-icon" alt="Voir en plein écran">
        <span class="image-title"><?php the_title(); ?></span>
        <span class="image-category">
            <?php
                $terms = wp_get_post_terms( get_the_ID(), 'categorie' ); 
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                    echo $terms[0]->name;
                }
            ?>
        </span>
    </div>
</div>
