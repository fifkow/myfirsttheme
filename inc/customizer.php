<?php
/**
 * Neve Lite Customizer
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer setup
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function neve_lite_customize_register( WP_Customize_Manager $wp_customize ): void {
    
    /**
     * Site Identity is already included by WordPress
     * We add additional settings here
     */
    
    // ============================================
    // PANEL: Global Settings
    // ============================================
    $wp_customize->add_panel(
        'neve_lite_global',
        array(
            'title'    => __( 'Global Settings', 'neve-lite' ),
            'priority' => 30,
        )
    );
    
    // ============================================
    // SECTION: Colors
    // ============================================
    $wp_customize->add_section(
        'neve_lite_colors',
        array(
            'title'    => __( 'Colors', 'neve-lite' ),
            'panel'    => 'neve_lite_global',
            'priority' => 10,
        )
    );
    
    // Primary Color
    $wp_customize->add_setting(
        'neve_lite_primary_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_primary_color',
            array(
                'label'   => __( 'Primary Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // Secondary Color
    $wp_customize->add_setting(
        'neve_lite_secondary_color',
        array(
            'default'           => '#676767',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_secondary_color',
            array(
                'label'   => __( 'Secondary Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // Text Color
    $wp_customize->add_setting(
        'neve_lite_text_color',
        array(
            'default'           => '#404248',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_text_color',
            array(
                'label'   => __( 'Text Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // Background Color
    $wp_customize->add_setting(
        'neve_lite_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_background_color',
            array(
                'label'   => __( 'Background Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // Link Color
    $wp_customize->add_setting(
        'neve_lite_link_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_link_color',
            array(
                'label'   => __( 'Link Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // Link Hover Color
    $wp_customize->add_setting(
        'neve_lite_link_hover_color',
        array(
            'default'           => '#005a87',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_link_hover_color',
            array(
                'label'   => __( 'Link Hover Color', 'neve-lite' ),
                'section' => 'neve_lite_colors',
            )
        )
    );
    
    // ============================================
    // SECTION: Typography
    // ============================================
    $wp_customize->add_section(
        'neve_lite_typography',
        array(
            'title'    => __( 'Typography', 'neve-lite' ),
            'panel'    => 'neve_lite_global',
            'priority' => 20,
        )
    );
    
    // Body Font Family
    $wp_customize->add_setting(
        'neve_lite_body_font_family',
        array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_body_font_family',
        array(
            'label'   => __( 'Body Font Family', 'neve-lite' ),
            'section' => 'neve_lite_typography',
            'type'    => 'select',
            'choices' => neve_lite_get_google_fonts_choices(),
        )
    );
    
    // Heading Font Family
    $wp_customize->add_setting(
        'neve_lite_heading_font_family',
        array(
            'default'           => 'Inter',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_heading_font_family',
        array(
            'label'   => __( 'Heading Font Family', 'neve-lite' ),
            'section' => 'neve_lite_typography',
            'type'    => 'select',
            'choices' => neve_lite_get_google_fonts_choices(),
        )
    );
    
    // Base Font Size
    $wp_customize->add_setting(
        'neve_lite_base_font_size',
        array(
            'default'           => 16,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_base_font_size',
        array(
            'label'       => __( 'Base Font Size (px)', 'neve-lite' ),
            'section'     => 'neve_lite_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        )
    );
    
    // Line Height
    $wp_customize->add_setting(
        'neve_lite_line_height',
        array(
            'default'           => 1.6,
            'sanitize_callback' => 'floatval',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_line_height',
        array(
            'label'       => __( 'Line Height', 'neve-lite' ),
            'section'     => 'neve_lite_typography',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2.5,
                'step' => 0.1,
            ),
        )
    );
    
    // ============================================
    // SECTION: Container
    // ============================================
    $wp_customize->add_section(
        'neve_lite_container',
        array(
            'title'    => __( 'Container', 'neve-lite' ),
            'panel'    => 'neve_lite_global',
            'priority' => 30,
        )
    );
    
    // Container Width
    $wp_customize->add_setting(
        'neve_lite_container_width_px',
        array(
            'default'           => 1200,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_container_width_px',
        array(
            'label'       => __( 'Container Width (px)', 'neve-lite' ),
            'section'     => 'neve_lite_container',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 800,
                'max'  => 1920,
                'step' => 10,
            ),
        )
    );
    
    // Container Padding
    $wp_customize->add_setting(
        'neve_lite_container_padding',
        array(
            'default'           => 15,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_container_padding',
        array(
            'label'       => __( 'Container Padding (px)', 'neve-lite' ),
            'section'     => 'neve_lite_container',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 100,
                'step' => 5,
            ),
        )
    );
    
    // ============================================
    // PANEL: Header
    // ============================================
    $wp_customize->add_panel(
        'neve_lite_header',
        array(
            'title'    => __( 'Header', 'neve-lite' ),
            'priority' => 40,
        )
    );
    
    // ============================================
    // SECTION: Header Layout
    // ============================================
    $wp_customize->add_section(
        'neve_lite_header_layout',
        array(
            'title'    => __( 'Layout', 'neve-lite' ),
            'panel'    => 'neve_lite_header',
            'priority' => 10,
        )
    );
    
    // Header Layout
    $wp_customize->add_setting(
        'neve_lite_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_layout',
        array(
            'label'   => __( 'Header Layout', 'neve-lite' ),
            'section' => 'neve_lite_header_layout',
            'type'    => 'select',
            'choices' => array(
                'default'     => __( 'Default', 'neve-lite' ),
                'centered'    => __( 'Centered', 'neve-lite' ),
                'transparent' => __( 'Transparent', 'neve-lite' ),
            ),
        )
    );
    
    // Sticky Header
    $wp_customize->add_setting(
        'neve_lite_sticky_header',
        array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_sticky_header',
        array(
            'label'   => __( 'Enable Sticky Header', 'neve-lite' ),
            'section' => 'neve_lite_header_layout',
            'type'    => 'checkbox',
        )
    );
    
    // Header Height
    $wp_customize->add_setting(
        'neve_lite_header_height',
        array(
            'default'           => 70,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_height',
        array(
            'label'       => __( 'Header Height (px)', 'neve-lite' ),
            'section'     => 'neve_lite_header_layout',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 50,
                'max'  => 150,
                'step' => 5,
            ),
        )
    );
    
    // ============================================
    // SECTION: Header Colors
    // ============================================
    $wp_customize->add_section(
        'neve_lite_header_colors',
        array(
            'title'    => __( 'Colors', 'neve-lite' ),
            'panel'    => 'neve_lite_header',
            'priority' => 20,
        )
    );
    
    // Header Background
    $wp_customize->add_setting(
        'neve_lite_header_background',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_header_background',
            array(
                'label'   => __( 'Header Background', 'neve-lite' ),
                'section' => 'neve_lite_header_colors',
            )
        )
    );
    
    // Header Text Color
    $wp_customize->add_setting(
        'neve_lite_header_text_color',
        array(
            'default'           => '#404248',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_header_text_color',
            array(
                'label'   => __( 'Header Text Color', 'neve-lite' ),
                'section' => 'neve_lite_header_colors',
            )
        )
    );
    
    // ============================================
    // SECTION: CTA Button
    // ============================================
    $wp_customize->add_section(
        'neve_lite_header_cta',
        array(
            'title'    => __( 'CTA Button', 'neve-lite' ),
            'panel'    => 'neve_lite_header',
            'priority' => 30,
        )
    );
    
    // Enable CTA Button
    $wp_customize->add_setting(
        'neve_lite_header_cta_enable',
        array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_cta_enable',
        array(
            'label'   => __( 'Enable CTA Button', 'neve-lite' ),
            'section' => 'neve_lite_header_cta',
            'type'    => 'checkbox',
        )
    );
    
    // CTA Button Text
    $wp_customize->add_setting(
        'neve_lite_header_cta_text',
        array(
            'default'           => __( 'Get Started', 'neve-lite' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_cta_text',
        array(
            'label'   => __( 'Button Text', 'neve-lite' ),
            'section' => 'neve_lite_header_cta',
            'type'    => 'text',
        )
    );
    
    // CTA Button URL
    $wp_customize->add_setting(
        'neve_lite_header_cta_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_cta_url',
        array(
            'label'   => __( 'Button URL', 'neve-lite' ),
            'section' => 'neve_lite_header_cta',
            'type'    => 'url',
        )
    );
    
    // CTA Button Style
    $wp_customize->add_setting(
        'neve_lite_header_cta_style',
        array(
            'default'           => 'primary',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_header_cta_style',
        array(
            'label'   => __( 'Button Style', 'neve-lite' ),
            'section' => 'neve_lite_header_cta',
            'type'    => 'select',
            'choices' => array(
                'primary'  => __( 'Primary', 'neve-lite' ),
                'outline'  => __( 'Outline', 'neve-lite' ),
                'ghost'    => __( 'Ghost', 'neve-lite' ),
            ),
        )
    );
    
    // ============================================
    // PANEL: Footer
    // ============================================
    $wp_customize->add_panel(
        'neve_lite_footer',
        array(
            'title'    => __( 'Footer', 'neve-lite' ),
            'priority' => 50,
        )
    );
    
    // ============================================
    // SECTION: Footer Layout
    // ============================================
    $wp_customize->add_section(
        'neve_lite_footer_layout',
        array(
            'title'    => __( 'Layout', 'neve-lite' ),
            'panel'    => 'neve_lite_footer',
            'priority' => 10,
        )
    );
    
    // Footer Widget Columns
    $wp_customize->add_setting(
        'neve_lite_footer_columns',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_footer_columns',
        array(
            'label'   => __( 'Widget Columns', 'neve-lite' ),
            'section' => 'neve_lite_footer_layout',
            'type'    => 'select',
            'choices' => array(
                1 => __( '1 Column', 'neve-lite' ),
                2 => __( '2 Columns', 'neve-lite' ),
                3 => __( '3 Columns', 'neve-lite' ),
                4 => __( '4 Columns', 'neve-lite' ),
            ),
        )
    );
    
    // ============================================
    // SECTION: Footer Colors
    // ============================================
    $wp_customize->add_section(
        'neve_lite_footer_colors',
        array(
            'title'    => __( 'Colors', 'neve-lite' ),
            'panel'    => 'neve_lite_footer',
            'priority' => 20,
        )
    );
    
    // Footer Background
    $wp_customize->add_setting(
        'neve_lite_footer_background',
        array(
            'default'           => '#24292e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_footer_background',
            array(
                'label'   => __( 'Footer Background', 'neve-lite' ),
                'section' => 'neve_lite_footer_colors',
            )
        )
    );
    
    // Footer Text Color
    $wp_customize->add_setting(
        'neve_lite_footer_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'neve_lite_footer_text_color',
            array(
                'label'   => __( 'Footer Text Color', 'neve-lite' ),
                'section' => 'neve_lite_footer_colors',
            )
        )
    );
    
    // ============================================
    // SECTION: Footer Copyright
    // ============================================
    $wp_customize->add_section(
        'neve_lite_footer_copyright',
        array(
            'title'    => __( 'Copyright', 'neve-lite' ),
            'panel'    => 'neve_lite_footer',
            'priority' => 30,
        )
    );
    
    // Copyright Text
    $wp_customize->add_setting(
        'neve_lite_copyright_text',
        array(
            'default'           => sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                __( '© %1$s %2$s. All rights reserved.', 'neve-lite' ),
                date_i18n( 'Y' ),
                get_bloginfo( 'name' )
            ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_copyright_text',
        array(
            'label'       => __( 'Copyright Text', 'neve-lite' ),
            'description' => __( 'Use {year} for current year and {site_title} for site name.', 'neve-lite' ),
            'section'     => 'neve_lite_footer_copyright',
            'type'        => 'textarea',
        )
    );
    
    // ============================================
    // SECTION: Blog
    // ============================================
    $wp_customize->add_section(
        'neve_lite_blog',
        array(
            'title'    => __( 'Blog', 'neve-lite' ),
            'priority' => 60,
        )
    );
    
    // Blog Layout
    $wp_customize->add_setting(
        'neve_lite_blog_layout',
        array(
            'default'           => 'list',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_blog_layout',
        array(
            'label'   => __( 'Blog Layout', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'select',
            'choices' => array(
                'list'    => __( 'List', 'neve-lite' ),
                'grid'    => __( 'Grid', 'neve-lite' ),
                'masonry' => __( 'Masonry', 'neve-lite' ),
            ),
        )
    );
    
    // Posts Per Row
    $wp_customize->add_setting(
        'neve_lite_posts_per_row',
        array(
            'default'           => 3,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_posts_per_row',
        array(
            'label'   => __( 'Posts Per Row', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'select',
            'choices' => array(
                2 => __( '2 Columns', 'neve-lite' ),
                3 => __( '3 Columns', 'neve-lite' ),
                4 => __( '4 Columns', 'neve-lite' ),
            ),
        )
    );
    
    // Excerpt Length
    $wp_customize->add_setting(
        'neve_lite_excerpt_length',
        array(
            'default'           => 55,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_excerpt_length',
        array(
            'label'       => __( 'Excerpt Length (words)', 'neve-lite' ),
            'section'     => 'neve_lite_blog',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 200,
                'step' => 5,
            ),
        )
    );
    
    // Show Author
    $wp_customize->add_setting(
        'neve_lite_show_author',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_show_author',
        array(
            'label'   => __( 'Show Author', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'checkbox',
        )
    );
    
    // Show Date
    $wp_customize->add_setting(
        'neve_lite_show_date',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_show_date',
        array(
            'label'   => __( 'Show Date', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'checkbox',
        )
    );
    
    // Show Categories
    $wp_customize->add_setting(
        'neve_lite_show_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_show_categories',
        array(
            'label'   => __( 'Show Categories', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'checkbox',
        )
    );
    
    // Show Featured Image
    $wp_customize->add_setting(
        'neve_lite_show_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_show_featured_image',
        array(
            'label'   => __( 'Show Featured Image', 'neve-lite' ),
            'section' => 'neve_lite_blog',
            'type'    => 'checkbox',
        )
    );
    
    // ============================================
    // SECTION: Performance
    // ============================================
    $wp_customize->add_section(
        'neve_lite_performance',
        array(
            'title'    => __( 'Performance', 'neve-lite' ),
            'priority' => 70,
        )
    );
    
    // Disable Emojis
    $wp_customize->add_setting(
        'neve_lite_disable_emojis',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_disable_emojis',
        array(
            'label'       => __( 'Disable Emojis', 'neve-lite' ),
            'description' => __( 'Remove WordPress emoji scripts for better performance.', 'neve-lite' ),
            'section'     => 'neve_lite_performance',
            'type'        => 'checkbox',
        )
    );
    
    // Lazy Load Images
    $wp_customize->add_setting(
        'neve_lite_lazy_load_images',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_lazy_load_images',
        array(
            'label'       => __( 'Lazy Load Images', 'neve-lite' ),
            'description' => __( 'Enable native lazy loading for images.', 'neve-lite' ),
            'section'     => 'neve_lite_performance',
            'type'        => 'checkbox',
        )
    );
}
add_action( 'customize_register', 'neve_lite_customize_register' );

/**
 * Get Google Fonts choices
 *
 * @since 1.0.0
 * @return array Font choices
 */
function neve_lite_get_google_fonts_choices(): array {
    return array(
        'Inter'       => 'Inter',
        'Roboto'      => 'Roboto',
        'Open Sans'   => 'Open Sans',
        'Lato'        => 'Lato',
        'Montserrat'  => 'Montserrat',
        'Poppins'     => 'Poppins',
        'Playfair Display' => 'Playfair Display',
        'Merriweather' => 'Merriweather',
        'Source Sans Pro' => 'Source Sans Pro',
        'Nunito'      => 'Nunito',
        'system'      => __( 'System Font Stack', 'neve-lite' ),
    );
}

/**
 * Generate custom CSS from Customizer settings
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_customizer_css(): void {
    ?>
    <style type="text/css">
        :root {
            --nv-primary-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_primary_color', '#0073aa' ) ); ?>;
            --nv-secondary-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_secondary_color', '#676767' ) ); ?>;
            --nv-text-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_text_color', '#404248' ) ); ?>;
            --nv-background-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_background_color', '#ffffff' ) ); ?>;
            --nv-primary-hover: <?php echo esc_attr( neve_lite_adjust_brightness( get_theme_mod( 'neve_lite_primary_color', '#0073aa' ), -20 ) ); ?>;
            --nv-link-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_link_color', '#0073aa' ) ); ?>;
            --nv-link-hover-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_link_hover_color', '#005a87' ) ); ?>;
            --nv-container-width: <?php echo esc_attr( get_theme_mod( 'neve_lite_container_width_px', 1200 ) ); ?>px;
            --nv-container-padding: <?php echo esc_attr( get_theme_mod( 'neve_lite_container_padding', 15 ) ); ?>px;
            --nv-header-height: <?php echo esc_attr( get_theme_mod( 'neve_lite_header_height', 70 ) ); ?>px;
            --nv-header-background: <?php echo esc_attr( get_theme_mod( 'neve_lite_header_background', '#ffffff' ) ); ?>;
            --nv-font-size-base: <?php echo esc_attr( get_theme_mod( 'neve_lite_base_font_size', 16 ) ); ?>px;
            --nv-line-height-base: <?php echo esc_attr( get_theme_mod( 'neve_lite_line_height', 1.6 ) ); ?>;
            --nv-footer-background: <?php echo esc_attr( get_theme_mod( 'neve_lite_footer_background', '#24292e' ) ); ?>;
            --nv-footer-text-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_footer_text_color', '#ffffff' ) ); ?>;
        }
        
        <?php
        $body_font = get_theme_mod( 'neve_lite_body_font_family', 'Inter' );
        $heading_font = get_theme_mod( 'neve_lite_heading_font_family', 'Inter' );
        
        if ( $body_font !== 'system' ) :
            ?>
            body {
                font-family: '<?php echo esc_attr( $body_font ); ?>', sans-serif;
            }
            <?php
        endif;
        
        if ( $heading_font !== 'system' && $heading_font !== $body_font ) :
            ?>
            h1, h2, h3, h4, h5, h6 {
                font-family: '<?php echo esc_attr( $heading_font ); ?>', sans-serif;
            }
            <?php
        endif;
        ?>
        
        .nv-header {
            background-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_header_background', '#ffffff' ) ); ?>;
        }
        
        .nv-nav-menu a {
            color: <?php echo esc_attr( get_theme_mod( 'neve_lite_header_text_color', '#404248' ) ); ?>;
        }
        
        .nv-footer {
            background-color: <?php echo esc_attr( get_theme_mod( 'neve_lite_footer_background', '#24292e' ) ); ?>;
            color: <?php echo esc_attr( get_theme_mod( 'neve_lite_footer_text_color', '#ffffff' ) ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'neve_lite_customizer_css' );

/**
 * Adjust color brightness
 *
 * @since 1.0.0
 * @param string $hex Hex color code.
 * @param int    $steps Steps to adjust brightness.
 * @return string Adjusted hex color
 */
function neve_lite_adjust_brightness( string $hex, int $steps ): string {
    $hex = ltrim( $hex, '#' );
    
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    
    $r = max( 0, min( 255, hexdec( substr( $hex, 0, 2 ) ) + $steps ) );
    $g = max( 0, min( 255, hexdec( substr( $hex, 2, 2 ) ) + $steps ) );
    $b = max( 0, min( 255, hexdec( substr( $hex, 4, 2 ) ) + $steps ) );
    
    return '#' . dechex( $r ) . dechex( $g ) . dechex( $b );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_customize_preview_js(): void {
    wp_enqueue_script(
        'neve-lite-customizer',
        NEVE_LITE_ASSETS_URI . 'js/customizer.js',
        array( 'customize-preview' ),
        NEVE_LITE_VERSION,
        true
    );
}
add_action( 'customize_preview_init', 'neve_lite_customize_preview_js' );

/**
 * Customizer controls scripts
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_customize_controls_js(): void {
    wp_enqueue_script(
        'neve-lite-customizer-controls',
        NEVE_LITE_ASSETS_URI . 'js/customizer-controls.js',
        array( 'customize-controls' ),
        NEVE_LITE_VERSION,
        true
    );
}
add_action( 'customize_controls_enqueue_scripts', 'neve_lite_customize_controls_js' );
