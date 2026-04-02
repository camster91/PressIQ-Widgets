<?php
/**
 * Pricing Table Block - Server-side Render
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

$title             = $attributes['title'] ?? '';
$subtitle          = $attributes['subtitle'] ?? '';
$currency          = $attributes['currency'] ?? '$';
$price             = $attributes['price'] ?? '0';
$original_price    = $attributes['originalPrice'] ?? '';
$period            = $attributes['period'] ?? '';
$currency_position = $attributes['currencyPosition'] ?? 'before';
$featured          = ! empty( $attributes['featured'] );
$ribbon_text       = $attributes['ribbonText'] ?? 'Popular';
$features          = $attributes['features'] ?? array();
$button_text       = $attributes['buttonText'] ?? '';
$button_url        = $attributes['buttonUrl'] ?? '#';
$button_target     = $attributes['buttonTarget'] ?? '';

$classes = 'pressiq-pricing-table';
if ( $featured ) {
    $classes .= ' pressiq-pricing-table--featured';
}

$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => $classes ) );
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php if ( $featured && ! empty( $ribbon_text ) ) : ?>
        <div class="pressiq-pricing-table__ribbon"><?php echo esc_html( $ribbon_text ); ?></div>
    <?php endif; ?>

    <div class="pressiq-pricing-table__header">
        <?php if ( ! empty( $title ) ) : ?>
            <h3 class="pressiq-pricing-table__title"><?php echo esc_html( $title ); ?></h3>
        <?php endif; ?>
        <?php if ( ! empty( $subtitle ) ) : ?>
            <div class="pressiq-pricing-table__subtitle"><?php echo esc_html( $subtitle ); ?></div>
        <?php endif; ?>
    </div>

    <div class="pressiq-pricing-table__price">
        <?php if ( ! empty( $original_price ) ) : ?>
            <span class="pressiq-pricing-table__original">
                <?php if ( $currency_position === 'before' ) : ?>
                    <span class="pressiq-pricing-table__currency"><?php echo esc_html( $currency ); ?></span>
                <?php endif; ?>
                <?php echo esc_html( $original_price ); ?>
                <?php if ( $currency_position === 'after' ) : ?>
                    <span class="pressiq-pricing-table__currency"><?php echo esc_html( $currency ); ?></span>
                <?php endif; ?>
            </span>
        <?php endif; ?>

        <span class="pressiq-pricing-table__amount-wrapper">
            <?php if ( $currency_position === 'before' ) : ?>
                <span class="pressiq-pricing-table__currency"><?php echo esc_html( $currency ); ?></span>
            <?php endif; ?>
            <span class="pressiq-pricing-table__amount"><?php echo esc_html( $price ); ?></span>
            <?php if ( $currency_position === 'after' ) : ?>
                <span class="pressiq-pricing-table__currency"><?php echo esc_html( $currency ); ?></span>
            <?php endif; ?>
        </span>

        <?php if ( ! empty( $period ) ) : ?>
            <span class="pressiq-pricing-table__period"><?php echo esc_html( $period ); ?></span>
        <?php endif; ?>
    </div>

    <?php if ( ! empty( $features ) ) : ?>
        <ul class="pressiq-pricing-table__features">
            <?php foreach ( $features as $feature ) :
                $available = ! empty( $feature['available'] );
            ?>
                <li class="pressiq-pricing-table__feature<?php echo ! $available ? ' pressiq-pricing-table__feature--unavailable' : ''; ?>">
                    <span class="pressiq-pricing-table__feature-icon"><?php echo $available ? '&#10003;' : '&#10005;'; ?></span>
                    <span class="pressiq-pricing-table__feature-text"><?php echo esc_html( $feature['text'] ?? '' ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ( ! empty( $button_text ) ) : ?>
        <div class="pressiq-pricing-table__footer">
            <a class="pressiq-pricing-table__button pressiq-button"
               href="<?php echo esc_url( $button_url ); ?>"
               <?php echo $button_target ? 'target="' . esc_attr( $button_target ) . '"' : ''; ?>>
                <span class="pressiq-button__text"><?php echo esc_html( $button_text ); ?></span>
            </a>
        </div>
    <?php endif; ?>
</div>
