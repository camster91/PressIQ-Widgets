<?php
/**
 * Query Manager for Filters
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Query Manager Class
 *
 * Builds WP_Query arguments based on filter parameters.
 */
class Query_Manager {

    /**
     * Filter configurations (stored from widget settings)
     *
     * @var array
     */
    private $filter_configs = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Hook into Elementor to capture query settings
        add_action( 'elementor/query/query_results', array( $this, 'capture_query_settings' ), 10, 2 );
    }

    /**
     * Capture Elementor query settings for later use
     *
     * @param \WP_Query $query  Query object.
     * @param object    $widget Widget instance.
     */
    public function capture_query_settings( $query, $widget ) {
        $query_id = $widget->get_settings( 'query_id' );
        if ( $query_id ) {
            $this->filter_configs[ $query_id ] = array(
                'post_type'      => $query->get( 'post_type' ),
                'posts_per_page' => $query->get( 'posts_per_page' ),
                'original_args'  => $query->query_vars,
            );
        }
    }

    /**
     * Register a filter configuration
     *
     * @param string $filter_id Filter ID.
     * @param array  $config    Filter configuration.
     */
    public function register_filter( $filter_id, $config ) {
        $this->filter_configs[ $filter_id ] = $config;
    }

    /**
     * Get filtered results
     *
     * @param array $filter_data Active filter values.
     * @param array $options     Additional options (query_id, page_id, paged).
     * @return array Results with posts, found_posts, and max_pages.
     */
    public function get_filtered_results( $filter_data, $options = array() ) {
        $query_args = $this->build_query_args( $filter_data, $options );

        $query = new \WP_Query( $query_args );

        return array(
            'posts'       => $query->posts,
            'found_posts' => $query->found_posts,
            'max_pages'   => $query->max_num_pages,
        );
    }

    /**
     * Build WP_Query arguments from filter data
     *
     * @param array $filter_data Active filter values.
     * @param array $options     Additional options.
     * @return array WP_Query arguments.
     */
    public function build_query_args( $filter_data, $options = array() ) {
        $defaults = array(
            'query_id' => '',
            'page_id'  => 0,
            'paged'    => 1,
        );

        $options = wp_parse_args( $options, $defaults );

        // Start with default args
        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 12,
            'paged'          => $options['paged'],
        );

        // Get stored config for this query_id if available
        if ( ! empty( $options['query_id'] ) && isset( $this->filter_configs[ $options['query_id'] ] ) ) {
            $config = $this->filter_configs[ $options['query_id'] ];
            $args   = array_merge( $args, $config['original_args'] ?? array() );
        }

        // Initialize query arrays
        $tax_query  = array();
        $meta_query = array();

        // Process each filter
        foreach ( $filter_data as $filter_key => $filter_value ) {
            // Skip internal keys
            if ( strpos( $filter_key, '_' ) === 0 ) {
                continue;
            }

            // Skip empty values
            if ( empty( $filter_value ) ) {
                continue;
            }

            // Determine filter type from key prefix
            if ( strpos( $filter_key, 'tax_' ) === 0 ) {
                // Taxonomy filter
                $taxonomy = str_replace( 'tax_', '', $filter_key );
                $tax_query[] = $this->build_tax_query( $taxonomy, $filter_value );
            } elseif ( strpos( $filter_key, 'meta_' ) === 0 ) {
                // Meta field filter
                $meta_key = str_replace( 'meta_', '', $filter_key );
                $meta_query[] = $this->build_meta_query( $meta_key, $filter_value );
            } elseif ( strpos( $filter_key, 'range_' ) === 0 ) {
                // Range filter
                $meta_key = str_replace( 'range_', '', $filter_key );
                $meta_query[] = $this->build_range_query( $meta_key, $filter_value );
            } elseif ( strpos( $filter_key, 'pa_' ) === 0 ) {
                // WooCommerce product attribute
                $tax_query[] = $this->build_tax_query( $filter_key, $filter_value );
            }
        }

        // Handle search
        if ( isset( $filter_data['_search'] ) && ! empty( $filter_data['_search'] ) ) {
            $args['s'] = $filter_data['_search'];
        }

        // Handle sorting
        if ( isset( $filter_data['_orderby'] ) ) {
            $orderby = $filter_data['_orderby'];

            // Handle WooCommerce specific sorting
            if ( $args['post_type'] === 'product' && class_exists( 'WooCommerce' ) ) {
                $args = $this->apply_wc_sorting( $args, $orderby, $filter_data['_order'] ?? 'DESC' );
            } else {
                $args['orderby'] = $this->sanitize_orderby( $orderby );
                $args['order']   = $filter_data['_order'] ?? 'DESC';

                // Handle meta value sorting
                if ( strpos( $orderby, 'meta_' ) === 0 ) {
                    $args['meta_key'] = str_replace( 'meta_', '', $orderby );
                    $args['orderby']  = 'meta_value';
                }
            }
        }

        // Add tax_query if we have taxonomy filters
        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = array_merge(
                array( 'relation' => 'AND' ),
                $tax_query
            );
        }

        // Add meta_query if we have meta filters
        if ( ! empty( $meta_query ) ) {
            $args['meta_query'] = array_merge(
                array( 'relation' => 'AND' ),
                $meta_query
            );
        }

        /**
         * Filter the query arguments
         *
         * @param array $args        WP_Query arguments.
         * @param array $filter_data Active filter values.
         * @param array $options     Additional options.
         */
        return apply_filters( 'acst/filter_query_args', $args, $filter_data, $options );
    }

    /**
     * Build taxonomy query array
     *
     * @param string       $taxonomy Taxonomy name.
     * @param string|array $value    Term slug(s).
     * @return array Tax query array.
     */
    private function build_tax_query( $taxonomy, $value ) {
        $terms = is_array( $value ) ? $value : array( $value );

        return array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => count( $terms ) > 1 ? 'IN' : '=',
        );
    }

    /**
     * Build meta query array
     *
     * @param string       $meta_key Meta key.
     * @param string|array $value    Meta value(s).
     * @return array Meta query array.
     */
    private function build_meta_query( $meta_key, $value ) {
        if ( is_array( $value ) && ! isset( $value['min'] ) ) {
            // Multiple values (checkbox filter)
            return array(
                'key'     => $meta_key,
                'value'   => $value,
                'compare' => 'IN',
            );
        }

        return array(
            'key'     => $meta_key,
            'value'   => $value,
            'compare' => '=',
        );
    }

    /**
     * Build range meta query
     *
     * @param string $meta_key Meta key.
     * @param array  $range    Range with 'min' and 'max'.
     * @return array Meta query array.
     */
    private function build_range_query( $meta_key, $range ) {
        // Handle price for WooCommerce
        if ( $meta_key === 'price' || $meta_key === '_price' ) {
            $meta_key = '_price';
        }

        return array(
            'key'     => $meta_key,
            'value'   => array( $range['min'], $range['max'] ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    /**
     * Apply WooCommerce-specific sorting
     *
     * @param array  $args    Query args.
     * @param string $orderby Orderby value.
     * @param string $order   Order direction.
     * @return array Modified query args.
     */
    private function apply_wc_sorting( $args, $orderby, $order ) {
        switch ( $orderby ) {
            case 'price':
            case 'price-asc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'ASC';
                break;

            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;

            case 'date':
                $args['orderby'] = 'date';
                $args['order']   = $order;
                break;

            case 'title':
                $args['orderby'] = 'title';
                $args['order']   = $order;
                break;

            default:
                $args['orderby'] = 'menu_order title';
                $args['order']   = 'ASC';
        }

        return $args;
    }

    /**
     * Sanitize orderby parameter
     *
     * @param string $orderby Raw orderby value.
     * @return string Sanitized orderby.
     */
    private function sanitize_orderby( $orderby ) {
        $allowed = array(
            'date',
            'title',
            'name',
            'modified',
            'author',
            'rand',
            'comment_count',
            'menu_order',
            'meta_value',
            'meta_value_num',
            'ID',
        );

        return in_array( $orderby, $allowed, true ) ? $orderby : 'date';
    }

    /**
     * Get min/max values for a numeric meta field
     *
     * @param string $meta_key  Meta key.
     * @param string $post_type Post type.
     * @return array Array with 'min' and 'max'.
     */
    public function get_meta_range( $meta_key, $post_type = 'post' ) {
        global $wpdb;

        // Handle WooCommerce price
        if ( $meta_key === 'price' && $post_type === 'product' ) {
            $meta_key = '_price';
        }

        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT MIN(CAST(pm.meta_value AS DECIMAL(10,2))) as min_val,
                        MAX(CAST(pm.meta_value AS DECIMAL(10,2))) as max_val
                FROM {$wpdb->postmeta} pm
                INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                WHERE p.post_type = %s
                AND p.post_status = 'publish'
                AND pm.meta_key = %s
                AND pm.meta_value != ''
                AND pm.meta_value REGEXP '^[0-9]+\.?[0-9]*$'",
                $post_type,
                $meta_key
            )
        );

        return array(
            'min' => floatval( $result->min_val ?? 0 ),
            'max' => floatval( $result->max_val ?? 100 ),
        );
    }
}
