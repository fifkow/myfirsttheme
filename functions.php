<?php
/**
 * Neve Lite Theme Functions
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define theme constants
 */
define( 'NEVE_LITE_VERSION', '1.0.0' );
define( 'NEVE_LITE_DIR', get_template_directory() );
define( 'NEVE_LITE_URI', get_template_directory_uri() );
define( 'NEVE_LITE_INC_DIR', NEVE_LITE_DIR . '/inc/' );
define( 'NEVE_LITE_ASSETS_URI', NEVE_LITE_URI . '/assets/' );

/**
 * Theme Setup
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_setup(): void {
    
    /**
     * Make theme available for translation
     */
    load_theme_textdomain( 'neve-lite', NEVE_LITE_DIR . '/languages' );
    
    /**
     * Add default posts and comments RSS feed links to head
     */
    add_theme_support( 'automatic-feed-links' );
    
    /**
     * Let WordPress manage the document title
     */
    add_theme_support( 'title-tag' );
    
    /**
     * Enable post thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 9999 );
    
    /**
     * Add support for responsive embeds
     */
    add_theme_support( 'responsive-embeds' );
    
    /**
     * Add support for block styles
     */
    add_theme_support( 'wp-block-styles' );
    
    /**
     * Add support for full and wide align images
     */
    add_theme_support( 'align-wide' );
    
    /**
     * Add support for custom logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 100,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        )
    );
    
    /**
     * Add support for custom header
     */
    add_theme_support(
        'custom-header',
        array(
            'default-image'      => '',
            'width'              => 1920,
            'height'             => 500,
            'flex-height'        => true,
            'flex-width'         => true,
            'uploads'            => true,
            'video'              => true,
            'wp-head-callback'   => 'neve_lite_header_style',
        )
    );
    
    /**
     * Add support for HTML5 markup
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );
    
    /**
     * Add support for post formats
     */
    add_theme_support(
        'post-formats',
        array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        )
    );
    
    /**
     * Add support for editor styles
     */
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );
    
    /**
     * Register navigation menus
     */
    register_nav_menus(
        array(
            'primary'   => __( 'Primary Menu', 'neve-lite' ),
            'footer'    => __( 'Footer Menu', 'neve-lite' ),
            'mobile'    => __( 'Mobile Menu', 'neve-lite' ),
            'top-bar'   => __( 'Top Bar Menu', 'neve-lite' ),
        )
    );
    
    /**
     * Add WooCommerce support
     */
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    /**
     * Add Elementor support
     */
    add_theme_support( 'elementor' );
    
    /**
     * Add editor color palette
     */
    add_theme_support(
        'editor-color-palette',
        array(
            array(
                'name'  => __( 'Black', 'neve-lite' ),
                'slug'  => 'black',
                'color' => '#000000',
            ),
            array(
                'name'  => __( 'White', 'neve-lite' ),
                'slug'  => 'white',
                'color' => '#ffffff',
            ),
            array(
                'name'  => __( 'Primary', 'neve-lite' ),
                'slug'  => 'primary',
                'color' => '#2271b1',
            ),
            array(
                'name'  => __( 'Gray', 'neve-lite' ),
                'slug'  => 'gray',
                'color' => '#6c757d',
            ),
        )
    );
    
    /**
     * Set content width
     */
    global $content_width;
    if ( ! isset( $content_width ) ) {
        $content_width = 1200;
    }
}
add_action( 'after_setup_theme', 'neve_lite_setup' );

/**
 * Enqueue scripts and styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_scripts(): void {
    
    // Theme stylesheet
    wp_enqueue_style(
        'neve-lite-style',
        get_stylesheet_uri(),
        array(),
        NEVE_LITE_VERSION
    );
    
    // Main theme CSS
    wp_enqueue_style(
        'neve-lite-main',
        NEVE_LITE_ASSETS_URI . 'css/main.css',
        array(),
        NEVE_LITE_VERSION
    );
    
    // Google Fonts (lazy loaded)
    $font_family = get_theme_mod( 'neve_lite_body_font_family', 'Inter' );
    $heading_font = get_theme_mod( 'neve_lite_heading_font_family', 'Inter' );
    
    $fonts = array();
    if ( $font_family !== 'system' ) {
        $fonts[] = $font_family . ':400,500,600,700';
    }
    if ( $heading_font !== 'system' && $heading_font !== $font_family ) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }
    
    if ( ! empty( $fonts ) ) {
        $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', array_map( 'urlencode', $fonts ) ) . '&display=swap';
        wp_enqueue_style(
            'neve-lite-google-fonts',
            $fonts_url,
            array(),
            null
        );
        add_filter( 'style_loader_tag', 'neve_lite_add_font_display_swap', 10, 4 );
    }
    
    // WooCommerce styles (only on WooCommerce pages)
    if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
        wp_enqueue_style(
            'neve-lite-woocommerce',
            NEVE_LITE_ASSETS_URI . 'css/woocommerce.css',
            array(),
            NEVE_LITE_VERSION
        );
    }
    
    // Theme JavaScript
    wp_enqueue_script(
        'neve-lite-main',
        NEVE_LITE_ASSETS_URI . 'js/main.js',
        array(),
        NEVE_LITE_VERSION,
        true
    );
    
    // Mobile menu script
    wp_enqueue_script(
        'neve-lite-navigation',
        NEVE_LITE_ASSETS_URI . 'js/navigation.js',
        array(),
        NEVE_LITE_VERSION,
        true
    );
    
    // Comment reply script on singular posts
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    // Pass theme settings to JavaScript
    wp_localize_script(
        'neve-lite-main',
        'neveLiteData',
        array(
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'neve-lite-nonce' ),
            'strings'   => array(
                'menuOpen'   => __( 'Open menu', 'neve-lite' ),
                'menuClose'  => __( 'Close menu', 'neve-lite' ),
                'submenuOpen' => __( 'Open submenu', 'neve-lite' ),
            ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'neve_lite_scripts' );

/**
 * Add font-display: swap to Google Fonts
 *
 * @since 1.0.0
 * @param string $html   The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href   The stylesheet's source URL.
 * @param string $media  The stylesheet's media attribute.
 * @return string Modified HTML link tag
 */
function neve_lite_add_font_display_swap( string $html, string $handle, string $href, string $media ): string {
    if ( 'neve-lite-google-fonts' === $handle ) {
        $html = str_replace( 'media=\'' . $media . '\'', 'media=\'' . $media . '\' crossorigin', $html );
    }
    return $html;
}

/**
 * Register widget areas
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_widgets_init(): void {
    
    // Main sidebar
    register_sidebar(
        array(
            'name'          => __( 'Sidebar', 'neve-lite' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets here to appear in your sidebar.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
    
    // Footer widget areas
    register_sidebar(
        array(
            'name'          => __( 'Footer 1', 'neve-lite' ),
            'id'            => 'footer-1',
            'description'   => __( 'First footer widget area.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="nv-footer-widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => __( 'Footer 2', 'neve-lite' ),
            'id'            => 'footer-2',
            'description'   => __( 'Second footer widget area.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="nv-footer-widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => __( 'Footer 3', 'neve-lite' ),
            'id'            => 'footer-3',
            'description'   => __( 'Third footer widget area.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="nv-footer-widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => __( 'Footer 4', 'neve-lite' ),
            'id'            => 'footer-4',
            'description'   => __( 'Fourth footer widget area.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="nv-footer-widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    // Header widget area
    register_sidebar(
        array(
            'name'          => __( 'Header', 'neve-lite' ),
            'id'            => 'header',
            'description'   => __( 'Widgets in the header area.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    // Shop sidebar (WooCommerce)
    if ( class_exists( 'WooCommerce' ) ) {
        register_sidebar(
            array(
                'name'          => __( 'Shop Sidebar', 'neve-lite' ),
                'id'            => 'shop-sidebar',
                'description'   => __( 'Sidebar for WooCommerce shop pages.', 'neve-lite' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );
    }
}
add_action( 'widgets_init', 'neve_lite_widgets_init' );

/**
 * Include required files
 */
require_once NEVE_LITE_INC_DIR . 'customizer.php';
require_once NEVE_LITE_INC_DIR . 'template-functions.php';
require_once NEVE_LITE_INC_DIR . 'template-tags.php';

// WooCommerce integration
if ( class_exists( 'WooCommerce' ) ) {
    require_once NEVE_LITE_INC_DIR . 'woocommerce.php';
}

// Elementor integration
if ( defined( 'ELEMENTOR_VERSION' ) ) {
    require_once NEVE_LITE_INC_DIR . 'elementor.php';
}

// Onboarding / Setup Wizard (admin only)
if ( is_admin() ) {
    require_once NEVE_LITE_INC_DIR . 'onboarding.php';
}

/**
 * Disable WordPress default gallery styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_disable_gallery_styles(): void {
    add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'init', 'neve_lite_disable_gallery_styles' );

/**
 * Add custom body classes
 *
 * @since 1.0.0
 * @param array $classes Classes for the body element.
 * @return array Modified body classes
 */
function neve_lite_body_classes( array $classes ): array {
    
    // Add class if sidebar is active
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'elementor-full-width.php' ) ) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add class for header layout
    $header_layout = get_theme_mod( 'neve_lite_header_layout', 'default' );
    $classes[] = 'nv-header-' . sanitize_html_class( $header_layout );
    
    // Add class for container width
    $container_width = get_theme_mod( 'neve_lite_container_width', 'contained' );
    $classes[] = 'nv-container-' . sanitize_html_class( $container_width );
    
    // Add class if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'has-woocommerce';
    }
    
    // Add class if Elementor is active
    if ( defined( 'ELEMENTOR_VERSION' ) ) {
        $classes[] = 'has-elementor';
    }
    
    return $classes;
}
add_filter( 'body_class', 'neve_lite_body_classes' );

/**
 * Add custom excerpt length
 *
 * @since 1.0.0
 * @param int $length Excerpt length.
 * @return int Modified excerpt length
 */
function neve_lite_excerpt_length( int $length ): int {
    return absint( get_theme_mod( 'neve_lite_excerpt_length', 55 ) );
}
add_filter( 'excerpt_length', 'neve_lite_excerpt_length', 999 );

/**
 * Add custom excerpt more
 *
 * @since 1.0.0
 * @param string $more The string shown within the more link.
 * @return string Modified excerpt more
 */
function neve_lite_excerpt_more( string $more ): string {
    return '...';
}
add_filter( 'excerpt_more', 'neve_lite_excerpt_more' );

/**
 * Add skip link focus fix for IE11
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_skip_link_focus_fix(): void {
    ?>
    <script>
    /(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
        var t, e = location.hash.substring(1);
        /^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
    }, !1);
    </script>
    <?php
}
add_action( 'wp_print_footer_scripts', 'neve_lite_skip_link_focus_fix' );

/**
 * Disable emoji scripts
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_disable_emojis(): void {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'neve_lite_disable_emojis' );

/**
 * Remove WordPress version from head
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_remove_wp_version(): void {
    remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'neve_lite_remove_wp_version' );

/**
 * Add async/defer to scripts
 *
 * @since 1.0.0
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag
 */
function neve_lite_add_async_defer( string $tag, string $handle ): string {
    $async_scripts = array(
        'neve-lite-main',
        'neve-lite-navigation',
    );
    
    if ( in_array( $handle, $async_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'neve_lite_add_async_defer', 10, 2 );

/**
 * Custom template hierarchy
 *
 * @since 1.0.0
 * @param string $template The path of the template to include.
 * @return string Modified template path
 */
function neve_lite_template_hierarchy( string $template ): string {
    
    // Check for custom templates in template-parts
    if ( is_singular( 'post' ) ) {
        $custom_template = locate_template( 'template-parts/content/single-post.php' );
        if ( $custom_template ) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter( 'template_include', 'neve_lite_template_hierarchy', 99 );

/**
 * Add custom image sizes
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_add_image_sizes(): void {
    add_image_size( 'neve-lite-grid', 400, 300, true );
    add_image_size( 'neve-lite-card', 600, 400, true );
    add_image_size( 'neve-lite-hero', 1920, 600, true );
    add_image_size( 'neve-lite-product', 400, 400, true );
}
add_action( 'after_setup_theme', 'neve_lite_add_image_sizes' );

/**
 * Make custom image sizes available in media library
 *
 * @since 1.0.0
 * @param array $sizes Image sizes.
 * @return array Modified image sizes
 */
function neve_lite_custom_image_sizes( array $sizes ): array {
    return array_merge(
        $sizes,
        array(
            'neve-lite-grid'   => __( 'Grid', 'neve-lite' ),
            'neve-lite-card'   => __( 'Card', 'neve-lite' ),
            'neve-lite-hero'   => __( 'Hero', 'neve-lite' ),
            'neve-lite-product' => __( 'Product', 'neve-lite' ),
        )
    );
}
add_filter( 'image_size_names_choose', 'neve_lite_custom_image_sizes' );

/**
 * Header style callback
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_header_style(): void {
    $header_text_color = get_header_textcolor();
    
    if ( 'blank' === $header_text_color ) {
        ?>
        <style type="text/css">
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }
        </style>
        <?php
    } else {
        ?>
        <style type="text/css">
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }
        </style>
        <?php
    }
}

/**
 * Add preconnect for Google Fonts
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_resource_hints( array $urls, string $relation_type ): array {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'neve_lite_resource_hints', 10, 2 );

/**
 * Custom login logo
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_login_logo(): void {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo ) {
            ?>
            <style type="text/css">
                #login h1 a {
                    background-image: url(<?php echo esc_url( $logo[0] ); ?>);
                    background-size: contain;
                    width: 100%;
                    height: 84px;
                }
            </style>
            <?php
        }
    }
}
add_action( 'login_enqueue_scripts', 'neve_lite_login_logo' );

/**
 * Change login logo URL
 *
 * @since 1.0.0
 * @return string Home URL
 */
function neve_lite_login_logo_url(): string {
    return home_url();
}
add_filter( 'login_headerurl', 'neve_lite_login_logo_url' );

/**
 * Change login logo title
 *
 * @since 1.0.0
 * @return string Site name
 */
function neve_lite_login_logo_title(): string {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'neve_lite_login_logo_title' );
