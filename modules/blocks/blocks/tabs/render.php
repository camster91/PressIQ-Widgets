<?php
/**
 * Tabs Block - Server-side Render
 *
 * @package PressIQ_Widgets
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tabs           = $attributes['tabs'] ?? array();
$layout         = $attributes['layout'] ?? 'horizontal';
$default_active = intval( $attributes['defaultActive'] ?? 1 );

if ( empty( $tabs ) ) {
    return;
}

$block_id     = wp_unique_id( 'pressiq-tabs-' );
$wrapper_attrs = get_block_wrapper_attributes( array(
    'class' => 'pressiq-tabs pressiq-tabs--' . esc_attr( $layout ),
) );
?>
<div <?php echo $wrapper_attrs; ?>>
    <div class="pressiq-tabs__nav" role="tablist">
        <?php foreach ( $tabs as $index => $tab ) :
            $tab_count = $index + 1;
            $is_active = $tab_count === $default_active;
            $tab_id    = $block_id . '-tab-' . $tab_count;
            $panel_id  = $block_id . '-panel-' . $tab_count;
        ?>
            <button type="button"
                    class="pressiq-tabs__tab<?php echo $is_active ? ' is-active' : ''; ?>"
                    id="<?php echo esc_attr( $tab_id ); ?>"
                    role="tab"
                    aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                    aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                    data-tab="<?php echo esc_attr( $tab_count ); ?>">
                <span class="pressiq-tabs__tab-title"><?php echo esc_html( $tab['title'] ?? '' ); ?></span>
            </button>
        <?php endforeach; ?>
    </div>
    <div class="pressiq-tabs__content">
        <?php foreach ( $tabs as $index => $tab ) :
            $tab_count = $index + 1;
            $is_active = $tab_count === $default_active;
            $tab_id    = $block_id . '-tab-' . $tab_count;
            $panel_id  = $block_id . '-panel-' . $tab_count;
        ?>
            <div class="pressiq-tabs__panel<?php echo $is_active ? ' is-active' : ''; ?>"
                 id="<?php echo esc_attr( $panel_id ); ?>"
                 role="tabpanel"
                 aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
                 data-tab="<?php echo esc_attr( $tab_count ); ?>"
                 <?php echo ! $is_active ? 'hidden' : ''; ?>>
                <?php echo wp_kses_post( $tab['content'] ?? '' ); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
