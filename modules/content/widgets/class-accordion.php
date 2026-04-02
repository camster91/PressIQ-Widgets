<?php
/**
 * Accordion Widget
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
 * Accordion Widget Class
 *
 * Display content in collapsible accordion sections.
 */
class Accordion extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-accordion';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Accordion', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-accordion';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'accordion', 'toggle', 'collapse', 'faq', 'content' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_accordion',
            array(
                'label' => esc_html__( 'Accordion', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            array(
                'label'   => esc_html__( 'Title', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Accordion Title', 'pressiq-widgets' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'item_icon',
            array(
                'label' => esc_html__( 'Icon', 'pressiq-widgets' ),
                'type'  => Controls_Manager::ICONS,
            )
        );

        $repeater->add_control(
            'item_content',
            array(
                'label'   => esc_html__( 'Content', 'pressiq-widgets' ),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Accordion content goes here.', 'pressiq-widgets' ),
            )
        );

        $repeater->add_control(
            'item_open',
            array(
                'label'        => esc_html__( 'Open by Default', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'items',
            array(
                'label'       => esc_html__( 'Accordion Items', 'pressiq-widgets' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'item_title'   => esc_html__( 'Accordion Item 1', 'pressiq-widgets' ),
                        'item_content' => esc_html__( 'Content for accordion item 1.', 'pressiq-widgets' ),
                        'item_open'    => 'yes',
                    ),
                    array(
                        'item_title'   => esc_html__( 'Accordion Item 2', 'pressiq-widgets' ),
                        'item_content' => esc_html__( 'Content for accordion item 2.', 'pressiq-widgets' ),
                    ),
                    array(
                        'item_title'   => esc_html__( 'Accordion Item 3', 'pressiq-widgets' ),
                        'item_content' => esc_html__( 'Content for accordion item 3.', 'pressiq-widgets' ),
                    ),
                ),
                'title_field' => '{{{ item_title }}}',
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
            'collapse_others',
            array(
                'label'        => esc_html__( 'Collapse Others', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
                'description'  => esc_html__( 'Close other accordion items when one is opened.', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'title_tag',
            array(
                'label'   => esc_html__( 'Title HTML Tag', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'div',
                'options' => array(
                    'h2'  => 'H2',
                    'h3'  => 'H3',
                    'h4'  => 'H4',
                    'h5'  => 'H5',
                    'h6'  => 'H6',
                    'div' => 'div',
                ),
            )
        );

        $this->add_control(
            'toggle_icon_active',
            array(
                'label'   => esc_html__( 'Active Icon', 'pressiq-widgets' ),
                'type'    => Controls_Manager::ICONS,
                'default' => array(
                    'value'   => 'fas fa-minus',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'toggle_icon_inactive',
            array(
                'label'   => esc_html__( 'Inactive Icon', 'pressiq-widgets' ),
                'type'    => Controls_Manager::ICONS,
                'default' => array(
                    'value'   => 'fas fa-plus',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'toggle_icon_position',
            array(
                'label'   => esc_html__( 'Toggle Icon Position', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left'  => esc_html__( 'Left', 'pressiq-widgets' ),
                    'right' => esc_html__( 'Right', 'pressiq-widgets' ),
                ),
            )
        );

        $this->end_controls_section();

        // Style: Items
        $this->start_controls_section(
            'section_style_items',
            array(
                'label' => esc_html__( 'Items', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'items_spacing',
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
                'default'    => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'item_border',
                'selector' => '{{WRAPPER}} .pressiq-accordion__item',
            )
        );

        $this->add_responsive_control(
            'item_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'item_shadow',
                'selector' => '{{WRAPPER}} .pressiq-accordion__item',
            )
        );

        $this->end_controls_section();

        // Style: Header
        $this->start_controls_section(
            'section_style_header',
            array(
                'label' => esc_html__( 'Header', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'header_typography',
                'selector' => '{{WRAPPER}} .pressiq-accordion__header',
            )
        );

        $this->start_controls_tabs( 'header_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            'header_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'header_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__header' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'header_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__header' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            'header_style_hover',
            array(
                'label' => esc_html__( 'Hover', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'header_hover_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__header:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'header_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__header:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Active state
        $this->start_controls_tab(
            'header_style_active',
            array(
                'label' => esc_html__( 'Active', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'header_active_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__item.is-active .pressiq-accordion__header' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'header_active_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__item.is-active .pressiq-accordion__header' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'header_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'header_icon_size',
            array(
                'label'      => esc_html__( 'Title Icon Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 40,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__title-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-accordion__title-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'header_icon_spacing',
            array(
                'label'      => esc_html__( 'Title Icon Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__title-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Toggle Icon
        $this->start_controls_section(
            'section_style_toggle',
            array(
                'label' => esc_html__( 'Toggle Icon', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'toggle_icon_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'toggle_icon_active_color',
            array(
                'label'     => esc_html__( 'Active Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__item.is-active .pressiq-accordion__toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'toggle_icon_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__toggle' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-accordion__toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .pressiq-accordion__content',
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-accordion__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .pressiq-accordion__content',
            )
        );

        $this->add_control(
            'content_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-accordion__content' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings         = $this->get_settings_for_display();
        $items            = $settings['items'];
        $collapse_others  = $settings['collapse_others'] === 'yes';
        $title_tag        = $settings['title_tag'];
        $toggle_position  = $settings['toggle_icon_position'];
        $widget_id        = $this->get_id();

        if ( empty( $items ) ) {
            return;
        }

        $this->add_render_attribute( 'wrapper', array(
            'class'                => 'pressiq-accordion',
            'data-collapse-others' => $collapse_others ? 'true' : 'false',
        ) );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php foreach ( $items as $index => $item ) :
                $item_count = $index + 1;
                $is_open    = $item['item_open'] === 'yes';
                $has_icon   = ! empty( $item['item_icon']['value'] );
                $header_id  = 'pressiq-accordion-header-' . $widget_id . '-' . $item_count;
                $content_id = 'pressiq-accordion-content-' . $widget_id . '-' . $item_count;
            ?>
                <div class="pressiq-accordion__item<?php echo $is_open ? ' is-active' : ''; ?>">
                    <<?php echo esc_attr( $title_tag ); ?> class="pressiq-accordion__header-wrapper">
                        <button type="button"
                                class="pressiq-accordion__header"
                                id="<?php echo esc_attr( $header_id ); ?>"
                                aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr( $content_id ); ?>">

                            <?php if ( $toggle_position === 'left' ) : ?>
                                <span class="pressiq-accordion__toggle pressiq-accordion__toggle--left">
                                    <span class="pressiq-accordion__toggle-icon pressiq-accordion__toggle-icon--inactive">
                                        <?php Icons_Manager::render_icon( $settings['toggle_icon_inactive'], array( 'aria-hidden' => 'true' ) ); ?>
                                    </span>
                                    <span class="pressiq-accordion__toggle-icon pressiq-accordion__toggle-icon--active">
                                        <?php Icons_Manager::render_icon( $settings['toggle_icon_active'], array( 'aria-hidden' => 'true' ) ); ?>
                                    </span>
                                </span>
                            <?php endif; ?>

                            <?php if ( $has_icon ) : ?>
                                <span class="pressiq-accordion__title-icon">
                                    <?php Icons_Manager::render_icon( $item['item_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                                </span>
                            <?php endif; ?>

                            <span class="pressiq-accordion__title"><?php echo esc_html( $item['item_title'] ); ?></span>

                            <?php if ( $toggle_position === 'right' ) : ?>
                                <span class="pressiq-accordion__toggle pressiq-accordion__toggle--right">
                                    <span class="pressiq-accordion__toggle-icon pressiq-accordion__toggle-icon--inactive">
                                        <?php Icons_Manager::render_icon( $settings['toggle_icon_inactive'], array( 'aria-hidden' => 'true' ) ); ?>
                                    </span>
                                    <span class="pressiq-accordion__toggle-icon pressiq-accordion__toggle-icon--active">
                                        <?php Icons_Manager::render_icon( $settings['toggle_icon_active'], array( 'aria-hidden' => 'true' ) ); ?>
                                    </span>
                                </span>
                            <?php endif; ?>
                        </button>
                    </<?php echo esc_attr( $title_tag ); ?>>

                    <div class="pressiq-accordion__content"
                         id="<?php echo esc_attr( $content_id ); ?>"
                         role="region"
                         aria-labelledby="<?php echo esc_attr( $header_id ); ?>"
                         <?php echo ! $is_open ? 'hidden' : ''; ?>>
                        <div class="pressiq-accordion__content-inner">
                            <?php echo wp_kses_post( $item['item_content'] ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
