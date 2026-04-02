<?php
/**
 * Plugin Name: PressIQ Widgets
 * Description: A modular Elementor widget toolkit with smart filters, content widgets, and more.
 * Version: 1.0.0
 * Author: Ashley Cameron
 * Author URI: https://example.com
 * Text Domain: pressiq-widgets
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Elementor tested up to: 3.19
 * Elementor Pro tested up to: 3.19
 *
 * @package PressIQ_Widgets
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Plugin constants
 */
define( 'PRESSIQ_VERSION', '1.0.0' );
define( 'PRESSIQ_PLUGIN_FILE', __FILE__ );
define( 'PRESSIQ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PRESSIQ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PRESSIQ_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Minimum requirements
 */
define( 'PRESSIQ_MINIMUM_ELEMENTOR_VERSION', '3.0.0' );
define( 'PRESSIQ_MINIMUM_PHP_VERSION', '7.4' );

/**
 * Check if the blocks module is enabled in options
 *
 * @return bool
 */
function acst_is_blocks_module_active() {
    $options = get_option( 'acst_options', array() );
    return ! empty( $options['modules']['blocks'] );
}

/**
 * Check if Elementor is available
 *
 * @return bool
 */
function acst_has_elementor() {
    return did_action( 'elementor/loaded' ) && defined( 'ELEMENTOR_VERSION' )
        && version_compare( ELEMENTOR_VERSION, PRESSIQ_MINIMUM_ELEMENTOR_VERSION, '>=' );
}

/**
 * Load the plugin after Elementor loads (or independently for blocks mode)
 */
function acst_load_plugin() {
    // Load text domain for translations
    load_plugin_textdomain( 'pressiq-widgets', false, dirname( PRESSIQ_PLUGIN_BASENAME ) . '/languages' );

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, PRESSIQ_MINIMUM_PHP_VERSION, '<' ) ) {
        add_action( 'admin_notices', 'acst_admin_notice_minimum_php_version' );
        return;
    }

    $has_elementor   = acst_has_elementor();
    $blocks_active   = acst_is_blocks_module_active();

    // If neither Elementor nor blocks module is available, show notice
    if ( ! $has_elementor && ! $blocks_active ) {
        add_action( 'admin_notices', 'acst_admin_notice_missing_elementor' );
        // Still load the plugin for admin settings so blocks can be enabled
        require_once PRESSIQ_PLUGIN_DIR . 'includes/class-plugin.php';
        \PressIQ_Widgets\Plugin::instance();
        return;
    }

    // All checks passed - load the plugin
    require_once PRESSIQ_PLUGIN_DIR . 'includes/class-plugin.php';
    \PressIQ_Widgets\Plugin::instance();
}
add_action( 'plugins_loaded', 'acst_load_plugin' );

/**
 * Admin notice: Elementor not installed (shown only when blocks module is also inactive)
 */
function acst_admin_notice_missing_elementor() {
    if ( isset( $_GET['activate'] ) ) {
        unset( $_GET['activate'] );
    }

    $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
        esc_html__( '"%1$s" works best with "%2$s" for Elementor widgets, or enable the Blocks module for native WordPress block support (compatible with block themes like Twenty Twenty-Six).', 'pressiq-widgets' ),
        '<strong>' . esc_html__( 'PressIQ Widgets', 'pressiq-widgets' ) . '</strong>',
        '<strong>' . esc_html__( 'Elementor', 'pressiq-widgets' ) . '</strong>'
    );

    printf( '<div class="notice notice-info is-dismissible"><p>%1$s</p></div>', $message );
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
        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'pressiq-widgets' ),
        '<strong>' . esc_html__( 'PressIQ Widgets', 'pressiq-widgets' ) . '</strong>',
        '<strong>' . esc_html__( 'Elementor', 'pressiq-widgets' ) . '</strong>',
        PRESSIQ_MINIMUM_ELEMENTOR_VERSION
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
        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'pressiq-widgets' ),
        '<strong>' . esc_html__( 'PressIQ Widgets', 'pressiq-widgets' ) . '</strong>',
        '<strong>' . esc_html__( 'PHP', 'pressiq-widgets' ) . '</strong>',
        PRESSIQ_MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * Plugin activation hook
 */
function acst_activate() {
    // Set default options – enable blocks by default for block theme compatibility
    $default_options = array(
        'modules' => array(
            'filters' => true,
            'content' => true,
            'blocks'  => true,
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
