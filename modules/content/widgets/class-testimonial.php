<?php
/**
 * Testimonial Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content\Widgets;

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
        return 'acst-testimonial';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Testimonial', 'ac-starter-toolkit' );
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
                'label' => esc_html__( 'Testimonial', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'   => esc_html__( 'Default', 'ac-starter-toolkit' ),
                    'bubble'    => esc_html__( 'Speech Bubble', 'ac-starter-toolkit' ),
                    'centered'  => esc_html__( 'Centered', 'ac-starter-toolkit' ),
                    'side'      => esc_html__( 'Side by Side', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Image', 'ac-starter-toolkit' ),
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
                'label'   => esc_html__( 'Name', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'John Doe', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'   => esc_html__( 'Title/Position', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'CEO, Company Name', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'content',
            array(
                'label'   => esc_html__( 'Content', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'ac-starter-toolkit' ),
                'rows'    => 5,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'show_rating',
            array(
                'label'        => esc_html__( 'Show Rating', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'rating',
            array(
                'label'     => esc_html__( 'Rating', 'ac-starter-toolkit' ),
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
                'label'     => esc_html__( 'Rating Position', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'before_content',
                'options'   => array(
                    'before_content' => esc_html__( 'Before Content', 'ac-starter-toolkit' ),
                    'after_content'  => esc_html__( 'After Content', 'ac-starter-toolkit' ),
                    'before_name'    => esc_html__( 'Before Name', 'ac-starter-toolkit' ),
                    'after_name'     => esc_html__( 'After Name', 'ac-starter-toolkit' ),
                ),
                'condition' => array(
                    'show_rating' => 'yes',
                ),
            )
        );

        $this->add_control(
            'show_quote_icon',
            array(
                'label'        => esc_html__( 'Show Quote Icon', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            )
        );

        $this->add_control(
            'quote_icon',
            array(
                'label'     => esc_html__( 'Quote Icon', 'ac-starter-toolkit' ),
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
                'label' => esc_html__( 'Link', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'link_type',
            array(
                'label'   => esc_html__( 'Link Type', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => array(
                    'none'   => esc_html__( 'None', 'ac-starter-toolkit' ),
                    'box'    => esc_html__( 'Whole Box', 'ac-starter-toolkit' ),
                    'name'   => esc_html__( 'Name Only', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ac-starter-toolkit' ),
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
                'label' => esc_html__( 'Box', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_box_style_controls( 'box', '{{WRAPPER}} .acst-testimonial' );

        $this->add_responsive_control(
            'box_alignment',
            array(
                'label'     => esc_html__( 'Alignment', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'left'   => array(
                        'title' => esc_html__( 'Left', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right'  => array(
                        'title' => esc_html__( 'Right', 'ac-starter-toolkit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .acst-testimonial' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Image Style.
        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_size',
            array(
                'label'      => esc_html__( 'Size', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-testimonial__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-testimonial__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .acst-testimonial--side .acst-testimonial__image' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
                ),
            )
        );

        $this->end_controls_section();

        // Content Style.
        $this->start_controls_section(
            'section_style_content',
            array(
                'label' => esc_html__( 'Content', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'content', '{{WRAPPER}} .acst-testimonial__content' );

        $this->add_control(
            'content_font_style',
            array(
                'label'     => esc_html__( 'Font Style', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'normal',
                'options'   => array(
                    'normal' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
                    'italic' => esc_html__( 'Italic', 'ac-starter-toolkit' ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .acst-testimonial__content' => 'font-style: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Name Style.
        $this->start_controls_section(
            'section_style_name',
            array(
                'label' => esc_html__( 'Name', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_title_style_controls( 'name', '{{WRAPPER}} .acst-testimonial__name' );

        $this->end_controls_section();

        // Title Style.
        $this->start_controls_section(
            'section_style_title',
            array(
                'label' => esc_html__( 'Title/Position', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'title', '{{WRAPPER}} .acst-testimonial__title' );

        $this->end_controls_section();

        // Rating Style.
        $this->start_controls_section(
            'section_style_rating',
            array(
                'label'     => esc_html__( 'Rating', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_rating' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'rating_size',
            array(
                'label'      => esc_html__( 'Size', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-testimonial__rating' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'rating_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffc107',
                'selectors' => array(
                    '{{WRAPPER}} .acst-testimonial__rating .acst-star--filled' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'rating_unmarked_color',
            array(
                'label'     => esc_html__( 'Unmarked Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e0e0e0',
                'selectors' => array(
                    '{{WRAPPER}} .acst-testimonial__rating .acst-star--empty' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'rating_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-testimonial__rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Quote Icon Style.
        $this->start_controls_section(
            'section_style_quote',
            array(
                'label'     => esc_html__( 'Quote Icon', 'ac-starter-toolkit' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_quote_icon' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'quote_size',
            array(
                'label'      => esc_html__( 'Size', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-testimonial__quote' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'quote_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-testimonial__quote' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'quote_opacity',
            array(
                'label'     => esc_html__( 'Opacity', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-testimonial__quote' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'quote_position',
            array(
                'label'     => esc_html__( 'Position', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'top-left',
                'options'   => array(
                    'top-left'     => esc_html__( 'Top Left', 'ac-starter-toolkit' ),
                    'top-right'    => esc_html__( 'Top Right', 'ac-starter-toolkit' ),
                    'bottom-left'  => esc_html__( 'Bottom Left', 'ac-starter-toolkit' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'ac-starter-toolkit' ),
                    'inline'       => esc_html__( 'Inline (Before Content)', 'ac-starter-toolkit' ),
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
            'acst-testimonial',
            'acst-testimonial--' . $layout,
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
                <div class="acst-testimonial__quote acst-testimonial__quote--<?php echo esc_attr( $quote_position ); ?>">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['quote_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( 'side' === $layout ) : ?>
                <div class="acst-testimonial__aside">
                    <?php $this->render_image( $settings ); ?>
                </div>
                <div class="acst-testimonial__main">
                    <?php $this->render_content_section( $settings ); ?>
                    <?php $this->render_author_section( $settings ); ?>
                </div>
            <?php else : ?>
                <?php if ( 'bubble' !== $layout ) : ?>
                    <?php $this->render_image( $settings ); ?>
                <?php endif; ?>

                <?php $this->render_content_section( $settings ); ?>

                <?php if ( 'bubble' === $layout ) : ?>
                    <div class="acst-testimonial__author-wrap">
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
        <div class="acst-testimonial__image">
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
        <div class="acst-testimonial__content-wrap">
            <?php if ( 'yes' === $settings['show_rating'] && 'before_content' === $rating_position ) : ?>
                <?php $this->render_rating( $settings ); ?>
            <?php endif; ?>

            <div class="acst-testimonial__content">
                <?php if ( 'yes' === $settings['show_quote_icon'] && 'inline' === $quote_position ) : ?>
                    <span class="acst-testimonial__quote acst-testimonial__quote--inline">
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
        <div class="acst-testimonial__author">
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
                <<?php echo esc_attr( $name_tag ); ?> class="acst-testimonial__name" <?php echo ( 'a' === $name_tag ) ? $this->get_render_attribute_string( 'name_link' ) : ''; ?>>
                    <?php echo esc_html( $settings['name'] ); ?>
                </<?php echo esc_attr( $name_tag ); ?>>
            <?php endif; ?>

            <?php if ( ! empty( $settings['title'] ) ) : ?>
                <div class="acst-testimonial__title">
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
        <div class="acst-testimonial__rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'ac-starter-toolkit' ), $rating ) ); ?>">
            <?php
            for ( $i = 1; $i <= 5; $i++ ) {
                if ( $i <= $rating ) {
                    echo '<span class="acst-star acst-star--filled">&#9733;</span>';
                } elseif ( $i - 0.5 <= $rating ) {
                    echo '<span class="acst-star acst-star--half">&#9733;</span>';
                } else {
                    echo '<span class="acst-star acst-star--empty">&#9734;</span>';
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

            var imageHtml = '<div class="acst-testimonial__image">';
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
            var html = '<div class="acst-testimonial__rating">';
            for ( var i = 1; i <= 5; i++ ) {
                if ( i <= rating ) {
                    html += '<span class="acst-star acst-star--filled">&#9733;</span>';
                } else if ( i - 0.5 <= rating ) {
                    html += '<span class="acst-star acst-star--half">&#9733;</span>';
                } else {
                    html += '<span class="acst-star acst-star--empty">&#9734;</span>';
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
            return '<div class="acst-testimonial__quote acst-testimonial__quote--' + posClass + '">' + ( iconHtml.value || '' ) + '</div>';
        }
        #>
        <{{{ linkTag }}} class="acst-testimonial acst-testimonial--{{{ layout }}}">

            {{{ renderQuoteIcon( 'positioned' ) }}}

            <# if ( 'side' === layout ) { #>
                <div class="acst-testimonial__aside">
                    {{{ renderImage() }}}
                </div>
                <div class="acst-testimonial__main">
                    <div class="acst-testimonial__content-wrap">
                        <# if ( 'yes' === settings.show_rating && 'before_content' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <div class="acst-testimonial__content">
                            {{{ renderQuoteIcon( 'inline' ) }}}
                            {{{ settings.content }}}
                        </div>
                        <# if ( 'yes' === settings.show_rating && 'after_content' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                    </div>
                    <div class="acst-testimonial__author">
                        <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <# if ( settings.name ) { #>
                            <div class="acst-testimonial__name">{{{ settings.name }}}</div>
                        <# } #>
                        <# if ( settings.title ) { #>
                            <div class="acst-testimonial__title">{{{ settings.title }}}</div>
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

                <div class="acst-testimonial__content-wrap">
                    <# if ( 'yes' === settings.show_rating && 'before_content' === settings.rating_position ) { #>
                        {{{ renderRating() }}}
                    <# } #>
                    <div class="acst-testimonial__content">
                        {{{ renderQuoteIcon( 'inline' ) }}}
                        {{{ settings.content }}}
                    </div>
                    <# if ( 'yes' === settings.show_rating && 'after_content' === settings.rating_position ) { #>
                        {{{ renderRating() }}}
                    <# } #>
                </div>

                <# if ( 'bubble' === layout ) { #>
                    <div class="acst-testimonial__author-wrap">
                        {{{ renderImage() }}}
                        <div class="acst-testimonial__author">
                            <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                                {{{ renderRating() }}}
                            <# } #>
                            <# if ( settings.name ) { #>
                                <div class="acst-testimonial__name">{{{ settings.name }}}</div>
                            <# } #>
                            <# if ( settings.title ) { #>
                                <div class="acst-testimonial__title">{{{ settings.title }}}</div>
                            <# } #>
                            <# if ( 'yes' === settings.show_rating && 'after_name' === settings.rating_position ) { #>
                                {{{ renderRating() }}}
                            <# } #>
                        </div>
                    </div>
                <# } else { #>
                    <div class="acst-testimonial__author">
                        <# if ( 'yes' === settings.show_rating && 'before_name' === settings.rating_position ) { #>
                            {{{ renderRating() }}}
                        <# } #>
                        <# if ( settings.name ) { #>
                            <div class="acst-testimonial__name">{{{ settings.name }}}</div>
                        <# } #>
                        <# if ( settings.title ) { #>
                            <div class="acst-testimonial__title">{{{ settings.title }}}</div>
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
