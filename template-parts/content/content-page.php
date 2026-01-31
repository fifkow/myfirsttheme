<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'nv-page-item' ); ?>>
    
    <?php
    /**
     * Hook: neve_lite_before_page_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_page_content' );
    
    // Featured Image
    if ( has_post_thumbnail() && get_theme_mod( 'neve_lite_show_featured_image', true ) ) :
        ?>
        <div class="nv-post-thumbnail-wrap">
            <?php
            the_post_thumbnail(
                'neve-lite-card',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                )
            );
            ?>
        </div>
        <?php
    endif;
    ?>
    
    <div class="nv-page-content">
        
        <?php
        // Page Title (if not hidden)
        if ( ! get_post_meta( get_the_ID(), '_neve_lite_hide_title', true ) && ! neve_lite_show_page_title() ) :
            the_title( '<h1 class="nv-page-title">', '</h1>' );
        endif;
        ?>
        
        <div class="nv-entry-content nv-content">
            <?php
            the_content();
            
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'neve-lite' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .nv-entry-content -->
        
        <?php if ( get_edit_post_link() ) : ?>
            <footer class="nv-entry-footer">
                <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'neve-lite' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
                ?>
            </footer><!-- .nv-entry-footer -->
        <?php endif; ?>
        
    </div><!-- .nv-page-content -->
    
    <?php
    /**
     * Hook: neve_lite_after_page_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_page_content' );
    ?>
    
</article><!-- #post-<?php the_ID(); ?> -->
