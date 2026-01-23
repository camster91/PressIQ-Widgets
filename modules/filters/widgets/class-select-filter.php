<?php
/**
 * Select Filter Widget
 *
 * @package AC_Starter_Toolkit
 */

namespace AC_Starter_Toolkit\Modules\Filters\Widgets;

use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Select Filter Widget Class
 *
 * Dropdown select filter for taxonomy or meta field values.
 */
class Select_Filter extends Filter_Base {

    /**
     * Get widget name
     *
     * @return string
     */
    public function get_name() {
        return 'acst-select-filter';
    }

    /**
     * Get widget title
     *
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Select Filter', 'ac-starter-toolkit' );
    }

    /**
     * Get widget icon
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-select';
    }

    /**
     * Get widget keywords
     *
     * @return array
     */
    public function get_keywords() {
        return array( 'filter', 'select', 'dropdown', 'taxonomy', 'category' );
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        // Filter Settings
        $this->register_filter_content_controls();

        // Data Source
        $this->register_data_source_controls();

        // Select-specific options
        $this->start_controls_section(
            'section_select_options',
            array(
                'label' => esc_html__( 'Select Options', 'ac-starter-toolkit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'placeholder',
            array(
                'label'   => esc_html__( 'Placeholder', 'ac-starter-toolkit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Select an option', 'ac-starter-toolkit' ),
            )
        );

        $this->add_control(
            'show_all_option',
            array(
                'label'        => esc_html__( 'Show "All" Option', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'all_option_label',
            array(
                'label'     => esc_html__( '"All" Option Label', 'ac-starter-toolkit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'All', 'ac-starter-toolkit' ),
                'condition' => array(
                    'show_all_option' => 'yes',
                ),
            )
        );

        $this->add_control(
            'hierarchical',
            array(
                'label'        => esc_html__( 'Show Hierarchy', 'ac-starter-toolkit' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => esc_html__( 'Yes', 'ac-starter-toolkit' ),
                'label_off'    => esc_html__( 'No', 'ac-starter-toolkit' ),
                'return_value' => 'yes',
                'description'  => esc_html__( 'Show parent-child hierarchy with indentation for taxonomies.', 'ac-starter-toolkit' ),
                'condition'    => array(
                    'source_type' => 'taxonomy',
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
                        'max' => 600,
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

        $this->add_responsive_control(
            'select_height',
            array(
                'label'      => esc_html__( 'Height', 'ac-starter-toolkit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'min' => 30,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .acst-filter__select' => 'height: {{SIZE}}{{UNIT}};',
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
        $options  = $this->get_filter_options( $settings );

        // Handle hierarchical display for taxonomies
        if ( $settings['source_type'] === 'taxonomy' && $settings['hierarchical'] === 'yes' ) {
            $options = $this->get_hierarchical_options( $settings );
        }

        $data_attrs = $this->get_filter_data_attrs( $settings );
        ?>
        <div class="acst-filter acst-filter--select"<?php echo $this->render_data_attrs( $data_attrs ); ?>>
            <?php $this->render_label( $settings ); ?>

            <select class="acst-filter__select acst-filter__input" name="<?php echo esc_attr( $this->get_filter_id( $settings ) ); ?>">
                <?php if ( $settings['show_all_option'] === 'yes' ) : ?>
                    <option value="">
                        <?php echo esc_html( $settings['all_option_label'] ?: $settings['placeholder'] ); ?>
                    </option>
                <?php elseif ( ! empty( $settings['placeholder'] ) ) : ?>
                    <option value="" disabled selected>
                        <?php echo esc_html( $settings['placeholder'] ); ?>
                    </option>
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
     * Get hierarchical taxonomy options
     *
     * @param array $settings Widget settings.
     * @return array Hierarchical options with indentation.
     */
    private function get_hierarchical_options( $settings ) {
        $taxonomy   = $settings['taxonomy'] ?? 'category';
        $hide_empty = $settings['hide_empty'] === 'yes';
        $show_count = $settings['show_count'] === 'yes';

        $terms = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => $hide_empty,
            'parent'     => 0,
        ) );

        if ( is_wp_error( $terms ) ) {
            return array();
        }

        $options = array();
        foreach ( $terms as $term ) {
            $options = array_merge(
                $options,
                $this->get_term_with_children( $term, $taxonomy, $hide_empty, $show_count, 0 )
            );
        }

        return $options;
    }

    /**
     * Recursively get term with children
     *
     * @param object $term       Term object.
     * @param string $taxonomy   Taxonomy name.
     * @param bool   $hide_empty Hide empty terms.
     * @param bool   $show_count Show term count.
     * @param int    $depth      Current depth level.
     * @return array Options array.
     */
    private function get_term_with_children( $term, $taxonomy, $hide_empty, $show_count, $depth ) {
        $indent = str_repeat( '— ', $depth );
        $label  = $indent . $term->name;

        if ( $show_count ) {
            $label .= sprintf( ' (%d)', $term->count );
        }

        $options = array(
            array(
                'value' => $term->slug,
                'label' => $label,
                'count' => $term->count,
            ),
        );

        // Get children
        $children = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => $hide_empty,
            'parent'     => $term->term_id,
        ) );

        if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
            foreach ( $children as $child ) {
                $options = array_merge(
                    $options,
                    $this->get_term_with_children( $child, $taxonomy, $hide_empty, $show_count, $depth + 1 )
                );
            }
        }

        return $options;
    }

    /**
     * Render plain content (for Elementor)
     */
    protected function content_template() {
        ?>
        <#
        var filterId = 'filter_' + view.getID();
        #>
        <div class="acst-filter acst-filter--select">
            <# if ( settings.show_label === 'yes' && settings.filter_label ) { #>
                <label class="acst-filter__label">{{{ settings.filter_label }}}</label>
            <# } #>

            <select class="acst-filter__select acst-filter__input">
                <# if ( settings.show_all_option === 'yes' ) { #>
                    <option value="">{{{ settings.all_option_label || settings.placeholder }}}</option>
                <# } else if ( settings.placeholder ) { #>
                    <option value="" disabled selected>{{{ settings.placeholder }}}</option>
                <# } #>
                <option value="option1"><?php esc_html_e( 'Option 1', 'ac-starter-toolkit' ); ?></option>
                <option value="option2"><?php esc_html_e( 'Option 2', 'ac-starter-toolkit' ); ?></option>
                <option value="option3"><?php esc_html_e( 'Option 3', 'ac-starter-toolkit' ); ?></option>
            </select>
        </div>
        <?php
    }
}
