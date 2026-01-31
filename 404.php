<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-error-404">
    
    <?php
    /**
     * Hook: neve_lite_before_404_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_404_content' );
    ?>
    
    <section class="nv-error-404-content">
        
        <div class="nv-error-404-icon">
            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        
        <h1 class="nv-error-404-title"><?php esc_html_e( '404', 'neve-lite' ); ?></h1>
        
        <h2 class="nv-error-404-subtitle"><?php esc_html_e( 'Page Not Found', 'neve-lite' ); ?></h2>
        
        <p class="nv-error-404-description">
            <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'neve-lite' ); ?>
        </p>
        
        <div class="nv-error-404-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nv-button nv-button-primary">
                <?php esc_html_e( 'Back to Home', 'neve-lite' ); ?>
            </a>
            
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="nv-button nv-button-outline">
                    <?php esc_html_e( 'Visit Shop', 'neve-lite' ); ?>
                </a>
            <?php endif; ?>
        </div>
        
        <div class="nv-error-404-search">
            <h3><?php esc_html_e( 'Or try searching:', 'neve-lite' ); ?></h3>
            <?php get_search_form(); ?>
        </div>
        
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <div class="nv-error-404-popular-products">
                <h3><?php esc_html_e( 'Popular Products', 'neve-lite' ); ?></h3>
                <?php
                $popular_products = new WP_Query(
                    array(
                        'post_type'      => 'product',
                        'posts_per_page' => 4,
                        'meta_key'       => 'total_sales',
                        'orderby'        => 'meta_value_num',
                        'order'          => 'DESC',
                    )
                );
                
                if ( $popular_products->have_posts() ) :
                    ?>
                    <div class="nv-products-grid">
                        <?php
                        while ( $popular_products->have_posts() ) :
                            $popular_products->the_post();
                            wc_get_template_part( 'content', 'product' );
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        <?php endif; ?>
        
    </section><!-- .nv-error-404-content -->
    
    <?php
    /**
     * Hook: neve_lite_after_404_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_404_content' );
    ?>
    
</main><!-- #primary -->

<?php
get_footer();
