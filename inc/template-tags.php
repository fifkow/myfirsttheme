<?php
/**
 * Neve Lite Template Tags
 *
 * Custom template tags for this theme
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'neve_lite_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_posted_on(): void {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }
        
        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );
        
        $posted_on = sprintf(
            /* translators: %s: Post date. */
            esc_html_x( 'Posted on %s', 'post date', 'neve-lite' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );
        
        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'neve_lite_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_posted_by(): void {
        $byline = sprintf(
            /* translators: %s: Post author. */
            esc_html_x( 'by %s', 'post author', 'neve-lite' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );
        
        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'neve_lite_entry_meta' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_entry_meta(): void {
        
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            
            // Categories
            if ( get_theme_mod( 'neve_lite_show_categories', true ) ) {
                $categories_list = get_the_category_list( esc_html__( ', ', 'neve-lite' ) );
                if ( $categories_list ) {
                    printf( '<span class="cat-links">%s</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
            
            // Tags
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'neve-lite' ) );
            if ( $tags_list ) {
                printf( '<span class="tags-links">%s</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }
        
        // Comments
        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: Post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'neve-lite' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );
            echo '</span>';
        }
        
        // Edit link
        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Post title */
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
    }
endif;

if ( ! function_exists( 'neve_lite_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     *
     * @since 1.0.0
     * @param string $size Image size.
     * @return void
     */
    function neve_lite_post_thumbnail( string $size = 'post-thumbnail' ): void {
        
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }
        
        if ( is_singular() ) :
            ?>
            <div class="nv-post-thumbnail">
                <?php the_post_thumbnail( $size, array( 'class' => 'img-responsive' ) ); ?>
            </div>
            <?php
        else :
            ?>
            <a class="nv-post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    $size,
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'img-responsive',
                    )
                );
                ?>
            </a>
            <?php
        endif;
    }
endif;

if ( ! function_exists( 'neve_lite_entry_header' ) ) :
    /**
     * Prints the entry header
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_entry_header(): void {
        ?>
        <header class="nv-entry-header">
            <?php
            if ( is_singular() ) :
                the_title( '<h1 class="nv-entry-title">', '</h1>' );
            else :
                the_title( '<h2 class="nv-entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;
            
            if ( 'post' === get_post_type() ) :
                ?>
                <div class="nv-entry-meta">
                    <?php
                    if ( get_theme_mod( 'neve_lite_show_date', true ) ) {
                        neve_lite_posted_on();
                    }
                    if ( get_theme_mod( 'neve_lite_show_author', true ) ) {
                        neve_lite_posted_by();
                    }
                    ?>
                </div>
                <?php
            endif;
            ?>
        </header>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_entry_footer' ) ) :
    /**
     * Prints the entry footer
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_entry_footer(): void {
        ?>
        <footer class="nv-entry-footer">
            <?php neve_lite_entry_meta(); ?>
        </footer>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_post_navigation' ) ) :
    /**
     * Prints post navigation
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_post_navigation(): void {
        the_post_navigation(
            array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'neve-lite' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'neve-lite' ) . '</span> <span class="nav-title">%title</span>',
            )
        );
    }
endif;

if ( ! function_exists( 'neve_lite_pagination' ) ) :
    /**
     * Prints pagination for archive pages
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_pagination(): void {
        the_posts_pagination(
            array(
                'mid_size'  => 2,
                'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'neve-lite' ) . '</span><span aria-hidden="true">&laquo;</span>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'neve-lite' ) . '</span><span aria-hidden="true">&raquo;</span>',
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'neve-lite' ) . ' </span>',
            )
        );
    }
endif;

if ( ! function_exists( 'neve_lite_site_logo' ) ) :
    /**
     * Displays the site logo or title
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_site_logo(): void {
        
        if ( has_custom_logo() ) {
            the_custom_logo();
        } else {
            ?>
            <div class="nv-logo">
                <?php if ( is_front_page() && is_home() ) : ?>
                    <h1 class="nv-logo-text">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                <?php else : ?>
                    <p class="nv-logo-text">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
            <?php
        }
    }
endif;

if ( ! function_exists( 'neve_lite_site_description' ) ) :
    /**
     * Displays the site description
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_site_description(): void {
        $description = get_bloginfo( 'description', 'display' );
        
        if ( $description || is_customize_preview() ) :
            ?>
            <p class="site-description">
                <?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </p>
            <?php
        endif;
    }
endif;

if ( ! function_exists( 'neve_lite_primary_navigation' ) ) :
    /**
     * Displays the primary navigation
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_primary_navigation(): void {
        if ( ! has_nav_menu( 'primary' ) ) {
            return;
        }
        ?>
        <nav id="site-navigation" class="nv-primary-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'neve-lite' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'primary',
                    'menu_id'         => 'primary-menu',
                    'menu_class'      => 'nv-nav-menu',
                    'container'       => false,
                    'fallback_cb'     => false,
                    'depth'           => 3,
                    'walker'          => new Neve_Lite_Walker_Nav_Menu(),
                )
            );
            ?>
        </nav>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_mobile_menu_toggle' ) ) :
    /**
     * Displays the mobile menu toggle button
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_mobile_menu_toggle(): void {
        if ( ! has_nav_menu( 'primary' ) ) {
            return;
        }
        ?>
        <button class="nv-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle menu', 'neve-lite' ); ?>">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_cta_button' ) ) :
    /**
     * Displays the CTA button in header
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_cta_button(): void {
        if ( ! neve_lite_is_cta_button_enabled() ) {
            return;
        }
        
        $text  = neve_lite_get_cta_button_text();
        $url   = neve_lite_get_cta_button_url();
        $style = neve_lite_get_cta_button_style();
        
        ?>
        <a href="<?php echo esc_url( $url ); ?>" class="nv-button nv-button-<?php echo esc_attr( $style ); ?> nv-header-cta">
            <?php echo esc_html( $text ); ?>
        </a>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_footer_widgets' ) ) :
    /**
     * Displays footer widgets
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_footer_widgets(): void {
        $columns = neve_lite_get_footer_columns();
        
        if ( $columns < 1 ) {
            return;
        }
        
        $has_widgets = false;
        for ( $i = 1; $i <= $columns; $i++ ) {
            if ( is_active_sidebar( 'footer-' . $i ) ) {
                $has_widgets = true;
                break;
            }
        }
        
        if ( ! $has_widgets ) {
            return;
        }
        ?>
        <div class="nv-footer-widgets" style="grid-template-columns: repeat(<?php echo esc_attr( $columns ); ?>, 1fr);">
            <?php
            for ( $i = 1; $i <= $columns; $i++ ) {
                if ( is_active_sidebar( 'footer-' . $i ) ) {
                    ?>
                    <div class="nv-footer-widget-area nv-footer-widget-<?php echo esc_attr( $i ); ?>">
                        <?php dynamic_sidebar( 'footer-' . $i ); ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_footer_copyright' ) ) :
    /**
     * Displays footer copyright
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_footer_copyright(): void {
        ?>
        <div class="nv-footer-bottom">
            <p><?php echo neve_lite_get_copyright_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_footer_navigation' ) ) :
    /**
     * Displays footer navigation
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_footer_navigation(): void {
        if ( ! has_nav_menu( 'footer' ) ) {
            return;
        }
        ?>
        <nav class="nv-footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'neve-lite' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'menu_class'     => 'nv-footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                )
            );
            ?>
        </nav>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_breadcrumbs' ) ) :
    /**
     * Displays breadcrumbs
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_breadcrumbs(): void {
        
        // Don't show on homepage
        if ( is_front_page() ) {
            return;
        }
        
        // Check if Yoast SEO breadcrumbs are available
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<nav class="nv-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'neve-lite' ) . '">', '</nav>' );
            return;
        }
        
        // Check if Rank Math breadcrumbs are available
        if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
            rank_math_the_breadcrumbs();
            return;
        }
        
        // Simple custom breadcrumbs
        ?>
        <nav class="nv-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'neve-lite' ); ?>">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'neve-lite' ); ?></a>
            <span class="separator">/</span>
            <?php
            if ( is_category() || is_single() ) {
                $category = get_the_category();
                if ( ! empty( $category ) ) {
                    echo '<a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '">' . esc_html( $category[0]->name ) . '</a>';
                    echo '<span class="separator">/</span>';
                }
                if ( is_single() ) {
                    the_title();
                }
            } elseif ( is_page() ) {
                if ( $post->post_parent ) {
                    $parent_id   = $post->post_parent;
                    $breadcrumbs = array();
                    
                    while ( $parent_id ) {
                        $page          = get_post( $parent_id );
                        $breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
                        $parent_id     = $page->post_parent;
                    }
                    
                    $breadcrumbs = array_reverse( $breadcrumbs );
                    
                    foreach ( $breadcrumbs as $crumb ) {
                        echo $crumb . '<span class="separator">/</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                }
                the_title();
            } elseif ( is_search() ) {
                esc_html_e( 'Search results for:', 'neve-lite' );
                echo ' ' . esc_html( get_search_query() );
            } elseif ( is_404() ) {
                esc_html_e( 'Error 404', 'neve-lite' );
            } elseif ( is_archive() ) {
                the_archive_title();
            }
            ?>
        </nav>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_page_title' ) ) :
    /**
     * Displays page title section
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_page_title(): void {
        
        if ( ! neve_lite_show_page_title() ) {
            return;
        }
        
        $title = neve_lite_get_page_title();
        
        if ( empty( $title ) ) {
            return;
        }
        ?>
        <div class="nv-page-title">
            <div class="nv-container">
                <h1><?php echo wp_kses_post( $title ); ?></h1>
                <?php neve_lite_breadcrumbs(); ?>
            </div>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_comments_template' ) ) :
    /**
     * Custom comments template
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_comments_template(): void {
        
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
    }
endif;

if ( ! function_exists( 'neve_lite_related_posts' ) ) :
    /**
     * Displays related posts
     *
     * @since 1.0.0
     * @param int $posts_per_page Number of posts to show.
     * @return void
     */
    function neve_lite_related_posts( int $posts_per_page = 3 ): void {
        
        if ( ! is_singular( 'post' ) ) {
            return;
        }
        
        $categories = get_the_category();
        
        if ( empty( $categories ) ) {
            return;
        }
        
        $category_ids = array();
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in'        => $category_ids,
            'post__not_in'        => array( get_the_ID() ),
            'posts_per_page'      => $posts_per_page,
            'ignore_sticky_posts' => 1,
        );
        
        $related_query = new WP_Query( $args );
        
        if ( ! $related_query->have_posts() ) {
            return;
        }
        ?>
        <div class="nv-related-posts">
            <h3 class="nv-related-posts-title"><?php esc_html_e( 'Related Posts', 'neve-lite' ); ?></h3>
            <div class="nv-related-posts-grid">
                <?php
                while ( $related_query->have_posts() ) :
                    $related_query->the_post();
                    ?>
                    <article class="nv-related-post">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="nv-related-post-thumbnail">
                                <?php the_post_thumbnail( 'neve-lite-grid' ); ?>
                            </a>
                        <?php endif; ?>
                        <h4 class="nv-related-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <time class="nv-related-post-date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'neve_lite_author_box' ) ) :
    /**
     * Displays author box
     *
     * @since 1.0.0
     * @return void
     */
    function neve_lite_author_box(): void {
        
        if ( ! is_singular( 'post' ) ) {
            return;
        }
        
        $author_id = get_the_author_meta( 'ID' );
        ?>
        <div class="nv-author-box">
            <div class="nv-author-avatar">
                <?php echo get_avatar( $author_id, 100 ); ?>
            </div>
            <div class="nv-author-info">
                <h4 class="nv-author-name">
                    <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </h4>
                <p class="nv-author-bio">
                    <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                </p>
                <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="nv-author-posts-link">
                    <?php esc_html_e( 'View all posts', 'neve-lite' ); ?>
                </a>
            </div>
        </div>
        <?php
    }
endif;

/**
 * Custom Walker for Navigation Menu
 *
 * @since 1.0.0
 */
class Neve_Lite_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Starts the element output.
     *
     * @since 1.0.0
     * @param string   $output            Used to append additional content (passed by reference).
     * @param WP_Post  $data_object       Menu item data object.
     * @param int      $depth             Depth of menu item. Used for padding.
     * @param stdClass $args              An object of wp_nav_menu() arguments.
     * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
     * @return void
     */
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
        
        // Restores the more descriptive, specific name for use within this method.
        $menu_item = $data_object;
        
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
        
        $classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
        $classes[] = 'menu-item-' . $menu_item->ID;
        
        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param WP_Post  $menu_item Menu item data object.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );
        
        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $menu_item The current menu item object.
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        /**
         * Filters the ID attribute applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_item_id The ID attribute applied to the menu item's `<li>` element.
         * @param WP_Post  $menu_item    The current menu item.
         * @param stdClass $args         An object of wp_nav_menu() arguments.
         * @param int      $depth        Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts           = array();
        $atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
        $atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
        
        if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $menu_item->xfn;
        }
        
        $atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
        $atts['aria-current'] = $menu_item->current ? 'page' : '';
        
        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts       {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title        Title attribute.
         *     @type string $target       Target attribute.
         *     @type string $rel          The rel attribute.
         *     @type string $href         The href attribute.
         *     @type string $aria-current The aria-current attribute.
         * }
         * @param WP_Post  $menu_item The current menu item object.
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );
        
        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        
        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
        
        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string   $title     The menu item's title.
         * @param WP_Post  $menu_item The current menu item object.
         * @param stdClass $args      An object of wp_nav_menu() arguments.
         * @param int      $depth     Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );
        
        $item_output  = $args->before ?? '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        
        // Add dropdown indicator for items with children
        if ( in_array( 'menu-item-has-children', $menu_item->classes, true ) ) {
            $item_output .= '<span class="nv-dropdown-arrow" aria-hidden="true"></span>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after ?? '';
        
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
    }
}
