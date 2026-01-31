<?php
/**
 * Neve Lite WooCommerce Integration
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WooCommerce setup
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_woocommerce_setup(): void {
    
    // Add theme support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Remove default WooCommerce styles
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}
add_action( 'after_setup_theme', 'neve_lite_woocommerce_setup' );

/**
 * Enqueue WooCommerce styles
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_woocommerce_scripts(): void {
    
    if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
        return;
    }
    
    wp_enqueue_style(
        'neve-lite-woocommerce',
        NEVE_LITE_ASSETS_URI . 'css/woocommerce.css',
        array(),
        NEVE_LITE_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'neve_lite_woocommerce_scripts', 20 );

/**
 * Remove default WooCommerce wrappers
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_remove_woocommerce_wrappers(): void {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'init', 'neve_lite_remove_woocommerce_wrappers' );

/**
 * Add custom WooCommerce wrappers
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_woocommerce_wrapper_start(): void {
    ?>
    <div id="primary" class="nv-content-area">
        <main id="main" class="site-main">
    <?php
}
add_action( 'woocommerce_before_main_content', 'neve_lite_woocommerce_wrapper_start', 10 );

/**
 * End custom WooCommerce wrappers
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_woocommerce_wrapper_end(): void {
    ?>
        </main>
    </div>
    <?php
}
add_action( 'woocommerce_after_main_content', 'neve_lite_woocommerce_wrapper_end', 10 );

/**
 * Add WooCommerce sidebar
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_woocommerce_sidebar(): void {
    if ( is_active_sidebar( 'shop-sidebar' ) ) {
        ?>
        <aside id="secondary" class="nv-sidebar nv-shop-sidebar">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </aside>
        <?php
    }
}
add_action( 'woocommerce_after_main_content', 'neve_lite_woocommerce_sidebar', 15 );

/**
 * Change number of products per page
 *
 * @since 1.0.0
 * @return int Number of products
 */
function neve_lite_products_per_page(): int {
    return absint( get_theme_mod( 'neve_lite_shop_products_per_page', 12 ) );
}
add_filter( 'loop_shop_per_page', 'neve_lite_products_per_page', 20 );

/**
 * Change number of products per row
 *
 * @since 1.0.0
 * @return int Number of columns
 */
function neve_lite_loop_columns(): int {
    return absint( get_theme_mod( 'neve_lite_shop_columns', 4 ) );
}
add_filter( 'loop_shop_columns', 'neve_lite_loop_columns', 20 );

/**
 * Change number of related products
 *
 * @since 1.0.0
 * @param array $args Related products args.
 * @return array Modified args
 */
function neve_lite_related_products_args( array $args ): array {
    $args['posts_per_page'] = absint( get_theme_mod( 'neve_lite_related_products_count', 4 ) );
    $args['columns']        = absint( get_theme_mod( 'neve_lite_related_products_columns', 4 ) );
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'neve_lite_related_products_args' );

/**
 * Change number of upsell products
 *
 * @since 1.0.0
 * @param array $args Upsell products args.
 * @return array Modified args
 */
function neve_lite_upsell_products_args( array $args ): array {
    $args['posts_per_page'] = absint( get_theme_mod( 'neve_lite_upsell_products_count', 4 ) );
    $args['columns']        = absint( get_theme_mod( 'neve_lite_upsell_products_columns', 4 ) );
    return $args;
}
add_filter( 'woocommerce_upsell_display_args', 'neve_lite_upsell_products_args' );

/**
 * Change number of cross-sell products
 *
 * @since 1.0.0
 * @param int $limit Number of products.
 * @return int Modified limit
 */
function neve_lite_cross_sells_limit( int $limit ): int {
    return absint( get_theme_mod( 'neve_lite_cross_sells_count', 4 ) );
}
add_filter( 'woocommerce_cross_sells_total', 'neve_lite_cross_sells_limit' );

/**
 * Change cross-sell columns
 *
 * @since 1.0.0
 * @param int $columns Number of columns.
 * @return int Modified columns
 */
function neve_lite_cross_sells_columns( int $columns ): int {
    return absint( get_theme_mod( 'neve_lite_cross_sells_columns', 4 ) );
}
add_filter( 'woocommerce_cross_sells_columns', 'neve_lite_cross_sells_columns' );

/**
 * Remove WooCommerce breadcrumbs (we use our own)
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_remove_woocommerce_breadcrumbs(): void {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}
add_action( 'init', 'neve_lite_remove_woocommerce_breadcrumbs' );

/**
 * Remove default WooCommerce product title
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_remove_product_title(): void {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
}
add_action( 'init', 'neve_lite_remove_product_title' );

/**
 * Remove default WooCommerce product rating
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_remove_product_rating(): void {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
}
add_action( 'init', 'neve_lite_remove_product_rating' );

/**
 * Add custom product title
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_custom_product_title(): void {
    the_title( '<h1 class="nv-product-title product-title">', '</h1>' );
}
add_action( 'woocommerce_single_product_summary', 'neve_lite_custom_product_title', 5 );

/**
 * Add custom product rating
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_custom_product_rating(): void {
    woocommerce_template_single_rating();
}
add_action( 'woocommerce_single_product_summary', 'neve_lite_custom_product_rating', 10 );

/**
 * Add sale flash badge
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_sale_flash(): void {
    global $product;
    
    if ( ! $product->is_on_sale() ) {
        return;
    }
    
    $sale_price    = $product->get_sale_price();
    $regular_price = $product->get_regular_price();
    
    if ( $regular_price > 0 ) {
        $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
        echo '<span class="nv-sale-badge">-' . esc_html( $percentage ) . '%</span>';
    } else {
        echo '<span class="nv-sale-badge">' . esc_html__( 'Sale!', 'neve-lite' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'neve_lite_sale_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'neve_lite_sale_flash', 10 );

/**
 * Add product quick view button
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_quick_view_button(): void {
    global $product;
    ?>
    <button class="nv-quick-view-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'neve-lite' ); ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
    </button>
    <?php
}
add_action( 'woocommerce_after_shop_loop_item', 'neve_lite_quick_view_button', 15 );

/**
 * Add wishlist button
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_wishlist_button(): void {
    global $product;
    ?>
    <button class="nv-wishlist-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'neve-lite' ); ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        </svg>
    </button>
    <?php
}
add_action( 'woocommerce_after_shop_loop_item', 'neve_lite_wishlist_button', 16 );

/**
 * Add product badges
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_product_badges(): void {
    global $product;
    
    // New badge (products added in last 30 days)
    $created = strtotime( $product->get_date_created() );
    if ( ( time() - $created ) < 30 * DAY_IN_SECONDS ) {
        echo '<span class="nv-badge nv-badge-new">' . esc_html__( 'New', 'neve-lite' ) . '</span>';
    }
    
    // Out of stock badge
    if ( ! $product->is_in_stock() ) {
        echo '<span class="nv-badge nv-badge-out-of-stock">' . esc_html__( 'Out of Stock', 'neve-lite' ) . '</span>';
    }
    
    // Featured badge
    if ( $product->is_featured() ) {
        echo '<span class="nv-badge nv-badge-featured">' . esc_html__( 'Featured', 'neve-lite' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'neve_lite_product_badges', 9 );

/**
 * Custom add to cart button
 *
 * @since 1.0.0
 * @param string $html Add to cart button HTML.
 * @param WP_Post $post Post object.
 * @param WC_Product $product Product object.
 * @return string Modified HTML
 */
function neve_lite_custom_add_to_cart_button( string $html, $post, WC_Product $product ): string {
    
    if ( $product->is_type( 'simple' ) && $product->is_in_stock() ) {
        $html = sprintf(
            '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( implode( ' ', array_filter( array(
                'nv-button',
                'nv-button-primary',
                'nv-add-to-cart',
                'product_type_' . $product->get_type(),
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
            ) ) ) ),
            wc_implode_html_attributes( array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => $product->add_to_cart_description(),
                'rel'              => 'nofollow',
            ) ),
            esc_html( $product->add_to_cart_text() )
        );
    }
    
    return $html;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'neve_lite_custom_add_to_cart_button', 10, 3 );

/**
 * Add mini cart to header
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_header_cart(): void {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    ?>
    <div class="nv-header-cart">
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="nv-cart-link" aria-label="<?php esc_attr_e( 'Cart', 'neve-lite' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="nv-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        </a>
        <div class="nv-mini-cart">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    <?php
}

/**
 * Update cart fragments
 *
 * @since 1.0.0
 * @param array $fragments Cart fragments.
 * @return array Modified fragments
 */
function neve_lite_cart_fragments( array $fragments ): array {
    ob_start();
    ?>
    <span class="nv-cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
    <?php
    $fragments['span.nv-cart-count'] = ob_get_clean();
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'neve_lite_cart_fragments' );

/**
 * Add account link to header
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_header_account(): void {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    $url = is_user_logged_in() ? wc_get_page_permalink( 'myaccount' ) : wc_get_page_permalink( 'myaccount' );
    ?>
    <a href="<?php echo esc_url( $url ); ?>" class="nv-account-link" aria-label="<?php esc_attr_e( 'My Account', 'neve-lite' ); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
    </a>
    <?php
}

/**
 * Custom checkout fields
 *
 * @since 1.0.0
 * @param array $fields Checkout fields.
 * @return array Modified fields
 */
function neve_lite_checkout_fields( array $fields ): array {
    
    // Add placeholder to billing first name
    $fields['billing']['billing_first_name']['placeholder'] = __( 'First name', 'neve-lite' );
    
    // Add placeholder to billing last name
    $fields['billing']['billing_last_name']['placeholder'] = __( 'Last name', 'neve-lite' );
    
    // Add placeholder to billing email
    $fields['billing']['billing_email']['placeholder'] = __( 'Email address', 'neve-lite' );
    
    // Add placeholder to billing phone
    $fields['billing']['billing_phone']['placeholder'] = __( 'Phone number', 'neve-lite' );
    
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'neve_lite_checkout_fields' );

/**
 * Add continue shopping button to cart
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_continue_shopping_button(): void {
    ?>
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="nv-button nv-button-outline nv-continue-shopping">
        <?php esc_html_e( 'Continue Shopping', 'neve-lite' ); ?>
    </a>
    <?php
}
add_action( 'woocommerce_cart_actions', 'neve_lite_continue_shopping_button', 10 );

/**
 * Add product meta to loop
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_loop_product_meta(): void {
    global $product;
    
    // SKU
    if ( $product->get_sku() ) {
        echo '<span class="nv-product-sku">' . esc_html__( 'SKU:', 'neve-lite' ) . ' ' . esc_html( $product->get_sku() ) . '</span>';
    }
}
add_action( 'woocommerce_after_shop_loop_item_title', 'neve_lite_loop_product_meta', 15 );

/**
 * Custom product tabs
 *
 * @since 1.0.0
 * @param array $tabs Product tabs.
 * @return array Modified tabs
 */
function neve_lite_product_tabs( array $tabs ): array {
    
    // Rename description tab
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title'] = __( 'Product Details', 'neve-lite' );
    }
    
    // Rename reviews tab
    if ( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['title'] = __( 'Customer Reviews', 'neve-lite' );
    }
    
    // Rename additional information tab
    if ( isset( $tabs['additional_information'] ) ) {
        $tabs['additional_information']['title'] = __( 'Specifications', 'neve-lite' );
    }
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'neve_lite_product_tabs' );

/**
 * Add custom product data tabs
 *
 * @since 1.0.0
 * @param array $tabs Product tabs.
 * @return array Modified tabs
 */
function neve_lite_custom_product_tabs( array $tabs ): array {
    
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __( 'Shipping & Returns', 'neve-lite' ),
        'priority' => 25,
        'callback' => 'neve_lite_shipping_tab_content',
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'neve_lite_custom_product_tabs', 20 );

/**
 * Shipping tab content
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_shipping_tab_content(): void {
    ?>
    <h2><?php esc_html_e( 'Shipping Information', 'neve-lite' ); ?></h2>
    <p><?php esc_html_e( 'We offer free shipping on all orders over $50. Standard shipping takes 3-5 business days. Express shipping available for an additional fee.', 'neve-lite' ); ?></p>
    <h2><?php esc_html_e( 'Returns Policy', 'neve-lite' ); ?></h2>
    <p><?php esc_html_e( 'You can return any item within 30 days of purchase for a full refund. Items must be unused and in original packaging.', 'neve-lite' ); ?></p>
    <?php
}

/**
 * Add size guide link
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_size_guide_link(): void {
    global $product;
    
    // Check if product has size attribute
    if ( $product->has_attributes() ) {
        $attributes = $product->get_attributes();
        foreach ( $attributes as $attribute ) {
            if ( $attribute->get_name() === 'pa_size' || $attribute->get_name() === 'size' ) {
                ?>
                <a href="#size-guide" class="nv-size-guide-link" data-toggle="modal">
                    <?php esc_html_e( 'Size Guide', 'neve-lite' ); ?>
                </a>
                <?php
                break;
            }
        }
    }
}
add_action( 'woocommerce_single_product_summary', 'neve_lite_size_guide_link', 25 );

/**
 * Add trust badges to product page
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_trust_badges(): void {
    ?>
    <div class="nv-trust-badges">
        <div class="nv-trust-badge">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span><?php esc_html_e( 'Secure Payment', 'neve-lite' ); ?></span>
        </div>
        <div class="nv-trust-badge">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
            </svg>
            <span><?php esc_html_e( 'Fast Shipping', 'neve-lite' ); ?></span>
        </div>
        <div class="nv-trust-badge">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
            </svg>
            <span><?php esc_html_e( '30-Day Returns', 'neve-lite' ); ?></span>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'neve_lite_trust_badges', 35 );

/**
 * Add custom WooCommerce body classes
 *
 * @since 1.0.0
 * @param array $classes Body classes.
 * @return array Modified classes
 */
function neve_lite_woocommerce_body_classes( array $classes ): array {
    
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        $classes[] = 'nv-shop-page';
    }
    
    if ( is_product() ) {
        $classes[] = 'nv-single-product';
    }
    
    if ( is_cart() ) {
        $classes[] = 'nv-cart-page';
    }
    
    if ( is_checkout() ) {
        $classes[] = 'nv-checkout-page';
    }
    
    if ( is_account_page() ) {
        $classes[] = 'nv-account-page';
    }
    
    return $classes;
}
add_filter( 'body_class', 'neve_lite_woocommerce_body_classes' );

/**
 * Disable WooCommerce CSS on non-WooCommerce pages
 *
 * @since 1.0.0
 * @return void
 */
function neve_lite_dequeue_woocommerce_css(): void {
    
    if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
    }
}
add_action( 'wp_enqueue_scripts', 'neve_lite_dequeue_woocommerce_css', 99 );

/**
 * Add WooCommerce Customizer settings
 *
 * @since 1.0.0
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function neve_lite_woocommerce_customizer( WP_Customize_Manager $wp_customize ): void {
    
    // Shop Section
    $wp_customize->add_section(
        'neve_lite_shop',
        array(
            'title'    => __( 'Shop', 'neve-lite' ),
            'priority' => 80,
        )
    );
    
    // Products Per Page
    $wp_customize->add_setting(
        'neve_lite_shop_products_per_page',
        array(
            'default'           => 12,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_shop_products_per_page',
        array(
            'label'       => __( 'Products Per Page', 'neve-lite' ),
            'section'     => 'neve_lite_shop',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 4,
                'max'  => 48,
                'step' => 4,
            ),
        )
    );
    
    // Shop Columns
    $wp_customize->add_setting(
        'neve_lite_shop_columns',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_shop_columns',
        array(
            'label'   => __( 'Shop Columns', 'neve-lite' ),
            'section' => 'neve_lite_shop',
            'type'    => 'select',
            'choices' => array(
                2 => __( '2 Columns', 'neve-lite' ),
                3 => __( '3 Columns', 'neve-lite' ),
                4 => __( '4 Columns', 'neve-lite' ),
                5 => __( '5 Columns', 'neve-lite' ),
            ),
        )
    );
    
    // Related Products Count
    $wp_customize->add_setting(
        'neve_lite_related_products_count',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_related_products_count',
        array(
            'label'       => __( 'Related Products Count', 'neve-lite' ),
            'section'     => 'neve_lite_shop',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );
    
    // Related Products Columns
    $wp_customize->add_setting(
        'neve_lite_related_products_columns',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );
    
    $wp_customize->add_control(
        'neve_lite_related_products_columns',
        array(
            'label'   => __( 'Related Products Columns', 'neve-lite' ),
            'section' => 'neve_lite_shop',
            'type'    => 'select',
            'choices' => array(
                2 => __( '2 Columns', 'neve-lite' ),
                3 => __( '3 Columns', 'neve-lite' ),
                4 => __( '4 Columns', 'neve-lite' ),
            ),
        )
    );
}
add_action( 'customize_register', 'neve_lite_woocommerce_customizer' );
