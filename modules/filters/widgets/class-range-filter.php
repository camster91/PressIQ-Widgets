<?php
/**
 * Range Filter Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Filters\Widgets;

use Elementor\Controls_Manager;
use PressIQ_Widgets\Modules\Filters\Query_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Range Filter Widget Class
 *
 * Number range filter with slider and/or min-max inputs.
 */
class Range_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-range-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Range Filter', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-slider-push';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'range', 'slider', 'price', 'min', 'max' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Range-specific data source
        $this->start_controls_section(
            'section_range_source',
            array(
                'label' => esc_html__( 'Data Source', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'source_type',
            array(
                'label'   => esc_html__( 'Source', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'price',
                'options' => array(
                    'price'  => esc_html__( 'WooCommerce Price', 'pressiq-widgets' ),
                    'meta'   => esc_html__( 'Meta Field', 'pressiq-widgets' ),
                    'manual' => esc_html__( 'Manual Range', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'post_type',
            array(
                'label'     => esc_html__( 'Post Type', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'product',
                'options'   => $this->get_post_types_options(),
                'condition' => array(
                    'source_type' => 'meta',
                ),
            )
        );

        $this->add_control(
            'meta_key',
            array(
                'label'       => esc_html__( 'Meta Key', 'pressiq-widgets' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__( 'Enter the numeric meta field key.', 'pressiq-widgets' ),
                'condition'   => array(
                    'source_type' => 'meta',
                ),
            )
        );

        $this->add_control(
            'manual_min',
            array(
                'label'     => esc_html__( 'Minimum Value', 'pressiq-widgets' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0,
                'condition' => array(
                    'source_type' => 'manual',
                ),
            )
        );

        $this->add_control(
            'manual_max',
            array(
                'label'     => esc_html__( 'Maximum Value', 'pressiq-widgets' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1000,
                'condition' => array(
                    'source_type' => 'manual',
                ),
            )
        );

        $this->add_control(
            'step',
            array(
                'label'   => esc_html__( 'Step', 'pressiq-widgets' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 0.01,
            )
        );

        $this->end_controls_section();

        // Range options
        $this->start_controls_section(
            'section_range_options',
            array(
                'label' => esc_html__( 'Range Options', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'display_type',
            array(
                'label'   => esc_html__( 'Display Type', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'slider_inputs',
                'options' => array(
                    'slider'        => esc_html__( 'Slider Only', 'pressiq-widgets' ),
                    'inputs'        => esc_html__( 'Inputs Only', 'pressiq-widgets' ),
                    'slider_inputs' => esc_html__( 'Slider + Inputs', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'prefix',
            array(
                'label'   => esc_html__( 'Prefix', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__( 'E.g., $ for currency', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'suffix',
            array(
                'label'   => esc_html__( 'Suffix', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
                'description' => esc_html__( 'E.g., km, sq ft', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'thousands_separator',
            array(
                'label'        => esc_html__( 'Use Thousands Separator', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'min_label',
            array(
                'label'   => esc_html__( 'Min Label', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Min', 'pressiq-widgets' ),
                'condition' => array(
                    'display_type!' => 'slider',
                ),
            )
        );

        $this->add_control(
            'max_label',
            array(
                'label'   => esc_html__( 'Max Label', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Max', 'pressiq-widgets' ),
                'condition' => array(
                    'display_type!' => 'slider',
                ),
            )
        );

        $this->end_controls_section();

        // Style controls
        $this->register_label_style_controls();

        // Slider styles
        $this->start_controls_section(
            'section_slider_style',
            array(
                'label'     => esc_html__( 'Slider', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_type!' => 'inputs',
                ),
            )
        );

        $this->add_control(
            'slider_track_color',
            array(
                'label'     => esc_html__( 'Track Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e0e0e0',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-slider' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'slider_active_color',
            array(
                'label'     => esc_html__( 'Active Track Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0073aa',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-track' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'slider_height',
            array(
                'label'      => esc_html__( 'Track Height', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 2,
                        'max' => 20,
                    ),
                ),
                'default'    => array(
                    'size' => 6,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__range-slider' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-filter__range-track' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'handle_color',
            array(
                'label'     => esc_html__( 'Handle Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-input::-webkit-slider-thumb' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__range-input::-moz-range-thumb' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'handle_border_color',
            array(
                'label'     => esc_html__( 'Handle Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#0073aa',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-input::-webkit-slider-thumb' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__range-input::-moz-range-thumb' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'handle_size',
            array(
                'label'      => esc_html__( 'Handle Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 12,
                        'max' => 30,
                    ),
                ),
                'default'    => array(
                    'size' => 18,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__range-input::-webkit-slider-thumb' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-filter__range-input::-moz-range-thumb' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Input styles
        $this->start_controls_section(
            'section_inputs_style',
            array(
                'label'     => esc_html__( 'Input Fields', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'display_type!' => 'slider',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'inputs_typography',
                'selector' => '{{WRAPPER}} .pressiq-filter__range-field input',
            )
        );

        $this->add_control(
            'inputs_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-field input' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'inputs_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-field input' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'inputs_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__range-field input' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get range values based on source
     *
     * @param array $settings Widget settings.
     * @return array Min and max values.
     */
    private function get_range_values( $settings ) {
        $source_type = $settings['source_type'] ?? 'price';

        switch ( $source_type ) {
            case 'price':
                if ( class_exists( 'WooCommerce' ) ) {
                    global $wpdb;

                    $result = $wpdb->get_row(
                        "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_val,
                                MAX(CAST(meta_value AS DECIMAL(10,2))) as max_val
                        FROM {$wpdb->postmeta}
                        WHERE meta_key = '_price'
                        AND meta_value != ''
                        AND meta_value REGEXP '^[0-9]+\.?[0-9]*$'"
                    );

                    return array(
                        'min' => floatval( $result->min_val ?? 0 ),
                        'max' => floatval( $result->max_val ?? 1000 ),
                    );
                }
                return array( 'min' => 0, 'max' => 1000 );

            case 'meta':
                $query_manager = new Query_Manager();
                $post_type     = $settings['post_type'] ?? 'post';
                $meta_key      = $settings['meta_key'] ?? '';

                if ( ! empty( $meta_key ) ) {
                    return $query_manager->get_meta_range( $meta_key, $post_type );
                }
                return array( 'min' => 0, 'max' => 100 );

            case 'manual':
                return array(
                    'min' => floatval( $settings['manual_min'] ?? 0 ),
                    'max' => floatval( $settings['manual_max'] ?? 1000 ),
                );

            default:
                return array( 'min' => 0, 'max' => 100 );
        }
    }

    /**
     * Get filter ID for range filter
     *
     * @param array $settings Widget settings.
     * @return string Filter ID.
     */
    protected function get_filter_id( $settings ) {
        $source_type = $settings['source_type'] ?? 'price';

        switch ( $source_type ) {
            case 'price':
                return 'range_price';

            case 'meta':
                return 'range_' . ( $settings['meta_key'] ?? '' );

            default:
                return 'range_' . $this->get_id();
        }
    }

    /**
     * Format a number for display
     *
     * @param float $number  Number to format.
     * @param array $settings Widget settings.
     * @return string Formatted number.
     */
    private function format_number( $number, $settings ) {
        $formatted = $settings['thousands_separator'] === 'yes'
            ? number_format( $number, 0, '.', ',' )
            : $number;

        $prefix = $settings['prefix'] ?? '';
        $suffix = $settings['suffix'] ?? '';

        return $prefix . $formatted . $suffix;
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings     = $this->get_settings_for_display();
        $range        = $this->get_range_values( $settings );
        $data_attrs   = $this->get_filter_data_attrs( $settings );
        $filter_id    = $this->get_filter_id( $settings );
        $display_type = $settings['display_type'];
        $step         = floatval( $settings['step'] ?? 1 );

        $data_attrs['data-filter-id'] = $filter_id;
        $data_attrs['data-min']       = $range['min'];
        $data_attrs['data-max']       = $range['max'];
        $data_attrs['data-step']      = $step;
        ?>
        <div class="pressiq-filter pressiq-filter--range"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <div class="pressiq-filter__range-wrapper">
                <?php if ( $display_type !== 'inputs' ) : ?>
                    <!-- Slider -->
                    <div class="pressiq-filter__range-slider">
                        <div class="pressiq-filter__range-track" style="left: 0%; width: 100%;"></div>
                        <input type="range"
                               class="pressiq-filter__range-input pressiq-filter__range-min"
                               min="<?php echo esc_attr( $range['min'] ); ?>"
                               max="<?php echo esc_attr( $range['max'] ); ?>"
                               step="<?php echo esc_attr( $step ); ?>"
                               value="<?php echo esc_attr( $range['min'] ); ?>">
                        <input type="range"
                               class="pressiq-filter__range-input pressiq-filter__range-max"
                               min="<?php echo esc_attr( $range['min'] ); ?>"
                               max="<?php echo esc_attr( $range['max'] ); ?>"
                               step="<?php echo esc_attr( $step ); ?>"
                               value="<?php echo esc_attr( $range['max'] ); ?>">
                    </div>

                    <!-- Range value display -->
                    <div class="pressiq-filter__range-values">
                        <span class="pressiq-filter__range-min-value">
                            <?php echo esc_html( $this->format_number( $range['min'], $settings ) ); ?>
                        </span>
                        <span class="pressiq-filter__range-max-value">
                            <?php echo esc_html( $this->format_number( $range['max'], $settings ) ); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ( $display_type !== 'slider' ) : ?>
                    <!-- Min/Max inputs -->
                    <div class="pressiq-filter__range-inputs">
                        <div class="pressiq-filter__range-field">
                            <label><?php echo esc_html( $settings['min_label'] ); ?></label>
                            <input type="number"
                                   class="pressiq-filter__range-min"
                                   min="<?php echo esc_attr( $range['min'] ); ?>"
                                   max="<?php echo esc_attr( $range['max'] ); ?>"
                                   step="<?php echo esc_attr( $step ); ?>"
                                   value="<?php echo esc_attr( $range['min'] ); ?>"
                                   placeholder="<?php echo esc_attr( $range['min'] ); ?>">
                        </div>
                        <div class="pressiq-filter__range-field">
                            <label><?php echo esc_html( $settings['max_label'] ); ?></label>
                            <input type="number"
                                   class="pressiq-filter__range-max"
                                   min="<?php echo esc_attr( $range['min'] ); ?>"
                                   max="<?php echo esc_attr( $range['max'] ); ?>"
                                   step="<?php echo esc_attr( $step ); ?>"
                                   value="<?php echo esc_attr( $range['max'] ); ?>"
                                   placeholder="<?php echo esc_attr( $range['max'] ); ?>">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render plain content (for Elementor)
     */
    protected function content_template() {
        ?>
        <div class="pressiq-filter pressiq-filter--range">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="pressiq-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <div class="pressiq-filter__range-wrapper">
                <# if ( settings.display_type !== 'inputs' ) { #>
                    <div class="pressiq-filter__range-slider">
                        <div class="pressiq-filter__range-track" style="left: 0%; width: 100%;"></div>
                        <input type="range" class="pressiq-filter__range-input" min="0" max="1000" value="0">
                        <input type="range" class="pressiq-filter__range-input" min="0" max="1000" value="1000">
                    </div>
                    <div class="pressiq-filter__range-values">
                        <span>{{{ settings.prefix }}}0{{{ settings.suffix }}}</span>
                        <span>{{{ settings.prefix }}}1000{{{ settings.suffix }}}</span>
                    </div>
                <# } #>

                <# if ( settings.display_type !== 'slider' ) { #>
                    <div class="pressiq-filter__range-inputs">
                        <div class="pressiq-filter__range-field">
                            <label>{{{ settings.min_label }}}</label>
                            <input type="number" value="0">
                        </div>
                        <div class="pressiq-filter__range-field">
                            <label>{{{ settings.max_label }}}</label>
                            <input type="number" value="1000">
                        </div>
                    </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
