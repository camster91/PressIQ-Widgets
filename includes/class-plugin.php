<?php
/**
 * Core Plugin Class
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin Class
 *
 * Handles autoloading, module loading, and core functionality.
 */
final class Plugin {

    /**
     * Plugin instance
     *
     * @var Plugin
     */
    private static $instance = null;

    /**
     * Active modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Plugin options
     *
     * @var array
     */
    private $options = array();

    /**
     * Get plugin instance
     *
     * @return Plugin
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_options();
        $this->register_autoloader();
        $this->init_hooks();
    }

    /**
     * Load plugin options
     */
    private function load_options() {
        $this->options = get_option( 'pressiq_options', array(
            'modules' => array(
                'filters' => true,
                'content' => true,
                'blocks'  => false,
            ),
        ) );
    }

    /**
     * Register autoloader for plugin classes
     */
    private function register_autoloader() {
        spl_autoload_register( array( $this, 'autoload' ) );
    }

    /**
     * Autoload classes
     *
     * @param string $class_name Class name to load.
     */
    public function autoload( $class_name ) {
        // Check if class is in our namespace
        if ( strpos( $class_name, 'PressIQ_Widgets\\' ) !== 0 ) {
            return;
        }

        // Remove namespace prefix
        $class_name = str_replace( 'PressIQ_Widgets\\', '', $class_name );

        // Convert class name to file path
        $class_file = strtolower( str_replace( '_', '-', $class_name ) );
        $class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_file );

        // Build possible file paths
        $paths = array(
            PRESSIQ_PLUGIN_DIR . 'includes/class-' . $class_file . '.php',
            PRESSIQ_PLUGIN_DIR . 'modules/' . $class_file . '.php',
        );

        // Handle widget classes
        if ( strpos( $class_name, 'Widgets\\' ) !== false ) {
            $widget_path = str_replace( 'Widgets\\', 'widgets/class-', $class_file );
            $paths[] = PRESSIQ_PLUGIN_DIR . 'modules/' . $widget_path . '.php';
        }

        // Handle module classes
        if ( strpos( $class_name, 'Modules\\' ) !== false ) {
            $parts = explode( '\\', $class_name );
            if ( count( $parts ) >= 2 ) {
                $module_name = strtolower( str_replace( '_', '-', $parts[1] ) );
                $class_part  = strtolower( str_replace( '_', '-', end( $parts ) ) );
                $paths[] = PRESSIQ_PLUGIN_DIR . 'modules/' . $module_name . '/class-' . $class_part . '.php';

                // Widget files within modules
                if ( strpos( $class_name, 'Widgets\\' ) !== false && count( $parts ) >= 4 ) {
                    $widget_name = strtolower( str_replace( '_', '-', $parts[3] ) );
                    $paths[] = PRESSIQ_PLUGIN_DIR . 'modules/' . $module_name . '/widgets/class-' . $widget_name . '.php';
                }
            }
        }

        // Try to load the file
        foreach ( $paths as $path ) {
            if ( file_exists( $path ) ) {
                require_once $path;
                return;
            }
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Initialize admin settings
        if ( is_admin() ) {
            Admin::instance();
        }

        $has_elementor = function_exists( 'pressiq_has_elementor' ) && pressiq_has_elementor();

        // Elementor-specific hooks (only when Elementor is active)
        if ( $has_elementor ) {
            // Initialize after Elementor is fully loaded
            add_action( 'elementor/init', array( $this, 'init' ) );

            // Register widget categories
            add_action( 'elementor/elements/categories_registered', array( $this, 'register_widget_categories' ) );

            // Register widgets
            add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

            // Enqueue editor scripts and styles
            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_assets' ) );

            // Enqueue Elementor frontend assets
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        }

        // Initialize AJAX handlers (needed for both Elementor and blocks)
        add_action( 'init', array( $this, 'init_ajax_handlers' ) );

        // Initialize blocks module (independent of Elementor)
        if ( $this->is_module_active( 'blocks' ) ) {
            add_action( 'init', array( $this, 'init_blocks_module' ) );

            // If no Elementor, still enqueue content CSS for block rendering
            if ( ! $has_elementor ) {
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_content_assets' ) );
            }
        }
    }

    /**
     * Initialize plugin (Elementor mode)
     */
    public function init() {
        // Load active Elementor modules
        $this->load_modules();

        /**
         * Fires after plugin initialization
         *
         * @param Plugin $this Plugin instance.
         */
        do_action( 'pressiq/init', $this );
    }

    /**
     * Load active Elementor modules (filters, content)
     */
    private function load_modules() {
        $active_modules = $this->options['modules'] ?? array();

        foreach ( $active_modules as $module_slug => $is_active ) {
            if ( ! $is_active ) {
                continue;
            }

            // Skip blocks module here – it's loaded separately
            if ( $module_slug === 'blocks' ) {
                continue;
            }

            $module_file = PRESSIQ_PLUGIN_DIR . 'modules/' . $module_slug . '/class-' . $module_slug . '-module.php';

            if ( file_exists( $module_file ) ) {
                require_once $module_file;

                $module_class = 'PressIQ_Widgets\\Modules\\' . ucfirst( $module_slug ) . '\\' . ucfirst( $module_slug ) . '_Module';

                if ( class_exists( $module_class ) ) {
                    $this->modules[ $module_slug ] = new $module_class();
                }
            }
        }
    }

    /**
     * Initialize the blocks module for FSE / block theme support
     */
    public function init_blocks_module() {
        $module_file = PRESSIQ_PLUGIN_DIR . 'modules/blocks/class-blocks-module.php';

        if ( file_exists( $module_file ) ) {
            require_once $module_file;
            $this->modules['blocks'] = new \PressIQ_Widgets\Modules\Blocks\Blocks_Module();
        }
    }

    /**
     * Enqueue content CSS for blocks when Elementor is not active
     */
    public function enqueue_block_content_assets() {
        wp_enqueue_style(
            'pressiq-content',
            PRESSIQ_PLUGIN_URL . 'assets/css/content.css',
            array(),
            PRESSIQ_VERSION
        );

        // Also load filters CSS if filters module is active
        if ( $this->is_module_active( 'filters' ) ) {
            wp_enqueue_style(
                'pressiq-filters',
                PRESSIQ_PLUGIN_URL . 'assets/css/filters.css',
                array(),
                PRESSIQ_VERSION
            );
        }
    }

    /**
     * Register widget categories in Elementor
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function register_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'pressiq-widgets',
            array(
                'title' => esc_html__( 'AC Starter Toolkit', 'pressiq-widgets' ),
                'icon'  => 'fa fa-plug',
            )
        );

        $elements_manager->add_category(
            'ac-filters',
            array(
                'title' => esc_html__( 'AC Smart Filters', 'pressiq-widgets' ),
                'icon'  => 'fa fa-filter',
            )
        );
    }

    /**
     * Register widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        // Each module handles its own widget registration
        foreach ( $this->modules as $module ) {
            if ( method_exists( $module, 'register_widgets' ) ) {
                $module->register_widgets( $widgets_manager );
            }
        }
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Filters module assets
        if ( $this->is_module_active( 'filters' ) ) {
            wp_enqueue_style(
                'pressiq-filters',
                PRESSIQ_PLUGIN_URL . 'assets/css/filters.css',
                array(),
                PRESSIQ_VERSION
            );

            wp_enqueue_script(
                'pressiq-filters',
                PRESSIQ_PLUGIN_URL . 'assets/js/filters.js',
                array(),
                PRESSIQ_VERSION,
                true
            );

            // Localize script with AJAX URL and other data
            wp_localize_script( 'pressiq-filters', 'pressiqFilters', array(
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'pressiq_filter_nonce' ),
                'i18n'      => array(
                    'loading'   => esc_html__( 'Loading...', 'pressiq-widgets' ),
                    'noResults' => esc_html__( 'No results found.', 'pressiq-widgets' ),
                    'error'     => esc_html__( 'An error occurred.', 'pressiq-widgets' ),
                ),
            ) );
        }

        // Content module assets
        if ( $this->is_module_active( 'content' ) ) {
            wp_enqueue_style(
                'pressiq-content',
                PRESSIQ_PLUGIN_URL . 'assets/css/content.css',
                array(),
                PRESSIQ_VERSION
            );

            wp_enqueue_script(
                'pressiq-content',
                PRESSIQ_PLUGIN_URL . 'assets/js/content.js',
                array(),
                PRESSIQ_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'pressiq-editor',
            PRESSIQ_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            PRESSIQ_VERSION
        );
    }

    /**
     * Initialize AJAX handlers
     */
    public function init_ajax_handlers() {
        if ( $this->is_module_active( 'filters' ) ) {
            $ajax_handler_file = PRESSIQ_PLUGIN_DIR . 'modules/filters/class-ajax-handler.php';
            if ( file_exists( $ajax_handler_file ) ) {
                require_once $ajax_handler_file;
                new Modules\Filters\Ajax_Handler();
            }
        }
    }

    /**
     * Check if a module is active
     *
     * @param string $module_slug Module slug.
     * @return bool
     */
    public function is_module_active( $module_slug ) {
        return isset( $this->options['modules'][ $module_slug ] ) && $this->options['modules'][ $module_slug ];
    }

    /**
     * Get a module instance
     *
     * @param string $module_slug Module slug.
     * @return object|null
     */
    public function get_module( $module_slug ) {
        return $this->modules[ $module_slug ] ?? null;
    }

    /**
     * Get all active modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialize singleton' );
    }
}
