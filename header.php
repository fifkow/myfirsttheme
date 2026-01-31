<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary">
    <?php esc_html_e( 'Skip to content', 'neve-lite' ); ?>
</a>

<?php
/**
 * Hook: neve_lite_before_header
 *
 * @since 1.0.0
 */
do_action( 'neve_lite_before_header' );
?>

<header id="masthead" class="nv-header nv-header-<?php echo esc_attr( neve_lite_get_header_layout() ); ?> <?php echo neve_lite_is_sticky_header() ? 'nv-header-sticky' : ''; ?>">
    <div class="nv-container">
        <div class="nv-header-inner">
            
            <div class="nv-header-left">
                <?php
                /**
                 * Hook: neve_lite_header_left
                 *
                 * @since 1.0.0
                 */
                do_action( 'neve_lite_header_left' );
                
                // Site Logo
                neve_lite_site_logo();
                ?>
            </div>
            
            <div class="nv-header-center">
                <?php
                /**
                 * Hook: neve_lite_header_center
                 *
                 * @since 1.0.0
                 */
                do_action( 'neve_lite_header_center' );
                
                // Primary Navigation
                neve_lite_primary_navigation();
                ?>
            </div>
            
            <div class="nv-header-right">
                <?php
                /**
                 * Hook: neve_lite_header_right
                 *
                 * @since 1.0.0
                 */
                do_action( 'neve_lite_header_right' );
                
                // WooCommerce Header Cart
                if ( class_exists( 'WooCommerce' ) ) {
                    neve_lite_header_cart();
                }
                
                // WooCommerce Account Link
                if ( class_exists( 'WooCommerce' ) ) {
                    neve_lite_header_account();
                }
                
                // CTA Button
                neve_lite_cta_button();
                
                // Mobile Menu Toggle
                neve_lite_mobile_menu_toggle();
                ?>
            </div>
            
        </div>
    </div>
</header>

<?php
/**
 * Hook: neve_lite_after_header
 *
 * @since 1.0.0
 */
do_action( 'neve_lite_after_header' );

// Page Title
neve_lite_page_title();
?>

<div id="page" class="nv-site">
    
    <div id="content" class="nv-site-content">
        <div class="nv-container">
            <div class="nv-content-wrap <?php echo esc_attr( neve_lite_get_content_class() ); ?>">
