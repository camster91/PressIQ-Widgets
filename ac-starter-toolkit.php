<?php
/**
 * Plugin Name: AC Starter Toolkit
 * Description: A modular Elementor widget toolkit with smart filters, content widgets, and more.
 * Version: 1.0.0
 * Author: Ashley Cameron
 * Author URI: https://example.com
 * Text Domain: ac-starter-toolkit
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Elementor tested up to: 3.19
 * Elementor Pro tested up to: 3.19
 *
 * @package AC_Starter_Toolkit
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin constants
 */
define( 'ACST_VERSION', '1.0.0' );
define( 'ACST_PLUGIN_FILE', __FILE__ );
define( 'ACST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACST_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ACST_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Minimum requirements
 */
define( 'ACST_MINIMUM_ELEMENTOR_VERSION', '3.0.0' );
define( 'ACST_MINIMUM_PHP_VERSION', '7.4' );

/**
 * Load the plugin after Elementor loads
 */
function acst_load_plugin() {
    // Load text domain for translations
    load_plugin_textdomain( 'ac-starter-toolkit', false, dirname( ACST_PLUGIN_BASENAME ) . '/languages' );

    // Check if Elementor is installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'acst_admin_notice_missing_elementor' );
        return;
    }

    // Check for required Elementor version
    if ( ! version_compare( ELEMENTOR_VERSION, ACST_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
        add_action( 'admin_notices', 'acst_admin_notice_minimum_elementor_version' );
        return;
    }

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, ACST_MINIMUM_PHP_VERSION, '<' ) ) {
        add_action( 'admin_notices', 'acst_admin_notice_minimum_php_version' );
        return;
    }

    // All checks passed - load the plugin
    require_once ACST_PLUGIN_DIR . 'includes/class-plugin.php';
    \AC_Starter_Toolkit\Plugin::instance();
}
add_action( 'plugins_loaded', 'acst_load_plugin' );

/**
 * Admin notice: Elementor not installed
 */
function acst_admin_notice_missing_elementor() {
    if ( isset( $_GET['activate'] ) ) {
        unset( $_GET['activate'] );
    }

    $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
        esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'ac-starter-toolkit' ),
        '<strong>' . esc_html__( 'AC Starter Toolkit', 'ac-starter-toolkit' ) . '</strong>',
        '<strong>' . esc_html__( 'Elementor', 'ac-starter-toolkit' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * Admin notice: Minimum Elementor version
 */
function acst_admin_notice_minimum_elementor_version() {
    if ( isset( $_GET['activate'] ) ) {
        unset( $_GET['activate'] );
    }

    $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required version */
        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ac-starter-toolkit' ),
        '<strong>' . esc_html__( 'AC Starter Toolkit', 'ac-starter-toolkit' ) . '</strong>',
        '<strong>' . esc_html__( 'Elementor', 'ac-starter-toolkit' ) . '</strong>',
        ACST_MINIMUM_ELEMENTOR_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * Admin notice: Minimum PHP version
 */
function acst_admin_notice_minimum_php_version() {
    if ( isset( $_GET['activate'] ) ) {
        unset( $_GET['activate'] );
    }

    $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required version */
        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'ac-starter-toolkit' ),
        '<strong>' . esc_html__( 'AC Starter Toolkit', 'ac-starter-toolkit' ) . '</strong>',
        '<strong>' . esc_html__( 'PHP', 'ac-starter-toolkit' ) . '</strong>',
        ACST_MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * Plugin activation hook
 */
function acst_activate() {
    // Set default options
    $default_options = array(
        'modules' => array(
            'filters' => true,
            'content' => false,
            'blocks'  => false,
        ),
    );

    if ( ! get_option( 'acst_options' ) ) {
        add_option( 'acst_options', $default_options );
    }

    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'acst_activate' );

/**
 * Plugin deactivation hook
 */
function acst_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'acst_deactivate' );
