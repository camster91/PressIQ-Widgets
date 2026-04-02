<?php
/**
 * Team Member Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Content\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Team Member Widget Class
 *
 * Display team member profiles with photo, name, role, bio, and social links.
 */
class Team_Member extends Content_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-team-member';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Team Member', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-person';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'team', 'member', 'staff', 'employee', 'profile', 'person' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Photo', 'pressiq-widgets' ),
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
                'default' => 'medium',
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
            'name_tag',
            array(
                'label'   => esc_html__( 'Name HTML Tag', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => array(
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                ),
            )
        );

        $this->add_control(
            'role',
            array(
                'label'   => esc_html__( 'Role / Position', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Designer', 'pressiq-widgets' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'bio',
            array(
                'label'   => esc_html__( 'Bio / Description', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'A short description about this team member.', 'pressiq-widgets' ),
                'rows'    => 4,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link', 'pressiq-widgets' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'pressiq-widgets' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->end_controls_section();

        // Social Links Section
        $this->start_controls_section(
            'section_social',
            array(
                'label' => esc_html__( 'Social Links', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_network',
            array(
                'label'   => esc_html__( 'Network', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'facebook',
                'options' => array(
                    'facebook'  => esc_html__( 'Facebook', 'pressiq-widgets' ),
                    'twitter'   => esc_html__( 'Twitter/X', 'pressiq-widgets' ),
                    'instagram' => esc_html__( 'Instagram', 'pressiq-widgets' ),
                    'linkedin'  => esc_html__( 'LinkedIn', 'pressiq-widgets' ),
                    'youtube'   => esc_html__( 'YouTube', 'pressiq-widgets' ),
                    'tiktok'    => esc_html__( 'TikTok', 'pressiq-widgets' ),
                    'pinterest' => esc_html__( 'Pinterest', 'pressiq-widgets' ),
                    'github'    => esc_html__( 'GitHub', 'pressiq-widgets' ),
                    'dribbble'  => esc_html__( 'Dribbble', 'pressiq-widgets' ),
                    'behance'   => esc_html__( 'Behance', 'pressiq-widgets' ),
                    'email'     => esc_html__( 'Email', 'pressiq-widgets' ),
                    'website'   => esc_html__( 'Website', 'pressiq-widgets' ),
                ),
            )
        );

        $repeater->add_control(
            'social_link',
            array(
                'label'       => esc_html__( 'Link', 'pressiq-widgets' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'pressiq-widgets' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'social_links',
            array(
                'label'       => esc_html__( 'Social Links', 'pressiq-widgets' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'social_network' => 'facebook',
                        'social_link'    => array( 'url' => '#' ),
                    ),
                    array(
                        'social_network' => 'twitter',
                        'social_link'    => array( 'url' => '#' ),
                    ),
                    array(
                        'social_network' => 'linkedin',
                        'social_link'    => array( 'url' => '#' ),
                    ),
                ),
                'title_field' => '{{{ social_network }}}',
            )
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'section_layout',
            array(
                'label' => esc_html__( 'Layout', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'card',
                'options' => array(
                    'card'       => esc_html__( 'Card (Stacked)', 'pressiq-widgets' ),
                    'horizontal' => esc_html__( 'Horizontal', 'pressiq-widgets' ),
                    'overlay'    => esc_html__( 'Image Overlay', 'pressiq-widgets' ),
                ),
            )
        );

        $this->add_responsive_control(
            'alignment',
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
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Box
        $this->start_controls_section(
            'section_style_box',
            array(
                'label' => esc_html__( 'Box', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_box_style_controls( 'box', '{{WRAPPER}} .pressiq-team-member' );

        $this->end_controls_section();

        // Style: Image
        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_width',
            array(
                'label'      => esc_html__( 'Width', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 500,
                    ),
                    '%'  => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__image img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_height',
            array(
                'label'      => esc_html__( 'Height', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 500,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ),
            )
        );

        $this->add_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} .pressiq-team-member__image img',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .pressiq-team-member__image img',
            )
        );

        $this->add_responsive_control(
            'image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Name
        $this->start_controls_section(
            'section_style_name',
            array(
                'label' => esc_html__( 'Name', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_title_style_controls( 'name', '{{WRAPPER}} .pressiq-team-member__name' );

        $this->end_controls_section();

        // Style: Role
        $this->start_controls_section(
            'section_style_role',
            array(
                'label' => esc_html__( 'Role', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'role_typography',
                'selector' => '{{WRAPPER}} .pressiq-team-member__role',
            )
        );

        $this->add_control(
            'role_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member__role' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'role_margin',
            array(
                'label'      => esc_html__( 'Margin', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Bio
        $this->start_controls_section(
            'section_style_bio',
            array(
                'label' => esc_html__( 'Bio', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'bio', '{{WRAPPER}} .pressiq-team-member__bio' );

        $this->end_controls_section();

        // Style: Social Links
        $this->start_controls_section(
            'section_style_social',
            array(
                'label' => esc_html__( 'Social Links', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'social_icon_size',
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
                'default'    => array(
                    'size' => 16,
                    'unit' => 'px',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pressiq-team-member__social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'social_spacing',
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
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'social_style_tabs' );

        $this->start_controls_tab(
            'social_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'social_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'social_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'social_style_hover',
            array(
                'label' => esc_html__( 'Hover', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'social_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member__social a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'social_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'pressiq-widgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .pressiq-team-member__social a:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'social_padding',
            array(
                'label'      => esc_html__( 'Padding', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'social_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'social_margin',
            array(
                'label'      => esc_html__( 'Container Margin', 'pressiq-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-team-member__social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get social icon class
     *
     * @param string $network Social network name.
     * @return string Icon class.
     */
    private function get_social_icon( $network ) {
        $icons = array(
            'facebook'  => 'fab fa-facebook-f',
            'twitter'   => 'fab fa-x-twitter',
            'instagram' => 'fab fa-instagram',
            'linkedin'  => 'fab fa-linkedin-in',
            'youtube'   => 'fab fa-youtube',
            'tiktok'    => 'fab fa-tiktok',
            'pinterest' => 'fab fa-pinterest-p',
            'github'    => 'fab fa-github',
            'dribbble'  => 'fab fa-dribbble',
            'behance'   => 'fab fa-behance',
            'email'     => 'fas fa-envelope',
            'website'   => 'fas fa-globe',
        );

        return $icons[ $network ] ?? 'fas fa-link';
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $layout   = $settings['layout'];
        $has_link = ! empty( $settings['link']['url'] );

        $this->add_render_attribute( 'wrapper', 'class', array(
            'pressiq-team-member',
            'pressiq-team-member--' . $layout,
        ) );

        if ( $has_link ) {
            $this->add_link_attributes( 'link', $settings['link'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="pressiq-team-member__image">
                    <?php if ( $has_link ) : ?>
                        <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                    <?php endif; ?>

                    <?php
                    echo \Elementor\Group_Control_Image_Size::get_attachment_image_html(
                        $settings,
                        'image',
                        'image'
                    );
                    ?>

                    <?php if ( $has_link ) : ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $layout === 'overlay' ) : ?>
                        <div class="pressiq-team-member__overlay">
                            <?php $this->render_content( $settings, $has_link ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( $layout !== 'overlay' ) : ?>
                <div class="pressiq-team-member__content">
                    <?php $this->render_content( $settings, $has_link ); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render content elements
     *
     * @param array $settings Widget settings.
     * @param bool  $has_link Whether there's a link.
     */
    private function render_content( $settings, $has_link ) {
        $name_tag = $settings['name_tag'];
        ?>
        <?php if ( ! empty( $settings['name'] ) ) : ?>
            <<?php echo esc_attr( $name_tag ); ?> class="pressiq-team-member__name">
                <?php if ( $has_link ) : ?>
                    <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                        <?php echo esc_html( $settings['name'] ); ?>
                    </a>
                <?php else : ?>
                    <?php echo esc_html( $settings['name'] ); ?>
                <?php endif; ?>
            </<?php echo esc_attr( $name_tag ); ?>>
        <?php endif; ?>

        <?php if ( ! empty( $settings['role'] ) ) : ?>
            <div class="pressiq-team-member__role">
                <?php echo esc_html( $settings['role'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $settings['bio'] ) ) : ?>
            <div class="pressiq-team-member__bio">
                <?php echo wp_kses_post( $settings['bio'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $settings['social_links'] ) ) : ?>
            <div class="pressiq-team-member__social">
                <?php foreach ( $settings['social_links'] as $social ) :
                    $link = $social['social_link'];
                    $network = $social['social_network'];
                    $icon = $this->get_social_icon( $network );

                    // Handle email links
                    $url = $link['url'] ?? '#';
                    if ( $network === 'email' && strpos( $url, 'mailto:' ) !== 0 && strpos( $url, '@' ) !== false ) {
                        $url = 'mailto:' . $url;
                    }
                ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       <?php echo $link['is_external'] ? 'target="_blank"' : ''; ?>
                       <?php echo $link['nofollow'] ? 'rel="nofollow"' : ''; ?>
                       aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php
    }

    /**
     * Render plain content (for Elementor)
     */
    protected function content_template() {
        ?>
        <#
        var layoutClass = 'pressiq-team-member--' + settings.layout;
        var nameTag = settings.name_tag || 'h3';
        var hasLink = settings.link && settings.link.url;

        var getSocialIcon = function(network) {
            var icons = {
                'facebook': 'fab fa-facebook-f',
                'twitter': 'fab fa-x-twitter',
                'instagram': 'fab fa-instagram',
                'linkedin': 'fab fa-linkedin-in',
                'youtube': 'fab fa-youtube',
                'tiktok': 'fab fa-tiktok',
                'pinterest': 'fab fa-pinterest-p',
                'github': 'fab fa-github',
                'dribbble': 'fab fa-dribbble',
                'behance': 'fab fa-behance',
                'email': 'fas fa-envelope',
                'website': 'fas fa-globe'
            };
            return icons[network] || 'fas fa-link';
        };
        #>
        <div class="pressiq-team-member {{ layoutClass }}">
            <# if ( settings.image && settings.image.url ) { #>
                <div class="pressiq-team-member__image">
                    <# if ( hasLink ) { #>
                        <a href="{{ settings.link.url }}">
                    <# } #>
                    <img src="{{ settings.image.url }}">
                    <# if ( hasLink ) { #>
                        </a>
                    <# } #>
                </div>
            <# } #>

            <# if ( settings.layout !== 'overlay' ) { #>
                <div class="pressiq-team-member__content">
            <# } #>

            <# if ( settings.name ) { #>
                <{{ nameTag }} class="pressiq-team-member__name">
                    <# if ( hasLink ) { #>
                        <a href="{{ settings.link.url }}">{{{ settings.name }}}</a>
                    <# } else { #>
                        {{{ settings.name }}}
                    <# } #>
                </{{ nameTag }}>
            <# } #>

            <# if ( settings.role ) { #>
                <div class="pressiq-team-member__role">{{{ settings.role }}}</div>
            <# } #>

            <# if ( settings.bio ) { #>
                <div class="pressiq-team-member__bio">{{{ settings.bio }}}</div>
            <# } #>

            <# if ( settings.social_links && settings.social_links.length ) { #>
                <div class="pressiq-team-member__social">
                    <# _.each( settings.social_links, function( social ) {
                        var icon = getSocialIcon( social.social_network );
                        var url = social.social_link.url || '#';
                    #>
                        <a href="{{ url }}">
                            <i class="{{ icon }}"></i>
                        </a>
                    <# }); #>
                </div>
            <# } #>

            <# if ( settings.layout !== 'overlay' ) { #>
                </div>
            <# } #>
        </div>
        <?php
    }
}
