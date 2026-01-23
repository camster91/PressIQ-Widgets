<?php
/**
 * Filter Base Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Abstract Filter Base Widget
 *
 * Provides common functionality for all filter widgets.
 */
abstract class Filter_Base extends Widget_Base {

    /**
     * Get widget categories
     *
     * @return array
     */
    public function get_categories() {
        return array( 'ac-filters' );
    }

    /**
     * Get style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return array( 'acst-filters' );
    }

    /**
     * Get script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return array( 'acst-filters' );
    }

    /**
     * Register common filter controls (Content Tab)
     */
    protected function register_filter_content_controls() {
        $this->start_controls_section(
            'section_filter_settings',
            array(
                'label' => esc_html__( 'Filter Settings', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        // Query ID - to target specific post grids
        $this->add_control(
            'query_id',
            array(
                'label'       => esc_html__( 'Query ID', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__( 'Enter the Query ID of the Posts/Loop Grid widget you want to filter. Leave empty to filter all grids on the page.', 'ac-starter-toolkit' ),
            )
        );

        // Filter label
        $this->add_control(
            'filter_label',
            array(
                'label'   => esc_html__( 'Label', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            )
        );

        // Show label
        $this->add_control(
            'show_label',
            array(
                'label'        => esc_html__( 'Show Label', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register data source controls
     */
    protected function register_data_source_controls() {
        $this->start_controls_section(
            'section_data_source',
            array(
                'label' => esc_html__( 'Data Source', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        // Source type
        $this->add_control(
            'source_type',
            array(
                'label'   => esc_html__( 'Source', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'taxonomy',
                'options' => array(
                    'taxonomy' => esc_html__( 'Taxonomy', 'ac-starter-toolkit' ),
                    'meta'     => esc_html__( 'Meta Field', 'ac-starter-toolkit' ),
                    'manual'   => esc_html__( 'Manual Options', 'ac-starter-toolkit' ),
                ),
            )
        );

        // Taxonomy selection
        $this->add_control(
            'taxonomy',
            array(
                'label'     => esc_html__( 'Taxonomy', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'category',
                'options'   => $this->get_taxonomies_options(),
                'condition' => array(
                    'source_type' => 'taxonomy',
                ),
            )
        );

        // Post type for meta fields
        $this->add_control(
            'post_type',
            array(
                'label'     => esc_html__( 'Post Type', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'post',
                'options'   => $this->get_post_types_options(),
                'condition' => array(
                    'source_type' => 'meta',
                ),
            )
        );

        // Meta key
        $this->add_control(
            'meta_key',
            array(
                'label'       => esc_html__( 'Meta Key', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__( 'Enter the meta field key to filter by.', 'ac-starter-toolkit' ),
                'condition'   => array(
                    'source_type' => 'meta',
                ),
            )
        );

        // Manual options
        $this->add_control(
            'manual_options',
            array(
                'label'       => esc_html__( 'Options', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => "value1|Label 1\nvalue2|Label 2\nvalue3|Label 3",
                'description' => esc_html__( 'Enter options in format: value|label (one per line)', 'ac-starter-toolkit' ),
                'condition'   => array(
                    'source_type' => 'manual',
                ),
            )
        );

        // Show count
        $this->add_control(
            'show_count',
            array(
                'label'        => esc_html__( 'Show Count', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'condition'    => array(
                    'source_type!' => 'manual',
                ),
            )
        );

        // Hide empty
        $this->add_control(
            'hide_empty',
            array(
                'label'        => esc_html__( 'Hide Empty', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'condition'    => array(
                    'source_type' => 'taxonomy',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register label style controls
     */
    protected function register_label_style_controls() {
        $this->start_controls_section(
            'section_label_style',
            array(
                'label'     => esc_html__( 'Label', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_label' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .acst-filter__label',
            )
        );

        $this->add_control(
            'label_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'label_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register filter input style controls
     */
    protected function register_input_style_controls() {
        $this->start_controls_section(
            'section_input_style',
            array(
                'label' => esc_html__( 'Input', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select',
            )
        );

        $this->start_controls_tabs( 'input_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'input_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'input_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'input_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'input_border',
                'selector' => '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select',
            )
        );

        $this->end_controls_tab();

        // Focus state
        $this->start_controls_tab(
            'input_style_focus',
            array(
                'label' => esc_html__( 'Focus', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'input_focus_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__input:focus, {{WRAPPER}} .acst-filter__select:focus' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}} .acst-filter__input:focus, {{WRAPPER}} .acst-filter__select:focus',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__input, {{WRAPPER}} .acst-filter__select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get available taxonomies as options array
     *
     * @return array
     */
    protected function get_taxonomies_options() {
        $taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
        $options    = array();

        foreach ( $taxonomies as $taxonomy ) {
            $options[ $taxonomy->name ] = $taxonomy->label;
        }

        // Add WooCommerce product attributes if available
        if ( class_exists( 'WooCommerce' ) ) {
            $attributes = wc_get_attribute_taxonomies();
            foreach ( $attributes as $attribute ) {
                $options[ 'pa_' . $attribute->attribute_name ] = sprintf(
                    '%s (%s)',
                    $attribute->attribute_label,
                    esc_html__( 'Product Attribute', 'ac-starter-toolkit' )
                );
            }
        }

        return $options;
    }

    /**
     * Get available post types as options array
     *
     * @return array
     */
    protected function get_post_types_options() {
        $post_types = get_post_types( array( 'public' => true ), 'objects' );
        $options    = array();

        foreach ( $post_types as $post_type ) {
            $options[ $post_type->name ] = $post_type->label;
        }

        return $options;
    }

    /**
     * Get filter options based on source type
     *
     * @param array $settings Widget settings.
     * @return array Array of options with 'value' and 'label'.
     */
    protected function get_filter_options( $settings ) {
        $source_type = $settings['source_type'] ?? 'taxonomy';
        $options     = array();

        switch ( $source_type ) {
            case 'taxonomy':
                $taxonomy   = $settings['taxonomy'] ?? 'category';
                $hide_empty = $settings['hide_empty'] === 'yes';
                $show_count = $settings['show_count'] === 'yes';

                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => $hide_empty,
                ) );

                if ( ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $label = $term->name;
                        if ( $show_count ) {
                            $label .= sprintf( ' (%d)', $term->count );
                        }
                        $options[] = array(
                            'value' => $term->slug,
                            'label' => $label,
                            'count' => $term->count,
                        );
                    }
                }
                break;

            case 'meta':
                $post_type = $settings['post_type'] ?? 'post';
                $meta_key  = $settings['meta_key'] ?? '';

                if ( ! empty( $meta_key ) ) {
                    global $wpdb;

                    $meta_values = $wpdb->get_col(
                        $wpdb->prepare(
                            "SELECT DISTINCT pm.meta_value
                            FROM {$wpdb->postmeta} pm
                            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
                            WHERE p.post_type = %s
                            AND p.post_status = 'publish'
                            AND pm.meta_key = %s
                            AND pm.meta_value != ''
                            ORDER BY pm.meta_value",
                            $post_type,
                            $meta_key
                        )
                    );

                    foreach ( $meta_values as $value ) {
                        $options[] = array(
                            'value' => $value,
                            'label' => $value,
                        );
                    }
                }
                break;

            case 'manual':
                $manual_options = $settings['manual_options'] ?? '';
                $lines          = explode( "\n", $manual_options );

                foreach ( $lines as $line ) {
                    $line = trim( $line );
                    if ( empty( $line ) ) {
                        continue;
                    }

                    if ( strpos( $line, '|' ) !== false ) {
                        list( $value, $label ) = explode( '|', $line, 2 );
                    } else {
                        $value = $label = $line;
                    }

                    $options[] = array(
                        'value' => trim( $value ),
                        'label' => trim( $label ),
                    );
                }
                break;
        }

        return $options;
    }

    /**
     * Generate filter ID from settings
     *
     * @param array $settings Widget settings.
     * @return string Filter ID.
     */
    protected function get_filter_id( $settings ) {
        $source_type = $settings['source_type'] ?? 'taxonomy';

        switch ( $source_type ) {
            case 'taxonomy':
                return 'tax_' . ( $settings['taxonomy'] ?? 'category' );

            case 'meta':
                return 'meta_' . ( $settings['meta_key'] ?? '' );

            case 'manual':
                return 'manual_' . $this->get_id();

            default:
                return 'filter_' . $this->get_id();
        }
    }

    /**
     * Render filter label
     *
     * @param array $settings Widget settings.
     */
    protected function render_label( $settings ) {
        if ( $settings['show_label'] !== 'yes' || empty( $settings['filter_label'] ) ) {
            return;
        }

        printf(
            '<label class="acst-filter__label">%s</label>',
            esc_html( $settings['filter_label'] )
        );
    }

    /**
     * Get common data attributes for filter elements
     *
     * @param array $settings Widget settings.
     * @return array Data attributes.
     */
    protected function get_filter_data_attrs( $settings ) {
        return array(
            'data-filter-id'   => $this->get_filter_id( $settings ),
            'data-query-id'    => $settings['query_id'] ?? '',
            'data-source-type' => $settings['source_type'] ?? 'taxonomy',
            'data-filter-type' => $this->get_name(),
        );
    }

    /**
     * Render data attributes as HTML string
     *
     * @param array $attrs Data attributes array.
     * @return string HTML attributes string.
     */
    protected function render_data_attrs( $attrs ) {
        $output = '';
        foreach ( $attrs as $key => $value ) {
            $output .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }
        return $output;
    }
}
