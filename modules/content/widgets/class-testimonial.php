<?php
/**
 * Testimonial Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Content\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Testimonial Widget Class
 */
class Testimonial extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-testimonial';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Testimonial', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-testimonial';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'testimonial', 'review', 'quote', 'rating', 'feedback' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Register content controls
     */
    protected function register_content_controls() {
        // Content Section.
        $this->start_controls_section(
            'section_testimonial',
            array(
                'label' => esc_html__( 'Testimonial', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'   => esc_html__( 'Default', 'pressiq-widgets' ),
                    'bubble'    => esc_html__( 'Speech Bubble', 'pressiq-widgets' ),
                    'centered'  => esc_html__( 'Centered', 'pressiq-widgets' ),
                    'side'      => esc_html__( 'Side by Side', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'pressiq-widgets' ),
                'type'    => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'    => 'image',
                'default' => 'thumbnail',
            )
        );

        $this->add_control(
            'name',
            array(
                'label'   => esc_html__( 'Name', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'John Doe', 'pressiq-widgets' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => esc_html__( 'Title/Position', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'CEO, Company Name', 'pressiq-widgets' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'content',
            array(
                'label'   => esc_html__( 'Content', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'pressiq-widgets' ),
                'rows'    => 5,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'show_rating',
            array(
                'label'        => esc_html__( 'Show Rating', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'rating',
            array(
                'label'     => esc_html__( 'Rating', 'pressiq-widgets' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 5,
                'step'      => 0.5,
                'default'   => 5,
                'condition' => array(
                    'show_rating' => 'yes',
                ),
            )
        );

        $this->add_control(
            'rating_position',
            array(
                'label'     => esc_html__( 'Rating Position', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'before_content',
                'options'   => array(
                    'before_content' => esc_html__( 'Before Content', 'pressiq-widgets' ),
                    'after_content'  => esc_html__( 'After Content', 'pressiq-widgets' ),
                    'before_name'    => esc_html__( 'Before Name', 'pressiq-widgets' ),
                    'after_name'     => esc_html__( 'After Name', 'pressiq-widgets' ),
                ),
                'condition' => array(
                    'show_rating' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_quote_icon',
            array(
                'label'        => esc_html__( 'Show Quote Icon', 'pressiq-widgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'pressiq-widgets' ),
                'label_off'    => esc_html__( 'No', 'pressiq-widgets' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'quote_icon',
            array(
                'label'     => esc_html__( 'Quote Icon', 'pressiq-widgets' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => array(
                    'value'   => 'fas fa-quote-left',
                    'library' => 'fa-solid',
                ),
                'condition' => array(
                    'show_quote_icon' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Link Section.
        $this->start_controls_section(
            'section_link',
            array(
                'label' => esc_html__( 'Link', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'link_type',
            array(
                'label'   => esc_html__( 'Link Type', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'   => esc_html__( 'None', 'pressiq-widgets' ),
                    'box'    => esc_html__( 'Whole Box', 'pressiq-widgets' ),
                    'name'   => esc_html__( 'Name Only', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link', 'pressiq-widgets' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'pressiq-widgets' ),
                'default'     => array(
                    'url' => '',
                ),
                'condition'   => array(
                    'link_type!' => 'none',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls
     */
    protected function register_style_controls() {
        // Box Style.
        $this->start_controls_section(
            'section_style_box',
            array(
                'label' => esc_html__( 'Box', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_box_style_controls( 'box', '{{WRAPPER}} .pressiq-testimonial' );

        $this->add_responsive_control(
            'box_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'pressiq-widgets' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'pressiq-widgets' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'pressiq-widgets' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'pressiq-widgets' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Image Style.
        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', 'rem' ),
                'range'      => array(
                    'px' => array(
                        'min' => 20,
                        'max' => 200,
                    ),
                ),
                'default'    => array(
                    'size' => 80,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'default'    => array(
                    'top'    => '50',
                    'right'  => '50',
                    'bottom' => '50',
                    'left'   => '50',
                    'unit'   => '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-testimonial--side .pressiq-testimonial__image' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
                ),
            )
        );

        $this->end_controls_section();

        // Content Style.
        $this->start_controls_section(
            'section_style_content',
            array(
                'label' => esc_html__( 'Content', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'content', '{{WRAPPER}} .pressiq-testimonial__content' );

        $this->add_control(
            'content_font_style',
            array(
                'label'     => esc_html__( 'Font Style', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'normal',
                'options'   => array(
                    'normal' => esc_html__( 'Normal', 'pressiq-widgets' ),
                    'italic' => esc_html__( 'Italic', 'pressiq-widgets' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial__content' => 'font-style: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Name Style.
        $this->start_controls_section(
            'section_style_name',
            array(
                'label' => esc_html__( 'Name', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_title_style_controls( 'name', '{{WRAPPER}} .pressiq-testimonial__name' );

        $this->end_controls_section();

        // Title Style.
        $this->start_controls_section(
            'section_style_title',
            array(
                'label' => esc_html__( 'Title/Position', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'title', '{{WRAPPER}} .pressiq-testimonial__title' );

        $this->end_controls_section();

        // Rating Style.
        $this->start_controls_section(
            'section_style_rating',
            array(
                'label'     => esc_html__( 'Rating', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_rating' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'rating_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'rating_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffc107',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial__rating .pressiq-star--filled' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'rating_unmarked_color',
            array(
                'label'     => esc_html__( 'Unmarked Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e0e0e0',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial__rating .pressiq-star--empty' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'rating_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Quote Icon Style.
        $this->start_controls_section(
            'section_style_quote',
            array(
                'label'     => esc_html__( 'Quote Icon', 'pressiq-widgets' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_quote_icon' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'quote_size',
            array(
                'label'      => esc_html__( 'Size', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'size' => 40,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-testimonial__quote' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'quote_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial__quote' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'quote_opacity',
            array(
                'label'     => esc_html__( 'Opacity', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1,
                    ),
                ),
                'default'   => array(
                    'size' => 0.2,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-testimonial__quote' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'quote_position',
            array(
                'label'     => esc_html__( 'Position', 'pressiq-widgets' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top-left',
                'options'   => array(
                    'top-left'     => esc_html__( 'Top Left', 'pressiq-widgets' ),
                    'top-right'    => esc_html__( 'Top Right', 'pressiq-widgets' ),
                    'bottom-left'  => esc_html__( 'Bottom Left', 'pressiq-widgets' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'pressiq-widgets' ),
                    'inline'       => esc_html__( 'Inline (Before Content)', 'pressiq-widgets' ),
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

        $layout = $settings['layout'];

        $this->add_render_attribute( 'wrapper', 'class', array(
            'pressiq-testimonial',
            'pressiq-testimonial--' . $layout,
        ) );

        // Box link.
        $link_tag = 'div';
        if ( 'box' === $settings['link_type'] && ! empty( $settings['link']['url'] ) ) {
            $link_tag = 'a';
            $this->add_link_attributes( 'wrapper', $settings['link'] );
        }

        $quote_position = $settings['quote_position'] ?? 'top-left';
        ?>
        <<?php echo esc_attr( $link_tag ); ?> <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

            <?php if ( 'yes' === $settings['show_quote_icon'] && 'inline' !== $quote_position ) : ?>
                <div class="pressiq-testimonial__quote pressiq-testimonial__quote--<?php echo esc_attr( $quote_position ); ?>">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( 'side' === $layout ) : ?>
                <div class="pressiq-testimonial__aside">
                    <?php $this->render_image( $settings ); ?>
                </div>
                <div class="pressiq-testimonial__main">
                    <?php $this->render_content_section( $settings ); ?>
                    <?php $this->render_author_section( $settings ); ?>
                </div>
            <?php else : ?>
                <?php if ( 'bubble' !== $layout ) : ?>
                    <?php $this->render_image( $settings ); ?>
                <?php endif; ?>

                <?php $this->render_content_section( $settings ); ?>

                <?php if ( 'bubble' === $layout ) : ?>
                    <div class="pressiq-testimonial__author-wrap">
                        <?php $this->render_image( $settings ); ?>
                        <?php $this->render_author_section( $settings ); ?>
                    </div>
                <?php else : ?>
                    <?php $this->render_author_section( $settings ); ?>
                <?php endif; ?>
            <?php endif; ?>

        </<?php echo esc_attr( $link_tag ); ?>>
        <?php
    }

    /**
     * Render image
     *
     * @param array $settings Widget settings.
     */
    protected function render_image( $settings ) {
        if ( empty( $settings['image']['url'] ) ) {
            return;
        }
        ?>
        <div class="pressiq-testimonial__image">
            <?php
            echo wp_kses_post(
                \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' )
            );
            ?>
        </div>
        <?php
    }

    /**
     * Render content section
     *
     * @param array $settings Widget settings.
     */
    protected function render_content_section( $settings ) {
        $rating_position = $settings['rating_position'];
        $quote_position  = $settings['quote_position'] ?? 'top-left';
        ?>
        <div class="pressiq-testimonial__content-wrap">
            <?php if ( 'yes' === $settings['show_rating'] && 'before_content' === $rating_position ) : ?>
                <?php $this->render_rating( $settings ); ?>
            <?php endif; ?>

            <div class="pressiq-testimonial__content">
                <?php if ( 'yes' === $settings['show_quote_icon'] && 'inline' === $quote_position ) : ?>
                    <span class="pressiq-testimonial__quote pressiq-testimonial__quote--inline">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                    </span>
                <?php endif; ?>
                <?php echo wp_kses_post( $settings['content'] ); ?>
            </div>

            <?php if ( 'yes' === $settings['show_rating'] && 'after_content' === $rating_position ) : ?>
                <?php $this->render_rating( $settings ); ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render author section
     *
     * @param array $settings Widget settings.
     */
    protected function render_author_section( $settings ) {
        $rating_position = $settings['rating_position'];
        ?>
        <div class="pressiq-testimonial__author">
            <?php if ( 'yes' === $settings['show_rating'] && 'before_name' === $rating_position ) : ?>
                <?php $this->render_rating( $settings ); ?>
            <?php endif; ?>

            <?php if ( ! empty( $settings['name'] ) ) : ?>
                <?php
                $name_tag = 'div';
                if ( 'name' === $settings['link_type'] && ! empty( $settings['link']['url'] ) ) {
                    $name_tag = 'a';
                    $this->add_link_attributes( 'name_link', $settings['link'] );
                }
                ?>
                <<?php echo esc_attr( $name_tag ); ?> class="pressiq-testimonial__name" <?php echo ( 'a' === $name_tag ) ? $this->get_render_attribute_string( 'name_link' ) : ''; ?>>
                    <?php echo esc_html( $settings['name'] ); ?>
                </<?php echo esc_attr( $name_tag ); ?>>
            <?php endif; ?>

            <?php if ( ! empty( $settings['title'] ) ) : ?>
                <div class="pressiq-testimonial__title">
                    <?php echo esc_html( $settings['title'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( 'yes' === $settings['show_rating'] && 'after_name' === $rating_position ) : ?>
                <?php $this->render_rating( $settings ); ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render rating stars
     *
     * @param array $settings Widget settings.
     */
    protected function render_rating( $settings ) {
        $rating = floatval( $settings['rating'] );
        ?>
        <div class="pressiq-testimonial__rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'pressiq-widgets' ), $rating ) ); ?>">
            <?php
            for ( $i = 1; $i <= 5; $i++ ) {
                if ( $i <= $rating ) {
                    echo '<span class="pressiq-star pressiq-star--filled">&#9733;</span>';
                } elseif ( $i - 0.5 <= $rating ) {
                    echo '<span class="pressiq-star pressiq-star--half">&#9733;</span>';
                } else {
                    echo '<span class="pressiq-star pressiq-star--empty">&#9734;</span>';
                }
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var layout = settings.layout;
        var linkTag = 'div';
        var quotePosition = settings.quote_position || 'top-left';

        if ( 'box' === settings.link_type && settings.link.url ) {
            linkTag = 'a';
        }

        function renderImage() {
            if ( ! settings.image.url ) return '';

            var imageHtml = '<div class="pressiq-testimonial__image">';
            var image = {
                id: settings.image.id,
                url: settings.image.url,
                size: settings.image_size,
                dimension: settings.image_custom_dimension,
                model: view.getEditModel()
            };
            var imageUrl = elementor.imagesManager.getImageUrl( image );
            imageHtml += '<img src="' + imageUrl + '" />';
            imageHtml += '</div>';
            return imageHtml;
        }

        function renderRating() {
            var rating = parseFloat( settings.rating );
            var html = '<div class="pressiq-testimonial__rating">';
            for ( var i = 1; i <= 5; i++ ) {
                if ( i <= rating ) {
                    html += '<span class="pressiq-star pressiq-star--filled">&#9733;</span>';
                } else if ( i - 0.5 <= rating ) {
                    html += '<span class="pressiq-star pressiq-star--half">&#9733;</span>';
                } else {
                    html += '<span class="pressiq-star pressiq-star--empty">&#9734;</span>';
                }
            }
            html += '</div>';
            return html;
        }

        function renderQuoteIcon( position ) {
            if ( 'yes' !== settings.show_quote_icon ) return '';
            if ( position === 'inline' && quotePosition !== 'inline' ) return '';
            if ( position === 'positioned' && quotePosition === 'inline' ) return '';

            var iconHtml = elementor.helpers.renderIcon( view, settings.quote_icon, { 'aria-hidden': 'true' }, 'i', 'object' );
            var posClass = position === 'inline' ? 'inline' : quotePosition;
            return '<div class="pressiq-testimonial__quote pressiq-testimonial__quote--' + posClass + '">' + ( iconHtml.value || '' ) + '</div>';
        }
        #>
        <{{{ linkTag }}} class="pressiq-testimonial pressiq-testimonial--{{{ layout }}}">

            {{{ renderQuoteIcon( 'positioned' ) }}}

            <# if ( 'side' === layout ) { #>
                <div class="pressiq-testimonial__aside">
                    {{{ renderImage() }}}
                </div>
                <div class="pressiq-testimonial__main">
                    <div class="pressiq-testimonial__content-wrap">
                        <# if ( 'yes' === settings.show_rating && 'before_content' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <div class="pressiq-testimonial__content">
                            {{{ renderQuoteIcon( 'inline' ) }}}
                            {{{ settings.content }}}
                        </div>
                        <# if ( 'yes' === settings.show_rating && 'after_content' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                    </div>
                    <div class="pressiq-testimonial__author">
                        <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <# if ( settings.name ) { #>
                            <div class="pressiq-testimonial__name">{{{ settings.name }}}</div>
                        <# } #>
                        <# if ( settings.title ) { #>
                            <div class="pressiq-testimonial__title">{{{ settings.title }}}</div>
                        <# } #>
                        <# if ( 'yes' === settings.show_rating && 'after_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                    </div>
                </div>
            <# } else { #>
                <# if ( 'bubble' !== layout ) { #>
                    {{{ renderImage() }}}
                <# } #>

                <div class="pressiq-testimonial__content-wrap">
                    <# if ( 'yes' === settings.show_rating && 'before_content' === settings.rating_position ) { #>
                        {{{ renderRating() }}}
                    <# } #>
                    <div class="pressiq-testimonial__content">
                        {{{ renderQuoteIcon( 'inline' ) }}}
                        {{{ settings.content }}}
                    </div>
                    <# if ( 'yes' === settings.show_rating && 'after_content' === settings.rating_position ) { #>
                        {{{ renderRating() }}}
                    <# } #>
                </div>

                <# if ( 'bubble' === layout ) { #>
                    <div class="pressiq-testimonial__author-wrap">
                        {{{ renderImage() }}}
                        <div class="pressiq-testimonial__author">
                            <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                                {{{ renderRating() }}}
                            <# } #>
                            <# if ( settings.name ) { #>
                                <div class="pressiq-testimonial__name">{{{ settings.name }}}</div>
                            <# } #>
                            <# if ( settings.title ) { #>
                                <div class="pressiq-testimonial__title">{{{ settings.title }}}</div>
                            <# } #>
                            <# if ( 'yes' === settings.show_rating && 'after_name' === settings.rating_position ) { #>
                                {{{ renderRating() }}}
                            <# } #>
                        </div>
                    </div>
                <# } else { #>
                    <div class="pressiq-testimonial__author">
                        <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <# if ( settings.name ) { #>
                            <div class="pressiq-testimonial__name">{{{ settings.name }}}</div>
                        <# } #>
                        <# if ( settings.title ) { #>
                            <div class="pressiq-testimonial__title">{{{ settings.title }}}</div>
                        <# } #>
                        <# if ( 'yes' === settings.show_rating && 'after_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                    </div>
                <# } #>
            <# } #>

        </{{{ linkTag }}}>
        <?php
    }
}
