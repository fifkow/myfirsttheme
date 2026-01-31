<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'nv-search-item' ); ?>>
    
    <?php
    // Featured Image
    if ( has_post_thumbnail() ) :
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
    
    <div class="nv-search-content-wrap">
        
        <header class="nv-entry-header">
            <?php
            // Post Type Label
            $post_type = get_post_type();
            $post_type_object = get_post_type_object( $post_type );
            if ( $post_type_object ) {
                echo '<span class="nv-post-type-label">' . esc_html( $post_type_object->label ) . '</span>';
            }
            
            // Post Title
            the_title( '<h2 class="nv-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            ?>
        </header><!-- .nv-entry-header -->
        
        <div class="nv-entry-summary">
            <?php the_excerpt(); ?>
        </div><!-- .nv-entry-summary -->
        
        <footer class="nv-entry-footer">
            <a href="<?php the_permalink(); ?>" class="nv-read-more nv-button nv-button-outline">
                <?php esc_html_e( 'Read More', 'neve-lite' ); ?>
            </a>
        </footer><!-- .nv-entry-footer -->
        
    </div><!-- .nv-search-content-wrap -->
    
</article><!-- #post-<?php the_ID(); ?> -->
