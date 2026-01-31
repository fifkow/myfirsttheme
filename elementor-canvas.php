<?php
/**
 * Template Name: Elementor Canvas
 *
 * This template removes the theme header, footer, and all wrappers,
 * allowing Elementor to build the entire page from scratch.
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

<body <?php body_class( 'nv-elementor-canvas-page' ); ?>>
<?php wp_body_open(); ?>

<?php
while ( have_posts() ) :
    the_post();
    
    the_content();
    
endwhile;
?>

<?php wp_footer(); ?>

</body>
</html>
