<?php
/**
 * Search Filter Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Filters\Widgets;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Search Filter Widget Class
 *
 * Text search filter for filtering posts/products by keyword.
 */
class Search_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-search-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Search Filter', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'search', 'text', 'keyword', 'query' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Search-specific options
        $this->start_controls_section(
            'section_search_options',
            array(
                'label' => esc_html__( 'Search Options', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'placeholder',
            array(
                'label'   => esc_html__( 'Placeholder', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Search...', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'min_characters',
            array(
                'label'       => esc_html__( 'Minimum Characters', 'pressiq-widgets' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 2,
                'min'         => 1,
                'max'         => 10,
                'description' => esc_html__( 'Minimum characters before search triggers.', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'show_search_icon',
            array(
                'label'        => esc_html__( 'Show Search Icon', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'icon_position',
            array(
                'label'     => esc_html__( 'Icon Position', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => array(
                    'left'  => esc_html__( 'Left', 'pressiq-widgets' ),
                    'right' => esc_html__( 'Right', 'pressiq-widgets' ),
                ),
                'condition' => array(
                    'show_search_icon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_clear_button',
            array(
                'label'        => esc_html__( 'Show Clear Button', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'search_in',
            array(
                'label'       => esc_html__( 'Search In', 'pressiq-widgets' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'default'     => array( 'title', 'content' ),
                'options'     => array(
                    'title'   => esc_html__( 'Title', 'pressiq-widgets' ),
                    'content' => esc_html__( 'Content', 'pressiq-widgets' ),
                    'excerpt' => esc_html__( 'Excerpt', 'pressiq-widgets' ),
                ),
                'description' => esc_html__( 'Select which fields to search in.', 'pressiq-widgets' ),
            )
        );

        $this->end_controls_section();

        // Style controls
        $this->register_label_style_controls();

        // Input styles
        $this->start_controls_section(
            'section_input_style',
            array(
                'label' => esc_html__( 'Input', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .pressiq-filter__text-input',
            )
        );

        $this->start_controls_tabs( 'input_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'input_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'input_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__text-input' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'input_placeholder_color',
            array(
                'label'     => esc_html__( 'Placeholder Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__text-input::placeholder' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'input_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'input_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Focus state
        $this->start_controls_tab(
            'input_style_focus',
            array(
                'label' => esc_html__( 'Focus', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'input_focus_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper:focus-within' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}} .pressiq-filter__search-wrapper:focus-within',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'input_border_width',
            array(
                'label'      => esc_html__( 'Border Width', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 5,
                    ),
                ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ),
            )
        );

        $this->add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Icon styles
        $this->start_controls_section(
            'section_icon_style',
            array(
                'label'     => esc_html__( 'Icon', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_search_icon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-filter__search-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 12,
                        'max' => 30,
                    ),
                ),
                'default'    => array(
                    'size' => 16,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__search-icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'icon_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'default'    => array(
                    'size' => 8,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__search-wrapper--icon-left .pressiq-filter__search-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-filter__search-wrapper--icon-right .pressiq-filter__search-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings       = $this->get_settings_for_display();
        $data_attrs     = $this->get_filter_data_attrs( $settings );
        $show_icon      = $settings['show_search_icon'] === 'yes';
        $icon_position  = $settings['icon_position'];
        $show_clear     = $settings['show_clear_button'] === 'yes';
        $min_chars      = intval( $settings['min_characters'] ?? 2 );
        $search_in      = $settings['search_in'] ?? array( 'title', 'content' );

        $data_attrs['data-filter-id']   = '_search';
        $data_attrs['data-filter-type'] = 'search';
        $data_attrs['data-min-chars']   = $min_chars;
        $data_attrs['data-search-in']   = implode( ',', $search_in );

        $wrapper_class = 'pressiq-filter__search-wrapper';
        if ( $show_icon ) {
            $wrapper_class .= ' pressiq-filter__search-wrapper--icon-' . $icon_position;
        }
        ?>
        <div class="pressiq-filter pressiq-filter--search"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <div class="<?php echo esc_attr( $wrapper_class ); ?>">
                <?php if ( $show_icon && $icon_position === 'left' ) : ?>
                    <span class="pressiq-filter__search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                <?php endif; ?>

                <input type="text"
                       class="pressiq-filter__text-input"
                       name="_search"
                       placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>"
                       autocomplete="off">

                <?php if ( $show_clear ) : ?>
                    <button type="button" class="pressiq-filter__search-clear" style="display: none;" aria-label="<?php esc_attr_e( 'Clear search', 'pressiq-widgets' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                <?php endif; ?>

                <?php if ( $show_icon && $icon_position === 'right' ) : ?>
                    <span class="pressiq-filter__search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
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
        <#
        var wrapperClass = 'pressiq-filter__search-wrapper';
        if ( settings.show_search_icon === 'yes' ) {
            wrapperClass += ' pressiq-filter__search-wrapper--icon-' + settings.icon_position;
        }
        #>
        <div class="pressiq-filter pressiq-filter--search">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="pressiq-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <div class="{{{ wrapperClass }}}">
                <# if ( settings.show_search_icon === 'yes' && settings.icon_position === 'left' ) { #>
                    <span class="pressiq-filter__search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                <# } #>

                <input type="text" class="pressiq-filter__text-input" placeholder="{{{ settings.placeholder }}}">

                <# if ( settings.show_clear_button === 'yes' ) { #>
                    <button type="button" class="pressiq-filter__search-clear">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                <# } #>

                <# if ( settings.show_search_icon === 'yes' && settings.icon_position === 'right' ) { #>
                    <span class="pressiq-filter__search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                <# } #>
            </div>
        </div>
        <?php
    }
}
