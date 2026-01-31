<?php
/**
 * Neve Lite Elementor Integration
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if Elementor is active
 *
 * @since 1.0.0
 * @return bool True if Elementor is active
 */
function neve_lite_is_elementor_active(): bool {
    return defined( 'ELEMENTOR_VERSION' );
}

/**
 * Check if current page is built with Elementor
 *
 * @since 1.0.0
 * @param int $post_id Optional. Post ID.
 * @return bool True if page is built with Elementor
 */
function neve_lite_is_elementor_page( int $post_id = 0 ): bool {
    if ( ! neve_lite_is_elementor_active() ) {
        return false;
    }
    
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    if ( ! $post_id ) {
        return false;
    }
    
    return \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
}

/**
 * Elementor compatibility setup
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_setup(): void {
    
    // Add theme support for Elementor
    add_theme_support( 'elementor' );
    
    // Add support for Elementor Pro locations
    add_theme_support( 'elementor-pro' );
    
    // Add support for Elementor experiments
    add_theme_support( 'elementor-experiment-container' );
    add_theme_support( 'elementor-experiment-container_grid' );
    add_theme_support( 'elementor-experiment-nested-elements' );
    add_theme_support( 'elementor-experiment-e_dom_optimization' );
    add_theme_support( 'elementor-experiment-e_optimized_assets_loading' );
    add_theme_support( 'elementor-experiment-e_optimized_css_loading' );
    add_theme_support( 'elementor-experiment-additional_custom_breakpoints' );
    add_theme_support( 'elementor-experiment-e_swiper_latest' );
    add_theme_support( 'elementor-experiment-e_nested_atomic_repeaters' );
    add_theme_support( 'elementor-experiment-e_onboarding' );
    
    // Register Elementor locations
    add_action( 'elementor/theme/register_locations', 'neve_lite_register_elementor_locations' );
}
add_action( 'after_setup_theme', 'neve_lite_elementor_setup' );

/**
 * Register Elementor theme locations
 *
 * @since 1.0.0
 * @param \Elementor\Core\Common\Modules\Finder\Categories\Theme $theme_manager Theme manager.
 * @return void
 */
function neve_lite_register_elementor_locations( $theme_manager ): void {
    $theme_manager->register_all_core_location();
}

/**
 * Add custom Elementor settings
 *
 * @since 1.0.0
 * @param \Elementor\Core\Kits\Documents\Tabs\Tab_Base $element Elementor element.
 * @return void
 */
function neve_lite_add_elementor_settings( $element ): void {
    
    // Add custom colors
    $element->add_control(
        'neve_lite_primary_color',
        array(
            'label'     => __( 'Primary Color', 'neve-lite' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0073aa',
            'selectors' => array(
                '{{WRAPPER}}' => '--nv-primary-color: {{VALUE}};',
            ),
        )
    );
}

/**
 * Enqueue Elementor compatibility styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_styles(): void {
    
    if ( ! neve_lite_is_elementor_active() ) {
        return;
    }
    
    wp_enqueue_style(
        'neve-lite-elementor',
        NEVE_LITE_ASSETS_URI . 'css/elementor.css',
        array(),
        NEVE_LITE_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'neve_lite_elementor_styles', 20 );

/**
 * Add Elementor editor styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_editor_styles(): void {
    
    wp_add_inline_style(
        'elementor-editor',
        '
        .elementor-panel-menu-item-neve-lite {
            display: none;
        }
        '
    );
}
add_action( 'elementor/editor/after_enqueue_styles', 'neve_lite_elementor_editor_styles' );

/**
 * Add Elementor preview styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_preview_styles(): void {
    
    wp_add_inline_style(
        'elementor-preview',
        '
        .elementor-editor-preview .nv-header,
        .elementor-editor-preview .nv-footer {
            display: block !important;
        }
        '
    );
}
add_action( 'elementor/preview/enqueue_styles', 'neve_lite_elementor_preview_styles' );

/**
 * Disable default page title on Elementor pages
 *
 * @since 1.0.0
 * @param bool $show_title Whether to show title.
 * @return bool Modified show title
 */
function neve_lite_elementor_disable_title( bool $show_title ): bool {
    
    if ( neve_lite_is_elementor_page() ) {
        return false;
    }
    
    return $show_title;
}
add_filter( 'neve_lite_show_page_title', 'neve_lite_elementor_disable_title' );

/**
 * Disable sidebar on Elementor pages
 *
 * @since 1.0.0
 * @param bool $has_sidebar Whether to show sidebar.
 * @return bool Modified has sidebar
 */
function neve_lite_elementor_disable_sidebar( bool $has_sidebar ): bool {
    
    if ( neve_lite_is_elementor_page() ) {
        return false;
    }
    
    return $has_sidebar;
}
add_filter( 'neve_lite_has_sidebar', 'neve_lite_elementor_disable_sidebar' );

/**
 * Add Elementor Canvas body class
 *
 * @since 1.0.0
 * @param array $classes Body classes.
 * @return array Modified classes
 */
function neve_lite_elementor_body_classes( array $classes ): array {
    
    if ( is_page_template( 'elementor-canvas.php' ) ) {
        $classes[] = 'nv-elementor-canvas';
    }
    
    if ( is_page_template( 'elementor-full-width.php' ) ) {
        $classes[] = 'nv-elementor-full-width';
    }
    
    if ( neve_lite_is_elementor_page() ) {
        $classes[] = 'nv-elementor-page';
    }
    
    return $classes;
}
add_filter( 'body_class', 'neve_lite_elementor_body_classes' );

/**
 * Add Elementor global colors support
 *
 * @since 1.0.0
 * @param \Elementor\Core\Kits\Documents\Kit $kit Elementor kit.
 * @return void
 */
function neve_lite_elementor_global_colors( $kit ): void {
    
    $system_colors = array(
        array(
            '_id'   => 'primary',
            'title' => __( 'Primary', 'neve-lite' ),
            'color' => get_theme_mod( 'neve_lite_primary_color', '#0073aa' ),
        ),
        array(
            '_id'   => 'secondary',
            'title' => __( 'Secondary', 'neve-lite' ),
            'color' => get_theme_mod( 'neve_lite_secondary_color', '#676767' ),
        ),
        array(
            '_id'   => 'text',
            'title' => __( 'Text', 'neve-lite' ),
            'color' => get_theme_mod( 'neve_lite_text_color', '#404248' ),
        ),
        array(
            '_id'   => 'accent',
            'title' => __( 'Accent', 'neve-lite' ),
            'color' => get_theme_mod( 'neve_lite_primary_color', '#0073aa' ),
        ),
    );
    
    $kit->update_settings( array( 'system_colors' => $system_colors ) );
}
add_action( 'elementor/kit/register_tabs', 'neve_lite_elementor_global_colors', 10 );

/**
 * Add Elementor global fonts support
 *
 * @since 1.0.0
 * @param \Elementor\Core\Kits\Documents\Kit $kit Elementor kit.
 * @return void
 */
function neve_lite_elementor_global_fonts( $kit ): void {
    
    $body_font    = get_theme_mod( 'neve_lite_body_font_family', 'Inter' );
    $heading_font = get_theme_mod( 'neve_lite_heading_font_family', 'Inter' );
    
    $system_typography = array(
        array(
            '_id'                     => 'primary',
            'title'                   => __( 'Primary', 'neve-lite' ),
            'typography_font_family'  => $body_font,
            'typography_font_weight'  => 'normal',
        ),
        array(
            '_id'                     => 'secondary',
            'title'                   => __( 'Secondary', 'neve-lite' ),
            'typography_font_family'  => $body_font,
            'typography_font_weight'  => 'normal',
        ),
        array(
            '_id'                     => 'text',
            'title'                   => __( 'Text', 'neve-lite' ),
            'typography_font_family'  => $body_font,
            'typography_font_weight'  => 'normal',
        ),
        array(
            '_id'                     => 'accent',
            'title'                   => __( 'Accent', 'neve-lite' ),
            'typography_font_family'  => $heading_font,
            'typography_font_weight'  => '500',
        ),
    );
    
    $kit->update_settings( array( 'system_typography' => $system_typography ) );
}
add_action( 'elementor/kit/register_tabs', 'neve_lite_elementor_global_fonts', 20 );

/**
 * Add Elementor custom breakpoints
 *
 * @since 1.0.0
 * @param array $breakpoints Default breakpoints.
 * @return array Modified breakpoints
 */
function neve_lite_elementor_breakpoints( array $breakpoints ): array {
    
    $breakpoints['mobile'] = 767;
    $breakpoints['tablet'] = 991;
    
    return $breakpoints;
}
add_filter( 'elementor/editor/breakpoints', 'neve_lite_elementor_breakpoints' );

/**
 * Add Elementor widget areas
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_widgets_init(): void {
    
    // Elementor Header widget area
    register_sidebar(
        array(
            'name'          => __( 'Elementor Header', 'neve-lite' ),
            'id'            => 'elementor-header',
            'description'   => __( 'Widget area for Elementor header templates.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    // Elementor Footer widget area
    register_sidebar(
        array(
            'name'          => __( 'Elementor Footer', 'neve-lite' ),
            'id'            => 'elementor-footer',
            'description'   => __( 'Widget area for Elementor footer templates.', 'neve-lite' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'neve_lite_elementor_widgets_init' );

/**
 * Add Elementor Pro support
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_pro_support(): void {
    
    if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
        return;
    }
    
    // Add support for theme builder
    add_theme_support( 'elementor-pro-theme-builder' );
    
    // Add support for WooCommerce builder
    add_theme_support( 'elementor-pro-woocommerce' );
    
    // Add support for popup builder
    add_theme_support( 'elementor-pro-popup' );
}
add_action( 'after_setup_theme', 'neve_lite_elementor_pro_support' );

/**
 * Add Elementor sticky header support
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_sticky_header(): void {
    
    if ( ! neve_lite_is_elementor_active() ) {
        return;
    }
    
    // Check if Elementor sticky header is active
    $sticky_header = get_option( 'elementor_sticky_header', false );
    
    if ( $sticky_header ) {
        add_filter( 'neve_lite_is_sticky_header', '__return_true' );
    }
}
add_action( 'init', 'neve_lite_elementor_sticky_header' );

/**
 * Add Elementor Flexbox container support
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_flexbox_container(): void {
    
    if ( ! neve_lite_is_elementor_active() ) {
        return;
    }
    
    // Enable flexbox container experiment
    update_option( 'elementor_experiment-container', 'active' );
    update_option( 'elementor_experiment-container_grid', 'active' );
    update_option( 'elementor_experiment-nested-elements', 'active' );
}
add_action( 'init', 'neve_lite_elementor_flexbox_container' );

/**
 * Add Elementor performance optimizations
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_performance(): void {
    
    if ( ! neve_lite_is_elementor_active() ) {
        return;
    }
    
    // Enable optimized DOM output
    update_option( 'elementor_experiment-e_dom_optimization', 'active' );
    
    // Enable optimized assets loading
    update_option( 'elementor_experiment-e_optimized_assets_loading', 'active' );
    
    // Enable optimized CSS loading
    update_option( 'elementor_experiment-e_optimized_css_loading', 'active' );
}
add_action( 'init', 'neve_lite_elementor_performance' );

/**
 * Add Elementor Customizer settings
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function neve_lite_elementor_customizer( WP_Customize_Manager $wp_customize ): void {
    
    // Elementor Section
    $wp_customize->add_section(
        'neve_lite_elementor',
        array(
            'title'       => __( 'Elementor', 'neve-lite' ),
            'description' => __( 'Settings for Elementor page builder integration.', 'neve-lite' ),
            'priority'    => 90,
        )
    );
    
    // Disable theme styles on Elementor pages
    $wp_customize->add_setting(
        'neve_lite_elementor_disable_styles',
        array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_elementor_disable_styles',
        array(
            'label'       => __( 'Disable theme styles on Elementor pages', 'neve-lite' ),
            'description' => __( 'Disable theme CSS on pages built with Elementor for full design control.', 'neve-lite' ),
            'section'     => 'neve_lite_elementor',
            'type'        => 'checkbox',
        )
    );
    
    // Full width sections
    $wp_customize->add_setting(
        'neve_lite_elementor_full_width',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_elementor_full_width',
        array(
            'label'       => __( 'Enable full-width sections', 'neve-lite' ),
            'description' => __( 'Allow Elementor sections to span full width of the page.', 'neve-lite' ),
            'section'     => 'neve_lite_elementor',
            'type'        => 'checkbox',
        )
    );
}
add_action( 'customize_register', 'neve_lite_elementor_customizer' );

/**
 * Disable theme styles on Elementor pages if enabled
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_maybe_disable_theme_styles(): void {
    
    if ( ! get_theme_mod( 'neve_lite_elementor_disable_styles', false ) ) {
        return;
    }
    
    if ( ! neve_lite_is_elementor_page() ) {
        return;
    }
    
    // Dequeue theme styles
    wp_dequeue_style( 'neve-lite-style' );
    wp_dequeue_style( 'neve-lite-main' );
}
add_action( 'wp_enqueue_scripts', 'neve_lite_maybe_disable_theme_styles', 100 );

/**
 * Add Elementor post type support
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_post_type_support(): void {
    
    // Add Elementor support to all public post types
    $post_types = get_post_types( array( 'public' => true ), 'names' );
    
    foreach ( $post_types as $post_type ) {
        add_post_type_support( $post_type, 'elementor' );
    }
}
add_action( 'init', 'neve_lite_elementor_post_type_support' );

/**
 * Add Elementor hooks for header/footer builder
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_elementor_header_footer_hooks(): void {
    
    if ( ! neve_lite_is_elementor_active() ) {
        return;
    }
    
    // Check if Elementor header/footer is being used
    $header_template = get_option( 'elementor_active_header_template' );
    $footer_template = get_option( 'elementor_active_footer_template' );
    
    if ( $header_template ) {
        remove_action( 'neve_lite_header', 'neve_lite_display_header', 10 );
    }
    
    if ( $footer_template ) {
        remove_action( 'neve_lite_footer', 'neve_lite_display_footer', 10 );
    }
}
add_action( 'wp', 'neve_lite_elementor_header_footer_hooks' );
