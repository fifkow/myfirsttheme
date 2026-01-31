<?php
/**
 * Neve Lite Onboarding / Setup Wizard
 *
 * @package Neve_Lite
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Onboarding class
 */
class Neve_Lite_Onboarding {

    /**
     * Demo configurations
     */
    private $demos = array();

    /**
     * Import batch size
     */
    private $batch_size = 5;

    /**
     * Constructor
     */
    public function __construct() {
        $this->setup_demos();
        $this->init_hooks();
        $this->increase_memory_limit();
    }

    /**
     * Increase memory limit for import operations
     */
    private function increase_memory_limit(): void {
        // Only increase if current limit is lower
        $current_limit = ini_get( 'memory_limit' );
        $current_limit_bytes = $this->convert_to_bytes( $current_limit );
        $desired_limit_bytes = 512 * 1024 * 1024; // 512MB

        if ( $current_limit_bytes < $desired_limit_bytes ) {
            @ini_set( 'memory_limit', '512M' );
        }

        // Also increase max execution time
        @set_time_limit( 300 ); // 5 minutes
    }

    /**
     * Convert memory limit string to bytes
     */
    private function convert_to_bytes( string $value ): int {
        $unit = strtolower( substr( $value, -1 ) );
        $value = (int) $value;

        switch ( $unit ) {
            case 'g':
                $value *= 1024;
                // no break
            case 'm':
                $value *= 1024;
                // no break
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Setup demo configurations
     */
    private function setup_demos(): void {
        $this->demos = array(
            'sportswear' => array(
                'name'        => __( 'ActiveWear Pro', 'neve-lite' ),
                'description' => __( 'Sklep z odzieżą sportową i fitness', 'neve-lite' ),
                'category'    => __( 'WooCommerce', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/sportswear.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-sportswear-shop.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-sportswear-shop-elementor.xml',
                'plugins'     => array( 'woocommerce' ),
                'plugins_elementor' => array( 'woocommerce', 'elementor' ),
                'color'       => '#0073aa',
                'typography'  => array(
                    'body'    => 'Inter',
                    'heading' => 'Inter',
                ),
            ),
            'beauty' => array(
                'name'        => __( 'LaVie Beauty', 'neve-lite' ),
                'description' => __( 'Salon kosmetologii estetycznej', 'neve-lite' ),
                'category'    => __( 'Usługi', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/beauty.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-beauty-salon.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-beauty-salon-elementor.xml',
                'plugins'     => array( 'contact-form-7' ),
                'plugins_elementor' => array( 'elementor', 'contact-form-7' ),
                'color'       => '#d4a574',
                'typography'  => array(
                    'body'    => 'Lato',
                    'heading' => 'Playfair Display',
                ),
            ),
            'construction' => array(
                'name'        => __( 'BudMax', 'neve-lite' ),
                'description' => __( 'Firma budowlana i remontowa', 'neve-lite' ),
                'category'    => __( 'Usługi', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/construction.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-construction.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-construction-elementor.xml',
                'plugins'     => array( 'contact-form-7' ),
                'plugins_elementor' => array( 'elementor', 'contact-form-7' ),
                'color'       => '#f39c12',
                'typography'  => array(
                    'body'    => 'Roboto',
                    'heading' => 'Montserrat',
                ),
            ),
            'restaurant' => array(
                'name'        => __( 'Trattoria Bella', 'neve-lite' ),
                'description' => __( 'Restauracja włoska', 'neve-lite' ),
                'category'    => __( 'Gastronomia', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/restaurant.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-restaurant.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-restaurant-elementor.xml',
                'plugins'     => array( 'contact-form-7', 'restaurant-reservations' ),
                'plugins_elementor' => array( 'elementor', 'contact-form-7', 'restaurant-reservations' ),
                'color'       => '#c41e3a',
                'typography'  => array(
                    'body'    => 'Lato',
                    'heading' => 'Playfair Display',
                ),
            ),
            'saas' => array(
                'name'        => __( 'CloudFlow', 'neve-lite' ),
                'description' => __( 'Landing page dla produktu SaaS', 'neve-lite' ),
                'category'    => __( 'SaaS / Tech', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/saas.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-saas.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-saas-elementor.xml',
                'plugins'     => array(),
                'plugins_elementor' => array( 'elementor' ),
                'color'       => '#6366f1',
                'typography'  => array(
                    'body'    => 'Inter',
                    'heading' => 'Inter',
                ),
            ),
            'blog' => array(
                'name'        => __( 'Expert Blog', 'neve-lite' ),
                'description' => __( 'Blog ekspercki / personal brand', 'neve-lite' ),
                'category'    => __( 'Blog', 'neve-lite' ),
                'preview'     => NEVE_LITE_URI . '/assets/images/demos/blog.jpg',
                'xml_file'    => NEVE_LITE_DIR . '/demo-content/demo-blog.xml',
                'xml_file_elementor' => NEVE_LITE_DIR . '/demo-content/demo-blog-elementor.xml',
                'plugins'     => array( 'mailchimp-for-wp' ),
                'plugins_elementor' => array( 'elementor', 'mailchimp-for-wp' ),
                'color'       => '#f59e0b',
                'typography'  => array(
                    'body'    => 'Merriweather',
                    'heading' => 'Montserrat',
                ),
            ),
        );
    }

    /**
     * Initialize hooks
     */
    private function init_hooks(): void {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_neve_lite_import_demo', array( $this, 'ajax_import_demo' ) );
        add_action( 'wp_ajax_neve_lite_import_batch', array( $this, 'ajax_import_batch' ) );
        add_action( 'wp_ajax_neve_lite_import_start', array( $this, 'ajax_import_start' ) );
        add_action( 'wp_ajax_neve_lite_import_finish', array( $this, 'ajax_import_finish' ) );
        add_action( 'wp_ajax_neve_lite_install_plugin', array( $this, 'ajax_install_plugin' ) );
        add_action( 'wp_ajax_neve_lite_activate_plugin', array( $this, 'ajax_activate_plugin' ) );
        add_action( 'wp_ajax_neve_lite_check_plugin', array( $this, 'ajax_check_plugin' ) );
        add_action( 'wp_ajax_neve_lite_set_colors', array( $this, 'ajax_set_colors' ) );
        add_action( 'wp_ajax_neve_lite_set_typography', array( $this, 'ajax_set_typography' ) );
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
    }

    /**
     * Add menu page
     */
    public function add_menu_page(): void {
        add_theme_page(
            __( 'Neve Lite Setup', 'neve-lite' ),
            __( 'Neve Lite Setup', 'neve-lite' ),
            'manage_options',
            'neve-lite-setup',
            array( $this, 'render_setup_page' )
        );
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets( string $hook ): void {
        if ( $hook !== 'appearance_page_neve-lite-setup' ) {
            return;
        }

        wp_enqueue_style(
            'neve-lite-onboarding',
            NEVE_LITE_ASSETS_URI . 'css/onboarding.css',
            array(),
            NEVE_LITE_VERSION
        );

        wp_enqueue_script(
            'neve-lite-onboarding',
            NEVE_LITE_ASSETS_URI . 'js/onboarding.js',
            array( 'jquery' ),
            NEVE_LITE_VERSION,
            true
        );

        // Build demos array for JS (ensure all keys are present)
        $demos_for_js = array();
        foreach ( $this->demos as $key => $demo ) {
            $demos_for_js[ $key ] = array(
                'name'               => $demo['name'],
                'description'        => $demo['description'],
                'category'           => $demo['category'],
                'preview'            => $demo['preview'],
                'color'              => $demo['color'],
                'typography'         => $demo['typography'],
                'plugins'            => isset( $demo['plugins'] ) ? $demo['plugins'] : array(),
                'plugins_elementor'  => isset( $demo['plugins_elementor'] ) ? $demo['plugins_elementor'] : array(),
            );
        }

        wp_localize_script(
            'neve-lite-onboarding',
            'neveLiteOnboarding',
            array(
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'neve-lite-onboarding' ),
                'demos'     => $demos_for_js,
                'strings'   => array(
                    'installing'      => __( 'Instalowanie...', 'neve-lite' ),
                    'activating'      => __( 'Aktywowanie...', 'neve-lite' ),
                    'importing'       => __( 'Importowanie...', 'neve-lite' ),
                    'success'         => __( 'Gotowe!', 'neve-lite' ),
                    'error'           => __( 'Błąd', 'neve-lite' ),
                    'selectDemo'      => __( 'Wybierz demo', 'neve-lite' ),
                    'installPlugins'  => __( 'Zainstaluj wtyczki', 'neve-lite' ),
                    'importContent'   => __( 'Importuj treść', 'neve-lite' ),
                    'customize'       => __( 'Dostosuj', 'neve-lite' ),
                    'finish'          => __( 'Zakończ', 'neve-lite' ),
                ),
            )
        );
    }

    /**
     * Render setup page
     */
    public function render_setup_page(): void {
        ?>
        <div class="wrap neve-lite-setup">
            <div class="neve-lite-setup-header">
                <h1><?php esc_html_e( 'Witaj w Neve Lite!', 'neve-lite' ); ?></h1>
                <p><?php esc_html_e( 'Wybierz jeden z gotowych szablonów lub zacznij od podstaw. Możesz zmienić wszystko później w Customizerze.', 'neve-lite' ); ?></p>
            </div>

            <div class="neve-lite-setup-steps">
                <div class="step-indicator">
                    <div class="step active" data-step="1">
                        <span class="step-number">1</span>
                        <span class="step-label"><?php esc_html_e( 'Wybierz demo', 'neve-lite' ); ?></span>
                    </div>
                    <div class="step" data-step="2">
                        <span class="step-number">2</span>
                        <span class="step-label"><?php esc_html_e( 'Wtyczki', 'neve-lite' ); ?></span>
                    </div>
                    <div class="step" data-step="3">
                        <span class="step-number">3</span>
                        <span class="step-label"><?php esc_html_e( 'Import', 'neve-lite' ); ?></span>
                    </div>
                    <div class="step" data-step="4">
                        <span class="step-number">4</span>
                        <span class="step-label"><?php esc_html_e( 'Gotowe', 'neve-lite' ); ?></span>
                    </div>
                </div>

                <!-- Step 1: Select Demo -->
                <div class="step-content active" data-step="1">
                    <h2><?php esc_html_e( 'Wybierz szablon startowy', 'neve-lite' ); ?></h2>
                    <p><?php esc_html_e( 'Każdy szablon zawiera gotowe strony, kolory i ustawienia. Możesz wszystko dostosować później.', 'neve-lite' ); ?></p>
                    
                    <!-- Debug info -->
                    <script>console.log('Demos from PHP:', <?php echo wp_json_encode( $demos_for_js ); ?>);</script>
                    
                    <!-- Builder Selection -->
                    <div class="builder-selection">
                        <h3><?php esc_html_e( 'Wybierz builder', 'neve-lite' ); ?></h3>
                        <div class="builder-options">
                            <label class="builder-option active">
                                <input type="radio" name="builder" value="gutenberg" checked>
                                <span class="builder-icon">📝</span>
                                <span class="builder-name"><?php esc_html_e( 'Gutenberg', 'neve-lite' ); ?></span>
                                <span class="builder-desc"><?php esc_html_e( 'Edytor blokowy WordPress', 'neve-lite' ); ?></span>
                            </label>
                            <label class="builder-option">
                                <input type="radio" name="builder" value="elementor">
                                <span class="builder-icon">🎨</span>
                                <span class="builder-name"><?php esc_html_e( 'Elementor', 'neve-lite' ); ?></span>
                                <span class="builder-desc"><?php esc_html_e( 'Kreator stron drag & drop', 'neve-lite' ); ?></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="demo-grid">
                        <!-- Blank Starter -->
                        <div class="demo-card blank" data-demo="blank">
                            <div class="demo-preview">
                                <div class="blank-icon">+</div>
                            </div>
                            <div class="demo-info">
                                <h3><?php esc_html_e( 'Zacznij od podstaw', 'neve-lite' ); ?></h3>
                                <p><?php esc_html_e( 'Czysty start bez żadnej treści', 'neve-lite' ); ?></p>
                            </div>
                        </div>

                        <?php foreach ( $this->demos as $key => $demo ) : ?>
                        <div class="demo-card" data-demo="<?php echo esc_attr( $key ); ?>">
                            <div class="demo-preview" style="background-color: <?php echo esc_attr( $demo['color'] ); ?>">
                                <span class="demo-category"><?php echo esc_html( $demo['category'] ); ?></span>
                                <div class="demo-icon"><?php echo esc_html( substr( $demo['name'], 0, 1 ) ); ?></div>
                                <div class="demo-preview-overlay">
                                    <button type="button" class="button demo-preview-btn" data-demo="<?php echo esc_attr( $key ); ?>">
                                        <?php esc_html_e( 'Podgląd', 'neve-lite' ); ?>
                                    </button>
                                </div>
                            </div>
                            <div class="demo-info">
                                <h3><?php echo esc_html( $demo['name'] ); ?></h3>
                                <p><?php echo esc_html( $demo['description'] ); ?></p>
                                <div class="demo-meta">
                                    <span class="demo-color" style="background-color: <?php echo esc_attr( $demo['color'] ); ?>"></span>
                                    <span class="demo-fonts"><?php echo esc_html( $demo['typography']['heading'] ); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="step-actions">
                        <button class="button button-primary next-step" data-next="2" disabled>
                            <?php esc_html_e( 'Dalej', 'neve-lite' ); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Install Plugins -->
                <div class="step-content" data-step="2">
                    <h2><?php esc_html_e( 'Zainstaluj wymagane wtyczki', 'neve-lite' ); ?></h2>
                    <p><?php esc_html_e( 'Te wtyczki są potrzebne do pełnej funkcjonalności wybranego szablonu.', 'neve-lite' ); ?></p>
                    
                    <div class="plugins-list" id="plugins-list">
                        <!-- Plugins will be loaded dynamically -->
                    </div>

                    <div class="step-actions">
                        <button class="button prev-step" data-prev="1">
                            <?php esc_html_e( 'Wstecz', 'neve-lite' ); ?>
                        </button>
                        <button class="button button-primary next-step" data-next="3">
                            <?php esc_html_e( 'Dalej', 'neve-lite' ); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Import Content -->
                <div class="step-content" data-step="3">
                    <h2><?php esc_html_e( 'Importuj treść', 'neve-lite' ); ?></h2>
                    <p><?php esc_html_e( 'Zaimportujemy strony, wpisy, menu i ustawienia.', 'neve-lite' ); ?></p>
                    
                    <div class="import-options">
                        <label class="import-option">
                            <input type="checkbox" id="import-content" checked>
                            <span class="checkmark"></span>
                            <span class="label"><?php esc_html_e( 'Importuj strony i wpisy', 'neve-lite' ); ?></span>
                        </label>
                        <label class="import-option">
                            <input type="checkbox" id="import-media" checked>
                            <span class="checkmark"></span>
                            <span class="label"><?php esc_html_e( 'Importuj obrazki (zalecane)', 'neve-lite' ); ?></span>
                        </label>
                        <label class="import-option">
                            <input type="checkbox" id="import-widgets" checked>
                            <span class="checkmark"></span>
                            <span class="label"><?php esc_html_e( 'Importuj widgety', 'neve-lite' ); ?></span>
                        </label>
                        <label class="import-option">
                            <input type="checkbox" id="set-homepage" checked>
                            <span class="checkmark"></span>
                            <span class="label"><?php esc_html_e( 'Ustaw stronę główną', 'neve-lite' ); ?></span>
                        </label>
                    </div>

                    <div class="import-progress" id="import-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                        <p class="progress-status"><?php esc_html_e( 'Przygotowanie...', 'neve-lite' ); ?></p>
                    </div>

                    <div class="step-actions">
                        <button class="button prev-step" data-prev="2">
                            <?php esc_html_e( 'Wstecz', 'neve-lite' ); ?>
                        </button>
                        <button class="button button-primary" id="start-import">
                            <?php esc_html_e( 'Rozpocznij import', 'neve-lite' ); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Finish -->
                <div class="step-content" data-step="4">
                    <div class="success-message">
                        <div class="success-icon">✓</div>
                        <h2><?php esc_html_e( 'Gotowe!', 'neve-lite' ); ?></h2>
                        <p><?php esc_html_e( 'Twój szablon został zaimportowany. Co chcesz zrobić teraz?', 'neve-lite' ); ?></p>
                    </div>

                    <div class="finish-actions">
                        <a href="<?php echo esc_url( home_url() ); ?>" class="button button-primary button-hero" target="_blank">
                            <?php esc_html_e( 'Zobacz stronę', 'neve-lite' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary button-hero">
                            <?php esc_html_e( 'Dostosuj w Customizerze', 'neve-lite' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'post.php?post=' . get_option( 'page_on_front' ) . '&action=edit' ) ); ?>" class="button button-secondary button-hero" id="edit-with-elementor">
                            <?php esc_html_e( 'Edytuj w Elementorze', 'neve-lite' ); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Demo Preview Modal -->
            <div class="demo-preview-modal" id="demo-preview-modal">
                <button type="button" class="demo-preview-close" id="demo-preview-close">&times;</button>
                <div class="demo-preview-container">
                    <div class="demo-preview-header">
                        <h3 id="preview-demo-name"></h3>
                        <div class="demo-preview-actions">
                            <button type="button" class="button button-primary select-this-demo">
                                <?php esc_html_e( 'Wybierz ten szablon', 'neve-lite' ); ?>
                            </button>
                        </div>
                    </div>
                    <div class="demo-preview-content">
                        <img id="preview-demo-image" src="" alt="">
                    </div>
                    <div class="demo-preview-info">
                        <div class="preview-info-section">
                            <h4><?php esc_html_e( 'Opis', 'neve-lite' ); ?></h4>
                            <p id="preview-demo-description"></p>
                        </div>
                        <div class="preview-info-section">
                            <h4><?php esc_html_e( 'Wymagane wtyczki', 'neve-lite' ); ?></h4>
                            <ul id="preview-demo-plugins"></ul>
                        </div>
                        <div class="preview-info-section">
                            <h4><?php esc_html_e( 'Kolory i typografia', 'neve-lite' ); ?></h4>
                            <div class="preview-colors">
                                <span class="preview-color" id="preview-demo-color"></span>
                                <span id="preview-demo-fonts"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX: Install plugin
     */
    public function ajax_install_plugin(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        $plugin = sanitize_text_field( $_POST['plugin'] ?? '' );
        $plugins_map = array(
            'woocommerce'               => 'woocommerce/woocommerce.php',
            'elementor'                 => 'elementor/elementor.php',
            'contact-form-7'            => 'contact-form-7/wp-contact-form-7.php',
            'restaurant-reservations'   => 'restaurant-reservations/restaurant-reservations.php',
            'mailchimp-for-wp'          => 'mailchimp-for-wp/mailchimp-for-wp.php',
        );

        if ( ! isset( $plugins_map[ $plugin ] ) ) {
            wp_send_json_error( __( 'Nieznana wtyczka', 'neve-lite' ) );
        }

        include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $plugin_slug = explode( '/', $plugins_map[ $plugin ] )[0];
        
        $api = plugins_api(
            'plugin_information',
            array(
                'slug'   => $plugin_slug,
                'fields' => array( 'sections' => false ),
            )
        );

        if ( is_wp_error( $api ) ) {
            wp_send_json_error( $api->get_error_message() );
        }

        $upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
        $result   = $upgrader->install( $api->download_link );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_message() );
        }

        wp_send_json_success( __( 'Wtyczka zainstalowana', 'neve-lite' ) );
    }

    /**
     * AJAX: Activate plugin
     */
    public function ajax_activate_plugin(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        $plugin = sanitize_text_field( $_POST['plugin'] ?? '' );
        $plugins_map = array(
            'woocommerce'               => 'woocommerce/woocommerce.php',
            'elementor'                 => 'elementor/elementor.php',
            'contact-form-7'            => 'contact-form-7/wp-contact-form-7.php',
            'restaurant-reservations'   => 'restaurant-reservations/restaurant-reservations.php',
            'mailchimp-for-wp'          => 'mailchimp-for-wp/mailchimp-for-wp.php',
        );

        if ( ! isset( $plugins_map[ $plugin ] ) ) {
            wp_send_json_error( __( 'Nieznana wtyczka', 'neve-lite' ) );
        }

        $result = activate_plugin( $plugins_map[ $plugin ] );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( $result->get_error_message() );
        }

        wp_send_json_success( __( 'Wtyczka aktywowana', 'neve-lite' ) );
    }

    /**
     * AJAX: Start import - initialize and count items
     */
    public function ajax_import_start(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'import' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        $demo = sanitize_text_field( $_POST['demo'] ?? '' );
        $builder = sanitize_text_field( $_POST['builder'] ?? 'gutenberg' );

        if ( ! isset( $this->demos[ $demo ] ) ) {
            wp_send_json_error( __( 'Nieznane demo', 'neve-lite' ) );
        }

        $demo_data = $this->demos[ $demo ];
        
        // Select XML file based on builder
        if ( $builder === 'elementor' && ! empty( $demo_data['xml_file_elementor'] ) && file_exists( $demo_data['xml_file_elementor'] ) ) {
            $xml_file = $demo_data['xml_file_elementor'];
        } else {
            $xml_file = $demo_data['xml_file'];
        }

        if ( ! file_exists( $xml_file ) ) {
            wp_send_json_error( __( 'Plik demo nie istnieje', 'neve-lite' ) );
        }

        // Set colors and typography
        set_theme_mod( 'neve_lite_primary_color', $demo_data['color'] );
        set_theme_mod( 'neve_lite_body_font_family', $demo_data['typography']['body'] );
        set_theme_mod( 'neve_lite_heading_font_family', $demo_data['typography']['heading'] );

        // Count items in XML (memory-efficient)
        $total = $this->count_xml_items( $xml_file );
        
        if ( $total === 0 ) {
            wp_send_json_error( __( 'Brak treści do importu', 'neve-lite' ) );
        }

        // Store minimal progress data
        set_transient( 'neve_lite_import_state', array(
            'xml_file'    => $xml_file,
            'demo'        => $demo,
            'builder'     => $builder,
            'total'       => $total,
            'imported'    => 0,
            'homepage'    => null,
            'offset'      => 0,
        ), HOUR_IN_SECONDS );

        wp_send_json_success( array(
            'total'     => $total,
            'message'   => __( 'Rozpoczynam import...', 'neve-lite' ),
        ) );
    }

    /**
     * Count items in XML file (memory-efficient)
     */
    private function count_xml_items( string $xml_file ): int {
        $count = 0;
        
        if ( ! class_exists( 'XMLReader' ) ) {
            // Fallback - count items differently
            $xml = simplexml_load_file( $xml_file );
            if ( $xml && $xml->channel && $xml->channel->item ) {
                foreach ( $xml->channel->item as $item ) {
                    $wp_ns = $item->children( 'wp', true );
                    $post_type = (string) $wp_ns->post_type;
                    if ( in_array( $post_type, array( 'page', 'post' ), true ) ) {
                        $count++;
                    }
                }
            }
            unset( $xml );
            return $count;
        }
        
        $reader = new XMLReader();
        
        if ( ! $reader->open( $xml_file ) ) {
            return 0;
        }

        while ( $reader->read() ) {
            if ( $reader->nodeType === XMLReader::ELEMENT && $reader->name === 'item' ) {
                // Parse item to get post_type
                $item_xml = $reader->readOuterXML();
                $item = @simplexml_load_string( $item_xml );
                
                if ( $item ) {
                    $wp_ns = $item->children( 'wp', true );
                    $post_type = (string) $wp_ns->post_type;
                    
                    if ( in_array( $post_type, array( 'page', 'post' ), true ) ) {
                        $count++;
                    }
                }
                
                // Clear to free memory
                unset( $item_xml, $item, $wp_ns );
            }
        }
        
        $reader->close();
        unset( $reader );
        
        return $count;
    }

    /**
     * AJAX: Import batch of items
     */
    public function ajax_import_batch(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'import' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        // Get state
        $state = get_transient( 'neve_lite_import_state' );

        if ( false === $state ) {
            wp_send_json_error( __( 'Sesja importu wygasła. Spróbuj ponownie.', 'neve-lite' ) );
        }

        // Import one item at a time to minimize memory usage
        $result = $this->import_next_item( $state );
        
        if ( false === $result ) {
            // No more items
            wp_send_json_success( array(
                'complete'  => true,
                'imported'  => $state['imported'],
                'total'     => $state['total'],
                'message'   => __( 'Import zakończony!', 'neve-lite' ),
            ) );
        }

        // Update state
        $state['imported']++;
        $state['offset']++;
        set_transient( 'neve_lite_import_state', $state, HOUR_IN_SECONDS );

        // Calculate percentage
        $percentage = round( ( $state['imported'] / $state['total'] ) * 100 );

        wp_send_json_success( array(
            'complete'    => false,
            'imported'    => $state['imported'],
            'total'       => $state['total'],
            'percentage'  => $percentage,
            'message'     => sprintf( 
                /* translators: 1: Current count, 2: Total count */
                __( 'Importowanie... %1$s / %2$s', 'neve-lite' ), 
                $state['imported'], 
                $state['total'] 
            ),
        ) );
    }

    /**
     * Import next item from XML
     */
    private function import_next_item( array &$state ) {
        // Fallback if XMLReader not available
        if ( ! class_exists( 'XMLReader' ) ) {
            return $this->import_next_item_fallback( $state );
        }
        
        $reader = new XMLReader();
        
        if ( ! $reader->open( $state['xml_file'] ) ) {
            return false;
        }

        $current_index = 0;
        $item_imported = false;

        while ( $reader->read() ) {
            if ( $reader->nodeType === XMLReader::ELEMENT && $reader->name === 'item' ) {
                // Skip already imported items
                if ( $current_index < $state['offset'] ) {
                    $current_index++;
                    continue;
                }

                // Get item XML
                $item_xml = $reader->readOuterXML();
                
                // Parse item
                $item = @simplexml_load_string( $item_xml );
                
                if ( $item ) {
                    $wp_ns = $item->children( 'wp', true );
                    
                    $post_type = (string) $wp_ns->post_type;
                    
                    // Only import pages and posts
                    if ( in_array( $post_type, array( 'page', 'post' ), true ) ) {
                        $this->create_post( $item, $state );
                        $item_imported = true;
                    }
                }
                
                // Clear to free memory
                unset( $item_xml, $item );
                if ( isset( $wp_ns ) ) {
                    unset( $wp_ns );
                }
                
                $reader->close();
                unset( $reader );
                
                return $item_imported;
            }
        }
        
        $reader->close();
        unset( $reader );
        
        return false;
    }

    /**
     * Fallback import method using simplexml
     */
    private function import_next_item_fallback( array &$state ) {
        $xml = simplexml_load_file( $state['xml_file'] );
        
        if ( ! $xml || ! $xml->channel || ! $xml->channel->item ) {
            return false;
        }

        $current_index = 0;
        
        foreach ( $xml->channel->item as $item ) {
            // Skip already imported items
            if ( $current_index < $state['offset'] ) {
                $current_index++;
                continue;
            }

            $wp_ns = $item->children( 'wp', true );
            $post_type = (string) $wp_ns->post_type;
            
            // Only import pages and posts
            if ( in_array( $post_type, array( 'page', 'post' ), true ) ) {
                $this->create_post( $item, $state );
                unset( $xml, $item, $wp_ns );
                return true;
            }
            
            $current_index++;
        }
        
        unset( $xml );
        return false;
    }

    /**
     * Create post from XML item
     */
    private function create_post( $item, array &$state ): void {
        // Get namespaces
        $wp_ns = $item->children( 'wp', true );
        $content_ns = $item->children( 'content', true );
        
        // Get post data from XML
        $title = isset( $item->title ) ? (string) $item->title : '';
        $content = isset( $content_ns->encoded ) ? (string) $content_ns->encoded : '';
        $post_type = isset( $wp_ns->post_type ) ? (string) $wp_ns->post_type : 'page';
        
        if ( empty( $title ) ) {
            return;
        }
        
        // Sanitize content
        $content = $this->sanitize_content( $content );

        // Prepare post data
        $post_data = array(
            'post_title'   => sanitize_text_field( $title ),
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => $post_type,
        );

        // Check if post already exists
        $existing_id = $this->get_post_by_title( $post_data['post_title'], $post_type );

        if ( $existing_id ) {
            $post_data['ID'] = $existing_id;
            $post_id = wp_update_post( $post_data, true );
        } else {
            $post_id = wp_insert_post( $post_data, true );
        }

        if ( is_wp_error( $post_id ) ) {
            return;
        }

        // Track homepage
        $is_homepage = false;
        if ( $post_type === 'page' ) {
            $title_lower = strtolower( $title );
            if ( 
                strpos( $title_lower, 'główna' ) !== false || 
                strpos( $title_lower, 'home' ) !== false ||
                strpos( $title_lower, 'strona główna' ) !== false
            ) {
                $state['homepage'] = $post_id;
                $is_homepage = true;
            }
        }

        // Import meta data from XML for Elementor builder
        if ( isset( $state['builder'] ) && $state['builder'] === 'elementor' ) {
            // Import all postmeta from XML
            if ( isset( $wp_ns->postmeta ) ) {
                foreach ( $wp_ns->postmeta as $meta ) {
                    $meta_key = (string) $meta->meta_key;
                    $meta_value = (string) $meta->meta_value;
                    
                    // Skip some internal meta keys
                    if ( in_array( $meta_key, array( '_edit_lock', '_edit_last' ), true ) ) {
                        continue;
                    }
                    
                    update_post_meta( $post_id, $meta_key, $meta_value );
                }
            }
        }
    }

    /**
     * Get post ID by title (replacement for deprecated get_page_by_title)
     */
    private function get_post_by_title( string $title, string $post_type = 'page' ): ?int {
        $query = new WP_Query(
            array(
                'post_type'              => $post_type,
                'title'                  => $title,
                'posts_per_page'         => 1,
                'no_found_rows'          => true,
                'ignore_sticky_posts'    => true,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
            )
        );

        if ( ! empty( $query->post ) ) {
            return $query->post->ID;
        }

        return null;
    }

    /**
     * Sanitize content to prevent memory issues
     */
    private function sanitize_content( string $content ): string {
        // Only limit content length if extremely large
        // Don't modify whitespace as it breaks Gutenberg blocks
        if ( strlen( $content ) > 1000000 ) { // ~1MB max
            $content = substr( $content, 0, 1000000 );
        }
        
        return $content;
    }

    /**
     * AJAX: Finish import - set homepage and cleanup
     */
    public function ajax_import_finish(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'import' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        $state = get_transient( 'neve_lite_import_state' );

        // Set homepage if found
        if ( $state && ! empty( $state['homepage'] ) ) {
            update_option( 'page_on_front', $state['homepage'] );
            update_option( 'show_on_front', 'page' );
        }

        // Cleanup transient
        delete_transient( 'neve_lite_import_state' );

        // Clear all caches
        wp_cache_flush();
        
        // Trigger garbage collection
        if ( function_exists( 'gc_collect_cycles' ) ) {
            gc_collect_cycles();
        }

        wp_send_json_success( array(
            'message'   => __( 'Import zakończony pomyślnie!', 'neve-lite' ),
            'imported'  => $state['imported'] ?? 0,
            'homepage'  => $state['homepage'] ?? null,
        ) );
    }

    /**
     * Legacy AJAX: Import demo (kept for compatibility)
     */
    public function ajax_import_demo(): void {
        // Delegate to new batch system
        $this->ajax_import_start();
    }

    /**
     * AJAX: Check plugin status
     */
    public function ajax_check_plugin(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );

        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( __( 'Brak uprawnień', 'neve-lite' ) );
        }

        $plugin = sanitize_text_field( $_POST['plugin'] ?? '' );
        $plugins_map = array(
            'woocommerce'               => 'woocommerce/woocommerce.php',
            'elementor'                 => 'elementor/elementor.php',
            'contact-form-7'            => 'contact-form-7/wp-contact-form-7.php',
            'restaurant-reservations'   => 'restaurant-reservations/restaurant-reservations.php',
            'mailchimp-for-wp'          => 'mailchimp-for-wp/mailchimp-for-wp.php',
        );

        if ( ! isset( $plugins_map[ $plugin ] ) ) {
            wp_send_json_error( __( 'Nieznana wtyczka', 'neve-lite' ) );
        }

        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $plugin_file = $plugins_map[ $plugin ];

        if ( is_plugin_active( $plugin_file ) ) {
            wp_send_json_success( array( 'status' => 'active' ) );
        } elseif ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
            wp_send_json_success( array( 'status' => 'installed' ) );
        } else {
            wp_send_json_success( array( 'status' => 'not-installed' ) );
        }
    }

    /**
     * AJAX: Set colors
     */
    public function ajax_set_colors(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );
        
        $color = sanitize_hex_color( $_POST['color'] ?? '' );
        
        if ( $color ) {
            set_theme_mod( 'neve_lite_primary_color', $color );
        }
        
        wp_send_json_success();
    }

    /**
     * AJAX: Set typography
     */
    public function ajax_set_typography(): void {
        check_ajax_referer( 'neve-lite-onboarding', 'nonce' );
        
        $body    = sanitize_text_field( $_POST['body'] ?? '' );
        $heading = sanitize_text_field( $_POST['heading'] ?? '' );
        
        if ( $body ) {
            set_theme_mod( 'neve_lite_body_font_family', $body );
        }
        
        if ( $heading ) {
            set_theme_mod( 'neve_lite_heading_font_family', $heading );
        }
        
        wp_send_json_success();
    }

    /**
     * Admin notice
     */
    public function admin_notice(): void {
        $screen = get_current_screen();
        
        if ( $screen->id === 'appearance_page_neve-lite-setup' ) {
            return;
        }

        $dismissed = get_option( 'neve_lite_notice_dismissed', false );
        
        if ( $dismissed ) {
            return;
        }
        ?>
        <div class="notice notice-info is-dismissible neve-lite-notice">
            <h3><?php esc_html_e( 'Witaj w Neve Lite!', 'neve-lite' ); ?></h3>
            <p><?php esc_html_e( 'Skonfiguruj swój motyw w kilka minut. Wybierz gotowy szablon lub zacznij od podstaw.', 'neve-lite' ); ?></p>
            <p>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=neve-lite-setup' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Rozpocznij konfigurację', 'neve-lite' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-secondary">
                    <?php esc_html_e( 'Przejdź do Customizera', 'neve-lite' ); ?>
                </a>
            </p>
        </div>
        <?php
    }
}

// Initialize
new Neve_Lite_Onboarding();
