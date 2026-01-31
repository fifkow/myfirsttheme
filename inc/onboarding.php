<?php
/**
 * Obsługa wymaganych wtyczek (TGMPA) - STANDARD REPOZYTORIUM (BEZ SOURCE)
 * Oraz konfiguracja One Click Demo Import (OCDI).
 *
 * @package Neve_Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. ŁADOWANIE BIBLIOTEKI TGM
 */
if ( file_exists( get_template_directory() . '/inc/class-tgm-plugin-activation.php' ) ) {
	require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * 2. KONFIGURACJA WTYCZEK
 */
function neve_lite_register_required_plugins() {

	/*
	 * CZYSTA KONFIGURACJA REPOZYTORIUM
	 * Usuwamy 'source'. TGM sam zapyta WordPressa o aktualny link.
	 * To jest najbardziej kuloodporna metoda, jeśli serwer ma dostęp do API WP.
	 */
	$plugins = array(
		// 1. One Click Demo Import
		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => true,
		),

		// 2. Elementor
		array(
			'name'      => 'Elementor Website Builder',
			'slug'      => 'elementor',
			'required'  => true,
		),

		// 3. Contact Form 7
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
		),

		// 4. WooCommerce
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
		),
	);

	$config = array(
		'id'           => 'neve-lite-standard',    // Zmiana ID wymusza odświeżenie cache TGM
		'default_path' => '',
		'menu'         => 'neve-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false, // Ręczna aktywacja jest bezpieczniejsza dla procesu
	);

	if ( function_exists( 'tgmpa' ) ) {
		tgmpa( $plugins, $config );
	}
}
add_action( 'tgmpa_register', 'neve_lite_register_required_plugins' );


/**
 * 3. KONFIGURACJA IMPORTU DEMO (OCDI)
 */
if ( class_exists( 'OCDI_Plugin' ) ) {

	function neve_lite_ocdi_import_files() {
		return array(
			array(
				'import_file_name'           => 'Sklep Sportowy',
				'categories'                 => array( 'E-Commerce' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-sportswear-shop.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/sportswear.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: WooCommerce, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Salon Beauty',
				'categories'                 => array( 'Usługi' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-beauty-salon.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/beauty.jpg',
			),
			array(
				'import_file_name'           => 'Firma Budowlana',
				'categories'                 => array( 'Biznes' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-construction.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/construction.jpg',
			),
			array(
				'import_file_name'           => 'Restauracja',
				'categories'                 => array( 'Gastro' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-restaurant.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/restaurant.jpg',
			),
			array(
				'import_file_name'           => 'SaaS / Startup',
				'categories'                 => array( 'Tech' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-saas.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/saas.jpg',
			),
			array(
				'import_file_name'           => 'Blog Osobisty',
				'categories'                 => array( 'Blog' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-blog.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/blog.jpg',
			),
		);
	}
	add_filter( 'pt-ocdi/import_files', 'neve_lite_ocdi_import_files' );

	function neve_lite_ocdi_after_import() {
		// Menu
		$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		if ( ! $main_menu ) {
			$menus = get_terms( 'nav_menu' );
			if ( ! empty( $menus ) ) $main_menu = $menus[0];
		}
		if ( $main_menu ) {
			set_theme_mod( 'nav_menu_locations', array( 'menu-1' => $main_menu->term_id ) );
		}

		// Home/Blog
		$front_page = get_page_by_title( 'Home' );
		$blog_page  = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Elementor Flush
		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// Woo Pages
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page = get_page_by_title( 'Shop' );
			if ( $shop_page ) update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		}
	}
	add_action( 'pt-ocdi/after_import', 'neve_lite_ocdi_after_import' );

	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
}
