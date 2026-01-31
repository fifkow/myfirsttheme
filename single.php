<?php
/**
 * The template for displaying all single posts
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-single">
    
    <?php
    /**
     * Hook: neve_lite_before_single_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_single_content' );
    
    while ( have_posts() ) :
        the_post();
        
        get_template_part( 'template-parts/content/content', 'single' );
        
        // Post Navigation
        neve_lite_post_navigation();
        
        // Author Box
        neve_lite_author_box();
        
        // Related Posts
        neve_lite_related_posts();
        
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        
    endwhile;
    
    /**
     * Hook: neve_lite_after_single_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_single_content' );
    ?>
    
</main><!-- #primary -->

<?php
if ( neve_lite_has_sidebar() ) {
    get_sidebar();
}

get_footer();
