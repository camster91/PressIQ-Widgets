<?php
/**
 * Tabs Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Content\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Tabs Widget Class
 *
 * Display content in tabbed interface.
 */
class Tabs extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-tabs';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Tabs', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'tabs', 'toggle', 'switch', 'content' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_tabs',
            array(
                'label' => esc_html__( 'Tabs', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            array(
                'label'   => esc_html__( 'Title', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Tab Title', 'pressiq-widgets' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'tab_icon',
            array(
                'label' => esc_html__( 'Icon', 'pressiq-widgets' ),
                'type'  => Controls_Manager::ICONS,
            )
        );

        $repeater->add_control(
            'tab_content',
            array(
                'label'   => esc_html__( 'Content', 'pressiq-widgets' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Tab content goes here.', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'tabs',
            array(
                'label'       => esc_html__( 'Tabs', 'pressiq-widgets' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'tab_title'   => esc_html__( 'Tab 1', 'pressiq-widgets' ),
                        'tab_content' => esc_html__( 'Content for tab 1.', 'pressiq-widgets' ),
                    ),
                    array(
                        'tab_title'   => esc_html__( 'Tab 2', 'pressiq-widgets' ),
                        'tab_content' => esc_html__( 'Content for tab 2.', 'pressiq-widgets' ),
                    ),
                    array(
                        'tab_title'   => esc_html__( 'Tab 3', 'pressiq-widgets' ),
                        'tab_content' => esc_html__( 'Content for tab 3.', 'pressiq-widgets' ),
                    ),
                ),
                'title_field' => '{{{ tab_title }}}',
            )
        );

        $this->end_controls_section();

        // Settings Section
        $this->start_controls_section(
            'section_settings',
            array(
                'label' => esc_html__( 'Settings', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'tabs_layout',
            array(
                'label'   => esc_html__( 'Layout', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => array(
                    'horizontal' => esc_html__( 'Horizontal', 'pressiq-widgets' ),
                    'vertical'   => esc_html__( 'Vertical', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_responsive_control(
            'tabs_alignment',
            array(
                'label'     => esc_html__( 'Tabs Alignment', 'pressiq-widgets' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'pressiq-widgets' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'pressiq-widgets' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'pressiq-widgets' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                    'stretch'    => array(
                        'title' => esc_html__( 'Stretch', 'pressiq-widgets' ),
                        'icon'  => 'eicon-h-align-stretch',
                    ),
                ),
                'default'   => 'flex-start',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__nav' => 'justify-content: {{VALUE}};',
                ),
                'condition' => array(
                    'tabs_layout' => 'horizontal',
                ),
            )
        );

        $this->add_control(
            'default_active',
            array(
                'label'   => esc_html__( 'Default Active Tab', 'pressiq-widgets' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 1,
                'min'     => 1,
            )
        );

        $this->add_control(
            'icon_position',
            array(
                'label'   => esc_html__( 'Icon Position', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => array(
                    'before' => esc_html__( 'Before Title', 'pressiq-widgets' ),
                    'after'  => esc_html__( 'After Title', 'pressiq-widgets' ),
                    'above'  => esc_html__( 'Above Title', 'pressiq-widgets' ),
                ),
            )
        );

        $this->end_controls_section();

        // Style: Tabs Nav
        $this->start_controls_section(
            'section_style_tabs_nav',
            array(
                'label' => esc_html__( 'Tabs Navigation', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'nav_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .pressiq-tabs__nav',
            )
        );

        $this->add_responsive_control(
            'nav_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'nav_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__nav' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'nav_border',
                'selector' => '{{WRAPPER}} .pressiq-tabs__nav',
            )
        );

        $this->end_controls_section();

        // Style: Tab Items
        $this->start_controls_section(
            'section_style_tab_items',
            array(
                'label' => esc_html__( 'Tab Items', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'tab_typography',
                'selector' => '{{WRAPPER}} .pressiq-tabs__tab',
            )
        );

        $this->start_controls_tabs( 'tab_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'tab_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'tab_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tab_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            'tab_style_hover',
            array(
                'label' => esc_html__( 'Hover', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'tab_hover_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tab_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Active state
        $this->start_controls_tab(
            'tab_style_active',
            array(
                'label' => esc_html__( 'Active', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'tab_active_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab.is-active' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'tab_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__tab.is-active' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'tab_border',
                'selector'  => '{{WRAPPER}} .pressiq-tabs__tab',
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'tab_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'tab_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__tab-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-tabs__tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_spacing',
            array(
                'label'      => esc_html__( 'Icon Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__tab-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-tabs__tab--icon-above .pressiq-tabs__tab-icon' => 'margin-right: 0; margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-tabs__tab--icon-after .pressiq-tabs__tab-icon' => 'margin-right: 0; margin-left: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Content
        $this->start_controls_section(
            'section_style_content',
            array(
                'label' => esc_html__( 'Content', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'content_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .pressiq-tabs__content',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'content_border',
                'selector' => '{{WRAPPER}} .pressiq-tabs__content',
            )
        );

        $this->add_responsive_control(
            'content_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-tabs__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .pressiq-tabs__panel',
            )
        );

        $this->add_control(
            'content_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-tabs__panel' => 'color: {{VALUE}};',
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
        $tabs           = $settings['tabs'];
        $layout         = $settings['tabs_layout'];
        $default_active = intval( $settings['default_active'] ?? 1 );
        $icon_position  = $settings['icon_position'];
        $widget_id      = $this->get_id();

        if ( empty( $tabs ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', 'class', array(
            'pressiq-tabs',
            'pressiq-tabs--' . $layout,
        ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="pressiq-tabs__nav" role="tablist">
                <?php foreach ( $tabs as $index => $tab ) :
                    $tab_count = $index + 1;
                    $is_active = $tab_count === $default_active;
                    $tab_id    = 'pressiq-tab-' . $widget_id . '-' . $tab_count;
                    $panel_id  = 'pressiq-panel-' . $widget_id . '-' . $tab_count;
                    $has_icon  = ! empty( $tab['tab_icon']['value'] );

                    $tab_class = 'pressiq-tabs__tab';
                    if ( $is_active ) {
                        $tab_class .= ' is-active';
                    }
                    if ( $has_icon ) {
                        $tab_class .= ' pressiq-tabs__tab--icon-' . $icon_position;
                    }
                ?>
                    <button type="button"
                            class="<?php echo esc_attr( $tab_class ); ?>"
                            id="<?php echo esc_attr( $tab_id ); ?>"
                            role="tab"
                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                            data-tab="<?php echo esc_attr( $tab_count ); ?>">
                        <?php if ( $has_icon && $icon_position !== 'after' ) : ?>
                            <span class="pressiq-tabs__tab-icon">
                                <?php Icons_Manager::render_icon( $tab['tab_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                            </span>
                        <?php endif; ?>

                        <span class="pressiq-tabs__tab-title"><?php echo esc_html( $tab['tab_title'] ); ?></span>

                        <?php if ( $has_icon && $icon_position === 'after' ) : ?>
                            <span class="pressiq-tabs__tab-icon">
                                <?php Icons_Manager::render_icon( $tab['tab_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                            </span>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="pressiq-tabs__content">
                <?php foreach ( $tabs as $index => $tab ) :
                    $tab_count = $index + 1;
                    $is_active = $tab_count === $default_active;
                    $tab_id    = 'pressiq-tab-' . $widget_id . '-' . $tab_count;
                    $panel_id  = 'pressiq-panel-' . $widget_id . '-' . $tab_count;
                ?>
                    <div class="pressiq-tabs__panel<?php echo $is_active ? ' is-active' : ''; ?>"
                         id="<?php echo esc_attr( $panel_id ); ?>"
                         role="tabpanel"
                         aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
                         data-tab="<?php echo esc_attr( $tab_count ); ?>"
                         <?php echo ! $is_active ? 'hidden' : ''; ?>>
                        <?php echo wp_kses_post( $tab['tab_content'] ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
