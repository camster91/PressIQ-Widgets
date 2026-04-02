<?php
/**
 * Admin Settings Page
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin Settings Class
 */
class Admin {

    /**
     * Instance
     *
     * @var Admin
     */
    private static $instance = null;

    /**
     * Option name
     *
     * @var string
     */
    private $option_name = 'pressiq_options';

    /**
     * Default options
     *
     * @var array
     */
    private $defaults = array(
        'modules' => array(
            'filters' => true,
            'content' => true,
            'blocks'  => false,
        ),
    );

    /**
     * Get instance
     *
     * @return Admin
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( PRESSIQ_PLUGIN_FILE ), array( $this, 'add_plugin_action_links' ) );
    }

    /**
     * Add plugin action links
     *
     * @param array $links Existing links.
     * @return array
     */
    public function add_plugin_action_links( $links ) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url( 'admin.php?page=pressiq-widgets' ),
            esc_html__( 'Settings', 'pressiq-widgets' )
        );
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_menu_page(
            esc_html__( 'AC Starter Toolkit', 'pressiq-widgets' ),
            esc_html__( 'AC Toolkit', 'pressiq-widgets' ),
            'manage_options',
            'pressiq-widgets',
            array( $this, 'render_settings_page' ),
            'dashicons-admin-generic',
            59
        );

        add_submenu_page(
            'pressiq-widgets',
            esc_html__( 'Dashboard', 'pressiq-widgets' ),
            esc_html__( 'Dashboard', 'pressiq-widgets' ),
            'manage_options',
            'pressiq-widgets',
            array( $this, 'render_settings_page' )
        );

        add_submenu_page(
            'pressiq-widgets',
            esc_html__( 'Widgets', 'pressiq-widgets' ),
            esc_html__( 'Widgets', 'pressiq-widgets' ),
            'manage_options',
            'pressiq-widgets-widgets',
            array( $this, 'render_widgets_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'pressiq_settings_group',
            $this->option_name,
            array(
                'type'              => 'array',
                'sanitize_callback' => array( $this, 'sanitize_options' ),
                'default'           => $this->defaults,
            )
        );
    }

    /**
     * Sanitize options
     *
     * @param array $input Input values.
     * @return array
     */
    public function sanitize_options( $input ) {
        $sanitized = array(
            'modules' => array(),
        );

        // Sanitize module toggles.
        $available_modules = $this->get_available_modules();
        foreach ( $available_modules as $module_id => $module ) {
            $sanitized['modules'][ $module_id ] = ! empty( $input['modules'][ $module_id ] );
        }

        return $sanitized;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function get_options() {
        $options = get_option( $this->option_name, $this->defaults );
        return wp_parse_args( $options, $this->defaults );
    }

    /**
     * Get available modules
     *
     * @return array
     */
    public function get_available_modules() {
        return array(
            'filters' => array(
                'name'        => esc_html__( 'Smart Filters', 'pressiq-widgets' ),
                'description' => esc_html__( 'AJAX-powered filtering widgets for posts, products, and custom post types. Includes Select, Checkbox, Radio, Range, Sorting, and Search filters.', 'pressiq-widgets' ),
                'icon'        => 'dashicons-filter',
                'widgets'     => array(
                    'pressiq-select-filter'   => esc_html__( 'Select Filter', 'pressiq-widgets' ),
                    'pressiq-checkbox-filter' => esc_html__( 'Checkbox Filter', 'pressiq-widgets' ),
                    'pressiq-radio-filter'    => esc_html__( 'Radio Filter', 'pressiq-widgets' ),
                    'pressiq-range-filter'    => esc_html__( 'Range Filter', 'pressiq-widgets' ),
                    'pressiq-sorting-filter'  => esc_html__( 'Sorting Filter', 'pressiq-widgets' ),
                    'pressiq-search-filter'   => esc_html__( 'Search Filter', 'pressiq-widgets' ),
                ),
            ),
            'content' => array(
                'name'        => esc_html__( 'Content Widgets', 'pressiq-widgets' ),
                'description' => esc_html__( 'Essential content widgets including Team Member, Pricing Table, Testimonial, Countdown Timer, Tabs, and Accordion.', 'pressiq-widgets' ),
                'icon'        => 'dashicons-layout',
                'widgets'     => array(
                    'pressiq-team-member'   => esc_html__( 'Team Member', 'pressiq-widgets' ),
                    'pressiq-pricing-table' => esc_html__( 'Pricing Table', 'pressiq-widgets' ),
                    'pressiq-testimonial'   => esc_html__( 'Testimonial', 'pressiq-widgets' ),
                    'pressiq-countdown'     => esc_html__( 'Countdown Timer', 'pressiq-widgets' ),
                    'pressiq-tabs'          => esc_html__( 'Tabs', 'pressiq-widgets' ),
                    'pressiq-accordion'     => esc_html__( 'Accordion', 'pressiq-widgets' ),
                ),
            ),
            'blocks' => array(
                'name'        => esc_html__( 'WordPress Blocks (FSE)', 'pressiq-widgets' ),
                'description' => esc_html__( 'Native WordPress blocks for the Site Editor and block themes like Twenty Twenty-Six. Works without Elementor. Includes Accordion, Tabs, Team Member, Pricing Table, Testimonial, Countdown Timer, and Post Filter blocks.', 'pressiq-widgets' ),
                'icon'        => 'dashicons-block-default',
                'widgets'     => array(
                    'pressiq-block-accordion'     => esc_html__( 'Accordion Block', 'pressiq-widgets' ),
                    'pressiq-block-tabs'          => esc_html__( 'Tabs Block', 'pressiq-widgets' ),
                    'pressiq-block-team-member'   => esc_html__( 'Team Member Block', 'pressiq-widgets' ),
                    'pressiq-block-pricing-table' => esc_html__( 'Pricing Table Block', 'pressiq-widgets' ),
                    'pressiq-block-testimonial'   => esc_html__( 'Testimonial Block', 'pressiq-widgets' ),
                    'pressiq-block-countdown'     => esc_html__( 'Countdown Timer Block', 'pressiq-widgets' ),
                    'pressiq-block-post-filter'   => esc_html__( 'Post Filter Block', 'pressiq-widgets' ),
                ),
            ),
        );
    }

    /**
     * Check if module is enabled
     *
     * @param string $module_id Module ID.
     * @return bool
     */
    public function is_module_enabled( $module_id ) {
        $options = $this->get_options();
        return ! empty( $options['modules'][ $module_id ] );
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Current admin page.
     */
    public function enqueue_admin_assets( $hook ) {
        if ( strpos( $hook, 'pressiq-widgets' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'pressiq-admin',
            PRESSIQ_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            PRESSIQ_VERSION
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        $options          = $this->get_options();
        $available_modules = $this->get_available_modules();
        ?>
        <div class="wrap pressiq-admin">
            <div class="pressiq-admin__header">
                <h1 class="pressiq-admin__title">
                    <?php esc_html_e( 'AC Starter Toolkit', 'pressiq-widgets' ); ?>
                </h1>
                <p class="pressiq-admin__version">
                    <?php echo esc_html( sprintf( __( 'Version %s', 'pressiq-widgets' ), PRESSIQ_VERSION ) ); ?>
                </p>
            </div>

            <div class="pressiq-admin__content">
                <div class="pressiq-admin__main">
                    <!-- Welcome Section -->
                    <div class="pressiq-card pressiq-card--welcome">
                        <div class="pressiq-card__content">
                            <h2><?php esc_html_e( 'Welcome to AC Starter Toolkit', 'pressiq-widgets' ); ?></h2>
                            <p><?php esc_html_e( 'A modular toolkit with smart filtering and content widgets. Works with Elementor widgets and native WordPress blocks for Full Site Editing themes like Twenty Twenty-Six. Enable the modules you need and start building.', 'pressiq-widgets' ); ?></p>
                        </div>
                    </div>

                    <!-- Modules Section -->
                    <div class="pressiq-card">
                        <div class="pressiq-card__header">
                            <h2><?php esc_html_e( 'Modules', 'pressiq-widgets' ); ?></h2>
                            <p><?php esc_html_e( 'Enable or disable plugin modules. Disabled modules will not load any assets or widgets.', 'pressiq-widgets' ); ?></p>
                        </div>
                        <div class="pressiq-card__content">
                            <form method="post" action="options.php">
                                <?php settings_fields( 'pressiq_settings_group' ); ?>

                                <div class="pressiq-modules">
                                    <?php foreach ( $available_modules as $module_id => $module ) : ?>
                                        <div class="pressiq-module">
                                            <div class="pressiq-module__header">
                                                <span class="dashicons <?php echo esc_attr( $module['icon'] ); ?> pressiq-module__icon"></span>
                                                <div class="pressiq-module__info">
                                                    <h3 class="pressiq-module__name"><?php echo esc_html( $module['name'] ); ?></h3>
                                                    <p class="pressiq-module__description"><?php echo esc_html( $module['description'] ); ?></p>
                                                </div>
                                                <label class="pressiq-toggle">
                                                    <input type="checkbox"
                                                           name="<?php echo esc_attr( $this->option_name ); ?>[modules][<?php echo esc_attr( $module_id ); ?>]"
                                                           value="1"
                                                           <?php checked( ! empty( $options['modules'][ $module_id ] ) ); ?>>
                                                    <span class="pressiq-toggle__slider"></span>
                                                </label>
                                            </div>
                                            <div class="pressiq-module__widgets">
                                                <strong><?php esc_html_e( 'Included Widgets:', 'pressiq-widgets' ); ?></strong>
                                                <ul>
                                                    <?php foreach ( $module['widgets'] as $widget_id => $widget_name ) : ?>
                                                        <li><?php echo esc_html( $widget_name ); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php submit_button( esc_html__( 'Save Changes', 'pressiq-widgets' ) ); ?>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="pressiq-admin__sidebar">
                    <!-- Quick Links -->
                    <div class="pressiq-card">
                        <div class="pressiq-card__header">
                            <h3><?php esc_html_e( 'Quick Links', 'pressiq-widgets' ); ?></h3>
                        </div>
                        <div class="pressiq-card__content">
                            <ul class="pressiq-links">
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=pressiq-widgets-widgets' ) ); ?>">
                                        <span class="dashicons dashicons-welcome-widgets-menus"></span>
                                        <?php esc_html_e( 'View All Widgets', 'pressiq-widgets' ); ?>
                                    </a>
                                </li>
                                <?php if ( defined( 'ELEMENTOR_VERSION' ) ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library' ) ); ?>">
                                        <span class="dashicons dashicons-admin-page"></span>
                                        <?php esc_html_e( 'Elementor Templates', 'pressiq-widgets' ); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if ( wp_is_block_theme() ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>">
                                        <span class="dashicons dashicons-layout"></span>
                                        <?php esc_html_e( 'Site Editor', 'pressiq-widgets' ); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="pressiq-card">
                        <div class="pressiq-card__header">
                            <h3><?php esc_html_e( 'System Info', 'pressiq-widgets' ); ?></h3>
                        </div>
                        <div class="pressiq-card__content">
                            <table class="pressiq-info-table">
                                <tr>
                                    <td><?php esc_html_e( 'WordPress', 'pressiq-widgets' ); ?></td>
                                    <td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'PHP', 'pressiq-widgets' ); ?></td>
                                    <td><?php echo esc_html( PHP_VERSION ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Elementor', 'pressiq-widgets' ); ?></td>
                                    <td>
                                        <?php
                                        if ( defined( 'ELEMENTOR_VERSION' ) ) {
                                            echo esc_html( ELEMENTOR_VERSION );
                                        } else {
                                            esc_html_e( 'Not Active', 'pressiq-widgets' );
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'WooCommerce', 'pressiq-widgets' ); ?></td>
                                    <td>
                                        <?php
                                        if ( defined( 'WC_VERSION' ) ) {
                                            echo esc_html( WC_VERSION );
                                        } else {
                                            esc_html_e( 'Not Active', 'pressiq-widgets' );
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Block Theme (FSE)', 'pressiq-widgets' ); ?></td>
                                    <td>
                                        <?php
                                        if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
                                            $theme = wp_get_theme();
                                            echo esc_html( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) );
                                        } else {
                                            esc_html_e( 'Classic Theme', 'pressiq-widgets' );
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render widgets page
     */
    public function render_widgets_page() {
        $options           = $this->get_options();
        $available_modules = $this->get_available_modules();
        ?>
        <div class="wrap pressiq-admin">
            <div class="pressiq-admin__header">
                <h1 class="pressiq-admin__title">
                    <?php esc_html_e( 'Available Widgets', 'pressiq-widgets' ); ?>
                </h1>
            </div>

            <div class="pressiq-admin__content pressiq-admin__content--full">
                <?php foreach ( $available_modules as $module_id => $module ) : ?>
                    <div class="pressiq-card">
                        <div class="pressiq-card__header">
                            <h2>
                                <span class="dashicons <?php echo esc_attr( $module['icon'] ); ?>"></span>
                                <?php echo esc_html( $module['name'] ); ?>
                                <?php if ( empty( $options['modules'][ $module_id ] ) ) : ?>
                                    <span class="pressiq-badge pressiq-badge--disabled"><?php esc_html_e( 'Disabled', 'pressiq-widgets' ); ?></span>
                                <?php else : ?>
                                    <span class="pressiq-badge pressiq-badge--enabled"><?php esc_html_e( 'Enabled', 'pressiq-widgets' ); ?></span>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="pressiq-card__content">
                            <div class="pressiq-widgets-grid">
                                <?php foreach ( $module['widgets'] as $widget_id => $widget_name ) : ?>
                                    <?php
                                    $widget_info = $this->get_widget_info( $widget_id );
                                    ?>
                                    <div class="pressiq-widget-card <?php echo empty( $options['modules'][ $module_id ] ) ? 'pressiq-widget-card--disabled' : ''; ?>">
                                        <div class="pressiq-widget-card__icon">
                                            <span class="<?php echo esc_attr( $widget_info['icon'] ); ?>"></span>
                                        </div>
                                        <h4 class="pressiq-widget-card__name"><?php echo esc_html( $widget_name ); ?></h4>
                                        <p class="pressiq-widget-card__description"><?php echo esc_html( $widget_info['description'] ); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- How to Use Section -->
                <div class="pressiq-card">
                    <div class="pressiq-card__header">
                        <h2><?php esc_html_e( 'How to Use', 'pressiq-widgets' ); ?></h2>
                    </div>
                    <div class="pressiq-card__content">
                        <div class="pressiq-instructions">
                            <div class="pressiq-instruction">
                                <div class="pressiq-instruction__number">1</div>
                                <div class="pressiq-instruction__content">
                                    <h4><?php esc_html_e( 'Enable Modules', 'pressiq-widgets' ); ?></h4>
                                    <p><?php esc_html_e( 'Go to the Dashboard tab and enable the modules you want to use. Each module contains a set of related widgets.', 'pressiq-widgets' ); ?></p>
                                </div>
                            </div>
                            <div class="pressiq-instruction">
                                <div class="pressiq-instruction__number">2</div>
                                <div class="pressiq-instruction__content">
                                    <h4><?php esc_html_e( 'Edit with Elementor', 'pressiq-widgets' ); ?></h4>
                                    <p><?php esc_html_e( 'Open any page with Elementor. You\'ll find AC Starter Toolkit widgets in the "AC Starter Toolkit" category in the widget panel.', 'pressiq-widgets' ); ?></p>
                                </div>
                            </div>
                            <div class="pressiq-instruction">
                                <div class="pressiq-instruction__number">3</div>
                                <div class="pressiq-instruction__content">
                                    <h4><?php esc_html_e( 'Configure Widgets', 'pressiq-widgets' ); ?></h4>
                                    <p><?php esc_html_e( 'Drag widgets to your page and configure them using the Content and Style tabs. Each widget has comprehensive styling options.', 'pressiq-widgets' ); ?></p>
                                </div>
                            </div>
                        </div>

                        <h3><?php esc_html_e( 'Using Smart Filters', 'pressiq-widgets' ); ?></h3>
                        <ol class="pressiq-steps">
                            <li><?php esc_html_e( 'Add an Elementor Posts widget or Loop Grid to your page', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Give it a Query ID in the widget settings (e.g., "main_query")', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Add filter widgets and set their Query ID to match', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Configure filter sources (taxonomy, meta field, or manual options)', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Visitors can now filter content without page reloads', 'pressiq-widgets' ); ?></li>
                        </ol>

                        <h3><?php esc_html_e( 'Using WordPress Blocks (FSE / Block Themes)', 'pressiq-widgets' ); ?></h3>
                        <ol class="pressiq-steps">
                            <li><?php esc_html_e( 'Enable the "WordPress Blocks (FSE)" module above', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Open any page or the Site Editor (Appearance → Editor)', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Search for "AC" or browse the "AC Starter Toolkit" block category', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Insert blocks and configure them via the block sidebar panel', 'pressiq-widgets' ); ?></li>
                            <li><?php esc_html_e( 'Blocks work natively with block themes like Twenty Twenty-Six — no Elementor required', 'pressiq-widgets' ); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get widget info
     *
     * @param string $widget_id Widget ID.
     * @return array
     */
    private function get_widget_info( $widget_id ) {
        $widgets = array(
            // Filter widgets.
            'pressiq-select-filter'   => array(
                'icon'        => 'eicon-select',
                'description' => esc_html__( 'Dropdown filter for taxonomy terms, meta values, or custom options.', 'pressiq-widgets' ),
            ),
            'pressiq-checkbox-filter' => array(
                'icon'        => 'eicon-checkbox',
                'description' => esc_html__( 'Multi-select filter with checkboxes. Supports collapsible groups and search.', 'pressiq-widgets' ),
            ),
            'pressiq-radio-filter'    => array(
                'icon'        => 'eicon-radio',
                'description' => esc_html__( 'Single-select filter with radio buttons or button-style display.', 'pressiq-widgets' ),
            ),
            'pressiq-range-filter'    => array(
                'icon'        => 'eicon-slider-push',
                'description' => esc_html__( 'Numeric range filter with slider. Perfect for prices and other numbers.', 'pressiq-widgets' ),
            ),
            'pressiq-sorting-filter'  => array(
                'icon'        => 'eicon-sort-amount-desc',
                'description' => esc_html__( 'Sort dropdown for date, title, price, and custom sorting options.', 'pressiq-widgets' ),
            ),
            'pressiq-search-filter'   => array(
                'icon'        => 'eicon-search',
                'description' => esc_html__( 'Text search filter with debounced input and clear button.', 'pressiq-widgets' ),
            ),
            // Content widgets.
            'pressiq-team-member'     => array(
                'icon'        => 'eicon-person',
                'description' => esc_html__( 'Display team members with photo, name, role, bio, and social links.', 'pressiq-widgets' ),
            ),
            'pressiq-pricing-table'   => array(
                'icon'        => 'eicon-price-table',
                'description' => esc_html__( 'Pricing table with features list, CTA button, and featured ribbon.', 'pressiq-widgets' ),
            ),
            'pressiq-testimonial'     => array(
                'icon'        => 'eicon-testimonial',
                'description' => esc_html__( 'Customer testimonials with photo, rating stars, and multiple layouts.', 'pressiq-widgets' ),
            ),
            'pressiq-countdown'       => array(
                'icon'        => 'eicon-countdown',
                'description' => esc_html__( 'Countdown timer with expire actions: hide, show message, or redirect.', 'pressiq-widgets' ),
            ),
            'pressiq-tabs'            => array(
                'icon'        => 'eicon-tabs',
                'description' => esc_html__( 'Tabbed content with horizontal or vertical layout and icon support.', 'pressiq-widgets' ),
            ),
            'pressiq-accordion'       => array(
                'icon'        => 'eicon-accordion',
                'description' => esc_html__( 'Collapsible accordion with custom toggle icons and title icons.', 'pressiq-widgets' ),
            ),
        );

        // Block widget info.
        $block_widgets = array(
            'pressiq-block-accordion'     => array(
                'icon'        => 'dashicons dashicons-list-view',
                'description' => esc_html__( 'Collapsible accordion sections for the WordPress Site Editor.', 'pressiq-widgets' ),
            ),
            'pressiq-block-tabs'          => array(
                'icon'        => 'dashicons dashicons-table-row-after',
                'description' => esc_html__( 'Tabbed content interface for block themes.', 'pressiq-widgets' ),
            ),
            'pressiq-block-team-member'   => array(
                'icon'        => 'dashicons dashicons-admin-users',
                'description' => esc_html__( 'Team member profiles with photo, social links for the Site Editor.', 'pressiq-widgets' ),
            ),
            'pressiq-block-pricing-table' => array(
                'icon'        => 'dashicons dashicons-money-alt',
                'description' => esc_html__( 'Pricing plans with features and CTA button for block themes.', 'pressiq-widgets' ),
            ),
            'pressiq-block-testimonial'   => array(
                'icon'        => 'dashicons dashicons-format-quote',
                'description' => esc_html__( 'Customer testimonials with rating stars for the Site Editor.', 'pressiq-widgets' ),
            ),
            'pressiq-block-countdown'     => array(
                'icon'        => 'dashicons dashicons-clock',
                'description' => esc_html__( 'Countdown timer with expire actions for block themes.', 'pressiq-widgets' ),
            ),
            'pressiq-block-post-filter'   => array(
                'icon'        => 'dashicons dashicons-filter',
                'description' => esc_html__( 'AJAX-powered post filter with search, taxonomy filters, and sorting for block themes.', 'pressiq-widgets' ),
            ),
        );

        $all_widgets = array_merge( $widgets, $block_widgets );

        return isset( $all_widgets[ $widget_id ] ) ? $all_widgets[ $widget_id ] : array(
            'icon'        => 'dashicons dashicons-admin-generic',
            'description' => '',
        );
    }
}
