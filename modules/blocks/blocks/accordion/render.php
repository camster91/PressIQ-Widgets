<?php
/**
 * Accordion Block - Server-side Render
 *
 * @package AC_Starter_Toolkit
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$items           = $attributes['items'] ?? array();
$collapse_others = ! empty( $attributes['collapseOthers'] );
$title_tag       = $attributes['titleTag'] ?? 'div';
$allowed_tags    = array( 'h2', 'h3', 'h4', 'h5', 'h6', 'div' );

if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
    $title_tag = 'div';
}

if ( empty( $items ) ) {
    return;
}

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class'                => 'acst-accordion',
    'data-collapse-others' => $collapse_others ? 'true' : 'false',
) );

$block_id = wp_unique_id( 'acst-accordion-' );
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php foreach ( $items as $index => $item ) :
        $item_count = $index + 1;
        $is_open    = ! empty( $item['open'] );
        $header_id  = $block_id . '-header-' . $item_count;
        $content_id = $block_id . '-content-' . $item_count;
    ?>
        <div class="acst-accordion__item<?php echo $is_open ? ' is-active' : ''; ?>">
            <<?php echo esc_attr( $title_tag ); ?> class="acst-accordion__header-wrapper">
                <button type="button"
                        class="acst-accordion__header"
                        id="<?php echo esc_attr( $header_id ); ?>"
                        aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr( $content_id ); ?>">
                    <span class="acst-accordion__title"><?php echo esc_html( $item['title'] ?? '' ); ?></span>
                    <span class="acst-accordion__toggle acst-accordion__toggle--right">
                        <span class="acst-accordion__toggle-icon acst-accordion__toggle-icon--inactive" aria-hidden="true">+</span>
                        <span class="acst-accordion__toggle-icon acst-accordion__toggle-icon--active" aria-hidden="true">&minus;</span>
                    </span>
                </button>
            </<?php echo esc_attr( $title_tag ); ?>>
            <div class="acst-accordion__content"
                 id="<?php echo esc_attr( $content_id ); ?>"
                 role="region"
                 aria-labelledby="<?php echo esc_attr( $header_id ); ?>"
                 <?php echo ! $is_open ? 'hidden' : ''; ?>>
                <div class="acst-accordion__content-inner">
                    <?php echo wp_kses_post( $item['content'] ?? '' ); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
