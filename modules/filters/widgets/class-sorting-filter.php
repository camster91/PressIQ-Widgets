<?php
/**
 * Sorting Filter Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters\Widgets;

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
        return 'acst-sorting-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Sorting Filter', 'ac-starter-toolkit' );
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
                'label' => esc_html__( 'Sorting Options', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'placeholder',
            array(
                'label'   => esc_html__( 'Placeholder', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Sort by', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'sorting_preset',
            array(
                'label'   => esc_html__( 'Options Preset', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'posts',
                'options' => array(
                    'posts'    => esc_html__( 'Posts', 'ac-starter-toolkit' ),
                    'products' => esc_html__( 'WooCommerce Products', 'ac-starter-toolkit' ),
                    'custom'   => esc_html__( 'Custom', 'ac-starter-toolkit' ),
                ),
            )
        );

        // Custom sorting options using repeater
        $repeater = new Repeater();

        $repeater->add_control(
            'option_label',
            array(
                'label'   => esc_html__( 'Label', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Sort Option', 'ac-starter-toolkit' ),
            )
        );

        $repeater->add_control(
            'orderby',
            array(
                'label'   => esc_html__( 'Order By', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => array(
                    'date'           => esc_html__( 'Date', 'ac-starter-toolkit' ),
                    'title'          => esc_html__( 'Title', 'ac-starter-toolkit' ),
                    'modified'       => esc_html__( 'Modified Date', 'ac-starter-toolkit' ),
                    'comment_count'  => esc_html__( 'Comment Count', 'ac-starter-toolkit' ),
                    'rand'           => esc_html__( 'Random', 'ac-starter-toolkit' ),
                    'menu_order'     => esc_html__( 'Menu Order', 'ac-starter-toolkit' ),
                    'meta_value'     => esc_html__( 'Meta Value (Text)', 'ac-starter-toolkit' ),
                    'meta_value_num' => esc_html__( 'Meta Value (Numeric)', 'ac-starter-toolkit' ),
                    // WooCommerce specific
                    'price'          => esc_html__( 'Price (WooCommerce)', 'ac-starter-toolkit' ),
                    'popularity'     => esc_html__( 'Popularity (WooCommerce)', 'ac-starter-toolkit' ),
                    'rating'         => esc_html__( 'Rating (WooCommerce)', 'ac-starter-toolkit' ),
                ),
            )
        );

        $repeater->add_control(
            'order',
            array(
                'label'   => esc_html__( 'Order', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => array(
                    'ASC'  => esc_html__( 'Ascending', 'ac-starter-toolkit' ),
                    'DESC' => esc_html__( 'Descending', 'ac-starter-toolkit' ),
                ),
            )
        );

        $repeater->add_control(
            'meta_key',
            array(
                'label'       => esc_html__( 'Meta Key', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'condition'   => array(
                    'orderby' => array( 'meta_value', 'meta_value_num' ),
                ),
                'description' => esc_html__( 'Enter the meta key to sort by.', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'custom_options',
            array(
                'label'       => esc_html__( 'Sorting Options', 'ac-starter-toolkit' ),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'option_label' => esc_html__( 'Newest First', 'ac-starter-toolkit' ),
                        'orderby'      => 'date',
                        'order'        => 'DESC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Oldest First', 'ac-starter-toolkit' ),
                        'orderby'      => 'date',
                        'order'        => 'ASC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Title A-Z', 'ac-starter-toolkit' ),
                        'orderby'      => 'title',
                        'order'        => 'ASC',
                    ),
                    array(
                        'option_label' => esc_html__( 'Title Z-A', 'ac-starter-toolkit' ),
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
                'label' => esc_html__( 'Dropdown', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'select_width',
            array(
                'label'      => esc_html__( 'Width', 'ac-starter-toolkit' ),
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
                    '{{WRAPPER}} .acst-filter__select' => 'width: {{SIZE}}{{UNIT}};',
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
                        'label' => esc_html__( 'Newest First', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'date|ASC',
                        'label' => esc_html__( 'Oldest First', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'title|ASC',
                        'label' => esc_html__( 'Title A-Z', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'title|DESC',
                        'label' => esc_html__( 'Title Z-A', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'comment_count|DESC',
                        'label' => esc_html__( 'Most Comments', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'rand|DESC',
                        'label' => esc_html__( 'Random', 'ac-starter-toolkit' ),
                    ),
                );

            case 'products':
                $options = array(
                    array(
                        'value' => 'menu_order|ASC',
                        'label' => esc_html__( 'Default', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'popularity|DESC',
                        'label' => esc_html__( 'Popularity', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'rating|DESC',
                        'label' => esc_html__( 'Average Rating', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'date|DESC',
                        'label' => esc_html__( 'Latest', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'price|ASC',
                        'label' => esc_html__( 'Price: Low to High', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'price|DESC',
                        'label' => esc_html__( 'Price: High to Low', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'title|ASC',
                        'label' => esc_html__( 'Name A-Z', 'ac-starter-toolkit' ),
                    ),
                    array(
                        'value' => 'title|DESC',
                        'label' => esc_html__( 'Name Z-A', 'ac-starter-toolkit' ),
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
        <div class="acst-filter acst-filter--sorting"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <select class="acst-filter__select acst-filter__input" name="_sort">
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
        <div class="acst-filter acst-filter--sorting">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="acst-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <select class="acst-filter__select acst-filter__input">
                <# if ( settings.placeholder ) { #>
                    <option value="">{{{ settings.placeholder }}}</option>
                <# } #>
                <option value="date|DESC"><?php esc_html_e( 'Newest First', 'ac-starter-toolkit' ); ?></option>
                <option value="date|ASC"><?php esc_html_e( 'Oldest First', 'ac-starter-toolkit' ); ?></option>
                <option value="title|ASC"><?php esc_html_e( 'Title A-Z', 'ac-starter-toolkit' ); ?></option>
            </select>
        </div>
        <?php
    }
}
