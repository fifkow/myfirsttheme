<?php
/**
 * Obsługa wymaganych wtyczek (TGMPA) oraz konfiguracja One Click Demo Import.
 *
 * @package Neve_Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. ŁADOWANIE BIBLIOTEKI TGM PLUGIN ACTIVATION
 * Upewnij się, że plik class-tgm-plugin-activation.php znajduje się w folderze /inc/
 */
if ( file_exists( get_template_directory() . '/inc/class-tgm-plugin-activation.php' ) ) {
	require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * 2. KONFIGURACJA WYMAGANYCH WTYCZEK
 */
function neve_lite_register_required_plugins() {
	/*
	 * Tablica wtyczek do zainstalowania.
	 * Jeśli source jest pusty, pobiera z oficjalnego repozytorium WP (Zalecane).
	 * Jeśli chcesz robić "Bundled", musisz wgrać zipy do folderu motywu i podać ścieżkę w 'source'.
	 */
	$plugins = array(
		// One Click Demo Import - KLUCZOWE
		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => true,
		),

		// Elementor
		array(
			'name'      => 'Elementor Website Builder',
			'slug'      => 'elementor',
			'required'  => true,
		),

		// WooCommerce
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false, // Opcjonalne (zależy od demo)
		),

		// Contact Form 7
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
		),
	);

	/*
	 * Konfiguracja biblioteki TGMPA
	 */
	$config = array(
		'id'           => 'neve-lite',             // Unikalne ID
		'default_path' => '',                      // Domyślna ścieżka dla bundled plugins (puste = repo)
		'menu'         => 'neve-install-plugins',  // Slug menu
		'parent_slug'  => 'themes.php',            // Gdzie ma się pojawić menu
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,                    // Pokaż komunikaty admina
		'dismissable'  => true,                    // Czy user może zamknąć komunikat
		'dismiss_msg'  => '',
		'is_automatic' => true,                    // Automatycznie aktywuj po instalacji
	);

	if ( function_exists( 'tgmpa' ) ) {
		tgmpa( $plugins, $config );
	}
}
add_action( 'tgmpa_register', 'neve_lite_register_required_plugins' );


/**
 * 3. KONFIGURACJA ONE CLICK DEMO IMPORT (OCDI)
 * Ta sekcja uruchamia się tylko, gdy wtyczka OCDI jest już zainstalowana przez TGM.
 */
if ( class_exists( 'OCDI_Plugin' ) ) {

	// A. Definicja plików demo
	function neve_lite_ocdi_import_files() {
		return array(
			array(
				'import_file_name'           => 'Sklep Sportowy (WooCommerce)',
				'categories'                 => array( 'Sklep', 'Elementor' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-sportswear-shop.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/sportswear.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: WooCommerce, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Salon Beauty',
				'categories'                 => array( 'Usługi', 'Elementor' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-beauty-salon.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/beauty.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Firma Budowlana',
				'categories'                 => array( 'Biznes', 'Elementor' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-construction.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/construction.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Restauracja',
				'categories'                 => array( 'Gastro', 'Elementor' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-restaurant.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/restaurant.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'SaaS / Tech',
				'categories'                 => array( 'Startup', 'Elementor' ),
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

	// B. Ustawienia po imporcie (Menu, Home Page)
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

		// Home & Blog Page
		$front_page = get_page_by_title( 'Home' );
		$blog_page  = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}
		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Elementor Setup
		if ( class_exists( '\Elementor\Plugin' ) ) {
			update_option( 'elementor_cpt_support', array( 'page', 'post', 'product' ) );
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// Woo Setup
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page = get_page_by_title( 'Shop' );
			if ( $shop_page ) update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		}
	}
	add_action( 'pt-ocdi/after_import', 'neve_lite_ocdi_after_import' );

	// C. Optymalizacja
	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
}
