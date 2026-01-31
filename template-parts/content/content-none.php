<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>

<section class="nv-no-results">
    
    <header class="nv-page-header">
        <h1 class="nv-page-title">
            <?php
            if ( is_search() ) {
                esc_html_e( 'Nothing Found', 'neve-lite' );
            } else {
                esc_html_e( 'No Posts Found', 'neve-lite' );
            }
            ?>
        </h1>
    </header><!-- .nv-page-header -->
    
    <div class="nv-page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :
            
            printf(
                '<p>' . wp_kses(
                    /* translators: 1: Link to WP admin new post page. */
                    __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'neve-lite' ),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url( admin_url( 'post-new.php' ) )
            );
            
        elseif ( is_search() ) :
            ?>
            
            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'neve-lite' ); ?></p>
            
            <div class="nv-search-form-wrap">
                <?php get_search_form(); ?>
            </div>
            
            <?php
        else :
            ?>
            
            <p><?php esc_html_e( 'It seems we cannot find what you are looking for. Perhaps searching can help.', 'neve-lite' ); ?></p>
            
            <div class="nv-search-form-wrap">
                <?php get_search_form(); ?>
            </div>
            
            <?php
        endif;
        ?>
    </div><!-- .nv-page-content -->
    
</section><!-- .nv-no-results -->
