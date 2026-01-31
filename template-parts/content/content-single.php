<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'nv-single-post' ); ?>>
    
    <?php
    /**
     * Hook: neve_lite_before_single_post_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_single_post_content' );
    
    // Featured Image
    if ( has_post_thumbnail() && get_theme_mod( 'neve_lite_show_featured_image', true ) ) :
        ?>
        <div class="nv-post-thumbnail-wrap nv-single-thumbnail">
            <?php
            the_post_thumbnail(
                'neve-lite-hero',
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
    
    <header class="nv-entry-header nv-single-header">
        <?php
        // Post Title
        the_title( '<h1 class="nv-entry-title">', '</h1>' );
        
        // Post Meta
        ?>
        <div class="nv-entry-meta">
            <?php
            if ( get_theme_mod( 'neve_lite_show_date', true ) ) {
                echo '<span class="posted-on">';
                echo '<time class="entry-date published" datetime="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . esc_html( get_the_date() ) . '</time>';
                if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
                    echo '<time class="updated" datetime="' . esc_attr( get_the_modified_date( DATE_W3C ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>';
                }
                echo '</span>';
            }
            
            if ( get_theme_mod( 'neve_lite_show_author', true ) ) {
                echo '<span class="byline">';
                printf(
                    /* translators: %s: Post author. */
                    esc_html_x( 'by %s', 'post author', 'neve-lite' ),
                    '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
                );
                echo '</span>';
            }
            
            if ( get_theme_mod( 'neve_lite_show_categories', true ) ) {
                $categories_list = get_the_category_list( esc_html__( ', ', 'neve-lite' ) );
                if ( $categories_list ) {
                    printf( '<span class="cat-links">%s</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
            
            // Reading Time
            echo '<span class="reading-time">' . esc_html( neve_lite_get_reading_time() ) . '</span>';
            ?>
        </div><!-- .nv-entry-meta -->
    </header><!-- .nv-entry-header -->
    
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
    
    <footer class="nv-entry-footer nv-single-footer">
        <?php
        // Tags
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'neve-lite' ) );
        if ( $tags_list ) {
            ?>
            <div class="nv-post-tags">
                <span class="nv-tags-label"><?php esc_html_e( 'Tags:', 'neve-lite' ); ?></span>
                <?php printf( '<span class="tags-links">%s</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php
        }
        
        // Share Buttons
        ?>
        <div class="nv-post-share">
            <span class="nv-share-label"><?php esc_html_e( 'Share:', 'neve-lite' ); ?></span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="nv-share-facebook">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( get_permalink() ); ?>&text=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="nv-share-twitter">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="nv-share-linkedin">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>
        </div>
    </footer><!-- .nv-entry-footer -->
    
    <?php
    /**
     * Hook: neve_lite_after_single_post_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_single_post_content' );
    ?>
    
</article><!-- #post-<?php the_ID(); ?> -->
