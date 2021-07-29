<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.3
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <link rel="preconnect" href="https://use.typekit.net">
    <link rel="preconnect" href="https://googleads.g.doubleclick.net">
    <link rel="preconnect" href="https://www.google-analytics.com">

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5T9GLL7');</script>
    <!-- End Google Tag Manager -->
    
    <link rel="preload" href="https://fonts.gstatic.com/s/montserrat/v15/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.gstatic.com/s/montserrat/v15/JTURjIg1_i6t8kCHKm45_dJE3gnD_g.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/vendor/font-awesome/fontawesome-webfont.woff2?v=4.7.0" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/bernadette/Bernadette-Rough.woff" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/fonts/dimbo/Dimbo-Regular.woff" as="font" type="font/woff2" crossorigin="anonymous">

    <?php if(is_front_page()){ ?>
        <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/img/video-backup-mobile.webp" as="image" type="image/webp">
        <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/video/pwillys_video_short.mp4" as="video" type="video/mp4">
        <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/video/pwillys_video_short.webm" as="video" type="video/webm">
    <?php } ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >
