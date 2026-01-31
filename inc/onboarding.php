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

	/**
	 * Instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// AJAX Handlers
		add_action( 'wp_ajax_neve_onboarding_get_plugins', array( $this, 'ajax_get_plugins' ) );
		add_action( 'wp_ajax_neve_onboarding_install_plugins', array( $this, 'ajax_install_plugin' ) );
		add_action( 'wp_ajax_neve_onboarding_import_content', array( $this, 'ajax_import_content' ) );
	}

	/**
	 * Add Menu Page
	 */
	public function add_menu_page() {
		add_theme_page(
			__( 'Neve Onboarding', 'neve-lite' ),
			__( 'Neve Onboarding', 'neve-lite' ),
			'manage_options',
			'neve-onboarding',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue Scripts
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'appearance_page_neve-onboarding' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'neve-onboarding-css', get_template_directory_uri() . '/assets/css/onboarding.css', array(), '1.0.0' );
		wp_enqueue_script( 'neve-onboarding-js', get_template_directory_uri() . '/assets/js/onboarding.js', array( 'jquery', 'wp-util', 'updates' ), '1.0.0', true );

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

	/**
	 * Get Demos Data
	 */
	private function get_demos_data() {
		return array(
			'sportswear' => array(
				'title'   => 'Sklep Sportowy',
				'preview' => get_template_directory_uri() . '/assets/images/demos/sportswear.jpg',
				'xml'     => 'demo-sportswear-shop.xml',
				'plugins' => array( 'woocommerce' ),
			),
			'beauty'     => array(
				'title'   => 'Salon Beauty',
				'preview' => get_template_directory_uri() . '/assets/images/demos/beauty.jpg',
				'xml'     => 'demo-beauty-salon.xml',
				'plugins' => array(),
			),
			'construction' => array(
				'title'   => 'Firma Budowlana',
				'preview' => get_template_directory_uri() . '/assets/images/demos/construction.jpg',
				'xml'     => 'demo-construction.xml',
				'plugins' => array(),
			),
			'restaurant' => array(
				'title'   => 'Restauracja',
				'preview' => get_template_directory_uri() . '/assets/images/demos/restaurant.jpg',
				'xml'     => 'demo-restaurant.xml',
				'plugins' => array(),
			),
			'saas'       => array(
				'title'   => 'SaaS / Tech',
				'preview' => get_template_directory_uri() . '/assets/images/demos/saas.jpg',
				'xml'     => 'demo-saas.xml',
				'plugins' => array(),
			),
			'blog'       => array(
				'title'   => 'Blog Osobisty',
				'preview' => get_template_directory_uri() . '/assets/images/demos/blog.jpg',
				'xml'     => 'demo-blog.xml',
				'plugins' => array(),
			),
		);
	}

	/**
	 * Render Page
	 */
	public function render_page() {
		?>
		<div class="neve-onboarding-wrap">
			<header class="neve-header">
				<h1><?php esc_html_e( 'Witaj w Neve Lite', 'neve-lite' ); ?></h1>
				<p><?php esc_html_e( 'Skonfiguruj swoją stronę w kilka minut importując gotowe demo.', 'neve-lite' ); ?></p>
			</header>

			<div id="neve-onboarding-app">
				<div class="step step-select-demo active" data-step="1">
					<h2><?php esc_html_e( 'Wybierz szablon startowy', 'neve-lite' ); ?></h2>
					<div class="demos-grid">
						</div>
				</div>

				<div class="step step-select-builder" data-step="2">
					<h2><?php esc_html_e( 'Wybierz Page Builder', 'neve-lite' ); ?></h2>
					<div class="builders-grid">
						<div class="builder-card" data-builder="gutenberg">
							<span class="dashicons dashicons-editor-table"></span>
							<h3>Gutenberg (Domyślny)</h3>
						</div>
						<div class="builder-card" data-builder="elementor">
							<span class="dashicons dashicons-art"></span>
							<h3>Elementor</h3>
						</div>
					</div>
					<button class="button button-primary next-step"><?php esc_html_e( 'Dalej', 'neve-lite' ); ?></button>
				</div>

				<div class="step step-import" data-step="3">
					<h2><?php esc_html_e( 'Instalacja i Import', 'neve-lite' ); ?></h2>
					<ul class="import-progress-list">
						</ul>
					<button class="button button-primary start-import"><?php esc_html_e( 'Rozpocznij Import', 'neve-lite' ); ?></button>
				</div>

				<div class="step step-success" data-step="4">
					<div class="success-message">
						<span class="dashicons dashicons-yes-alt"></span>
						<h2><?php esc_html_e( 'Gotowe!', 'neve-lite' ); ?></h2>
						<p><?php esc_html_e( 'Twoja strona została pomyślnie zaimportowana.', 'neve-lite' ); ?></p>
						<a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Zobacz stronę', 'neve-lite' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX: Get Required Plugins
	 */
	public function ajax_get_plugins() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );

		$builder = isset( $_POST['builder'] ) ? sanitize_text_field( $_POST['builder'] ) : 'gutenberg';
		$demo    = isset( $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : '';

		$plugins_to_install = array();

		// 1. Zawsze wymagany importer WordPressa do obsługi XML
		$plugins_to_install[] = array(
			'slug' => 'wordpress-importer',
			'name' => 'WordPress Importer',
		);

		// 2. Elementor jeśli wybrano
		if ( 'elementor' === $builder ) {
			$plugins_to_install[] = array(
				'slug' => 'elementor',
				'name' => 'Elementor Website Builder',
			);
		}

		// 3. WooCommerce jeśli demo tego wymaga
		$demos_data = $this->get_demos_data();
		if ( isset( $demos_data[ $demo ] ) && in_array( 'woocommerce', $demos_data[ $demo ]['plugins'] ) ) {
			$plugins_to_install[] = array(
				'slug' => 'woocommerce',
				'name' => 'WooCommerce',
			);
		}

		// Filtrujemy tylko te, które nie są aktywne
		$final_list = array();
		foreach ( $plugins_to_install as $plugin ) {
			if ( ! is_plugin_active( $plugin['slug'] . '/' . $plugin['slug'] . '.php' ) ) {
				$final_list[] = $plugin;
			}
		}

		wp_send_json_success( $final_list );
	}

	/**
	 * AJAX: Install/Activate Plugin
	 */
	public function ajax_install_plugin() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => 'Brak uprawnień.' ) );
		}

		$slug = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
		if ( empty( $slug ) ) {
			wp_send_json_error( array( 'message' => 'Brak sluga wtyczki.' ) );
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		// Sprawdź czy zainstalowana
		$plugin_main_file = $this->get_plugin_main_file( $slug );

		if ( ! $plugin_main_file ) {
			// Instalacja
			$api = plugins_api( 'plugin_information', array(
				'slug'   => $slug,
				'fields' => array( 'sections' => false ),
			) );

			if ( is_wp_error( $api ) ) {
				wp_send_json_error( array( 'message' => $api->get_error_message() ) );
			}

			$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
			$result   = $upgrader->install( $api->download_link );

			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'message' => $result->get_error_message() ) );
			}

			$plugin_main_file = $this->get_plugin_main_file( $slug );
		}

		// Aktywacja
		if ( $plugin_main_file ) {
			activate_plugin( $plugin_main_file );
			wp_send_json_success( array( 'message' => "Zainstalowano $slug" ) );
		} else {
			wp_send_json_error( array( 'message' => "Nie udało się zlokalizować pliku wtyczki $slug" ) );
		}
	}

	/**
	 * Helper: Znajdź plik główny wtyczki po slugu folderu
	 */
	private function get_plugin_main_file( $slug ) {
		$plugins = get_plugins();
		foreach ( $plugins as $file => $data ) {
			if ( strpos( $file, $slug . '/' ) === 0 ) {
				return $file;
			}
		}
		return false;
	}

	/**
	 * AJAX: Import Content
	 */
	public function ajax_import_content() {
		check_ajax_referer( 'neve_onboarding_nonce', 'nonce' );

		// 1. Ustawienia środowiska dla ciężkich operacji
		ini_set( 'memory_limit', '512M' );
		set_time_limit( 300 );

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		// 2. Sprawdź i załaduj importer
		// Najpierw upewnij się, że plik importera istnieje (powinien zostać zainstalowany w kroku wtyczek)
		$importer_plugin_path = WP_PLUGIN_DIR . '/wordpress-importer/wordpress-importer.php';

		if ( file_exists( $importer_plugin_path ) && ! class_exists( 'WP_Import' ) ) {
			require_once $importer_plugin_path;
		}

		// Jeśli nadal brak klasy, spróbuj załadować natywny (często niedostępny w AJAX, stąd wtyczka jest bezpieczniejsza)
		if ( ! class_exists( 'WP_Import' ) ) {
			if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-importer.php' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			}
			// Importer WordPressa z wtyczki definiuje klasę WP_Import rozszerzającą WP_Importer
			// Jeśli jej nie ma, musimy przerwać, bo nie mamy silnika.
			wp_send_json_error( array( 'message' => 'Krytyczny błąd: Klasa WP_Import nie została załadowana. Upewnij się, że wtyczka WordPress Importer jest aktywna.' ) );
		}

		// 3. Przygotuj plik XML
		$demo = isset( $_POST['demo'] ) ? sanitize_text_field( $_POST['demo'] ) : '';
		$demos_data = $this->get_demos_data();

		if ( ! isset( $demos_data[ $demo ] ) ) {
			wp_send_json_error( array( 'message' => 'Nieprawidłowe demo.' ) );
		}

		$xml_file = get_template_directory() . '/demo-content/' . $demos_data[ $demo ]['xml'];

		if ( ! file_exists( $xml_file ) ) {
			wp_send_json_error( array( 'message' => 'Plik XML demo nie istnieje: ' . $xml_file ) );
		}

		// 4. Wykonaj Import
		try {
			// Wycisz output importera, żeby nie psuł JSON response
			ob_start();

			$importer = new WP_Import();
			$importer->fetch_attachments = true;
			$importer->import( $xml_file );

			$log = ob_get_clean();

			// Opcjonalnie: Ustawienie strony głównej i bloga
			$homepage = get_page_by_title( 'Home' );
			$blogpage = get_page_by_title( 'Blog' );

			if ( $homepage ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID );
			}
			if ( $blogpage ) {
				update_option( 'page_for_posts', $blogpage->ID );
			}

			// Jeśli wybrano Elementor, ustaw zestaw
			$builder = isset( $_POST['builder'] ) ? sanitize_text_field( $_POST['builder'] ) : 'gutenberg';
			if( 'elementor' === $builder ) {
				update_option( 'elementor_cpt_support', array( 'page', 'post', 'product' ) );
				update_option( 'elementor_disable_color_schemes', 'yes' );
				update_option( 'elementor_disable_typography_schemes', 'yes' );
			}

			wp_send_json_success( array( 'message' => 'Import zakończony sukcesem.', 'log' => $log ) );

		} catch ( Exception $e ) {
			ob_end_clean();
			wp_send_json_error( array( 'message' => 'Błąd podczas importu: ' . $e->getMessage() ) );
		}
	}
}

Neve_Lite_Onboarding::get_instance();
