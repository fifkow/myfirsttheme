<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="nv-content-area nv-search">
    
    <?php
    /**
     * Hook: neve_lite_before_search_results
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_search_results' );
    
    if ( have_posts() ) :
        ?>
        
        <header class="nv-search-header">
            <h1 class="nv-search-title">
                <?php
                /* translators: %s: Search query. */
                printf( esc_html__( 'Search Results for: %s', 'neve-lite' ), '<span>' . get_search_query() . '</span>' );
                ?>
            </h1>
            <p class="nv-search-results-count">
                <?php
                /* translators: %d: Number of search results. */
                printf( esc_html( _n( 'Found %d result', 'Found %d results', (int) $wp_query->found_posts, 'neve-lite' ) ), (int) $wp_query->found_posts );
                ?>
            </p>
        </header><!-- .nv-search-header -->
        
        <div class="nv-posts-wrapper nv-layout-list">
            <div class="nv-posts-grid">
                
                <?php
                // Start the Loop
                while ( have_posts() ) :
                    the_post();
                    
                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'template-parts/content/content', 'search' );
                    
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
     * Hook: neve_lite_after_search_results
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_search_results' );
    ?>
    
</main><!-- #primary -->

<?php
if ( neve_lite_has_sidebar() ) {
    get_sidebar();
}

get_footer();
