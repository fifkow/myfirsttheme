<?php
/**
 * Obsługa wymaganych wtyczek (TGMPA) z Repozytorium WP
 * oraz konfiguracja One Click Demo Import (OCDI).
 *
 * @package Neve_Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. ŁADOWANIE BIBLIOTEKI TGM (Z Twojego repozytorium)
 */
if ( file_exists( get_template_directory() . '/inc/class-tgm-plugin-activation.php' ) ) {
	require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * 2. KONFIGURACJA WTYCZEK Z REPOZYTORIUM
 */
function neve_lite_register_required_plugins() {

	/*
	 * Lista wtyczek do pobrania z WordPress.org.
	 * Nie podajemy 'source', więc TGM pobierze je automatycznie po 'slug'.
	 */
	$plugins = array(
		// 1. One Click Demo Import - Niezbędne do importu demo
		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => true,
		),

		// 2. Elementor - Builder
		array(
			'name'      => 'Elementor Website Builder',
			'slug'      => 'elementor',
			'required'  => true,
		),

		// 3. Contact Form 7 - Formularze
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
		),

		// 4. WooCommerce - Sklep
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
		),
	);

	/*
	 * Konfiguracja zachowania TGM
	 */
	$config = array(
		'id'           => 'neve-lite',             // Unikalne ID
		'default_path' => '',                      // Puste = pobieraj z repozytorium
		'menu'         => 'neve-install-plugins',  // Slug strony instalacji
		'parent_slug'  => 'themes.php',            // Gdzie w menu
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,                    // Pokaż komunikat adminowi
		'dismissable'  => true,                    // Można zamknąć
		'dismiss_msg'  => '',
		'is_automatic' => false,                   // False jest bezpieczniejsze (nie wymusza aktywacji na siłę, co czasem powoduje błędy 500 przy dużych wtyczkach)
	);

	if ( function_exists( 'tgmpa' ) ) {
		tgmpa( $plugins, $config );
	}
}
add_action( 'tgmpa_register', 'neve_lite_register_required_plugins' );


/**
 * 3. KONFIGURACJA IMPORTU DEMO (OCDI)
 * Ta sekcja działa tylko, gdy użytkownik zainstaluje i włączy wtyczkę One Click Demo Import.
 */
if ( class_exists( 'OCDI_Plugin' ) ) {

	// A. Lista Dem
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
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Firma Budowlana',
				'categories'                 => array( 'Biznes' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-construction.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/construction.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
			),
			array(
				'import_file_name'           => 'Restauracja',
				'categories'                 => array( 'Gastro' ),
				'import_file_url'            => get_template_directory_uri() . '/demo-content/demo-restaurant.xml',
				'import_preview_image_url'   => get_template_directory_uri() . '/assets/images/demos/restaurant.jpg',
				'import_notice'              => __( 'Wymaga wtyczek: Contact Form 7, Elementor.', 'neve-lite' ),
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

	// B. Konfiguracja po imporcie (Menu, Strona główna)
	function neve_lite_ocdi_after_import() {
		// Przypisanie Menu
		$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		if ( ! $main_menu ) {
			// Jeśli nazwa jest inna, weź pierwsze dostępne
			$menus = get_terms( 'nav_menu' );
			if ( ! empty( $menus ) ) {
				$main_menu = $menus[0];
			}
		}

		if ( $main_menu ) {
			set_theme_mod( 'nav_menu_locations', array(
				'menu-1' => $main_menu->term_id,
			) );
		}

		// Ustawienie strony głównej i bloga
		$front_page = get_page_by_title( 'Home' );
		$blog_page  = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Odświeżenie CSS Elementora
		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// Podstawowa konfiguracja Woo
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page = get_page_by_title( 'Shop' );
			if ( $shop_page ) {
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
			}
		}
	}
	add_action( 'pt-ocdi/after_import', 'neve_lite_ocdi_after_import' );

	// C. Optymalizacja: Wyłącz generowanie miniatur podczas importu (przyspiesza proces)
	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
}
