<?php
    // Récupérer les custom fields avec ACF
    $photo_retrieval = get_field('photos');
    $type = get_field('type');
    $reference = get_field('reference');

    // Champs de Taxonomies
    $taxo_categorie = get_the_terms(get_the_ID(), 'categorie'); 
    $taxo_format = get_the_terms(get_the_ID(), 'format');
    $taxo_annee = get_the_terms(get_the_ID(), 'annee');
?>

<div class="container-principal">
    <div class="container-principal__single">
        <div class="details-photo">
            <h2><?php echo esc_html(get_the_title()); ?></h2>
            <div>
                <?php 
                    if (isset($reference)) {
                        echo '<p>RÉFÉRENCE: ' . esc_html($reference) . '<br></p>';
                    }
                    
                    if ($taxo_categorie && isset($taxo_categorie[0])) {
                        echo '<p>CATÉGORIE: ' . esc_html($taxo_categorie[0]->name) . '<br></p>';
                    }

                    if ($taxo_format && isset($taxo_format[0])) {
                        echo '<p>FORMAT: ' . esc_html($taxo_format[0]->name) . '<br></p>';
                    }

                    if (isset($type) && is_string($type)) {
                        echo '<p>TYPE: ' . esc_html($type) . '<br></p>';
                    }

                    if ($taxo_annee && isset($taxo_annee[0]) && preg_match('/^\d{4}$/', $taxo_annee[0]->name)) {
                        echo '<p>ANNÉE: ' . esc_html($taxo_annee[0]->name) . '<br></p>';
                    }
                ?>
            </div>
        </div>
        <img src="<?php echo esc_url($photo_retrieval); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"><br>
    </div>

    <div class="container-contact">
        <div class="container-contact__contact-btn">
            <p> Cette photo vous intéresse ? </p>
            <button type="button" class="contact-link" data-reference="<?php echo $reference; ?>">Contact</button>
        </div>

        <?php
            //Flèches précédent et suivant
            $nextPost = get_next_post();
            $previousPost = get_previous_post();

            // Si on est sur la dernière photo, définir $nextPost comme le premier post
            if (empty($nextPost)) {
                $args = array(
                    'posts_per_page' => 1,
                    'order'          => 'ASC',
                    'post_type'      => 'photos' // 
                );
                $firstPost = get_posts($args);
                if (!empty($firstPost)) {
                    $nextPost = $firstPost[0];
                }
            }

            // Si on est sur la première photo, définir $previousPost comme le dernier post
            if (empty($previousPost)) {
                $args = array(
                    'posts_per_page' => 1,
                    'order'          => 'DESC',
                    'post_type'      => 'photos' // 
                );
                $lastPost = get_posts($args);
                if (!empty($lastPost)) {
                    $previousPost = $lastPost[0];
                }
            }
        ?>

        <div class="container-contact__navigation-arrows">
            <?php if (!empty($previousPost) || !empty($nextPost)) { ?>
                <div class="container-miniature">
                    <?php
                        $thumbnailID = null;

                        if (!empty($nextPost)) {
                            $thumbnailID = get_post_thumbnail_id($nextPost->ID);
                        } elseif (!empty($previousPost)) {
                            $thumbnailID = get_post_thumbnail_id($previousPost->ID);
                        }

                        if ($thumbnailID) {
                            echo wp_get_attachment_image($thumbnailID, 'thumbnail', false, ['class' => 'container-miniature__img-arrows']);
                        }
                    ?>
                </div>
                <div class="container-arrows">
                    <?php if (!empty($previousPost)) { ?>
                        <a href="<?php echo get_permalink($previousPost->ID) ?>"><img class="arrowLeft" src="<?php echo get_theme_file_uri() .'/assets/images/arrow-left.png';?>" alt="Flèche précédent"></a>
                    <?php } ?>
                    <?php if (!empty($nextPost)) { ?>
                        <a href="<?php echo get_permalink($nextPost->ID) ?>"><img class="arrowRight" src="<?php echo get_theme_file_uri() .'/assets/images/arrow-right.png';?>" alt="Flèche suivant"></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="container-photo-block">
        <p class="container-photo-block__title"> vous aimerez aussi </p>
        
        <?php get_template_part('template-parts/photo-block'); ?>

        <button type="button" class="container-photo-block__all-btn">Toutes les photos</button>
    </div>
</div>