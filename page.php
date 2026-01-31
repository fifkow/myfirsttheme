<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-page">
    
    <?php
    /**
     * Hook: neve_lite_before_page_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_page_content' );
    
    while ( have_posts() ) :
        the_post();
        
        get_template_part( 'template-parts/content/content', 'page' );
        
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        
    endwhile;
    
    /**
     * Hook: neve_lite_after_page_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_page_content' );
    ?>
    
</main><!-- #primary -->

<?php
if ( neve_lite_has_sidebar() ) {
    get_sidebar();
}

get_footer();
