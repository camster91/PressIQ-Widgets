<?php
/**
 * Checkbox Filter Widget
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
 * Checkbox Filter Widget Class
 *
 * Multi-select checkbox filter for taxonomy or meta field values.
 */
class Checkbox_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'acst-checkbox-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Checkbox Filter', 'ac-starter-toolkit' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-checkbox';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'checkbox', 'multi', 'select', 'taxonomy', 'category' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Data Source
        $this->register_data_source_controls();

        // Checkbox-specific options
        $this->start_controls_section(
            'section_checkbox_options',
            array(
                'label' => esc_html__( 'Checkbox Options', 'ac-starter-toolkit' ),
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
                    'columns'    => esc_html__( 'Columns', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'     => esc_html__( 'Columns', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '2',
                'options'   => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
                'condition' => array(
                    'layout' => 'columns',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__options--columns' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ),
            )
        );

        $this->add_control(
            'collapsible',
            array(
                'label'        => esc_html__( 'Collapsible', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'collapsed_default',
            array(
                'label'        => esc_html__( 'Collapsed by Default', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'condition'    => array(
                    'collapsible' => 'yes',
                ),
            )
        );

        $this->add_control(
            'max_visible',
            array(
                'label'       => esc_html__( 'Max Visible Items', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 0,
                'min'         => 0,
                'description' => esc_html__( 'Set to 0 to show all items. Otherwise, remaining items will be hidden behind a "Show more" link.', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'show_more_text',
            array(
                'label'     => esc_html__( 'Show More Text', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Show more', 'ac-starter-toolkit' ),
                'condition' => array(
                    'max_visible!' => 0,
                ),
            )
        );

        $this->add_control(
            'show_less_text',
            array(
                'label'     => esc_html__( 'Show Less Text', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Show less', 'ac-starter-toolkit' ),
                'condition' => array(
                    'max_visible!' => 0,
                ),
            )
        );

        $this->add_control(
            'search_enabled',
            array(
                'label'        => esc_html__( 'Enable Search', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'description'  => esc_html__( 'Add a search box to filter the checkbox options.', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'search_placeholder',
            array(
                'label'     => esc_html__( 'Search Placeholder', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Search...', 'ac-starter-toolkit' ),
                'condition' => array(
                    'search_enabled' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Style controls
        $this->register_label_style_controls();

        // Checkbox styles
        $this->start_controls_section(
            'section_checkbox_style',
            array(
                'label' => esc_html__( 'Checkboxes', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-filter__options--columns' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'checkbox_size',
            array(
                'label'      => esc_html__( 'Checkbox Size', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-filter__checkbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'checkbox_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'checkbox_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'checkbox_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__checkbox' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'checkbox_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__checkbox' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Checked state
        $this->start_controls_tab(
            'checkbox_style_checked',
            array(
                'label' => esc_html__( 'Checked', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'checkbox_checked_border_color',
            array(
                'label'     => esc_html__( 'Border Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__checkbox:checked' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'checkbox_checked_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__checkbox:checked' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'checkbox_checkmark_color',
            array(
                'label'     => esc_html__( 'Checkmark Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__checkbox:checked::after' => 'border-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'checkbox_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 10,
                    ),
                ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__checkbox' => 'border-radius: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'option_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__option:hover .acst-filter__option-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'count_color',
            array(
                'label'     => esc_html__( 'Count Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-filter__option-count' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings    = $this->get_settings_for_display();
        $options     = $this->get_filter_options( $settings );
        $data_attrs  = $this->get_filter_data_attrs( $settings );
        $filter_id   = $this->get_filter_id( $settings );
        $max_visible = intval( $settings['max_visible'] );

        $layout_class = 'acst-filter__options--' . $settings['layout'];
        $collapsed    = $settings['collapsible'] === 'yes' && $settings['collapsed_default'] === 'yes';
        ?>
        <div class="acst-filter acst-filter--checkbox"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php if ( $settings['collapsible'] === 'yes' ) : ?>
                <div class="acst-filter__header" data-collapsed="<?php echo $collapsed ? 'true' : 'false'; ?>">
                    <?php $this->render_label( $settings ); ?>
                    <button type="button" class="acst-filter__toggle" aria-expanded="<?php echo $collapsed ? 'false' : 'true'; ?>">
                        <span class="acst-filter__toggle-icon"></span>
                    </button>
                </div>
            <?php else : ?>
                <?php $this->render_label( $settings ); ?>
            <?php endif; ?>

            <div class="acst-filter__body"<?php echo $collapsed ? ' style="display: none;"' : ''; ?>>
                <?php if ( $settings['search_enabled'] === 'yes' ) : ?>
                    <div class="acst-filter__search">
                        <input type="text"
                               class="acst-filter__search-input"
                               placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>"
                               data-search-target="<?php echo esc_attr( $filter_id ); ?>">
                    </div>
                <?php endif; ?>

                <div class="acst-filter__options <?php echo esc_attr( $layout_class ); ?>">
                    <?php foreach ( $options as $index => $option ) :
                        $hidden = $max_visible > 0 && $index >= $max_visible;
                    ?>
                        <label class="acst-filter__option<?php echo $hidden ? ' acst-filter__option--hidden' : ''; ?>"
                               data-value="<?php echo esc_attr( $option['value'] ); ?>">
                            <input type="checkbox"
                                   class="acst-filter__checkbox"
                                   name="<?php echo esc_attr( $filter_id ); ?>[]"
                                   value="<?php echo esc_attr( $option['value'] ); ?>">
                            <span class="acst-filter__option-label">
                                <?php echo esc_html( $option['label'] ); ?>
                            </span>
                            <?php if ( isset( $option['count'] ) && $settings['show_count'] === 'yes' ) : ?>
                                <span class="acst-filter__option-count">(<?php echo esc_html( $option['count'] ); ?>)</span>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <?php if ( $max_visible > 0 && count( $options ) > $max_visible ) : ?>
                    <button type="button" class="acst-filter__show-more"
                            data-show-more-text="<?php echo esc_attr( $settings['show_more_text'] ); ?>"
                            data-show-less-text="<?php echo esc_attr( $settings['show_less_text'] ); ?>">
                        <?php echo esc_html( $settings['show_more_text'] ); ?>
                    </button>
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
        var layoutClass = 'acst-filter__options--' + settings.layout;
        #>
        <div class="acst-filter acst-filter--checkbox">
            <# if ( settings.collapsible === 'yes' ) { #>
                <div class="acst-filter__header">
                    <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                        <label class="acst-filter__label">{{{ settings.filter_label }}}</label>
                    <# } #>
                    <button type="button" class="acst-filter__toggle">
                        <span class="acst-filter__toggle-icon"></span>
                    </button>
                </div>
            <# } else { #>
                <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                    <label class="acst-filter__label">{{{ settings.filter_label }}}</label>
                <# } #>
            <# } #>

            <div class="acst-filter__body">
                <# if ( settings.search_enabled === 'yes' ) { #>
                    <div class="acst-filter__search">
                        <input type="text" class="acst-filter__search-input" placeholder="{{{ settings.search_placeholder }}}">
                    </div>
                <# } #>

                <div class="acst-filter__options {{{ layoutClass }}}">
                    <label class="acst-filter__option">
                        <input type="checkbox" class="acst-filter__checkbox">
                        <span class="acst-filter__option-label"><?php esc_html_e( 'Option 1', 'ac-starter-toolkit' ); ?></span>
                    </label>
                    <label class="acst-filter__option">
                        <input type="checkbox" class="acst-filter__checkbox">
                        <span class="acst-filter__option-label"><?php esc_html_e( 'Option 2', 'ac-starter-toolkit' ); ?></span>
                    </label>
                    <label class="acst-filter__option">
                        <input type="checkbox" class="acst-filter__checkbox">
                        <span class="acst-filter__option-label"><?php esc_html_e( 'Option 3', 'ac-starter-toolkit' ); ?></span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
}
