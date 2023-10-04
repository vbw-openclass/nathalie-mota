<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?> >
<?php wp_body_open(); ?> 


<header class="navheader">
    <div class="navheader__logo">
        <a href="<?php echo home_url(); ?>">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/images/logo-mota.png" alt="Logo du site">
        </a>
    </div>

    <nav>
        <?php
            wp_nav_menu(array(
                'theme_location' => 'header', 
                'container' => false, 
                'menu_class' => 'menu-principal',
            ));
        ?>
    </nav>

    <div class="burgerBtn">
        <span></span>
    </div>

</header>