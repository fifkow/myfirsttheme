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

	/**
	 * Redirect to onboarding page after theme activation
	 */
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

		wp_enqueue_style( 'neve-onboarding-css', get_template_directory_uri() . '/assets/css/onboarding.css', array(), '1.1.0' );
		wp_enqueue_script( 'neve-onboarding-js', get_template_directory_uri() . '/assets/js/onboarding.js', array( 'jquery', 'wp-util', 'updates' ), '1.1.0', true );

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
		// Upewnij się, że ścieżki do obrazków są poprawne
		$base_img = get_template_directory_uri() . '/assets/images/demos/';

		return array(
			'sportswear' => array(
				'title'   => 'Sklep Sportowy (Fashion)',
				'preview' => $base_img . 'sportswear.jpg',
				'xml'     => 'demo-sportswear-shop.xml',
				'plugins' => array( 'woocommerce' ),
			),
			'beauty'     => array(
				'title'   => 'Salon Beauty',
				'preview' => $base_img . 'beauty.jpg',
				'xml'     => 'demo-beauty-salon.xml',
				'plugins' => array(),
			),
			'construction' => array(
				'title'   => 'Firma Budowlana',
				'preview' => $base_img . 'construction.jpg',
				'xml'     => 'demo-construction.xml',
				'plugins' => array(),
			),
			'restaurant' => array(
				'title'   => 'Restauracja',
				'preview' => $base_img . 'restaurant.jpg',
				'xml'     => 'demo-restaurant.xml',
				'plugins' => array(),
			),
			'saas'       => array(
				'title'   => 'SaaS / Startup',
				'preview' => $base_img . 'saas.jpg',
				'xml'     => 'demo-saas.xml',
				'plugins' => array(),
			),
			'blog'       => array(
				'title'   => 'Blog Osobisty',
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
						<h1><?php esc_html_e( 'Witaj w konfiguratorze!', 'neve-lite' ); ?></h1>
						<p><?php esc_html_e( 'Ten kreator pomoże Ci zaimportować gotową stronę demo i niezbędne wtyczki.', 'neve-lite' ); ?></p>
					</div>
					<div class="steps-indicator">
						<span class="step-dot active" data-step="1">1. Wybierz Demo</span>
						<span class="step-dot" data-step="2">2. Builder</span>
						<span class="step-dot" data-step="3">3. Import</span>
					</div>
				</header>

				<div id="neve-onboarding-app">
					<div class="step-content step-select-demo active" data-step="1">
						<h2><?php esc_html_e( 'Wybierz wygląd swojej strony', 'neve-lite' ); ?></h2>
						<div class="demos-grid">
							</div>
					</div>

					<div class="step-content step-select-builder" data-step="2">
						<h2><?php esc_html_e( 'Wybierz swój Page Builder', 'neve-lite' ); ?></h2>
						<p class="step-desc"><?php esc_html_e( 'Z jakim narzędziem chcesz pracować?', 'neve-lite' ); ?></p>

						<div class="builders-grid">
							<div class="builder-card selected" data-builder="gutenberg">
								<div class="builder-icon"><span class="dashicons dashicons-editor-table"></span></div>
								<h3>Gutenberg</h3>
								<p>Domyślny edytor WordPress. Szybki i lekki.</p>
							</div>
							<div class="builder-card" data-builder="elementor">
								<div class="builder-icon"><span class="dashicons dashicons-art"></span></div>
								<h3>Elementor</h3>
								<p>Najpopularniejszy Page Builder. Duże możliwości.</p>
							</div>
						</div>

						<div class="step-actions">
							<button class="button button-secondary prev-step" data-goto="1">Wróć</button>
							<button class="button button-primary next-step">Dalej <span class="dashicons dashicons-arrow-right-alt2"></span></button>
						</div>
					</div>

					<div class="step-content step-import" data-step="3">
						<h2><?php esc_html_e( 'Instalujemy Twoją stronę...', 'neve-lite' ); ?></h2>
						<p class="step-desc"><?php esc_html_e( 'Nie zamykaj tej karty. To może potrwać kilka minut.', 'neve-lite' ); ?></p>

						<div class="import-progress-box">
							<ul class="import-progress-list">
								</ul>
						</div>

						<div class="step-actions center">
							<button class="button button-primary button-hero start-import"><?php esc_html_e( 'Rozpocznij Instalację', 'neve-lite' ); ?></button>
						</div>
					</div>

					<div class="step-content step-success" data-step="4">
						<div class="success-message">
							<div class="success-icon"><span class="dashicons dashicons-yes-alt"></span></div>
							<h2><?php esc_html_e( 'Gratulacje!', 'neve-lite' ); ?></h2>
							<p><?php esc_html_e( 'Twoja strona jest gotowa do edycji.', 'neve-lite' ); ?></p>
							<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary button-hero" target="_blank"><?php esc_html_e( 'Zobacz stronę', 'neve-lite' ); ?></a>
						</div>
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

		// 1. ZAWSZE WordPress Importer (kluczowe do działania)
		$plugins_to_install[] = array('slug' => 'wordpress-importer', 'name' => 'WordPress Importer');

		// 2. Elementor
		if ( 'elementor' === $builder ) {
			$plugins_to_install[] = array('slug' => 'elementor', 'name' => 'Elementor');
		}

		// 3. WooCommerce jeśli wymagane
		$demos_data = $this->get_demos_data();
		if ( isset( $demos_data[ $demo ] ) && in_array( 'woocommerce', $demos_data[ $demo ]['plugins'] ) ) {
			$plugins_to_install[] = array('slug' => 'woocommerce', 'name' => 'WooCommerce');
		}

		$final_list = array();
		foreach ( $plugins_to_install as $plugin ) {
			// Sprawdzamy czy wtyczka jest aktywna. Jeśli nie (nawet jeśli zainstalowana) - dodajemy do listy, żeby skrypt ją aktywował
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
			// Instalacja
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
			wp_send_json_error( array( 'message' => "Błąd lokalizacji pliku $slug" ) );
		}
	}

	private function get_plugin_main_file( $slug ) {
		$plugins = get_plugins();
		foreach ( $plugins as $file => $data ) {
			if ( strpos( $file, $slug . '/' ) === 0 ) return $file;
		}
		return false;
	}

	public function ajax_import_content() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );

		ini_set( 'memory_limit', '512M' );
		set_time_limit( 300 );

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );

		// --- NAPRAWA BŁĘDU KRYTYCZNEGO ---
		// 1. Najpierw ładujemy klasę bazową WordPressa (WP_Importer)
		if ( ! class_exists( 'WP_Importer' ) ) {
			$wp_importer_path = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $wp_importer_path ) ) {
				require_once $wp_importer_path;
			}
		}

		// 2. Potem ładujemy klasę wtyczki (WP_Import)
		// Szukamy ścieżki dynamicznie, bo czasem katalog różni się od sluga
		$plugin_path = WP_PLUGIN_DIR . '/wordpress-importer/wordpress-importer.php';

		if ( file_exists( $plugin_path ) ) {
			require_once $plugin_path;
		}

		// 3. Sprawdzenie ostateczne
		if ( ! class_exists( 'WP_Import' ) ) {
			wp_send_json_error( array(
				'message' => 'Błąd krytyczny: Nie udało się załadować klasy WP_Import. Upewnij się, że wtyczka WordPress Importer została zainstalowana w poprzednim kroku.'
			) );
		}

		// Reszta logiki importu
		$demo = sanitize_text_field( $_POST['demo'] );
		$demos_data = $this->get_demos_data();

		if ( ! isset( $demos_data[ $demo ] ) ) wp_send_json_error( array( 'message' => 'Nieprawidłowe demo.' ) );

		$xml_file = get_template_directory() . '/demo-content/' . $demos_data[ $demo ]['xml'];
		if ( ! file_exists( $xml_file ) ) wp_send_json_error( array( 'message' => 'Brak pliku XML: ' . $xml_file ) );

		try {
			ob_start();
			$importer = new WP_Import();
			$importer->fetch_attachments = true;
			$importer->import( $xml_file );
			$log = ob_get_clean();

			// Ustawienia Home/Blog
			$homepage = get_page_by_title( 'Home' );
			$blogpage = get_page_by_title( 'Blog' );
			if ( $homepage ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID );
			}
			if ( $blogpage ) update_option( 'page_for_posts', $blogpage->ID );

			// Ustawienia Elementor
			if( 'elementor' === $_POST['builder'] ) {
				update_option( 'elementor_cpt_support', array( 'page', 'post', 'product' ) );
				update_option( 'elementor_disable_color_schemes', 'yes' );
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}

			wp_send_json_success( array( 'message' => 'Import OK' ) );

		} catch ( Exception $e ) {
			ob_end_clean();
			wp_send_json_error( array( 'message' => 'Wyjątek: ' . $e->getMessage() ) );
		}
	}
}

Neve_Lite_Onboarding::get_instance();
