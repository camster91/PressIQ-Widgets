<?php
/**
 * AJAX Handler for Filters
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AJAX Handler Class
 *
 * Handles all AJAX requests for filtering posts and products.
 */
class Ajax_Handler {

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
        require_once ACST_PLUGIN_DIR . 'modules/filters/class-query-manager.php';
        $this->query_manager = new Query_Manager();

        $this->register_ajax_actions();
    }

    /**
     * Register AJAX actions
     */
    private function register_ajax_actions() {
        // Public AJAX action for filtering
        add_action( 'wp_ajax_acst_filter', array( $this, 'handle_filter_request' ) );
        add_action( 'wp_ajax_nopriv_acst_filter', array( $this, 'handle_filter_request' ) );

        // Get filter options (for dynamic selects)
        add_action( 'wp_ajax_acst_get_filter_options', array( $this, 'get_filter_options' ) );
        add_action( 'wp_ajax_nopriv_acst_get_filter_options', array( $this, 'get_filter_options' ) );
    }

    /**
     * Handle filter request
     */
    public function handle_filter_request() {
        // Verify nonce
        if ( ! check_ajax_referer( 'acst_filter_nonce', 'nonce', false ) ) {
            wp_send_json_error( array(
                'message' => __( 'Security check failed.', 'ac-starter-toolkit' ),
            ) );
        }

        // Get filter data from request
        $filter_data = $this->sanitize_filter_data( $_POST );

        // Get query ID to target specific grid
        $query_id = sanitize_text_field( $_POST['query_id'] ?? '' );

        // Get the page/post ID where the filter is being used
        $page_id = absint( $_POST['page_id'] ?? 0 );

        // Get pagination info
        $paged = absint( $_POST['paged'] ?? 1 );

        // Build and execute the query
        $results = $this->query_manager->get_filtered_results( $filter_data, array(
            'query_id' => $query_id,
            'page_id'  => $page_id,
            'paged'    => $paged,
        ) );

        // Get the rendered HTML
        $html = $this->render_results( $results['posts'], $filter_data );

        // Send response
        wp_send_json_success( array(
            'html'        => $html,
            'found_posts' => $results['found_posts'],
            'max_pages'   => $results['max_pages'],
            'current_page'=> $paged,
            'query_id'    => $query_id,
        ) );
    }

    /**
     * Sanitize filter data from request
     *
     * @param array $data Raw request data.
     * @return array Sanitized filter data.
     */
    private function sanitize_filter_data( $data ) {
        $filters = array();

        // Get filters array from request
        $raw_filters = isset( $data['filters'] ) ? $data['filters'] : array();

        if ( is_string( $raw_filters ) ) {
            $raw_filters = json_decode( stripslashes( $raw_filters ), true );
        }

        if ( ! is_array( $raw_filters ) ) {
            return $filters;
        }

        foreach ( $raw_filters as $filter_id => $filter_value ) {
            $filter_id = sanitize_key( $filter_id );

            // Handle different filter types
            if ( is_array( $filter_value ) ) {
                // Checkbox filters (multiple values)
                $filters[ $filter_id ] = array_map( 'sanitize_text_field', $filter_value );
            } elseif ( strpos( $filter_value, '|' ) !== false ) {
                // Range filters (min|max format)
                $range = explode( '|', $filter_value );
                $filters[ $filter_id ] = array(
                    'min' => floatval( $range[0] ),
                    'max' => floatval( $range[1] ?? $range[0] ),
                );
            } else {
                // Single value filters
                $filters[ $filter_id ] = sanitize_text_field( $filter_value );
            }
        }

        // Handle sorting
        if ( isset( $data['orderby'] ) ) {
            $filters['_orderby'] = sanitize_text_field( $data['orderby'] );
        }
        if ( isset( $data['order'] ) ) {
            $filters['_order'] = in_array( strtoupper( $data['order'] ), array( 'ASC', 'DESC' ), true )
                ? strtoupper( $data['order'] )
                : 'DESC';
        }

        // Handle search
        if ( isset( $data['search'] ) && ! empty( $data['search'] ) ) {
            $filters['_search'] = sanitize_text_field( $data['search'] );
        }

        return $filters;
    }

    /**
     * Render filtered results as HTML
     *
     * @param array $posts  Array of post objects.
     * @param array $filter_data Filter data used.
     * @return string HTML output.
     */
    private function render_results( $posts, $filter_data ) {
        if ( empty( $posts ) ) {
            return $this->render_no_results();
        }

        ob_start();

        /**
         * Filter the results template path
         *
         * @param string $template_path Default template path.
         * @param array  $posts         Posts to render.
         * @param array  $filter_data   Filter data.
         */
        $template = apply_filters(
            'acst/filter_results_template',
            ACST_PLUGIN_DIR . 'templates/filter-results.php',
            $posts,
            $filter_data
        );

        if ( file_exists( $template ) ) {
            include $template;
        } else {
            // Fallback rendering
            foreach ( $posts as $post ) {
                $this->render_post_item( $post );
            }
        }

        return ob_get_clean();
    }

    /**
     * Render a single post item (fallback)
     *
     * @param \WP_Post $post Post object.
     */
    private function render_post_item( $post ) {
        ?>
        <article class="acst-filter-item" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
            <?php if ( has_post_thumbnail( $post ) ) : ?>
                <div class="acst-filter-item__image">
                    <a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
                        <?php echo get_the_post_thumbnail( $post, 'medium' ); ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="acst-filter-item__content">
                <h3 class="acst-filter-item__title">
                    <a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
                        <?php echo esc_html( get_the_title( $post ) ); ?>
                    </a>
                </h3>
                <div class="acst-filter-item__excerpt">
                    <?php echo wp_kses_post( wp_trim_words( $post->post_excerpt ?: $post->post_content, 20 ) ); ?>
                </div>
            </div>
        </article>
        <?php
    }

    /**
     * Render no results message
     *
     * @return string HTML output.
     */
    private function render_no_results() {
        $message = apply_filters(
            'acst/no_results_message',
            __( 'No results found matching your criteria.', 'ac-starter-toolkit' )
        );

        return sprintf(
            '<div class="acst-no-results"><p>%s</p></div>',
            esc_html( $message )
        );
    }

    /**
     * Get filter options via AJAX (for dependent filters)
     */
    public function get_filter_options() {
        // Verify nonce
        if ( ! check_ajax_referer( 'acst_filter_nonce', 'nonce', false ) ) {
            wp_send_json_error( array(
                'message' => __( 'Security check failed.', 'ac-starter-toolkit' ),
            ) );
        }

        $filter_type = sanitize_text_field( $_POST['filter_type'] ?? '' );
        $source      = sanitize_text_field( $_POST['source'] ?? '' );
        $post_type   = sanitize_text_field( $_POST['post_type'] ?? 'post' );

        $options = array();

        switch ( $filter_type ) {
            case 'taxonomy':
                $terms = get_terms( array(
                    'taxonomy'   => $source,
                    'hide_empty' => true,
                ) );

                if ( ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $options[] = array(
                            'value' => $term->slug,
                            'label' => $term->name,
                            'count' => $term->count,
                        );
                    }
                }
                break;

            case 'meta':
                global $wpdb;

                $meta_values = $wpdb->get_col(
                    $wpdb->prepare(
                        "SELECT DISTINCT pm.meta_value
                        FROM {$wpdb->postmeta} pm
                        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                        WHERE p.post_type = %s
                        AND pm.meta_key = %s
                        AND pm.meta_value != ''
                        ORDER BY pm.meta_value",
                        $post_type,
                        $source
                    )
                );

                foreach ( $meta_values as $value ) {
                    $options[] = array(
                        'value' => $value,
                        'label' => $value,
                    );
                }
                break;

            case 'product_attribute':
                if ( class_exists( 'WooCommerce' ) ) {
                    $terms = get_terms( array(
                        'taxonomy'   => $source,
                        'hide_empty' => true,
                    ) );

                    if ( ! is_wp_error( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $options[] = array(
                                'value' => $term->slug,
                                'label' => $term->name,
                                'count' => $term->count,
                            );
                        }
                    }
                }
                break;
        }

        wp_send_json_success( array(
            'options' => $options,
        ) );
    }
}
