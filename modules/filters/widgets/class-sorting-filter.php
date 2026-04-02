<?php
/**
 * Sorting Filter Widget
 *
 * @package PressIQ_Widgets
 */

namespace PressIQ_Widgets\Modules\Filters\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Sorting Filter Widget Class
 *
 * Dropdown for sorting posts/products.
 */
class Sorting_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'pressiq-sorting-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Sorting Filter', 'pressiq-widgets' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-sort-amount-desc';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'sort', 'order', 'sorting', 'orderby' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Sorting options
        $this->start_controls_section(
            'section_sorting_options',
            array(
                'label' => esc_html__( 'Sorting Options', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'placeholder',
            array(
                'label'   => esc_html__( 'Placeholder', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Sort by', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'sorting_preset',
            array(
                'label'   => esc_html__( 'Options Preset', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'posts',
                'options' => array(
                    'posts'    => esc_html__( 'Posts', 'pressiq-widgets' ),
                    'products' => esc_html__( 'WooCommerce Products', 'pressiq-widgets' ),
                    'custom'   => esc_html__( 'Custom', 'pressiq-widgets' ),
                ),
            )
        );

        // Custom sorting options using repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'option_label',
            array(
                'label'   => esc_html__( 'Label', 'pressiq-widgets' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Sort Option', 'pressiq-widgets' ),
            )
        );

        $repeater->add_control(
            'orderby',
            array(
                'label'   => esc_html__( 'Order By', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => array(
                    'date'           => esc_html__( 'Date', 'pressiq-widgets' ),
                    'title'          => esc_html__( 'Title', 'pressiq-widgets' ),
                    'modified'       => esc_html__( 'Modified Date', 'pressiq-widgets' ),
                    'comment_count'  => esc_html__( 'Comment Count', 'pressiq-widgets' ),
                    'rand'           => esc_html__( 'Random', 'pressiq-widgets' ),
                    'menu_order'     => esc_html__( 'Menu Order', 'pressiq-widgets' ),
                    'meta_value'     => esc_html__( 'Meta Value (Text)', 'pressiq-widgets' ),
                    'meta_value_num' => esc_html__( 'Meta Value (Numeric)', 'pressiq-widgets' ),
                    // WooCommerce specific
                    'price'          => esc_html__( 'Price (WooCommerce)', 'pressiq-widgets' ),
                    'popularity'     => esc_html__( 'Popularity (WooCommerce)', 'pressiq-widgets' ),
                    'rating'         => esc_html__( 'Rating (WooCommerce)', 'pressiq-widgets' ),
                ),
            )
        );

        $repeater->add_control(
            'order',
            array(
                'label'   => esc_html__( 'Order', 'pressiq-widgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => array(
                    'ASC'  => esc_html__( 'Ascending', 'pressiq-widgets' ),
                    'DESC' => esc_html__( 'Descending', 'pressiq-widgets' ),
                ),
            )
        );

        $repeater->add_control(
            'meta_key',
            array(
                'label'       => esc_html__( 'Meta Key', 'pressiq-widgets' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'condition'   => array(
                    'orderby' => array( 'meta_value', 'meta_value_num' ),
                ),
                'description' => esc_html__( 'Enter the meta key to sort by.', 'pressiq-widgets' ),
            )
        );

        $this->add_control(
            'custom_options',
            array(
                'label'       => esc_html__( 'Sorting Options', 'pressiq-widgets' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'option_label' => esc_html__( 'Newest First', 'pressiq-widgets' ),
                        'orderby'      => 'date',
                        'order'        => 'DESC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Oldest First', 'pressiq-widgets' ),
                        'orderby'      => 'date',
                        'order'        => 'ASC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Title A-Z', 'pressiq-widgets' ),
                        'orderby'      => 'title',
                        'order'        => 'ASC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Title Z-A', 'pressiq-widgets' ),
                        'orderby'      => 'title',
                        'order'        => 'DESC',
                    ),
                ),
                'title_field' => '{{{ option_label }}}',
                'condition'   => array(
                    'sorting_preset' => 'custom',
                ),
            )
        );

        $this->end_controls_section();

        // Style controls
        $this->register_label_style_controls();
        $this->register_input_style_controls();

        // Select-specific styles
        $this->start_controls_section(
            'section_select_style',
            array(
                'label' => esc_html__( 'Dropdown', 'pressiq-widgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'select_width',
            array(
                'label'      => esc_html__( 'Width', 'pressiq-widgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 400,
                    ),
                    '%'  => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'default'    => array(
                    'unit' => '%',
                    'size' => 100,
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .pressiq-filter__select' => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get sorting options based on preset
     *
     * @param array $settings Widget settings.
     * @return array Sorting options.
     */
    private function get_sorting_options( $settings ) {
        $preset = $settings['sorting_preset'] ?? 'posts';

        switch ( $preset ) {
            case 'posts':
                return array(
                    array(
                        'value' => 'date|DESC',
                        'label' => esc_html__( 'Newest First', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'date|ASC',
                        'label' => esc_html__( 'Oldest First', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'title|ASC',
                        'label' => esc_html__( 'Title A-Z', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'title|DESC',
                        'label' => esc_html__( 'Title Z-A', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'comment_count|DESC',
                        'label' => esc_html__( 'Most Comments', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'rand|DESC',
                        'label' => esc_html__( 'Random', 'pressiq-widgets' ),
                    ),
                );

            case 'products':
                $options = array(
                    array(
                        'value' => 'menu_order|ASC',
                        'label' => esc_html__( 'Default', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'popularity|DESC',
                        'label' => esc_html__( 'Popularity', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'rating|DESC',
                        'label' => esc_html__( 'Average Rating', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'date|DESC',
                        'label' => esc_html__( 'Latest', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'price|ASC',
                        'label' => esc_html__( 'Price: Low to High', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'price|DESC',
                        'label' => esc_html__( 'Price: High to Low', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'title|ASC',
                        'label' => esc_html__( 'Name A-Z', 'pressiq-widgets' ),
                    ),
                    array(
                        'value' => 'title|DESC',
                        'label' => esc_html__( 'Name Z-A', 'pressiq-widgets' ),
                    ),
                );
                return $options;

            case 'custom':
                $options        = array();
                $custom_options = $settings['custom_options'] ?? array();

                foreach ( $custom_options as $option ) {
                    $orderby = $option['orderby'];
                    $order   = $option['order'];

                    // Handle meta sorting
                    if ( in_array( $orderby, array( 'meta_value', 'meta_value_num' ), true ) && ! empty( $option['meta_key'] ) ) {
                        $orderby = 'meta_' . $option['meta_key'];
                    }

                    $options[] = array(
                        'value' => $orderby . '|' . $order,
                        'label' => $option['option_label'],
                    );
                }
                return $options;

            default:
                return array();
        }
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings   = $this->get_settings_for_display();
        $options    = $this->get_sorting_options( $settings );
        $data_attrs = $this->get_filter_data_attrs( $settings );

        // Override filter type for sorting
        $data_attrs['data-filter-type'] = 'sorting';
        $data_attrs['data-filter-id']   = '_sort';
        ?>
        <div class="pressiq-filter pressiq-filter--sorting"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <select class="pressiq-filter__select pressiq-filter__input" name="_sort">
                <?php if ( ! empty( $settings['placeholder'] ) ) : ?>
                    <option value=""><?php echo esc_html( $settings['placeholder'] ); ?></option>
                <?php endif; ?>

                <?php foreach ( $options as $option ) : ?>
                    <option value="<?php echo esc_attr( $option['value'] ); ?>">
                        <?php echo esc_html( $option['label'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }

    /**
     * Render plain content (for Elementor)
     */
    protected function content_template() {
        ?>
        <div class="pressiq-filter pressiq-filter--sorting">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="pressiq-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <select class="pressiq-filter__select pressiq-filter__input">
                <# if ( settings.placeholder ) { #>
                    <option value="">{{{ settings.placeholder }}}</option>
                <# } #>
                <option value="date|DESC"><?php esc_html_e( 'Newest First', 'pressiq-widgets' ); ?></option>
                <option value="date|ASC"><?php esc_html_e( 'Oldest First', 'pressiq-widgets' ); ?></option>
                <option value="title|ASC"><?php esc_html_e( 'Title A-Z', 'pressiq-widgets' ); ?></option>
            </select>
        </div>
        <?php
    }
}
