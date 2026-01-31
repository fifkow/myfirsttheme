<?php
/**
 * Neve Lite Template Functions
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if sidebar should be displayed
 *
 * @since 1.0.0
 * @return bool True if sidebar should be displayed
 */
function neve_lite_has_sidebar(): bool {
    
    // Never show sidebar on Elementor templates
    if ( is_page_template( 'elementor-full-width.php' ) || is_page_template( 'elementor-canvas.php' ) ) {
        return false;
    }
    
    // Never show sidebar on 404 page
    if ( is_404() ) {
        return false;
    }
    
    // Check if sidebar has widgets
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        return false;
    }
    
    // Don't show on single pages if disabled
    if ( is_page() && get_post_meta( get_the_ID(), '_neve_lite_disable_sidebar', true ) ) {
        return false;
    }
    
    // Don't show on single posts if disabled
    if ( is_single() && get_post_meta( get_the_ID(), '_neve_lite_disable_sidebar', true ) ) {
        return false;
    }
    
    return true;
}

/**
 * Get content area class
 *
 * @since 1.0.0
 * @return string CSS class for content area
 */
function neve_lite_get_content_class(): string {
    if ( neve_lite_has_sidebar() ) {
        return 'nv-has-sidebar';
    }
    return 'nv-no-sidebar';
}

/**
 * Get sidebar class
 *
 * @since 1.0.0
 * @return string CSS class for sidebar
 */
function neve_lite_get_sidebar_class(): string {
    $position = get_theme_mod( 'neve_lite_sidebar_position', 'right' );
    return 'nv-sidebar-' . sanitize_html_class( $position );
}

/**
 * Check if page title should be displayed
 *
 * @since 1.0.0
 * @return bool True if page title should be displayed
 */
function neve_lite_show_page_title(): bool {
    
    // Don't show on homepage
    if ( is_front_page() ) {
        return false;
    }
    
    // Don't show if disabled in post meta
    if ( is_singular() && get_post_meta( get_the_ID(), '_neve_lite_hide_title', true ) ) {
        return false;
    }
    
    // Don't show on Elementor canvas template
    if ( is_page_template( 'elementor-canvas.php' ) ) {
        return false;
    }
    
    return true;
}

/**
 * Get the page title
 *
 * @since 1.0.0
 * @return string Page title
 */
function neve_lite_get_page_title(): string {
    
    if ( is_home() ) {
        $title = get_theme_mod( 'neve_lite_blog_title', __( 'Blog', 'neve-lite' ) );
        if ( empty( $title ) ) {
            $title = __( 'Blog', 'neve-lite' );
        }
        return $title;
    }
    
    if ( is_category() ) {
        return single_cat_title( '', false );
    }
    
    if ( is_tag() ) {
        return single_tag_title( '', false );
    }
    
    if ( is_author() ) {
        return get_the_author();
    }
    
    if ( is_archive() ) {
        return get_the_archive_title();
    }
    
    if ( is_search() ) {
        /* translators: %s: Search query */
        return sprintf( __( 'Search Results for: %s', 'neve-lite' ), get_search_query() );
    }
    
    if ( is_404() ) {
        return __( 'Page Not Found', 'neve-lite' );
    }
    
    if ( is_singular() ) {
        return single_post_title( '', false );
    }
    
    return '';
}

/**
 * Get header layout
 *
 * @since 1.0.0
 * @return string Header layout
 */
function neve_lite_get_header_layout(): string {
    $layout = get_theme_mod( 'neve_lite_header_layout', 'default' );
    
    // Override for specific pages
    if ( is_singular() ) {
        $page_layout = get_post_meta( get_the_ID(), '_neve_lite_header_layout', true );
        if ( $page_layout && $page_layout !== 'default' ) {
            $layout = $page_layout;
        }
    }
    
    return sanitize_html_class( $layout );
}

/**
 * Check if header is sticky
 *
 * @since 1.0.0
 * @return bool True if header is sticky
 */
function neve_lite_is_sticky_header(): bool {
    return (bool) get_theme_mod( 'neve_lite_sticky_header', false );
}

/**
 * Get container width
 *
 * @since 1.0.0
 * @return string Container width class
 */
function neve_lite_get_container_class(): string {
    $width = get_theme_mod( 'neve_lite_container_width', 'contained' );
    return 'nv-container-' . sanitize_html_class( $width );
}

/**
 * Get blog layout
 *
 * @since 1.0.0
 * @return string Blog layout
 */
function neve_lite_get_blog_layout(): string {
    return sanitize_html_class( get_theme_mod( 'neve_lite_blog_layout', 'list' ) );
}

/**
 * Get posts per row
 *
 * @since 1.0.0
 * @return int Number of posts per row
 */
function neve_lite_get_posts_per_row(): int {
    return absint( get_theme_mod( 'neve_lite_posts_per_row', 3 ) );
}

/**
 * Get copyright text
 *
 * @since 1.0.0
 * @return string Copyright text
 */
function neve_lite_get_copyright_text(): string {
    $text = get_theme_mod( 'neve_lite_copyright_text', '' );
    
    if ( empty( $text ) ) {
        $text = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __( '© %1$s %2$s. All rights reserved.', 'neve-lite' ),
            date_i18n( 'Y' ),
            get_bloginfo( 'name' )
        );
    } else {
        $text = str_replace( '{year}', date_i18n( 'Y' ), $text );
        $text = str_replace( '{site_title}', get_bloginfo( 'name' ), $text );
    }
    
    return wp_kses_post( $text );
}

/**
 * Get footer columns
 *
 * @since 1.0.0
 * @return int Number of footer columns
 */
function neve_lite_get_footer_columns(): int {
    return absint( get_theme_mod( 'neve_lite_footer_columns', 4 ) );
}

/**
 * Check if CTA button is enabled
 *
 * @since 1.0.0
 * @return bool True if CTA button is enabled
 */
function neve_lite_is_cta_button_enabled(): bool {
    return (bool) get_theme_mod( 'neve_lite_header_cta_enable', false );
}

/**
 * Get CTA button text
 *
 * @since 1.0.0
 * @return string CTA button text
 */
function neve_lite_get_cta_button_text(): string {
    return esc_html( get_theme_mod( 'neve_lite_header_cta_text', __( 'Get Started', 'neve-lite' ) ) );
}

/**
 * Get CTA button URL
 *
 * @since 1.0.0
 * @return string CTA button URL
 */
function neve_lite_get_cta_button_url(): string {
    return esc_url( get_theme_mod( 'neve_lite_header_cta_url', '#' ) );
}

/**
 * Get CTA button style
 *
 * @since 1.0.0
 * @return string CTA button style
 */
function neve_lite_get_cta_button_style(): string {
    return sanitize_html_class( get_theme_mod( 'neve_lite_header_cta_style', 'primary' ) );
}

/**
 * Add custom meta boxes
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_add_meta_boxes(): void {
    
    $post_types = array( 'post', 'page' );
    
    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'neve_lite_page_settings',
            __( 'Page Settings', 'neve-lite' ),
            'neve_lite_page_settings_callback',
            $post_type,
            'side',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', 'neve_lite_add_meta_boxes' );

/**
 * Page settings meta box callback
 *
 * @since 1.0.0
 * @param WP_Post $post Post object.
 * @return void
 */
function neve_lite_page_settings_callback( WP_Post $post ): void {
    wp_nonce_field( 'neve_lite_page_settings', 'neve_lite_page_settings_nonce' );
    
    $hide_title   = get_post_meta( $post->ID, '_neve_lite_hide_title', true );
    $hide_sidebar = get_post_meta( $post->ID, '_neve_lite_disable_sidebar', true );
    $header_layout = get_post_meta( $post->ID, '_neve_lite_header_layout', true );
    ?>
    <p>
        <label>
            <input type="checkbox" name="_neve_lite_hide_title" value="1" <?php checked( $hide_title, 1 ); ?>>
            <?php esc_html_e( 'Hide page title', 'neve-lite' ); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="_neve_lite_disable_sidebar" value="1" <?php checked( $hide_sidebar, 1 ); ?>>
            <?php esc_html_e( 'Disable sidebar', 'neve-lite' ); ?>
        </label>
    </p>
    <p>
        <label for="_neve_lite_header_layout">
            <?php esc_html_e( 'Header Layout:', 'neve-lite' ); ?>
        </label>
        <select name="_neve_lite_header_layout" id="_neve_lite_header_layout" style="width: 100%;">
            <option value="default" <?php selected( $header_layout, 'default' ); ?>><?php esc_html_e( 'Default', 'neve-lite' ); ?></option>
            <option value="transparent" <?php selected( $header_layout, 'transparent' ); ?>><?php esc_html_e( 'Transparent', 'neve-lite' ); ?></option>
        </select>
    </p>
    <?php
}

/**
 * Save page settings meta box
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return void
 */
function neve_lite_save_page_settings( int $post_id ): void {
    
    if ( ! isset( $_POST['neve_lite_page_settings_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['neve_lite_page_settings_nonce'] ) ), 'neve_lite_page_settings' ) ) {
        return;
    }
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    $hide_title = isset( $_POST['_neve_lite_hide_title'] ) ? 1 : 0;
    update_post_meta( $post_id, '_neve_lite_hide_title', $hide_title );
    
    $hide_sidebar = isset( $_POST['_neve_lite_disable_sidebar'] ) ? 1 : 0;
    update_post_meta( $post_id, '_neve_lite_disable_sidebar', $hide_sidebar );
    
    if ( isset( $_POST['_neve_lite_header_layout'] ) ) {
        update_post_meta( $post_id, '_neve_lite_header_layout', sanitize_text_field( wp_unslash( $_POST['_neve_lite_header_layout'] ) ) );
    }
}
add_action( 'save_post', 'neve_lite_save_page_settings' );

/**
 * Add lazy loading to images
 *
 * @since 1.0.0
 * @param string $content Post content.
 * @return string Modified content
 */
function neve_lite_add_lazy_loading( string $content ): string {
    
    if ( ! get_theme_mod( 'neve_lite_lazy_load_images', true ) ) {
        return $content;
    }
    
    if ( is_feed() || is_preview() || wp_doing_ajax() ) {
        return $content;
    }
    
    // Add loading="lazy" to images
    $content = preg_replace( '/<img([^>]+?)src=/i', '<img$1loading="lazy" src=', $content );
    
    return $content;
}
add_filter( 'the_content', 'neve_lite_add_lazy_loading', 20 );

/**
 * Add responsive image sizes
 *
 * @since 1.0.0
 * @param array $attr Attributes for the image markup.
 * @return array Modified attributes
 */
function neve_lite_add_responsive_image_sizes( array $attr ): array {
    
    if ( isset( $attr['sizes'] ) ) {
        $attr['sizes'] = '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 33vw';
    }
    
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'neve_lite_add_responsive_image_sizes' );

/**
 * Add schema.org markup to posts
 *
 * @since 1.0.0
 * @param array $attr Post attributes.
 * @return array Modified attributes
 */
function neve_lite_article_schema( array $attr ): array {
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'https://schema.org/CreativeWork';
    return $attr;
}
add_filter( 'neve_lite_post_attr', 'neve_lite_article_schema' );

/**
 * Output schema.org markup for posts
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_schema_markup(): void {
    
    if ( ! is_singular( 'post' ) ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'BlogPosting',
        'headline' => get_the_title(),
        'url'      => get_permalink(),
        'datePublished' => get_the_date( 'c' ),
        'dateModified'  => get_the_modified_date( 'c' ),
        'author'   => array(
            '@type' => 'Person',
            'name'  => get_the_author(),
        ),
    );
    
    if ( has_post_thumbnail() ) {
        $schema['image'] = array(
            '@type'  => 'ImageObject',
            'url'    => get_the_post_thumbnail_url( null, 'full' ),
            'width'  => 1200,
            'height' => 630,
        );
    }
    
    ?>
    <script type="application/ld+json">
    <?php echo wp_json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ); ?>
    </script>
    <?php
}
add_action( 'wp_head', 'neve_lite_schema_markup' );

/**
 * Add reading time estimate
 *
 * @since 1.0.0
 * @return string Reading time
 */
function neve_lite_get_reading_time(): string {
    $content = get_post_field( 'post_content', get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $reading_time = ceil( $word_count / 200 );
    
    /* translators: %d: Reading time in minutes */
    return sprintf( _n( '%d min read', '%d min read', $reading_time, 'neve-lite' ), $reading_time );
}

/**
 * Add custom excerpt more link
 *
 * @since 1.0.0
 * @param string $more Default more string.
 * @return string Modified more string
 */
function neve_lite_custom_excerpt_more( string $more ): string {
    return sprintf(
        '... <a href="%s" class="nv-read-more">%s</a>',
        esc_url( get_permalink() ),
        esc_html__( 'Read More', 'neve-lite' )
    );
}
add_filter( 'excerpt_more', 'neve_lite_custom_excerpt_more' );

/**
 * Add custom class to excerpt
 *
 * @since 1.0.0
 * @param string $output The excerpt.
 * @return string Modified excerpt
 */
function neve_lite_excerpt_class( string $output ): string {
    return '<p class="nv-excerpt">' . $output . '</p>';
}
add_filter( 'the_excerpt', 'neve_lite_excerpt_class' );

/**
 * Add custom class to content
 *
 * @since 1.0.0
 * @param string $content The content.
 * @return string Modified content
 */
function neve_lite_content_class( string $content ): string {
    return '<div class="nv-entry-content nv-content">' . $content . '</div>';
}
add_filter( 'the_content', 'neve_lite_content_class', 9 );

/**
 * Wrap post thumbnail with link
 *
 * @since 1.0.0
 * @param string $html Post thumbnail HTML.
 * @param int    $post_id Post ID.
 * @return string Modified HTML
 */
function neve_lite_post_thumbnail_html( string $html, int $post_id ): string {
    
    if ( is_singular() ) {
        return $html;
    }
    
    return '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="nv-post-thumbnail-link">' . $html . '</a>';
}
add_filter( 'post_thumbnail_html', 'neve_lite_post_thumbnail_html', 10, 2 );

/**
 * Add custom image sizes to srcset
 *
 * @since 1.0.0
 * @param array $sources Image sources.
 * @return array Modified sources
 */
function neve_lite_custom_image_srcset( array $sources ): array {
    
    // Add custom sizes if not present
    $custom_sizes = array(
        'neve-lite-grid'   => 400,
        'neve-lite-card'   => 600,
        'neve-lite-product' => 400,
    );
    
    foreach ( $custom_sizes as $size => $width ) {
        if ( ! isset( $sources[ $width ] ) ) {
            $sources[ $width ] = array(
                'url'        => wp_get_attachment_image_url( get_post_thumbnail_id(), $size ),
                'descriptor' => 'w',
                'value'      => $width,
            );
        }
    }
    
    return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'neve_lite_custom_image_srcset' );

/**
 * Add preload for critical assets
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_preload_assets(): void {
    
    // Preload main stylesheet
    ?>
    <link rel="preload" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" as="style">
    <?php
    
    // Preload Google Fonts if used
    $font_family = get_theme_mod( 'neve_lite_body_font_family', 'Inter' );
    if ( $font_family !== 'system' ) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . urlencode( $font_family ) . ':wght@400;500;600;700&display=swap';
        ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" href="<?php echo esc_url( $fonts_url ); ?>" as="style">
        <?php
    }
}
add_action( 'wp_head', 'neve_lite_preload_assets', 1 );
