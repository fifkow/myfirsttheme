<?php
/**
 * Neve Lite Child Theme Functions
 *
 * @package Neve_Lite_Child
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_child_enqueue_styles(): void {
    
    // Parent theme style
    wp_enqueue_style(
        'neve-lite-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( 'neve-lite' )->get( 'Version' )
    );
    
    // Child theme style
    wp_enqueue_style(
        'neve-lite-child-style',
        get_stylesheet_uri(),
        array( 'neve-lite-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
    
    // Child theme custom JavaScript
    wp_enqueue_script(
        'neve-lite-child-script',
        get_stylesheet_directory_uri() . '/js/child-script.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'neve_lite_child_enqueue_styles', 20 );

/**
 * Add custom theme support
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_child_setup(): void {
    
    // Add custom image sizes
    // add_image_size( 'child-custom-size', 800, 600, true );
    
    // Register additional navigation menu
    // register_nav_menu( 'child-custom-menu', __( 'Child Custom Menu', 'neve-lite-child' ) );
    
    // Add theme support for additional features
    // add_theme_support( 'custom-header', array(
    //     'width'  => 1920,
    //     'height' => 400,
    // ) );
}
add_action( 'after_setup_theme', 'neve_lite_child_setup' );

/**
 * Add custom body classes
 *
 * @since 1.0.0
 * @param array $classes Existing body classes.
 * @return array Modified body classes
 */
function neve_lite_child_body_classes( array $classes ): array {
    
    // Add custom class
    // $classes[] = 'child-custom-class';
    
    return $classes;
}
// add_filter( 'body_class', 'neve_lite_child_body_classes' );

/**
 * Modify excerpt length
 *
 * @since 1.0.0
 * @param int $length Default excerpt length.
 * @return int Modified excerpt length
 */
function neve_lite_child_excerpt_length( int $length ): int {
    return $length;
}
// add_filter( 'excerpt_length', 'neve_lite_child_excerpt_length', 999 );

/**
 * Add custom widget areas
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_child_widgets_init(): void {
    
    // Register custom widget area
    // register_sidebar( array(
    //     'name'          => __( 'Child Custom Sidebar', 'neve-lite-child' ),
    //     'id'            => 'child-custom-sidebar',
    //     'description'   => __( 'Add widgets here.', 'neve-lite-child' ),
    //     'before_widget' => '<section id="%1$s" class="widget %2$s">',
    //     'after_widget'  => '</section>',
    //     'before_title'  => '<h2 class="widget-title">',
    //     'after_title'   => '</h2>',
    // ) );
}
// add_action( 'widgets_init', 'neve_lite_child_widgets_init', 11 );

/**
 * Add custom Customizer settings
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function neve_lite_child_customize_register( WP_Customize_Manager $wp_customize ): void {
    
    // Add custom section
    // $wp_customize->add_section( 'neve_lite_child_custom', array(
    //     'title'    => __( 'Child Theme Options', 'neve-lite-child' ),
    //     'priority' => 160,
    // ) );
    
    // Add custom setting
    // $wp_customize->add_setting( 'neve_lite_child_custom_option', array(
    //     'default'           => '',
    //     'sanitize_callback' => 'sanitize_text_field',
    //     'transport'         => 'refresh',
    // ) );
    
    // Add custom control
    // $wp_customize->add_control( 'neve_lite_child_custom_option', array(
    //     'label'   => __( 'Custom Option', 'neve-lite-child' ),
    //     'section' => 'neve_lite_child_custom',
    //     'type'    => 'text',
    // ) );
}
// add_action( 'customize_register', 'neve_lite_child_customize_register' );

/**
 * Add custom meta boxes
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_child_add_meta_boxes(): void {
    
    // Add custom meta box
    // add_meta_box(
    //     'neve_lite_child_meta_box',
    //     __( 'Custom Meta Box', 'neve-lite-child' ),
    //     'neve_lite_child_meta_box_callback',
    //     array( 'post', 'page' ),
    //     'side',
    //     'default'
    // );
}
// add_action( 'add_meta_boxes', 'neve_lite_child_add_meta_boxes' );

/**
 * Custom template tags
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_child_custom_template_tag(): void {
    // Your custom template tag code here
}

/**
 * Custom shortcodes
 *
 * @since 1.0.0
 * @param array $atts Shortcode attributes.
 * @return string Shortcode output
 */
function neve_lite_child_custom_shortcode( array $atts ): string {
    $atts = shortcode_atts(
        array(
            'param' => 'default',
        ),
        $atts,
        'neve_lite_child_shortcode'
    );
    
    ob_start();
    ?>
    <!-- Your shortcode HTML here -->
    <div class="neve-lite-child-shortcode">
        <?php echo esc_html( $atts['param'] ); ?>
    </div>
    <?php
    return ob_get_clean();
}
// add_shortcode( 'neve_lite_child_shortcode', 'neve_lite_child_custom_shortcode' );
