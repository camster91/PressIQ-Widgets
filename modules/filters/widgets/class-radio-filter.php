<?php
/**
 * Radio Filter Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Filters\Widgets;

use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Radio Filter Widget Class
 *
 * Single-select radio filter for taxonomy or meta field values.
 */
class Radio_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-radio-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Radio Filter', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-radio';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'radio', 'single', 'select', 'taxonomy' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Data Source
        $this->register_data_source_controls();

        // Radio-specific options
        $this->start_controls_section(
            'section_radio_options',
            array(
                'label' => esc_html__( 'Radio Options', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => array(
                    'vertical'   => esc_html__( 'Vertical', 'pressiq-widgets' ),
                    'horizontal' => esc_html__( 'Horizontal', 'pressiq-widgets' ),
                    'button'     => esc_html__( 'Button Style', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'show_all_option',
            array(
                'label'        => esc_html__( 'Show "All" Option', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'all_option_label',
            array(
                'label'     => esc_html__( '"All" Option Label', 'pressiq-widgets' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All', 'pressiq-widgets' ),
                'condition' => array(
                    'show_all_option' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Style controls
        $this->register_label_style_controls();

        // Radio styles
        $this->start_controls_section(
            'section_radio_style',
            array(
                'label' => esc_html__( 'Radio Buttons', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'item_spacing',
            array(
                'label'      => esc_html__( 'Item Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default'    => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__option' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-filter__options--horizontal .pressiq-filter__option' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
                ),
            )
        );

        $this->add_control(
            'radio_size',
            array(
                'label'      => esc_html__( 'Radio Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 14,
                        'max' => 30,
                    ),
                ),
                'default'    => array(
                    'size' => 18,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__radio' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'layout!' => 'button',
                ),
            )
        );

        $this->start_controls_tabs( 'radio_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'radio_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'radio_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__radio' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__radio' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Selected state
        $this->start_controls_tab(
            'radio_style_selected',
            array(
                'label' => esc_html__( 'Selected', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'radio_selected_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__radio:checked' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option.is-active' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_selected_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__radio:checked::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option.is-active' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_selected_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option.is-active' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'layout' => 'button',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Button style specific
        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'layout' => 'button',
                ),
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__options--button .pressiq-filter__option' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'layout' => 'button',
                ),
            )
        );

        $this->end_controls_section();

        // Option label styles
        $this->start_controls_section(
            'section_option_label_style',
            array(
                'label' => esc_html__( 'Option Labels', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'option_typography',
                'selector' => '{{WRAPPER}} .pressiq-filter__option-label',
            )
        );

        $this->add_control(
            'option_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__option-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings   = $this->get_settings_for_display();
        $options    = $this->get_filter_options( $settings );
        $data_attrs = $this->get_filter_data_attrs( $settings );
        $filter_id  = $this->get_filter_id( $settings );
        $layout     = $settings['layout'];

        $layout_class = 'pressiq-filter__options--' . $layout;
        ?>
        <div class="pressiq-filter pressiq-filter--radio"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <div class="pressiq-filter__options <?php echo esc_attr( $layout_class ); ?>">
                <?php if ( $settings['show_all_option'] === 'yes' ) : ?>
                    <label class="pressiq-filter__option<?php echo $layout === 'button' ? ' is-active' : ''; ?>">
                        <?php if ( $layout !== 'button' ) : ?>
                            <input type="radio"
                                   class="pressiq-filter__radio"
                                   name="<?php echo esc_attr( $filter_id ); ?>"
                                   value=""
                                   checked>
                        <?php endif; ?>
                        <span class="pressiq-filter__option-label">
                            <?php echo esc_html( $settings['all_option_label'] ); ?>
                        </span>
                    </label>
                <?php endif; ?>

                <?php foreach ( $options as $option ) : ?>
                    <label class="pressiq-filter__option" data-value="<?php echo esc_attr( $option['value'] ); ?>">
                        <?php if ( $layout !== 'button' ) : ?>
                            <input type="radio"
                                   class="pressiq-filter__radio"
                                   name="<?php echo esc_attr( $filter_id ); ?>"
                                   value="<?php echo esc_attr( $option['value'] ); ?>">
                        <?php endif; ?>
                        <span class="pressiq-filter__option-label">
                            <?php echo esc_html( $option['label'] ); ?>
                        </span>
                        <?php if ( isset( $option['count'] ) && $settings['show_count'] === 'yes' ) : ?>
                            <span class="pressiq-filter__option-count">(<?php echo esc_html( $option['count'] ); ?>)</span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render plain content (for Elementor)
     */
    protected function content_template() {
        ?>
        <#
        var layoutClass = 'pressiq-filter__options--' + settings.layout;
        #>
        <div class="pressiq-filter pressiq-filter--radio">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="pressiq-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <div class="pressiq-filter__options {{{ layoutClass }}}">
                <# if ( settings.show_all_option === 'yes' ) { #>
                    <label class="pressiq-filter__option<# if ( settings.layout === 'button' ) { #> is-active<# } #>">
                        <# if ( settings.layout !== 'button' ) { #>
                            <input type="radio" class="pressiq-filter__radio" checked>
                        <# } #>
                        <span class="pressiq-filter__option-label">{{{ settings.all_option_label }}}</span>
                    </label>
                <# } #>
                <label class="pressiq-filter__option">
                    <# if ( settings.layout !== 'button' ) { #>
                        <input type="radio" class="pressiq-filter__radio">
                    <# } #>
                    <span class="pressiq-filter__option-label"><?php esc_html_e( 'Option 1', 'pressiq-widgets' ); ?></span>
                </label>
                <label class="pressiq-filter__option">
                    <# if ( settings.layout !== 'button' ) { #>
                        <input type="radio" class="pressiq-filter__radio">
                    <# } #>
                    <span class="pressiq-filter__option-label"><?php esc_html_e( 'Option 2', 'pressiq-widgets' ); ?></span>
                </label>
            </div>
        </div>
        <?php
    }
}
