<?php
/**
 * Pricing Table Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content\Widgets;

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
 * Pricing Table Widget Class
 *
 * Display pricing plans with features list and CTA button.
 */
class Pricing_Table extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'acst-pricing-table';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Pricing Table', 'ac-starter-toolkit' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-price-table';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'pricing', 'price', 'table', 'plan', 'package', 'subscription' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Header Section
        $this->start_controls_section(
            'section_header',
            array(
                'label' => esc_html__( 'Header', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => esc_html__( 'Title', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Basic Plan', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'   => esc_html__( 'Subtitle', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'For individuals', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'featured',
            array(
                'label'        => esc_html__( 'Featured / Popular', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'ribbon_text',
            array(
                'label'     => esc_html__( 'Ribbon Text', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Popular', 'ac-starter-toolkit' ),
                'condition' => array(
                    'featured' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Pricing Section
        $this->start_controls_section(
            'section_pricing',
            array(
                'label' => esc_html__( 'Pricing', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'currency',
            array(
                'label'   => esc_html__( 'Currency Symbol', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '$',
            )
        );

        $this->add_control(
            'currency_position',
            array(
                'label'   => esc_html__( 'Currency Position', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'before',
                'options' => array(
                    'before' => esc_html__( 'Before', 'ac-starter-toolkit' ),
                    'after'  => esc_html__( 'After', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'price',
            array(
                'label'   => esc_html__( 'Price', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => '29',
            )
        );

        $this->add_control(
            'original_price',
            array(
                'label'       => esc_html__( 'Original Price', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__( 'Leave empty to hide. Used for showing discounts.', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'period',
            array(
                'label'   => esc_html__( 'Period', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( '/month', 'ac-starter-toolkit' ),
            )
        );

        $this->end_controls_section();

        // Features Section
        $this->start_controls_section(
            'section_features',
            array(
                'label' => esc_html__( 'Features', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            array(
                'label'   => esc_html__( 'Text', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Feature item', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $repeater->add_control(
            'feature_icon',
            array(
                'label'   => esc_html__( 'Icon', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::ICONS,
                'default' => array(
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ),
            )
        );

        $repeater->add_control(
            'feature_available',
            array(
                'label'        => esc_html__( 'Available', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'features',
            array(
                'label'       => esc_html__( 'Features', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'feature_text'      => esc_html__( '10 Projects', 'ac-starter-toolkit' ),
                        'feature_available' => 'yes',
                    ),
                    array(
                        'feature_text'      => esc_html__( '5GB Storage', 'ac-starter-toolkit' ),
                        'feature_available' => 'yes',
                    ),
                    array(
                        'feature_text'      => esc_html__( 'Email Support', 'ac-starter-toolkit' ),
                        'feature_available' => 'yes',
                    ),
                    array(
                        'feature_text'      => esc_html__( 'Priority Support', 'ac-starter-toolkit' ),
                        'feature_available' => '',
                    ),
                ),
                'title_field' => '{{{ feature_text }}}',
            )
        );

        $this->end_controls_section();

        // Button Section
        $this->start_controls_section(
            'section_button',
            array(
                'label' => esc_html__( 'Button', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'   => esc_html__( 'Button Text', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Get Started', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'button_link',
            array(
                'label'       => esc_html__( 'Link', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ac-starter-toolkit' ),
                'default'     => array(
                    'url' => '#',
                ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'button_icon',
            array(
                'label' => esc_html__( 'Icon', 'ac-starter-toolkit' ),
                'type'  => Controls_Manager::ICONS,
            )
        );

        $this->add_control(
            'button_icon_position',
            array(
                'label'   => esc_html__( 'Icon Position', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => array(
                    'before' => esc_html__( 'Before', 'ac-starter-toolkit' ),
                    'after'  => esc_html__( 'After', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->end_controls_section();

        // Style: Box
        $this->start_controls_section(
            'section_style_box',
            array(
                'label' => esc_html__( 'Box', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_box_style_controls( 'box', '{{WRAPPER}} .acst-pricing-table' );

        $this->end_controls_section();

        // Style: Header
        $this->start_controls_section(
            'section_style_header',
            array(
                'label' => esc_html__( 'Header', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'header_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .acst-pricing-table__header',
            )
        );

        $this->add_responsive_control(
            'header_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-pricing-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'header_title_heading',
            array(
                'label'     => esc_html__( 'Title', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'header_title_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__title',
            )
        );

        $this->add_control(
            'header_title_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'header_subtitle_heading',
            array(
                'label'     => esc_html__( 'Subtitle', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'header_subtitle_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__subtitle',
            )
        );

        $this->add_control(
            'header_subtitle_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__subtitle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Price
        $this->start_controls_section(
            'section_style_price',
            array(
                'label' => esc_html__( 'Price', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'price_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .acst-pricing-table__price',
            )
        );

        $this->add_responsive_control(
            'price_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-pricing-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'price_amount_heading',
            array(
                'label'     => esc_html__( 'Amount', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_amount_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__amount',
            )
        );

        $this->add_control(
            'price_amount_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__amount' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'price_currency_heading',
            array(
                'label'     => esc_html__( 'Currency', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_currency_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__currency',
            )
        );

        $this->add_control(
            'price_currency_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__currency' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'price_period_heading',
            array(
                'label'     => esc_html__( 'Period', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'price_period_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__period',
            )
        );

        $this->add_control(
            'price_period_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__period' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'price_original_heading',
            array(
                'label'     => esc_html__( 'Original Price', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'price_original_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__original' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Features
        $this->start_controls_section(
            'section_style_features',
            array(
                'label' => esc_html__( 'Features', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'features_padding',
            array(
                'label'      => esc_html__( 'Container Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-pricing-table__features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'features_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__feature',
            )
        );

        $this->add_control(
            'features_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__feature' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'features_unavailable_color',
            array(
                'label'     => esc_html__( 'Unavailable Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__feature--unavailable' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'features_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__feature-icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'features_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-pricing-table__feature-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .acst-pricing-table__feature-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'features_spacing',
            array(
                'label'      => esc_html__( 'Item Spacing', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-pricing-table__feature' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'features_divider',
            array(
                'label'        => esc_html__( 'Show Divider', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'selectors'    => array(
                    '{{WRAPPER}} .acst-pricing-table__feature' => 'border-bottom: 1px solid #eee;',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Button
        $this->start_controls_section(
            'section_style_button',
            array(
                'label' => esc_html__( 'Button', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_button_style_controls( 'button', '{{WRAPPER}} .acst-pricing-table__button' );

        $this->end_controls_section();

        // Style: Ribbon
        $this->start_controls_section(
            'section_style_ribbon',
            array(
                'label'     => esc_html__( 'Ribbon', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'featured' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'ribbon_typography',
                'selector' => '{{WRAPPER}} .acst-pricing-table__ribbon',
            )
        );

        $this->add_control(
            'ribbon_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__ribbon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'ribbon_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-pricing-table__ribbon' => 'background-color: {{VALUE}};',
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

        $this->add_render_attribute( 'wrapper', 'class', array(
            'acst-pricing-table',
            $settings['featured'] === 'yes' ? 'acst-pricing-table--featured' : '',
        ) );

        $this->add_render_attribute( 'button', 'class', 'acst-pricing-table__button acst-button' );

        if ( ! empty( $settings['button_link']['url'] ) ) {
            $this->add_link_attributes( 'button', $settings['button_link'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( $settings['featured'] === 'yes' && ! empty( $settings['ribbon_text'] ) ) : ?>
                <div class="acst-pricing-table__ribbon">
                    <?php echo esc_html( $settings['ribbon_text'] ); ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="acst-pricing-table__header">
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h3 class="acst-pricing-table__title">
                        <?php echo esc_html( $settings['title'] ); ?>
                    </h3>
                <?php endif; ?>

                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <div class="acst-pricing-table__subtitle">
                        <?php echo esc_html( $settings['subtitle'] ); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="acst-pricing-table__price">
                <?php if ( ! empty( $settings['original_price'] ) ) : ?>
                    <span class="acst-pricing-table__original">
                        <?php if ( $settings['currency_position'] === 'before' ) : ?>
                            <span class="acst-pricing-table__currency"><?php echo esc_html( $settings['currency'] ); ?></span>
                        <?php endif; ?>
                        <?php echo esc_html( $settings['original_price'] ); ?>
                        <?php if ( $settings['currency_position'] === 'after' ) : ?>
                            <span class="acst-pricing-table__currency"><?php echo esc_html( $settings['currency'] ); ?></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>

                <span class="acst-pricing-table__amount-wrapper">
                    <?php if ( $settings['currency_position'] === 'before' ) : ?>
                        <span class="acst-pricing-table__currency"><?php echo esc_html( $settings['currency'] ); ?></span>
                    <?php endif; ?>

                    <span class="acst-pricing-table__amount"><?php echo esc_html( $settings['price'] ); ?></span>

                    <?php if ( $settings['currency_position'] === 'after' ) : ?>
                        <span class="acst-pricing-table__currency"><?php echo esc_html( $settings['currency'] ); ?></span>
                    <?php endif; ?>
                </span>

                <?php if ( ! empty( $settings['period'] ) ) : ?>
                    <span class="acst-pricing-table__period"><?php echo esc_html( $settings['period'] ); ?></span>
                <?php endif; ?>
            </div>

            <!-- Features -->
            <?php if ( ! empty( $settings['features'] ) ) : ?>
                <ul class="acst-pricing-table__features">
                    <?php foreach ( $settings['features'] as $feature ) :
                        $available = $feature['feature_available'] === 'yes';
                    ?>
                        <li class="acst-pricing-table__feature<?php echo ! $available ? ' acst-pricing-table__feature--unavailable' : ''; ?>">
                            <?php if ( ! empty( $feature['feature_icon']['value'] ) ) : ?>
                                <span class="acst-pricing-table__feature-icon">
                                    <?php Icons_Manager::render_icon( $feature['feature_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                                </span>
                            <?php endif; ?>
                            <span class="acst-pricing-table__feature-text">
                                <?php echo esc_html( $feature['feature_text'] ); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Button -->
            <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                <div class="acst-pricing-table__footer">
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                        <?php if ( ! empty( $settings['button_icon']['value'] ) && $settings['button_icon_position'] === 'before' ) : ?>
                            <span class="acst-button__icon acst-button__icon--before">
                                <?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                            </span>
                        <?php endif; ?>

                        <span class="acst-button__text"><?php echo esc_html( $settings['button_text'] ); ?></span>

                        <?php if ( ! empty( $settings['button_icon']['value'] ) && $settings['button_icon_position'] === 'after' ) : ?>
                            <span class="acst-button__icon acst-button__icon--after">
                                <?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
