<?php
/**
 * Content Widgets Module
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Content;

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
        require_once ACST_PLUGIN_DIR . 'modules/content/widgets/class-content-base.php';

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
            $widget_file = ACST_PLUGIN_DIR . 'modules/content/widgets/class-' . $file . '.php';

            if ( file_exists( $widget_file ) ) {
                require_once $widget_file;

                $widget_class = 'AC_Starter_Toolkit\\Modules\\Content\\Widgets\\' . $class;

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
                'label' => esc_html__( 'Facebook', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-facebook-f',
            ),
            'twitter'     => array(
                'label' => esc_html__( 'Twitter/X', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-x-twitter',
            ),
            'instagram'   => array(
                'label' => esc_html__( 'Instagram', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-instagram',
            ),
            'linkedin'    => array(
                'label' => esc_html__( 'LinkedIn', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-linkedin-in',
            ),
            'youtube'     => array(
                'label' => esc_html__( 'YouTube', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-youtube',
            ),
            'tiktok'      => array(
                'label' => esc_html__( 'TikTok', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-tiktok',
            ),
            'pinterest'   => array(
                'label' => esc_html__( 'Pinterest', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-pinterest-p',
            ),
            'github'      => array(
                'label' => esc_html__( 'GitHub', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-github',
            ),
            'dribbble'    => array(
                'label' => esc_html__( 'Dribbble', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-dribbble',
            ),
            'behance'     => array(
                'label' => esc_html__( 'Behance', 'ac-starter-toolkit' ),
                'icon'  => 'fab fa-behance',
            ),
            'email'       => array(
                'label' => esc_html__( 'Email', 'ac-starter-toolkit' ),
                'icon'  => 'fas fa-envelope',
            ),
            'website'     => array(
                'label' => esc_html__( 'Website', 'ac-starter-toolkit' ),
                'icon'  => 'fas fa-globe',
            ),
        );
    }
}
