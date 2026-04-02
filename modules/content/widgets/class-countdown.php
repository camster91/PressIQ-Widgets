<?php
/**
 * Countdown Timer Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Content\Widgets;

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
        return 'pressiq-countdown';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Countdown Timer', 'pressiq-widgets' );
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
                'label' => esc_html__( 'Countdown', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'countdown_type',
            array(
                'label'   => esc_html__( 'Type', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'due_date',
                'options' => array(
                    'due_date'  => esc_html__( 'Due Date', 'pressiq-widgets' ),
                    'evergreen' => esc_html__( 'Evergreen Timer', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'due_date',
            array(
                'label'          => esc_html__( 'Due Date', 'pressiq-widgets' ),
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
                'label'     => esc_html__( 'Hours', 'pressiq-widgets' ),
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
                'label'     => esc_html__( 'Minutes', 'pressiq-widgets' ),
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
                'label'        => esc_html__( 'Show Days', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_hours',
            array(
                'label'        => esc_html__( 'Show Hours', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_minutes',
            array(
                'label'        => esc_html__( 'Show Minutes', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_seconds',
            array(
                'label'        => esc_html__( 'Show Seconds', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_labels',
            array(
                'label'        => esc_html__( 'Show Labels', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->end_controls_section();

        // Labels Section
        $this->start_controls_section(
            'section_labels',
            array(
                'label'     => esc_html__( 'Labels', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'show_labels' => 'yes',
                ),
            )
        );

        $this->add_control(
            'label_days',
            array(
                'label'   => esc_html__( 'Days', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Days', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'label_hours',
            array(
                'label'   => esc_html__( 'Hours', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Hours', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'label_minutes',
            array(
                'label'   => esc_html__( 'Minutes', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Minutes', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'label_seconds',
            array(
                'label'   => esc_html__( 'Seconds', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Seconds', 'pressiq-widgets' ),
            )
        );

        $this->end_controls_section();

        // Actions Section
        $this->start_controls_section(
            'section_actions',
            array(
                'label' => esc_html__( 'Actions', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'expire_action',
            array(
                'label'   => esc_html__( 'On Expire', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'hide',
                'options' => array(
                    'hide'    => esc_html__( 'Hide', 'pressiq-widgets' ),
                    'message' => esc_html__( 'Show Message', 'pressiq-widgets' ),
                    'redirect'=> esc_html__( 'Redirect', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'expire_message',
            array(
                'label'     => esc_html__( 'Message', 'pressiq-widgets' ),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => esc_html__( 'This offer has expired!', 'pressiq-widgets' ),
                'rows'      => 3,
                'condition' => array(
                    'expire_action' => 'message',
                ),
            )
        );

        $this->add_control(
            'expire_redirect',
            array(
                'label'       => esc_html__( 'Redirect URL', 'pressiq-widgets' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'pressiq-widgets' ),
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
                'label' => esc_html__( 'Layout', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'layout_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'pressiq-widgets' ),
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
                ),
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-countdown' => 'justify-content: {{VALUE}};',
                ),
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
                    'size' => 15,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Boxes
        $this->start_controls_section(
            'section_style_box',
            array(
                'label' => esc_html__( 'Boxes', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'box_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .pressiq-countdown__item',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'box_border',
                'selector' => '{{WRAPPER}} .pressiq-countdown__item',
            )
        );

        $this->add_responsive_control(
            'box_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .pressiq-countdown__item',
            )
        );

        $this->add_responsive_control(
            'box_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'box_min_width',
            array(
                'label'      => esc_html__( 'Min Width', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 200,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown__item' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Numbers
        $this->start_controls_section(
            'section_style_numbers',
            array(
                'label' => esc_html__( 'Numbers', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'numbers_typography',
                'selector' => '{{WRAPPER}} .pressiq-countdown__number',
            )
        );

        $this->add_control(
            'numbers_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-countdown__number' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Labels
        $this->start_controls_section(
            'section_style_labels',
            array(
                'label'     => esc_html__( 'Labels', 'pressiq-widgets' ),
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
                'selector' => '{{WRAPPER}} .pressiq-countdown__label',
            )
        );

        $this->add_control(
            'labels_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-countdown__label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'labels_margin',
            array(
                'label'      => esc_html__( 'Margin', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Separators
        $this->start_controls_section(
            'section_style_separator',
            array(
                'label' => esc_html__( 'Separator', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'show_separator',
            array(
                'label'        => esc_html__( 'Show Separator', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'separator_type',
            array(
                'label'     => esc_html__( 'Type', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'colon',
                'options'   => array(
                    'colon' => esc_html__( 'Colon (:)', 'pressiq-widgets' ),
                    'line'  => esc_html__( 'Line (|)', 'pressiq-widgets' ),
                    'dot'   => esc_html__( 'Dot (\u2022)', 'pressiq-widgets' ),
                ),
                'condition' => array(
                    'show_separator' => 'yes',
                ),
            )
        );

        $this->add_control(
            'separator_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-countdown__separator' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_separator' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'separator_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 60,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-countdown__separator' => 'font-size: {{SIZE}}{{UNIT}};',
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

        // Build data attributes that match the JavaScript expectations
        $countdown_attrs = array(
            'class'              => 'pressiq-countdown',
            'data-expire-action' => $settings['expire_action'],
        );

        // Set the appropriate date attribute based on countdown type
        if ( $settings['countdown_type'] === 'due_date' ) {
            // JavaScript expects ISO 8601 format for due_date
            $countdown_attrs['data-due-date'] = gmdate( 'c', $target_date );
        } else {
            // For evergreen timers, pass the total seconds
            $hours   = intval( $settings['evergreen_hours'] ?? 24 );
            $minutes = intval( $settings['evergreen_minutes'] ?? 0 );
            $countdown_attrs['data-evergreen'] = 'yes';
            $countdown_attrs['data-evergreen-seconds'] = ( $hours * 3600 ) + ( $minutes * 60 );
        }

        $this->add_render_attribute( 'countdown', $countdown_attrs );

        if ( $settings['expire_action'] === 'message' ) {
            $this->add_render_attribute( 'countdown', 'data-expire-message', $settings['expire_message'] );
        } elseif ( $settings['expire_action'] === 'redirect' && ! empty( $settings['expire_redirect']['url'] ) ) {
            // Only allow safe redirect URLs (same origin or relative)
            $redirect_url = $settings['expire_redirect']['url'];
            if ( wp_validate_redirect( $redirect_url, home_url() ) ) {
                $this->add_render_attribute( 'countdown', 'data-expire-redirect', esc_url( $redirect_url ) );
            }
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'countdown' ); ?>>
            <?php if ( $settings['show_days'] === 'yes' ) : ?>
                <div class="pressiq-countdown__item" data-unit="days">
                    <span class="pressiq-countdown__number" data-countdown="days">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="pressiq-countdown__label"><?php echo esc_html( $settings['label_days'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_hours'] === 'yes' ) : ?>
                    <span class="pressiq-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_hours'] === 'yes' ) : ?>
                <div class="pressiq-countdown__item" data-unit="hours">
                    <span class="pressiq-countdown__number" data-countdown="hours">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="pressiq-countdown__label"><?php echo esc_html( $settings['label_hours'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_minutes'] === 'yes' ) : ?>
                    <span class="pressiq-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_minutes'] === 'yes' ) : ?>
                <div class="pressiq-countdown__item" data-unit="minutes">
                    <span class="pressiq-countdown__number" data-countdown="minutes">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="pressiq-countdown__label"><?php echo esc_html( $settings['label_minutes'] ); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ( $settings['show_separator'] === 'yes' && $settings['show_seconds'] === 'yes' ) : ?>
                    <span class="pressiq-countdown__separator"><?php echo esc_html( $separator_char ); ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ( $settings['show_seconds'] === 'yes' ) : ?>
                <div class="pressiq-countdown__item" data-unit="seconds">
                    <span class="pressiq-countdown__number" data-countdown="seconds">00</span>
                    <?php if ( $settings['show_labels'] === 'yes' ) : ?>
                        <span class="pressiq-countdown__label"><?php echo esc_html( $settings['label_seconds'] ); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
