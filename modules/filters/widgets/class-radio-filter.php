<?php
/**
 * Radio Filter Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters\Widgets;

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
        return 'acst-radio-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Radio Filter', 'ac-starter-toolkit' );
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
                'label' => esc_html__( 'Radio Options', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => array(
                    'vertical'   => esc_html__( 'Vertical', 'ac-starter-toolkit' ),
                    'horizontal' => esc_html__( 'Horizontal', 'ac-starter-toolkit' ),
                    'button'     => esc_html__( 'Button Style', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'show_all_option',
            array(
                'label'        => esc_html__( 'Show "All" Option', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'all_option_label',
            array(
                'label'     => esc_html__( '"All" Option Label', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All', 'ac-starter-toolkit' ),
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
                'label' => esc_html__( 'Radio Buttons', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'item_spacing',
            array(
                'label'      => esc_html__( 'Item Spacing', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-filter__option' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .acst-filter__options--horizontal .acst-filter__option' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
                ),
            )
        );

        $this->add_control(
            'radio_size',
            array(
                'label'      => esc_html__( 'Radio Size', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-filter__radio' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                'label' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'radio_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__radio' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__radio' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Selected state
        $this->start_controls_tab(
            'radio_style_selected',
            array(
                'label' => esc_html__( 'Selected', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'radio_selected_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__radio:checked' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option.is-active' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_selected_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__radio:checked::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option.is-active' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'radio_selected_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option.is-active' => 'color: {{VALUE}};',
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
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'layout' => 'button',
                ),
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__options--button .acst-filter__option' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => esc_html__( 'Option Labels', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'option_typography',
                'selector' => '{{WRAPPER}} .acst-filter__option-label',
            )
        );

        $this->add_control(
            'option_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__option-label' => 'color: {{VALUE}};',
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

        $layout_class = 'acst-filter__options--' . $layout;
        ?>
        <div class="acst-filter acst-filter--radio"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <div class="acst-filter__options <?php echo esc_attr( $layout_class ); ?>">
                <?php if ( $settings['show_all_option'] === 'yes' ) : ?>
                    <label class="acst-filter__option<?php echo $layout === 'button' ? ' is-active' : ''; ?>">
                        <?php if ( $layout !== 'button' ) : ?>
                            <input type="radio"
                                   class="acst-filter__radio"
                                   name="<?php echo esc_attr( $filter_id ); ?>"
                                   value=""
                                   checked>
                        <?php endif; ?>
                        <span class="acst-filter__option-label">
                            <?php echo esc_html( $settings['all_option_label'] ); ?>
                        </span>
                    </label>
                <?php endif; ?>

                <?php foreach ( $options as $option ) : ?>
                    <label class="acst-filter__option" data-value="<?php echo esc_attr( $option['value'] ); ?>">
                        <?php if ( $layout !== 'button' ) : ?>
                            <input type="radio"
                                   class="acst-filter__radio"
                                   name="<?php echo esc_attr( $filter_id ); ?>"
                                   value="<?php echo esc_attr( $option['value'] ); ?>">
                        <?php endif; ?>
                        <span class="acst-filter__option-label">
                            <?php echo esc_html( $option['label'] ); ?>
                        </span>
                        <?php if ( isset( $option['count'] ) && $settings['show_count'] === 'yes' ) : ?>
                            <span class="acst-filter__option-count">(<?php echo esc_html( $option['count'] ); ?>)</span>
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
        var layoutClass = 'acst-filter__options--' + settings.layout;
        #>
        <div class="acst-filter acst-filter--radio">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="acst-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <div class="acst-filter__options {{{ layoutClass }}}">
                <# if ( settings.show_all_option === 'yes' ) { #>
                    <label class="acst-filter__option<# if ( settings.layout === 'button' ) { #> is-active<# } #>">
                        <# if ( settings.layout !== 'button' ) { #>
                            <input type="radio" class="acst-filter__radio" checked>
                        <# } #>
                        <span class="acst-filter__option-label">{{{ settings.all_option_label }}}</span>
                    </label>
                <# } #>
                <label class="acst-filter__option">
                    <# if ( settings.layout !== 'button' ) { #>
                        <input type="radio" class="acst-filter__radio">
                    <# } #>
                    <span class="acst-filter__option-label"><?php esc_html_e( 'Option 1', 'ac-starter-toolkit' ); ?></span>
                </label>
                <label class="acst-filter__option">
                    <# if ( settings.layout !== 'button' ) { #>
                        <input type="radio" class="acst-filter__radio">
                    <# } #>
                    <span class="acst-filter__option-label"><?php esc_html_e( 'Option 2', 'ac-starter-toolkit' ); ?></span>
                </label>
            </div>
        </div>
        <?php
    }
}
