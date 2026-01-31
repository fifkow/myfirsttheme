<?php
/**
 * Obsługa wymaganych wtyczek (TGMPA) z Repozytorium WP (Linki bezpośrednie)
 * oraz konfiguracja One Click Demo Import (OCDI).
 *
 * @package Neve_Lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. ŁADOWANIE BIBLIOTEKI TGM
 * Korzystamy z pliku, który masz już w repozytorium (inc/class-tgm-plugin-activation.php).
 */
if ( file_exists( get_template_directory() . '/inc/class-tgm-plugin-activation.php' ) ) {
	require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * 2. KONFIGURACJA WTYCZEK
 */
function neve_lite_register_required_plugins() {

	/*
	 * Używamy bezpośrednich linków do repozytorium ('source').
	 * Rozwiązuje to problem "Paczka nie mogła zostać zainstalowana",
	 * gdy serwer nie może połączyć się z API WordPressa, aby pobrać metadane.
	 * To nadal jest pobieranie z oficjalnego źródła (nie bundled), ale bardziej niezawodne.
	 */
	$plugins = array(
		// 1. One Click Demo Import
		array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'source'    => 'https://downloads.wordpress.org/plugin/one-click-demo-import.latest-stable.zip',
			'required'  => true,
		),

		// 2. Elementor
		array(
			'name'      => 'Elementor Website Builder',
			'slug'      => 'elementor',
			'source'    => 'https://downloads.wordpress.org/plugin/elementor.latest-stable.zip',
			'required'  => true,
		),

		// 3. Contact Form 7
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'source'    => 'https://downloads.wordpress.org/plugin/contact-form-7.latest-stable.zip',
			'required'  => false,
		),

		// 4. WooCommerce
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'source'    => 'https://downloads.wordpress.org/plugin/woocommerce.latest-stable.zip',
			'required'  => false,
		),
	);

	/*
	 * Konfiguracja TGM
	 */
	$config = array(
		'id'           => 'neve-lite',             // ID konfiguracji
		'default_path' => '',                      // Puste = pobieraj ze źródła
		'menu'         => 'neve-install-plugins',  // Slug menu
		'parent_slug'  => 'themes.php',            // Gdzie w menu
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,                    // Pokaż komunikaty
		'dismissable'  => true,                    // Pozwól zamknąć
		'dismiss_msg'  => '',
		'is_automatic' => false,                   // False jest bezpieczniejsze (unikamy timeoutów przy dużych paczkach)
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

	// B. Konfiguracja po imporcie
	function neve_lite_ocdi_after_import() {
		// Menu
		$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		if ( ! $main_menu ) {
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

		// Home & Blog
		$front_page = get_page_by_title( 'Home' );
		$blog_page  = get_page_by_title( 'Blog' );

		if ( $front_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_page->ID );
		}

		if ( $blog_page ) {
			update_option( 'page_for_posts', $blog_page->ID );
		}

		// Elementor CSS
		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		// WooCommerce Pages
		if ( class_exists( 'WooCommerce' ) ) {
			$shop_page = get_page_by_title( 'Shop' );
			if ( $shop_page ) {
				update_option( 'woocommerce_shop_page_id', $shop_page->ID );
			}
		}
	}
	add_action( 'pt-ocdi/after_import', 'neve_lite_ocdi_after_import' );

	// C. Wyłącz miniatury przy imporcie (Optymalizacja)
	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
}
