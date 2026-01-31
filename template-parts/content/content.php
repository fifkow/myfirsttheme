<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'nv-post-item' ); ?>>
    
    <?php
    /**
     * Hook: neve_lite_before_post_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_before_post_content' );
    
    // Featured Image
    if ( get_theme_mod( 'neve_lite_show_featured_image', true ) && has_post_thumbnail() ) :
        ?>
        <div class="nv-post-thumbnail-wrap">
            <a href="<?php the_permalink(); ?>" class="nv-post-thumbnail-link">
                <?php
                the_post_thumbnail(
                    'neve-lite-grid',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>
        </div>
        <?php
    endif;
    ?>
    
    <div class="nv-post-content-wrap">
        
        <header class="nv-entry-header">
            <?php
            // Post Title
            if ( is_singular() ) :
                the_title( '<h1 class="nv-entry-title">', '</h1>' );
            else :
                the_title( '<h2 class="nv-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;
            
            // Post Meta
            if ( 'post' === get_post_type() ) :
                ?>
                <div class="nv-entry-meta">
                    <?php
                    if ( get_theme_mod( 'neve_lite_show_date', true ) ) {
                        echo '<span class="posted-on">';
                        echo '<time class="entry-date published" datetime="' . esc_attr( get_the_date( DATE_W3C ) ) . '">' . esc_html( get_the_date() ) . '</time>';
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
                    ?>
                </div><!-- .nv-entry-meta -->
                <?php
            endif;
            ?>
        </header><!-- .nv-entry-header -->
        
        <div class="nv-entry-content">
            <?php
            if ( is_singular() ) :
                the_content();
                
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'neve-lite' ),
                        'after'  => '</div>',
                    )
                );
            else :
                the_excerpt();
            endif;
            ?>
        </div><!-- .nv-entry-content -->
        
        <footer class="nv-entry-footer">
            <?php
            // Read More Link
            if ( ! is_singular() ) :
                ?>
                <a href="<?php the_permalink(); ?>" class="nv-read-more nv-button nv-button-outline">
                    <?php esc_html_e( 'Read More', 'neve-lite' ); ?>
                </a>
                <?php
            endif;
            
            // Tags
            if ( is_singular() ) {
                $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'neve-lite' ) );
                if ( $tags_list ) {
                    printf( '<span class="tags-links">%s</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
            ?>
        </footer><!-- .nv-entry-footer -->
        
    </div><!-- .nv-post-content-wrap -->
    
    <?php
    /**
     * Hook: neve_lite_after_post_content
     *
     * @since 1.0.0
     */
    do_action( 'neve_lite_after_post_content' );
    ?>
    
</article><!-- #post-<?php the_ID(); ?> -->
