<?php
/**
 * Neve Lite Onboarding / Setup Wizard
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Neve_Lite_Onboarding
 */
class Neve_Lite_Onboarding {

	private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'after_switch_theme', array( $this, 'redirect_on_activation' ) );

		// AJAX Handlers
		add_action( 'wp_ajax_neve_onboarding_get_plugins', array( $this, 'ajax_get_plugins' ) );
		add_action( 'wp_ajax_neve_onboarding_install_plugins', array( $this, 'ajax_install_plugin' ) );
		add_action( 'wp_ajax_neve_onboarding_import_content', array( $this, 'ajax_import_content' ) );
	}

	public function redirect_on_activation() {
		global $pagenow;
		if ( is_admin() && 'themes.php' === $pagenow && isset( $_GET['activated'] ) ) {
			wp_safe_redirect( admin_url( 'themes.php?page=neve-onboarding' ) );
			exit;
		}
	}

	public function add_menu_page() {
		add_theme_page(
			__( 'Neve Onboarding', 'neve-lite' ),
			__( 'Neve Onboarding', 'neve-lite' ),
			'manage_options',
			'neve-onboarding',
			array( $this, 'render_page' )
		);
	}

	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_neve-onboarding' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'neve-onboarding-css', get_template_directory_uri() . '/assets/css/onboarding.css', array(), '1.8.0' );
		wp_enqueue_script( 'neve-onboarding-js', get_template_directory_uri() . '/assets/js/onboarding.js', array( 'jquery', 'wp-util', 'updates' ), '1.8.0', true );

		wp_localize_script(
			'neve-onboarding-js',
			'neveOnboarding',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'neve_onboarding_nonce' ),
				'demos'   => $this->get_demos_data(),
			)
		);
	}

	private function get_demos_data() {
		$base_img = get_template_directory_uri() . '/assets/images/demos/';

		return array(
			'sportswear' => array(
				'title'   => 'Sklep Sportowy (Fashion)',
				'desc'    => 'Idealny dla e-commerce. Nowoczesny design, duży nacisk na zdjęcia produktów.',
				'preview' => $base_img . 'sportswear.jpg',
				'xml'     => 'demo-sportswear-shop.xml',
				'plugins' => array( 'woocommerce' ),
			),
			'beauty'     => array(
				'title'   => 'Salon Beauty',
				'desc'    => 'Elegancki, delikatny styl dla salonów kosmetycznych i SPA.',
				'preview' => $base_img . 'beauty.jpg',
				'xml'     => 'demo-beauty-salon.xml',
				'plugins' => array(),
			),
			'construction' => array(
				'title'   => 'Firma Budowlana',
				'desc'    => 'Solidny i konkretny layout dla branży budowlanej i remontowej.',
				'preview' => $base_img . 'construction.jpg',
				'xml'     => 'demo-construction.xml',
				'plugins' => array(),
			),
			'restaurant' => array(
				'title'   => 'Restauracja',
				'desc'    => 'Apetyczny design z menu i galerią potraw.',
				'preview' => $base_img . 'restaurant.jpg',
				'xml'     => 'demo-restaurant.xml',
				'plugins' => array(),
			),
			'saas'       => array(
				'title'   => 'SaaS / Startup',
				'desc'    => 'Czysty, technologiczny styl dla aplikacji i usług online.',
				'preview' => $base_img . 'saas.jpg',
				'xml'     => 'demo-saas.xml',
				'plugins' => array(),
			),
			'blog'       => array(
				'title'   => 'Blog Osobisty',
				'desc'    => 'Minimalistyczny szablon skupiony na treści.',
				'preview' => $base_img . 'blog.jpg',
				'xml'     => 'demo-blog.xml',
				'plugins' => array(),
			),
		);
	}

	public function render_page() {
		?>
		<div class="neve-onboarding-wrap">
			<div class="neve-onboarding-container">
				<header class="neve-header">
					<div class="header-content">
						<h1><?php esc_html_e( 'Konfigurator Motywu', 'neve-lite' ); ?></h1>
						<p><?php esc_html_e( 'Wybierz gotowy projekt i uruchom swoją stronę w kilka minut.', 'neve-lite' ); ?></p>
					</div>
					<div class="steps-indicator">
						<span class="step-dot active" data-step="1">1. Wybierz Demo</span>
						<span class="step-dot" data-step="2">2. Narzędzia</span>
						<span class="step-dot" data-step="3">3. Instalacja</span>
					</div>
				</header>

				<div id="neve-onboarding-app">
					<div class="step-content step-select-demo active" data-step="1">
						<h2><?php esc_html_e( 'Wybierz szablon startowy', 'neve-lite' ); ?></h2>
						<div class="demos-grid">
							</div>
					</div>

					<div class="step-content step-select-builder" data-step="2">
						<h2><?php esc_html_e( 'Preferowany edytor treści', 'neve-lite' ); ?></h2>
						<p class="step-desc"><?php esc_html_e( 'Wybierz narzędzie, którym będziesz edytować swoją stronę.', 'neve-lite' ); ?></p>

						<div class="builders-grid">
							<div class="builder-card selected" data-builder="gutenberg">
								<div class="builder-icon"><span class="dashicons dashicons-editor-table"></span></div>
								<h3>Gutenberg</h3>
								<p>Szybki, natywny edytor WordPress. Najlepszy dla wydajności.</p>
							</div>
							<div class="builder-card" data-builder="elementor">
								<div class="builder-icon"><span class="dashicons dashicons-art"></span></div>
								<h3>Elementor</h3>
								<p>Zaawansowany edytor wizualny metodą przeciągnij i upuść.</p>
							</div>
						</div>

						<div class="step-actions">
							<button class="button button-secondary prev-step" data-goto="1">Wróć</button>
							<button class="button button-primary next-step">Dalej <span class="dashicons dashicons-arrow-right-alt2"></span></button>
						</div>
					</div>

					<div class="step-content step-import" data-step="3">
						<h2><?php esc_html_e( 'Trwa instalacja...', 'neve-lite' ); ?></h2>
						<p class="step-desc"><?php esc_html_e( 'Prosimy o cierpliwość. Pobieramy wtyczki i importujemy treści.', 'neve-lite' ); ?></p>

						<div class="import-progress-box">
							<ul class="import-progress-list">
								</ul>
						</div>

						<div class="step-actions center">
							<button class="button button-primary button-hero start-import"><?php esc_html_e( 'Rozpocznij Import', 'neve-lite' ); ?></button>
						</div>
					</div>

					<div class="step-content step-success" data-step="4">
						<div class="success-message">
							<div class="success-icon"><span class="dashicons dashicons-yes-alt"></span></div>
							<h2><?php esc_html_e( 'Wszystko gotowe!', 'neve-lite' ); ?></h2>
							<p><?php esc_html_e( 'Twój serwis został pomyślnie zainstalowany.', 'neve-lite' ); ?></p>
							<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary button-hero" target="_blank"><?php esc_html_e( 'Zobacz stronę', 'neve-lite' ); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class="neve-modal-overlay">
				<div class="neve-modal">
					<button class="close-modal"><span class="dashicons dashicons-no-alt"></span></button>
					<div class="neve-modal-content">
						<img src="" alt="Demo Preview" id="modal-preview-image">
					</div>
					<div class="neve-modal-footer">
						<button class="button button-primary select-demo-from-modal">Wybierz ten szablon</button>
					</div>
				</div>
			</div>

		</div>
		<?php
	}

	// --- AJAX Handlers ---

	public function ajax_get_plugins() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );
		$builder = isset( $_POST['builder'] ) ? sanitize_text_field( $_POST['builder'] ) : 'gutenberg';
		$demo    = isset( $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : '';

		$plugins_to_install = array();

		$plugins_to_install[] = array('slug' => 'wordpress-importer', 'name' => 'WordPress Importer');

		if ( 'elementor' === $builder ) {
			$plugins_to_install[] = array('slug' => 'elementor', 'name' => 'Elementor');
		}

		$demos_data = $this->get_demos_data();
		if ( isset( $demos_data[ $demo ] ) && in_array( 'woocommerce', $demos_data[ $demo ]['plugins'] ) ) {
			$plugins_to_install[] = array('slug' => 'woocommerce', 'name' => 'WooCommerce');
		}

		$final_list = array();
		foreach ( $plugins_to_install as $plugin ) {
			if ( ! is_plugin_active( $plugin['slug'] . '/' . $plugin['slug'] . '.php' ) ) {
				$final_list[] = $plugin;
			}
		}

		wp_send_json_success( $final_list );
	}

	public function ajax_install_plugin() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );
		if ( ! current_user_can( 'install_plugins' ) ) wp_send_json_error( array( 'message' => 'Brak uprawnień.' ) );

		$slug = sanitize_text_field( $_POST['slug'] );

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$plugin_main_file = $this->get_plugin_main_file( $slug );

		if ( ! $plugin_main_file ) {
			$api = plugins_api( 'plugin_information', array( 'slug' => $slug, 'fields' => array( 'sections' => false ) ) );
			if ( is_wp_error( $api ) ) wp_send_json_error( array( 'message' => $api->get_error_message() ) );

			$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );
			if ( is_wp_error( $result ) ) wp_send_json_error( array( 'message' => $result->get_error_message() ) );

			$plugin_main_file = $this->get_plugin_main_file( $slug );
		}

		if ( $plugin_main_file ) {
			activate_plugin( $plugin_main_file );
			wp_send_json_success( array( 'message' => "Zainstalowano $slug" ) );
		} else {
			wp_send_json_error( array( 'message' => "Błąd: nie znaleziono pliku wtyczki $slug po instalacji." ) );
		}
	}

	private function get_plugin_main_file( $slug ) {
		$plugins = get_plugins();
		foreach ( $plugins as $file => $data ) {
			if ( strpos( $file, $slug . '/' ) === 0 ) return $file;
		}
		return false;
	}

	/**
	 * OPTIMIZED IMPORT
	 */
	public function ajax_import_content() {
		// 1. Zwiększ zasoby (Najważniejsze!)
		@ini_set( 'memory_limit', '1024M' );
		@set_time_limit( 0 );
		ignore_user_abort( true );

		// Wyłącz wyświetlanie błędów, aby nie zepsuć JSON-a (Notice, Deprecated etc.)
		@ini_set( 'display_errors', 0 );

		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );

		// 2. GAME CHANGER: Wyłącz generowanie miniatur podczas importu
		add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );

		// Definicje wymagane przez importer
		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );
		if ( ! defined( 'WP_IMPORTING' ) ) define( 'WP_IMPORTING', true );

		// Załaduj biblioteki WP
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/post.php';
		require_once ABSPATH . 'wp-admin/includes/taxonomy.php';

		// Załaduj klasę bazową
		if ( ! class_exists( 'WP_Importer' ) ) {
			$wp_importer_path = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $wp_importer_path ) ) require_once $wp_importer_path;
		}

		// Załaduj wtyczkę importera (Bezpiecznie!)
		if ( ! class_exists( 'WP_Import' ) ) {
			$plugin_dir = WP_PLUGIN_DIR . '/wordpress-importer';

			// Jeśli folder ma inną nazwę (np. wersję), znajdź go
			if ( ! is_dir( $plugin_dir ) ) {
				$found = glob( WP_PLUGIN_DIR . '/wordpress-importer*' );
				if ( ! empty( $found ) ) $plugin_dir = $found[0];
			}

			$main_file   = $plugin_dir . '/wordpress-importer.php';
			$parser_file = $plugin_dir . '/parsers.php';

			// Używamy require_once, aby uniknąć "Cannot redeclare function"
			if ( file_exists( $parser_file ) ) require_once $parser_file;
			if ( file_exists( $main_file ) ) require_once $main_file;
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			wp_send_json_error( array( 'message' => 'Błąd krytyczny: Klasa WP_Import niedostępna. Upewnij się, że wtyczka jest zainstalowana.' ) );
		}

		$demo = sanitize_text_field( $_POST['demo'] );
		$demos_data = $this->get_demos_data();

		if ( ! isset( $demos_data[ $demo ] ) ) wp_send_json_error( array( 'message' => 'Nieprawidłowe demo.' ) );
		$xml_file = get_template_directory() . '/demo-content/' . $demos_data[ $demo ]['xml'];
		if ( ! file_exists( $xml_file ) ) wp_send_json_error( array( 'message' => 'Brak pliku XML.' ) );

		try {
			ob_start();

			$importer = new WP_Import();

			// WAŻNE: Włączamy pobieranie załączników.
			// Jeśli nadal będziesz miał błąd 500, zmień tę wartość na false.
			// To pozwoli zaimportować strukturę bez zdjęć.
			$importer->fetch_attachments = true;

			$importer->import( $xml_file );

			$log = ob_get_clean();

			// Sprzątanie po filtrach
			remove_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );

			// Konfiguracja strony głównej
			$homepage = get_page_by_title( 'Home' );
			$blogpage = get_page_by_title( 'Blog' );
			if ( $homepage ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID );
			}
			if ( $blogpage ) update_option( 'page_for_posts', $blogpage->ID );

			// Konfiguracja Elementor
			if( 'elementor' === $_POST['builder'] ) {
				update_option( 'elementor_cpt_support', array( 'page', 'post', 'product' ) );
				update_option( 'elementor_disable_color_schemes', 'yes' );
				update_option( 'elementor_disable_typography_schemes', 'yes' );
				update_option( 'elementor_default_page_template', 'elementor_header_footer' );
			}

			wp_send_json_success( array( 'message' => 'Import zakończony sukcesem!' ) );

		} catch ( Exception $e ) {
			ob_end_clean();
			wp_send_json_error( array( 'message' => 'Wyjątek: ' . $e->getMessage() ) );
		}
	}
}

Neve_Lite_Onboarding::get_instance();
