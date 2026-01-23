<?php
/**
 * Team Member Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content\Widgets;

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
        return 'acst-team-member';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Team Member', 'ac-starter-toolkit' );
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
                'label' => esc_html__( 'Content', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'image',
            array(
                'label'   => esc_html__( 'Photo', 'ac-starter-toolkit' ),
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
                'label'   => esc_html__( 'Name', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'John Doe', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'name_tag',
            array(
                'label'   => esc_html__( 'Name HTML Tag', 'ac-starter-toolkit' ),
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
                'label'   => esc_html__( 'Role / Position', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Designer', 'ac-starter-toolkit' ),
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'bio',
            array(
                'label'   => esc_html__( 'Bio / Description', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'A short description about this team member.', 'ac-starter-toolkit' ),
                'rows'    => 4,
                'dynamic' => array( 'active' => true ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ac-starter-toolkit' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->end_controls_section();

        // Social Links Section
        $this->start_controls_section(
            'section_social',
            array(
                'label' => esc_html__( 'Social Links', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'social_network',
            array(
                'label'   => esc_html__( 'Network', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'facebook',
                'options' => array(
                    'facebook'  => esc_html__( 'Facebook', 'ac-starter-toolkit' ),
                    'twitter'   => esc_html__( 'Twitter/X', 'ac-starter-toolkit' ),
                    'instagram' => esc_html__( 'Instagram', 'ac-starter-toolkit' ),
                    'linkedin'  => esc_html__( 'LinkedIn', 'ac-starter-toolkit' ),
                    'youtube'   => esc_html__( 'YouTube', 'ac-starter-toolkit' ),
                    'tiktok'    => esc_html__( 'TikTok', 'ac-starter-toolkit' ),
                    'pinterest' => esc_html__( 'Pinterest', 'ac-starter-toolkit' ),
                    'github'    => esc_html__( 'GitHub', 'ac-starter-toolkit' ),
                    'dribbble'  => esc_html__( 'Dribbble', 'ac-starter-toolkit' ),
                    'behance'   => esc_html__( 'Behance', 'ac-starter-toolkit' ),
                    'email'     => esc_html__( 'Email', 'ac-starter-toolkit' ),
                    'website'   => esc_html__( 'Website', 'ac-starter-toolkit' ),
                ),
            )
        );

        $repeater->add_control(
            'social_link',
            array(
                'label'       => esc_html__( 'Link', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'ac-starter-toolkit' ),
                'dynamic'     => array( 'active' => true ),
            )
        );

        $this->add_control(
            'social_links',
            array(
                'label'       => esc_html__( 'Social Links', 'ac-starter-toolkit' ),
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
                'label' => esc_html__( 'Layout', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'card',
                'options' => array(
                    'card'       => esc_html__( 'Card (Stacked)', 'ac-starter-toolkit' ),
                    'horizontal' => esc_html__( 'Horizontal', 'ac-starter-toolkit' ),
                    'overlay'    => esc_html__( 'Image Overlay', 'ac-starter-toolkit' ),
                ),
            )
        );

        $this->add_responsive_control(
            'alignment',
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
                'default'   => 'center',
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member' => 'text-align: {{VALUE}};',
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

        $this->register_box_style_controls( 'box', '{{WRAPPER}} .acst-team-member' );

        $this->end_controls_section();

        // Style: Image
        $this->start_controls_section(
            'section_style_image',
            array(
                'label' => esc_html__( 'Image', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'image_width',
            array(
                'label'      => esc_html__( 'Width', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-team-member__image img' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'image_height',
            array(
                'label'      => esc_html__( 'Height', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 500,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ),
            )
        );

        $this->add_control(
            'image_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} .acst-team-member__image img',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .acst-team-member__image img',
            )
        );

        $this->add_responsive_control(
            'image_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Name
        $this->start_controls_section(
            'section_style_name',
            array(
                'label' => esc_html__( 'Name', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_title_style_controls( 'name', '{{WRAPPER}} .acst-team-member__name' );

        $this->end_controls_section();

        // Style: Role
        $this->start_controls_section(
            'section_style_role',
            array(
                'label' => esc_html__( 'Role', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'role_typography',
                'selector' => '{{WRAPPER}} .acst-team-member__role',
            )
        );

        $this->add_control(
            'role_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member__role' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'role_margin',
            array(
                'label'      => esc_html__( 'Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__role' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Style: Bio
        $this->start_controls_section(
            'section_style_bio',
            array(
                'label' => esc_html__( 'Bio', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->register_description_style_controls( 'bio', '{{WRAPPER}} .acst-team-member__bio' );

        $this->end_controls_section();

        // Style: Social Links
        $this->start_controls_section(
            'section_style_social',
            array(
                'label' => esc_html__( 'Social Links', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'social_icon_size',
            array(
                'label'      => esc_html__( 'Icon Size', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-team-member__social a' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .acst-team-member__social svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'social_spacing',
            array(
                'label'      => esc_html__( 'Spacing', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-team-member__social a' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'social_style_tabs' );

        $this->start_controls_tab(
            'social_style_normal',
            array(
                'label' => esc_html__( 'Normal', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'social_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member__social a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'social_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member__social a' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'social_style_hover',
            array(
                'label' => esc_html__( 'Hover', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'social_hover_color',
            array(
                'label'     => esc_html__( 'Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member__social a:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'social_hover_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .acst-team-member__social a:hover' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'social_padding',
            array(
                'label'      => esc_html__( 'Padding', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__social a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'social_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__social a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'social_margin',
            array(
                'label'      => esc_html__( 'Container Margin', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-team-member__social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'acst-team-member',
            'acst-team-member--' . $layout,
        ) );

        if ( $has_link ) {
            $this->add_link_attributes( 'link', $settings['link'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( ! empty( $settings['image']['url'] ) ) : ?>
                <div class="acst-team-member__image">
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
                        <div class="acst-team-member__overlay">
                            <?php $this->render_content( $settings, $has_link ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( $layout !== 'overlay' ) : ?>
                <div class="acst-team-member__content">
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
            <<?php echo esc_attr( $name_tag ); ?> class="acst-team-member__name">
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
            <div class="acst-team-member__role">
                <?php echo esc_html( $settings['role'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $settings['bio'] ) ) : ?>
            <div class="acst-team-member__bio">
                <?php echo wp_kses_post( $settings['bio'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $settings['social_links'] ) ) : ?>
            <div class="acst-team-member__social">
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
        var layoutClass = 'acst-team-member--' + settings.layout;
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
        <div class="acst-team-member {{ layoutClass }}">
            <# if ( settings.image && settings.image.url ) { #>
                <div class="acst-team-member__image">
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
                <div class="acst-team-member__content">
            <# } #>

            <# if ( settings.name ) { #>
                <{{ nameTag }} class="acst-team-member__name">
                    <# if ( hasLink ) { #>
                        <a href="{{ settings.link.url }}">{{{ settings.name }}}</a>
                    <# } else { #>
                        {{{ settings.name }}}
                    <# } #>
                </{{ nameTag }}>
            <# } #>

            <# if ( settings.role ) { #>
                <div class="acst-team-member__role">{{{ settings.role }}}</div>
            <# } #>

            <# if ( settings.bio ) { #>
                <div class="acst-team-member__bio">{{{ settings.bio }}}</div>
            <# } #>

            <# if ( settings.social_links && settings.social_links.length ) { #>
                <div class="acst-team-member__social">
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
