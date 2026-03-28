<?php
/**
 * Blocks Module - WordPress Block Editor (Gutenberg) Integration
 *
 * Registers native WordPress blocks for use in the Site Editor
 * and block-based themes like Twenty Twenty-Six.
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Blocks Module Class
 */
class Blocks_Module {

    /**
     * Block directory path
     *
     * @var string
     */
    private $blocks_dir;

    /**
     * Available blocks
     *
     * @var array
     */
    private $blocks = array(
        'accordion',
        'tabs',
        'team-member',
        'pricing-table',
        'testimonial',
        'countdown',
        'post-filter',
    );

    /**
     * Constructor
     */
    public function __construct() {
        $this->blocks_dir = ACST_PLUGIN_DIR . 'modules/blocks/blocks/';

        add_action( 'init', array( $this, 'register_blocks' ) );
        add_action( 'init', array( $this, 'register_block_category' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_assets' ) );
    }

    /**
     * Register all blocks
     */
    public function register_blocks() {
        foreach ( $this->blocks as $block_name ) {
            $block_dir = $this->blocks_dir . $block_name;

            if ( file_exists( $block_dir . '/block.json' ) ) {
                register_block_type( $block_dir );
            }
        }
    }

    /**
     * Register block category
     */
    public function register_block_category() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        add_filter( 'block_categories_all', function ( $categories ) {
            // Check if category already exists.
            foreach ( $categories as $category ) {
                if ( $category['slug'] === 'ac-starter-toolkit' ) {
                    return $categories;
                }
            }

            array_unshift( $categories, array(
                'slug'  => 'ac-starter-toolkit',
                'title' => esc_html__( 'AC Starter Toolkit', 'ac-starter-toolkit' ),
                'icon'  => 'admin-generic',
            ) );

            return $categories;
        } );
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_editor_assets() {
        $asset_file = ACST_PLUGIN_DIR . 'assets/js/blocks-editor.asset.php';
        $deps       = array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data' );
        $version    = ACST_VERSION;

        if ( file_exists( $asset_file ) ) {
            $asset = require $asset_file;
            $deps  = $asset['dependencies'] ?? $deps;
            $version = $asset['version'] ?? $version;
        }

        wp_enqueue_script(
            'acst-blocks-editor',
            ACST_PLUGIN_URL . 'assets/js/blocks-editor.js',
            $deps,
            $version,
            true
        );

        wp_set_script_translations( 'acst-blocks-editor', 'ac-starter-toolkit' );

        wp_enqueue_style(
            'acst-blocks-editor',
            ACST_PLUGIN_URL . 'assets/css/blocks-editor.css',
            array( 'wp-edit-blocks' ),
            ACST_VERSION
        );
    }

    /**
     * Enqueue frontend assets for blocks
     */
    public function enqueue_frontend_assets() {
        if ( ! is_admin() ) {
            wp_enqueue_script(
                'acst-blocks-frontend',
                ACST_PLUGIN_URL . 'assets/js/blocks-frontend.js',
                array(),
                ACST_VERSION,
                true
            );

            wp_localize_script( 'acst-blocks-frontend', 'acstBlocks', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'acst_filter_nonce' ),
                'i18n'    => array(
                    'loading'   => esc_html__( 'Loading...', 'ac-starter-toolkit' ),
                    'noResults' => esc_html__( 'No results found.', 'ac-starter-toolkit' ),
                    'error'     => esc_html__( 'An error occurred.', 'ac-starter-toolkit' ),
                ),
            ) );
        }
    }

    /**
     * Enqueue block assets (both editor and frontend)
     */
    public function enqueue_block_assets() {
        wp_enqueue_style(
            'acst-blocks',
            ACST_PLUGIN_URL . 'assets/css/blocks.css',
            array(),
            ACST_VERSION
        );
    }

    /**
     * Get available blocks info
     *
     * @return array
     */
    public function get_blocks() {
        return $this->blocks;
    }
}
