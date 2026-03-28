<?php
/**
 * Admin Settings Page
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit;

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
    private $option_name = 'acst_options';

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
        add_filter( 'plugin_action_links_' . plugin_basename( ACST_PLUGIN_FILE ), array( $this, 'add_plugin_action_links' ) );
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
            admin_url( 'admin.php?page=ac-starter-toolkit' ),
            esc_html__( 'Settings', 'ac-starter-toolkit' )
        );
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Add menu page
     */
    public function add_menu_page() {
        add_menu_page(
            esc_html__( 'AC Starter Toolkit', 'ac-starter-toolkit' ),
            esc_html__( 'AC Toolkit', 'ac-starter-toolkit' ),
            'manage_options',
            'ac-starter-toolkit',
            array( $this, 'render_settings_page' ),
            'dashicons-admin-generic',
            59
        );

        add_submenu_page(
            'ac-starter-toolkit',
            esc_html__( 'Dashboard', 'ac-starter-toolkit' ),
            esc_html__( 'Dashboard', 'ac-starter-toolkit' ),
            'manage_options',
            'ac-starter-toolkit',
            array( $this, 'render_settings_page' )
        );

        add_submenu_page(
            'ac-starter-toolkit',
            esc_html__( 'Widgets', 'ac-starter-toolkit' ),
            esc_html__( 'Widgets', 'ac-starter-toolkit' ),
            'manage_options',
            'ac-starter-toolkit-widgets',
            array( $this, 'render_widgets_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'acst_settings_group',
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
                'name'        => esc_html__( 'Smart Filters', 'ac-starter-toolkit' ),
                'description' => esc_html__( 'AJAX-powered filtering widgets for posts, products, and custom post types. Includes Select, Checkbox, Radio, Range, Sorting, and Search filters.', 'ac-starter-toolkit' ),
                'icon'        => 'dashicons-filter',
                'widgets'     => array(
                    'acst-select-filter'   => esc_html__( 'Select Filter', 'ac-starter-toolkit' ),
                    'acst-checkbox-filter' => esc_html__( 'Checkbox Filter', 'ac-starter-toolkit' ),
                    'acst-radio-filter'    => esc_html__( 'Radio Filter', 'ac-starter-toolkit' ),
                    'acst-range-filter'    => esc_html__( 'Range Filter', 'ac-starter-toolkit' ),
                    'acst-sorting-filter'  => esc_html__( 'Sorting Filter', 'ac-starter-toolkit' ),
                    'acst-search-filter'   => esc_html__( 'Search Filter', 'ac-starter-toolkit' ),
                ),
            ),
            'content' => array(
                'name'        => esc_html__( 'Content Widgets', 'ac-starter-toolkit' ),
                'description' => esc_html__( 'Essential content widgets including Team Member, Pricing Table, Testimonial, Countdown Timer, Tabs, and Accordion.', 'ac-starter-toolkit' ),
                'icon'        => 'dashicons-layout',
                'widgets'     => array(
                    'acst-team-member'   => esc_html__( 'Team Member', 'ac-starter-toolkit' ),
                    'acst-pricing-table' => esc_html__( 'Pricing Table', 'ac-starter-toolkit' ),
                    'acst-testimonial'   => esc_html__( 'Testimonial', 'ac-starter-toolkit' ),
                    'acst-countdown'     => esc_html__( 'Countdown Timer', 'ac-starter-toolkit' ),
                    'acst-tabs'          => esc_html__( 'Tabs', 'ac-starter-toolkit' ),
                    'acst-accordion'     => esc_html__( 'Accordion', 'ac-starter-toolkit' ),
                ),
            ),
            'blocks' => array(
                'name'        => esc_html__( 'WordPress Blocks (FSE)', 'ac-starter-toolkit' ),
                'description' => esc_html__( 'Native WordPress blocks for the Site Editor and block themes like Twenty Twenty-Six. Works without Elementor. Includes Accordion, Tabs, Team Member, Pricing Table, Testimonial, Countdown Timer, and Post Filter blocks.', 'ac-starter-toolkit' ),
                'icon'        => 'dashicons-block-default',
                'widgets'     => array(
                    'acst-block-accordion'     => esc_html__( 'Accordion Block', 'ac-starter-toolkit' ),
                    'acst-block-tabs'          => esc_html__( 'Tabs Block', 'ac-starter-toolkit' ),
                    'acst-block-team-member'   => esc_html__( 'Team Member Block', 'ac-starter-toolkit' ),
                    'acst-block-pricing-table' => esc_html__( 'Pricing Table Block', 'ac-starter-toolkit' ),
                    'acst-block-testimonial'   => esc_html__( 'Testimonial Block', 'ac-starter-toolkit' ),
                    'acst-block-countdown'     => esc_html__( 'Countdown Timer Block', 'ac-starter-toolkit' ),
                    'acst-block-post-filter'   => esc_html__( 'Post Filter Block', 'ac-starter-toolkit' ),
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
        if ( strpos( $hook, 'ac-starter-toolkit' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'acst-admin',
            ACST_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            ACST_VERSION
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        $options          = $this->get_options();
        $available_modules = $this->get_available_modules();
        ?>
        <div class="wrap acst-admin">
            <div class="acst-admin__header">
                <h1 class="acst-admin__title">
                    <?php esc_html_e( 'AC Starter Toolkit', 'ac-starter-toolkit' ); ?>
                </h1>
                <p class="acst-admin__version">
                    <?php echo esc_html( sprintf( __( 'Version %s', 'ac-starter-toolkit' ), ACST_VERSION ) ); ?>
                </p>
            </div>

            <div class="acst-admin__content">
                <div class="acst-admin__main">
                    <!-- Welcome Section -->
                    <div class="acst-card acst-card--welcome">
                        <div class="acst-card__content">
                            <h2><?php esc_html_e( 'Welcome to AC Starter Toolkit', 'ac-starter-toolkit' ); ?></h2>
                            <p><?php esc_html_e( 'A modular toolkit with smart filtering and content widgets. Works with Elementor widgets and native WordPress blocks for Full Site Editing themes like Twenty Twenty-Six. Enable the modules you need and start building.', 'ac-starter-toolkit' ); ?></p>
                        </div>
                    </div>

                    <!-- Modules Section -->
                    <div class="acst-card">
                        <div class="acst-card__header">
                            <h2><?php esc_html_e( 'Modules', 'ac-starter-toolkit' ); ?></h2>
                            <p><?php esc_html_e( 'Enable or disable plugin modules. Disabled modules will not load any assets or widgets.', 'ac-starter-toolkit' ); ?></p>
                        </div>
                        <div class="acst-card__content">
                            <form method="post" action="options.php">
                                <?php settings_fields( 'acst_settings_group' ); ?>

                                <div class="acst-modules">
                                    <?php foreach ( $available_modules as $module_id => $module ) : ?>
                                        <div class="acst-module">
                                            <div class="acst-module__header">
                                                <span class="dashicons <?php echo esc_attr( $module['icon'] ); ?> acst-module__icon"></span>
                                                <div class="acst-module__info">
                                                    <h3 class="acst-module__name"><?php echo esc_html( $module['name'] ); ?></h3>
                                                    <p class="acst-module__description"><?php echo esc_html( $module['description'] ); ?></p>
                                                </div>
                                                <label class="acst-toggle">
                                                    <input type="checkbox"
                                                           name="<?php echo esc_attr( $this->option_name ); ?>[modules][<?php echo esc_attr( $module_id ); ?>]"
                                                           value="1"
                                                           <?php checked( ! empty( $options['modules'][ $module_id ] ) ); ?>>
                                                    <span class="acst-toggle__slider"></span>
                                                </label>
                                            </div>
                                            <div class="acst-module__widgets">
                                                <strong><?php esc_html_e( 'Included Widgets:', 'ac-starter-toolkit' ); ?></strong>
                                                <ul>
                                                    <?php foreach ( $module['widgets'] as $widget_id => $widget_name ) : ?>
                                                        <li><?php echo esc_html( $widget_name ); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php submit_button( esc_html__( 'Save Changes', 'ac-starter-toolkit' ) ); ?>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="acst-admin__sidebar">
                    <!-- Quick Links -->
                    <div class="acst-card">
                        <div class="acst-card__header">
                            <h3><?php esc_html_e( 'Quick Links', 'ac-starter-toolkit' ); ?></h3>
                        </div>
                        <div class="acst-card__content">
                            <ul class="acst-links">
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=ac-starter-toolkit-widgets' ) ); ?>">
                                        <span class="dashicons dashicons-welcome-widgets-menus"></span>
                                        <?php esc_html_e( 'View All Widgets', 'ac-starter-toolkit' ); ?>
                                    </a>
                                </li>
                                <?php if ( defined( 'ELEMENTOR_VERSION' ) ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=elementor_library' ) ); ?>">
                                        <span class="dashicons dashicons-admin-page"></span>
                                        <?php esc_html_e( 'Elementor Templates', 'ac-starter-toolkit' ); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if ( wp_is_block_theme() ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>">
                                        <span class="dashicons dashicons-layout"></span>
                                        <?php esc_html_e( 'Site Editor', 'ac-starter-toolkit' ); ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="acst-card">
                        <div class="acst-card__header">
                            <h3><?php esc_html_e( 'System Info', 'ac-starter-toolkit' ); ?></h3>
                        </div>
                        <div class="acst-card__content">
                            <table class="acst-info-table">
                                <tr>
                                    <td><?php esc_html_e( 'WordPress', 'ac-starter-toolkit' ); ?></td>
                                    <td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'PHP', 'ac-starter-toolkit' ); ?></td>
                                    <td><?php echo esc_html( PHP_VERSION ); ?></td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Elementor', 'ac-starter-toolkit' ); ?></td>
                                    <td>
                                        <?php
                                        if ( defined( 'ELEMENTOR_VERSION' ) ) {
                                            echo esc_html( ELEMENTOR_VERSION );
                                        } else {
                                            esc_html_e( 'Not Active', 'ac-starter-toolkit' );
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'WooCommerce', 'ac-starter-toolkit' ); ?></td>
                                    <td>
                                        <?php
                                        if ( defined( 'WC_VERSION' ) ) {
                                            echo esc_html( WC_VERSION );
                                        } else {
                                            esc_html_e( 'Not Active', 'ac-starter-toolkit' );
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e( 'Block Theme (FSE)', 'ac-starter-toolkit' ); ?></td>
                                    <td>
                                        <?php
                                        if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
                                            $theme = wp_get_theme();
                                            echo esc_html( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) );
                                        } else {
                                            esc_html_e( 'Classic Theme', 'ac-starter-toolkit' );
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
        <div class="wrap acst-admin">
            <div class="acst-admin__header">
                <h1 class="acst-admin__title">
                    <?php esc_html_e( 'Available Widgets', 'ac-starter-toolkit' ); ?>
                </h1>
            </div>

            <div class="acst-admin__content acst-admin__content--full">
                <?php foreach ( $available_modules as $module_id => $module ) : ?>
                    <div class="acst-card">
                        <div class="acst-card__header">
                            <h2>
                                <span class="dashicons <?php echo esc_attr( $module['icon'] ); ?>"></span>
                                <?php echo esc_html( $module['name'] ); ?>
                                <?php if ( empty( $options['modules'][ $module_id ] ) ) : ?>
                                    <span class="acst-badge acst-badge--disabled"><?php esc_html_e( 'Disabled', 'ac-starter-toolkit' ); ?></span>
                                <?php else : ?>
                                    <span class="acst-badge acst-badge--enabled"><?php esc_html_e( 'Enabled', 'ac-starter-toolkit' ); ?></span>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <div class="acst-card__content">
                            <div class="acst-widgets-grid">
                                <?php foreach ( $module['widgets'] as $widget_id => $widget_name ) : ?>
                                    <?php
                                    $widget_info = $this->get_widget_info( $widget_id );
                                    ?>
                                    <div class="acst-widget-card <?php echo empty( $options['modules'][ $module_id ] ) ? 'acst-widget-card--disabled' : ''; ?>">
                                        <div class="acst-widget-card__icon">
                                            <span class="<?php echo esc_attr( $widget_info['icon'] ); ?>"></span>
                                        </div>
                                        <h4 class="acst-widget-card__name"><?php echo esc_html( $widget_name ); ?></h4>
                                        <p class="acst-widget-card__description"><?php echo esc_html( $widget_info['description'] ); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- How to Use Section -->
                <div class="acst-card">
                    <div class="acst-card__header">
                        <h2><?php esc_html_e( 'How to Use', 'ac-starter-toolkit' ); ?></h2>
                    </div>
                    <div class="acst-card__content">
                        <div class="acst-instructions">
                            <div class="acst-instruction">
                                <div class="acst-instruction__number">1</div>
                                <div class="acst-instruction__content">
                                    <h4><?php esc_html_e( 'Enable Modules', 'ac-starter-toolkit' ); ?></h4>
                                    <p><?php esc_html_e( 'Go to the Dashboard tab and enable the modules you want to use. Each module contains a set of related widgets.', 'ac-starter-toolkit' ); ?></p>
                                </div>
                            </div>
                            <div class="acst-instruction">
                                <div class="acst-instruction__number">2</div>
                                <div class="acst-instruction__content">
                                    <h4><?php esc_html_e( 'Edit with Elementor', 'ac-starter-toolkit' ); ?></h4>
                                    <p><?php esc_html_e( 'Open any page with Elementor. You\'ll find AC Starter Toolkit widgets in the "AC Starter Toolkit" category in the widget panel.', 'ac-starter-toolkit' ); ?></p>
                                </div>
                            </div>
                            <div class="acst-instruction">
                                <div class="acst-instruction__number">3</div>
                                <div class="acst-instruction__content">
                                    <h4><?php esc_html_e( 'Configure Widgets', 'ac-starter-toolkit' ); ?></h4>
                                    <p><?php esc_html_e( 'Drag widgets to your page and configure them using the Content and Style tabs. Each widget has comprehensive styling options.', 'ac-starter-toolkit' ); ?></p>
                                </div>
                            </div>
                        </div>

                        <h3><?php esc_html_e( 'Using Smart Filters', 'ac-starter-toolkit' ); ?></h3>
                        <ol class="acst-steps">
                            <li><?php esc_html_e( 'Add an Elementor Posts widget or Loop Grid to your page', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Give it a Query ID in the widget settings (e.g., "main_query")', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Add filter widgets and set their Query ID to match', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Configure filter sources (taxonomy, meta field, or manual options)', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Visitors can now filter content without page reloads', 'ac-starter-toolkit' ); ?></li>
                        </ol>

                        <h3><?php esc_html_e( 'Using WordPress Blocks (FSE / Block Themes)', 'ac-starter-toolkit' ); ?></h3>
                        <ol class="acst-steps">
                            <li><?php esc_html_e( 'Enable the "WordPress Blocks (FSE)" module above', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Open any page or the Site Editor (Appearance → Editor)', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Search for "AC" or browse the "AC Starter Toolkit" block category', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Insert blocks and configure them via the block sidebar panel', 'ac-starter-toolkit' ); ?></li>
                            <li><?php esc_html_e( 'Blocks work natively with block themes like Twenty Twenty-Six — no Elementor required', 'ac-starter-toolkit' ); ?></li>
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
            'acst-select-filter'   => array(
                'icon'        => 'eicon-select',
                'description' => esc_html__( 'Dropdown filter for taxonomy terms, meta values, or custom options.', 'ac-starter-toolkit' ),
            ),
            'acst-checkbox-filter' => array(
                'icon'        => 'eicon-checkbox',
                'description' => esc_html__( 'Multi-select filter with checkboxes. Supports collapsible groups and search.', 'ac-starter-toolkit' ),
            ),
            'acst-radio-filter'    => array(
                'icon'        => 'eicon-radio',
                'description' => esc_html__( 'Single-select filter with radio buttons or button-style display.', 'ac-starter-toolkit' ),
            ),
            'acst-range-filter'    => array(
                'icon'        => 'eicon-slider-push',
                'description' => esc_html__( 'Numeric range filter with slider. Perfect for prices and other numbers.', 'ac-starter-toolkit' ),
            ),
            'acst-sorting-filter'  => array(
                'icon'        => 'eicon-sort-amount-desc',
                'description' => esc_html__( 'Sort dropdown for date, title, price, and custom sorting options.', 'ac-starter-toolkit' ),
            ),
            'acst-search-filter'   => array(
                'icon'        => 'eicon-search',
                'description' => esc_html__( 'Text search filter with debounced input and clear button.', 'ac-starter-toolkit' ),
            ),
            // Content widgets.
            'acst-team-member'     => array(
                'icon'        => 'eicon-person',
                'description' => esc_html__( 'Display team members with photo, name, role, bio, and social links.', 'ac-starter-toolkit' ),
            ),
            'acst-pricing-table'   => array(
                'icon'        => 'eicon-price-table',
                'description' => esc_html__( 'Pricing table with features list, CTA button, and featured ribbon.', 'ac-starter-toolkit' ),
            ),
            'acst-testimonial'     => array(
                'icon'        => 'eicon-testimonial',
                'description' => esc_html__( 'Customer testimonials with photo, rating stars, and multiple layouts.', 'ac-starter-toolkit' ),
            ),
            'acst-countdown'       => array(
                'icon'        => 'eicon-countdown',
                'description' => esc_html__( 'Countdown timer with expire actions: hide, show message, or redirect.', 'ac-starter-toolkit' ),
            ),
            'acst-tabs'            => array(
                'icon'        => 'eicon-tabs',
                'description' => esc_html__( 'Tabbed content with horizontal or vertical layout and icon support.', 'ac-starter-toolkit' ),
            ),
            'acst-accordion'       => array(
                'icon'        => 'eicon-accordion',
                'description' => esc_html__( 'Collapsible accordion with custom toggle icons and title icons.', 'ac-starter-toolkit' ),
            ),
        );

        // Block widget info.
        $block_widgets = array(
            'acst-block-accordion'     => array(
                'icon'        => 'dashicons dashicons-list-view',
                'description' => esc_html__( 'Collapsible accordion sections for the WordPress Site Editor.', 'ac-starter-toolkit' ),
            ),
            'acst-block-tabs'          => array(
                'icon'        => 'dashicons dashicons-table-row-after',
                'description' => esc_html__( 'Tabbed content interface for block themes.', 'ac-starter-toolkit' ),
            ),
            'acst-block-team-member'   => array(
                'icon'        => 'dashicons dashicons-admin-users',
                'description' => esc_html__( 'Team member profiles with photo, social links for the Site Editor.', 'ac-starter-toolkit' ),
            ),
            'acst-block-pricing-table' => array(
                'icon'        => 'dashicons dashicons-money-alt',
                'description' => esc_html__( 'Pricing plans with features and CTA button for block themes.', 'ac-starter-toolkit' ),
            ),
            'acst-block-testimonial'   => array(
                'icon'        => 'dashicons dashicons-format-quote',
                'description' => esc_html__( 'Customer testimonials with rating stars for the Site Editor.', 'ac-starter-toolkit' ),
            ),
            'acst-block-countdown'     => array(
                'icon'        => 'dashicons dashicons-clock',
                'description' => esc_html__( 'Countdown timer with expire actions for block themes.', 'ac-starter-toolkit' ),
            ),
            'acst-block-post-filter'   => array(
                'icon'        => 'dashicons dashicons-filter',
                'description' => esc_html__( 'AJAX-powered post filter with search, taxonomy filters, and sorting for block themes.', 'ac-starter-toolkit' ),
            ),
        );

        $all_widgets = array_merge( $widgets, $block_widgets );

        return isset( $all_widgets[ $widget_id ] ) ? $all_widgets[ $widget_id ] : array(
            'icon'        => 'dashicons dashicons-admin-generic',
            'description' => '',
        );
    }
}
