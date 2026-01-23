<?php
/**
 * Countdown Timer Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Countdown Timer Widget Class
 *
 * Display a countdown timer to a specific date/time.
 */
class Countdown extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'acst-countdown';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Countdown Timer', 'ac-starter-toolkit' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-countdown';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'countdown', 'timer', 'clock', 'date', 'time', 'launch', 'sale' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_countdown',
            array(
                'label' => esc_html__( 'Countdown', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'countdown_type',
            array(
                'label'   => esc_html__( 'Type', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'due_date',
                'options' => array(
                    'due_date'  => esc_html__( 'Due Date', 'ac-starter-toolkit' ),
                    'evergreen' => esc_html__( 'Evergreen Timer', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'due_date',
            array(
                'label'          => esc_html__( 'Due Date', 'ac-starter-toolkit' ),
                'type'           => Controls_Manager::DATE_TIME,
                'default'        => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) ),
                'picker_options' => array(
                    'enableTime' => true,
                ),
                'condition'      => array(
                    'countdown_type' => 'due_date',
                ),
            )
        );

        $this->add_control(
            'evergreen_hours',
            array(
                'label'     => esc_html__( 'Hours', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 24,
                'min'       => 0,
                'condition' => array(
                    'countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'evergreen_minutes',
            array(
                'label'     => esc_html__( 'Minutes', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 0,
                'min'       => 0,
                'max'       => 59,
                'condition' => array(
                    'countdown_type' => 'evergreen',
                ),
            )
        );

        $this->add_control(
            'show_days',
            array(
                'label'        => esc_html__( 'Show Days', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_hours',
            array(
                'label'        => esc_html__( 'Show Hours', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_minutes',
            array(
                'label'        => esc_html__( 'Show Minutes', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_seconds',
            array(
                'label'        => esc_html__( 'Show Seconds', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_labels',
            array(
                'label'        => esc_html__( 'Show Labels', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->end_controls_section();

        // Labels Section
        $this->start_controls_section(
            'section_labels',
            array(
                'label'     => esc_html__( 'Labels', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'show_labels' => 'yes',
                ),
            )
        );

        $this->add_control(
            'label_days',
            array(
                'label'   => esc_html__( 'Days', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Days', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'label_hours',
            array(
                'label'   => esc_html__( 'Hours', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Hours', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'label_minutes',
            array(
                'label'   => esc_html__( 'Minutes', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Minutes', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'label_seconds',
            array(
                'label'   => esc_html__( 'Seconds', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Seconds', 'ac-starter-toolkit' ),
            )
        );

        $this->end_controls_section();

        // Actions Section
        $this->start_controls_section(
            'section_actions',
            array(
                'label' => esc_html__( 'Actions', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'expire_action',
            array(
                'label'   => esc_html__( 'On Expire', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hide',
                'options' => array(
                    'hide'    => esc_html__( 'Hide', 'ac-starter-toolkit' ),
                    'message' => esc_html__( 'Show Message', 'ac-starter-toolkit' ),
                    'redirect'=> esc_html__( 'Redirect', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'expire_message',
            array(
                'label'     => esc_html__( 'Message', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => esc_html__( 'This offer has expired!', 'ac-starter-toolkit' ),
                'rows'      => 3,
                'condition' => array(
                    'expire_action' => 'message',
                ),
            )
        );

        $this->add_control(
            'expire_redirect',
            array(
                'label'       => esc_html__( 'Redirect URL', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ac-starter-toolkit' ),
                'condition'   => array(
                    'expire_action' => 'redirect',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Layout
        $this->start_controls_section(
            'section_style_layout',
            array(
                'label' => esc_html__( 'Layout', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'layout_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center'     => array(
                        'title' => esc_html__( 'Center', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'flex-end'   => array(
                        'title' => esc_html__( 'Right', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .acst-countdown' => 'justify-content: {{VALUE}};',
                ),
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
                    'size' => 15,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Boxes
        $this->start_controls_section(
            'section_style_box',
            array(
                'label' => esc_html__( 'Boxes', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'box_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .acst-countdown__item',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'box_border',
                'selector' => '{{WRAPPER}} .acst-countdown__item',
            )
        );

        $this->add_responsive_control(
            'box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .acst-countdown__item',
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'box_min_width',
            array(
                'label'      => esc_html__( 'Min Width', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown__item' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Numbers
        $this->start_controls_section(
            'section_style_numbers',
            array(
                'label' => esc_html__( 'Numbers', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'numbers_typography',
                'selector' => '{{WRAPPER}} .acst-countdown__number',
            )
        );

        $this->add_control(
            'numbers_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-countdown__number' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Labels
        $this->start_controls_section(
            'section_style_labels',
            array(
                'label'     => esc_html__( 'Labels', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_labels' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'labels_typography',
                'selector' => '{{WRAPPER}} .acst-countdown__label',
            )
        );

        $this->add_control(
            'labels_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-countdown__label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'labels_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Separators
        $this->start_controls_section(
            'section_style_separator',
            array(
                'label' => esc_html__( 'Separator', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'show_separator',
            array(
                'label'        => esc_html__( 'Show Separator', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'separator_type',
            array(
                'label'     => esc_html__( 'Type', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'colon',
                'options'   => array(
                    'colon' => esc_html__( 'Colon (:)', 'ac-starter-toolkit' ),
                    'line'  => esc_html__( 'Line (|)', 'ac-starter-toolkit' ),
                    'dot'   => esc_html__( 'Dot (\u2022)', 'ac-starter-toolkit' ),
                ),
                'condition' => array(
                    'show_separator' => 'yes',
                ),
            )
        );

        $this->add_control(
            'separator_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-countdown__separator' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_separator' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_size',
            array(
                'label'      => esc_html__( 'Size', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 60,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-countdown__separator' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'show_separator' => 'yes',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Calculate target time
        if ( $settings['countdown_type'] === 'due_date' ) {
            $target_date = strtotime( $settings['due_date'] );
        } else {
            // Evergreen timer - calculate from now
            $hours   = intval( $settings['evergreen_hours'] ?? 24 );
            $minutes = intval( $settings['evergreen_minutes'] ?? 0 );
            $target_date = time() + ( $hours * 3600 ) + ( $minutes * 60 );
        }

        $separator_char = ':';
        if ( $settings['show_separator'] === 'yes' ) {
            switch ( $settings['separator_type'] ) {
                case 'line':
                    $separator_char = '|';
                    break;
                case 'dot':
                    $separator_char = '•';
                    break;
                default:
                    $separator_char = ':';
            }
        }

        $this->add_render_attribute( 'countdown', array(
            'class'             => 'acst-countdown',
            'data-target-date'  => $target_date,
            'data-expire-action'=> $settings['expire_action'],
            'data-show-separator' => $settings['show_separator'],
        ) );

        if ( $settings['expire_action'] === 'message' ) {
            $this->add_render_attribute( 'countdown', 'data-expire-message', $settings['expire_message'] );
        } elseif ( $settings['expire_action'] === 'redirect' && ! empty( $settings['expire_redirect']['url'] ) ) {
            $this->add_render_attribute( 'countdown', 'data-expire-redirect', $settings['expire_redirect']['url'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'countdown' ); ?>>
            <?php if ( $settings['show_days'] === 'yes' ) : ?>
                <div class="acst-countdown__item" data-unit="days">
                    <span class="acst-countdown__number">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="acst-countdown__label"><?php echo esc_html( $settings['label_days'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_hours'] === 'yes' ) : ?>
                    <span class="acst-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_hours'] === 'yes' ) : ?>
                <div class="acst-countdown__item" data-unit="hours">
                    <span class="acst-countdown__number">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="acst-countdown__label"><?php echo esc_html( $settings['label_hours'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_minutes'] === 'yes' ) : ?>
                    <span class="acst-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_minutes'] === 'yes' ) : ?>
                <div class="acst-countdown__item" data-unit="minutes">
                    <span class="acst-countdown__number">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="acst-countdown__label"><?php echo esc_html( $settings['label_minutes'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_seconds'] === 'yes' ) : ?>
                    <span class="acst-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_seconds'] === 'yes' ) : ?>
                <div class="acst-countdown__item" data-unit="seconds">
                    <span class="acst-countdown__number">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="acst-countdown__label"><?php echo esc_html( $settings['label_seconds'] ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
