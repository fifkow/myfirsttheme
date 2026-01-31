<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-archive">
    
    <?php
    /**
     * Hook: neve_lite_before_archive
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_archive' );
    
    if ( have_posts() ) :
        ?>
        
        <header class="nv-archive-header">
            <?php
            the_archive_title( '<h1 class="nv-archive-title">', '</h1>' );
            the_archive_description( '<div class="nv-archive-description">', '</div>' );
            ?>
        </header><!-- .nv-archive-header -->
        
        <div class="nv-posts-wrapper nv-layout-<?php echo esc_attr( neve_lite_get_blog_layout() ); ?>">
            <div class="nv-posts-grid" style="--nv-posts-columns: <?php echo esc_attr( neve_lite_get_posts_per_row() ); ?>">
                
                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    
                    /**
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'template-parts/content/content', get_post_type() );
                    
                endwhile;
                ?>
                
            </div><!-- .nv-posts-grid -->
        </div><!-- .nv-posts-wrapper -->
        
        <?php
        // Pagination
        neve_lite_pagination();
        
    else :
        
        get_template_part( 'template-parts/content/content', 'none' );
        
    endif;
    
    /**
     * Hook: neve_lite_after_archive
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_archive' );
    ?>
    
</main><!-- #primary -->

<?php
if ( neve_lite_has_sidebar() ) {
    get_sidebar();
}

get_footer();
