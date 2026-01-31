<?php
/**
 * The template for displaying WooCommerce pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce.php
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-woocommerce">
    
    <?php
    /**
     * Hook: neve_lite_before_woocommerce_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_woocommerce_content' );
    
    if ( have_posts() ) :
        
        woocommerce_content();
        
    endif;
    
    /**
     * Hook: neve_lite_after_woocommerce_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_woocommerce_content' );
    ?>
    
</main><!-- #primary -->

<?php
// Show sidebar on shop pages if enabled
if ( is_active_sidebar( 'shop-sidebar' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
    ?>
    <aside id="secondary" class="nv-sidebar nv-shop-sidebar">
        <?php dynamic_sidebar( 'shop-sidebar' ); ?>
    </aside>
    <?php
}

get_footer();
