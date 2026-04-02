<?php
/**
 * Content Widgets Module
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Content;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Content Module Class
 *
 * Handles content widgets like Team Member, Pricing Table, Testimonials, etc.
 */
class Content_Module {

    /**
     * Module slug
     *
     * @var string
     */
    const SLUG = 'content';

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the module
     */
    private function init() {
        // Assets are enqueued by the main plugin class
    }

    /**
     * Register content widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        // Load base class first
        require_once PRESSIQ_PLUGIN_DIR . 'modules/content/widgets/class-content-base.php';

        // Load and register individual widgets
        $widgets = array(
            'team-member'    => 'Team_Member',
            'pricing-table'  => 'Pricing_Table',
            'countdown'      => 'Countdown',
            'tabs'           => 'Tabs',
            'accordion'      => 'Accordion',
            'testimonial'    => 'Testimonial',
            'counter'        => 'Counter',
        );

        foreach ( $widgets as $file => $class ) {
            $widget_file = PRESSIQ_PLUGIN_DIR . 'modules/content/widgets/class-' . $file . '.php';

            if ( file_exists( $widget_file ) ) {
                require_once $widget_file;

                $widget_class = 'PressIQ_Widgets\\Modules\\Content\\Widgets\\' . $class;

                if ( class_exists( $widget_class ) ) {
                    $widgets_manager->register( new $widget_class() );
                }
            }
        }
    }

    /**
     * Get social network options
     *
     * @return array
     */
    public static function get_social_networks() {
        return array(
            'facebook'    => array(
                'label' => esc_html__( 'Facebook', 'pressiq-widgets' ),
                'icon'  => 'fab fa-facebook-f',
            ),
            'twitter'     => array(
                'label' => esc_html__( 'Twitter/X', 'pressiq-widgets' ),
                'icon'  => 'fab fa-x-twitter',
            ),
            'instagram'   => array(
                'label' => esc_html__( 'Instagram', 'pressiq-widgets' ),
                'icon'  => 'fab fa-instagram',
            ),
            'linkedin'    => array(
                'label' => esc_html__( 'LinkedIn', 'pressiq-widgets' ),
                'icon'  => 'fab fa-linkedin-in',
            ),
            'youtube'     => array(
                'label' => esc_html__( 'YouTube', 'pressiq-widgets' ),
                'icon'  => 'fab fa-youtube',
            ),
            'tiktok'      => array(
                'label' => esc_html__( 'TikTok', 'pressiq-widgets' ),
                'icon'  => 'fab fa-tiktok',
            ),
            'pinterest'   => array(
                'label' => esc_html__( 'Pinterest', 'pressiq-widgets' ),
                'icon'  => 'fab fa-pinterest-p',
            ),
            'github'      => array(
                'label' => esc_html__( 'GitHub', 'pressiq-widgets' ),
                'icon'  => 'fab fa-github',
            ),
            'dribbble'    => array(
                'label' => esc_html__( 'Dribbble', 'pressiq-widgets' ),
                'icon'  => 'fab fa-dribbble',
            ),
            'behance'     => array(
                'label' => esc_html__( 'Behance', 'pressiq-widgets' ),
                'icon'  => 'fab fa-behance',
            ),
            'email'       => array(
                'label' => esc_html__( 'Email', 'pressiq-widgets' ),
                'icon'  => 'fas fa-envelope',
            ),
            'website'     => array(
                'label' => esc_html__( 'Website', 'pressiq-widgets' ),
                'icon'  => 'fas fa-globe',
            ),
        );
    }
}
