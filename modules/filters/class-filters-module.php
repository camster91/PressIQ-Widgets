<?php
/**
 * Filters Module
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Filters;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Filters Module Class
 *
 * Handles the smart filters functionality including widget registration
 * and AJAX filtering.
 */
class Filters_Module {

    /**
     * Module slug
     *
     * @var string
     */
    const SLUG = 'filters';

    /**
     * Query Manager instance
     *
     * @var Query_Manager
     */
    private $query_manager;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the module
     */
    private function init() {
        // Load Query Manager
        require_once PRESSIQ_PLUGIN_DIR . 'modules/filters/class-query-manager.php';
        $this->query_manager = new Query_Manager();

        // Add hooks
        add_action( 'wp_footer', array( $this, 'render_filter_state_container' ) );
    }

    /**
     * Get Query Manager instance
     *
     * @return Query_Manager
     */
    public function get_query_manager() {
        return $this->query_manager;
    }

    /**
     * Register filter widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        // Load base class first
        require_once PRESSIQ_PLUGIN_DIR . 'modules/filters/widgets/class-filter-base.php';

        // Load and register individual widgets
        $widgets = array(
            'select-filter'   => 'Select_Filter',
            'checkbox-filter' => 'Checkbox_Filter',
            'radio-filter'    => 'Radio_Filter',
            'range-filter'    => 'Range_Filter',
            'sorting-filter'  => 'Sorting_Filter',
            'search-filter'   => 'Search_Filter',
        );

        foreach ( $widgets as $file => $class ) {
            $widget_file = PRESSIQ_PLUGIN_DIR . 'modules/filters/widgets/class-' . $file . '.php';

            if ( file_exists( $widget_file ) ) {
                require_once $widget_file;

                $widget_class = 'PressIQ_Widgets\\Modules\\Filters\\Widgets\\' . $class;

                if ( class_exists( $widget_class ) ) {
                    $widgets_manager->register( new $widget_class() );
                }
            }
        }
    }

    /**
     * Render hidden container for filter state (used by JavaScript)
     */
    public function render_filter_state_container() {
        ?>
        <div id="pressiq-filter-state" style="display: none;" data-filters="{}"></div>
        <?php
    }

    /**
     * Get available taxonomies for filtering
     *
     * @return array
     */
    public static function get_available_taxonomies() {
        $taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
        $options    = array();

        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }

        return $options;
    }

    /**
     * Get available post types for filtering
     *
     * @return array
     */
    public static function get_available_post_types() {
        $post_types = get_post_types( array( 'public' => true ), 'objects' );
        $options    = array();

        foreach ( $post_types as $post_type ) {
            $options[ $post_type->name ] = $post_type->label;
        }

        // Add WooCommerce products if available
        if ( class_exists( 'WooCommerce' ) && ! isset( $options['product'] ) ) {
            $options['product'] = __( 'Products', 'pressiq-widgets' );
        }

        return $options;
    }

    /**
     * Get terms for a taxonomy
     *
     * @param string $taxonomy Taxonomy name.
     * @param array  $args     Additional arguments for get_terms().
     * @return array
     */
    public static function get_taxonomy_terms( $taxonomy, $args = array() ) {
        $defaults = array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
            'orderby'    => 'name',
            'order'      => 'ASC',
        );

        $args  = wp_parse_args( $args, $defaults );
        $terms = get_terms( $args );

        if ( is_wp_error( $terms ) ) {
            return array();
        }

        return $terms;
    }

    /**
     * Get meta keys for a post type
     *
     * @param string $post_type Post type name.
     * @return array
     */
    public static function get_meta_keys( $post_type = 'post' ) {
        global $wpdb;

        $meta_keys = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT pm.meta_key
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE p.post_type = %s
                AND pm.meta_key NOT LIKE '\_%'
                ORDER BY pm.meta_key",
                $post_type
            )
        );

        return $meta_keys;
    }

    /**
     * Get WooCommerce product attributes
     *
     * @return array
     */
    public static function get_product_attributes() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return array();
        }

        $attributes = wc_get_attribute_taxonomies();
        $options    = array();

        foreach ( $attributes as $attribute ) {
            $options[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
        }

        return $options;
    }
}
