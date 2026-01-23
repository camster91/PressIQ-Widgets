<?php
/**
 * Content Widget Base
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content\Widgets;

use Elementor\Widget_Base;
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
 * Abstract Content Base Widget
 *
 * Provides common functionality for all content widgets.
 */
abstract class Content_Base extends Widget_Base {

    /**
     * Get widget categories
     *
     * @return array
     */
    public function get_categories() {
        return array( 'ac-starter-toolkit' );
    }

    /**
     * Get style dependencies
     *
     * @return array
     */
    public function get_style_depends() {
        return array( 'acst-content' );
    }

    /**
     * Get script dependencies
     *
     * @return array
     */
    public function get_script_depends() {
        return array( 'acst-content' );
    }

    /**
     * Register common box style controls
     *
     * @param string $prefix Control prefix.
     * @param string $selector CSS selector.
     */
    protected function register_box_style_controls( $prefix = 'box', $selector = '{{WRAPPER}} .acst-widget-box' ) {
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => $prefix . '_background',
                'types'    => array( 'classic', 'gradient' ),
                'selector' => $selector,
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => $prefix . '_border',
                'selector' => $selector,
            )
        );

        $this->add_responsive_control(
            $prefix . '_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => $prefix . '_box_shadow',
                'selector' => $selector,
            )
        );

        $this->add_responsive_control(
            $prefix . '_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
    }

    /**
     * Register common title style controls
     *
     * @param string $prefix Control prefix.
     * @param string $selector CSS selector.
     */
    protected function register_title_style_controls( $prefix = 'title', $selector = '{{WRAPPER}} .acst-widget-title' ) {
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => $prefix . '_typography',
                'selector' => $selector,
            )
        );

        $this->add_control(
            $prefix . '_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            $prefix . '_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
    }

    /**
     * Register common description style controls
     *
     * @param string $prefix Control prefix.
     * @param string $selector CSS selector.
     */
    protected function register_description_style_controls( $prefix = 'description', $selector = '{{WRAPPER}} .acst-widget-description' ) {
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => $prefix . '_typography',
                'selector' => $selector,
            )
        );

        $this->add_control(
            $prefix . '_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            $prefix . '_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    $selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
    }

    /**
     * Register button style controls
     *
     * @param string $prefix Control prefix.
     * @param string $selector CSS selector.
     */
    protected function register_button_style_controls( $prefix = 'button', $selector = '{{WRAPPER}} .acst-button' ) {
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => $prefix . '_typography',
                'selector' => $selector,
            )
        );

        $this->start_controls_tabs( $prefix . '_style_tabs' );

        // Normal state
        $this->start_controls_tab(
            $prefix . '_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            $prefix . '_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            $prefix . '_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        // Hover state
        $this->start_controls_tab(
            $prefix . '_style_hover',
            array(
                'label' => esc_html__( 'Hover', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            $prefix . '_hover_text_color',
            array(
                'label'     => esc_html__( 'Text Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector . ':hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            $prefix . '_hover_background_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    $selector . ':hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => $prefix . '_border',
                'selector'  => $selector,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            $prefix . '_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            $prefix . '_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
    }

    /**
     * Render icon
     *
     * @param array  $icon_settings Icon settings array.
     * @param array  $attributes    Additional attributes.
     * @param string $tag           HTML tag.
     */
    protected function render_icon( $icon_settings, $attributes = array(), $tag = 'span' ) {
        if ( empty( $icon_settings['value'] ) ) {
            return;
        }

        $attr_string = '';
        foreach ( $attributes as $key => $value ) {
            $attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }

        printf( '<%s class="acst-icon"%s>', esc_attr( $tag ), $attr_string );
        \Elementor\Icons_Manager::render_icon( $icon_settings, array( 'aria-hidden' => 'true' ) );
        printf( '</%s>', esc_attr( $tag ) );
    }
}
