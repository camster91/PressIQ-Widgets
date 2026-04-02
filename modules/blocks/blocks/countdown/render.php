<?php
/**
 * Countdown Timer Block - Server-side Render
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

$countdown_type    = $attributes['countdownType'] ?? 'due_date';
$due_date          = $attributes['dueDate'] ?? '';
$evergreen_hours   = intval( $attributes['evergreenHours'] ?? 24 );
$evergreen_minutes = intval( $attributes['evergreenMinutes'] ?? 0 );
$show_days         = ! empty( $attributes['showDays'] );
$show_hours        = ! empty( $attributes['showHours'] );
$show_minutes      = ! empty( $attributes['showMinutes'] );
$show_seconds      = ! empty( $attributes['showSeconds'] );
$show_labels       = ! empty( $attributes['showLabels'] );
$show_separator    = ! empty( $attributes['showSeparator'] );
$label_days        = $attributes['labelDays'] ?? 'Days';
$label_hours       = $attributes['labelHours'] ?? 'Hours';
$label_minutes     = $attributes['labelMinutes'] ?? 'Minutes';
$label_seconds     = $attributes['labelSeconds'] ?? 'Seconds';
$expire_action     = $attributes['expireAction'] ?? 'hide';
$expire_message    = $attributes['expireMessage'] ?? '';

$data_attrs = array(
    'data-expire-action' => $expire_action,
);

if ( $countdown_type === 'due_date' && ! empty( $due_date ) ) {
    $data_attrs['data-due-date'] = gmdate( 'c', strtotime( $due_date ) );
} else {
    $data_attrs['data-evergreen']         = 'yes';
    $data_attrs['data-evergreen-seconds'] = ( $evergreen_hours * 3600 ) + ( $evergreen_minutes * 60 );
}

if ( $expire_action === 'message' ) {
    $data_attrs['data-expire-message'] = $expire_message;
}

$extra_attrs = '';
foreach ( $data_attrs as $key => $value ) {
    $extra_attrs .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
}

$wrapper_attrs = get_block_wrapper_attributes( array( 'class' => 'pressiq-countdown' ) );
// Inject data attributes into wrapper
$wrapper_attrs .= $extra_attrs;

$separator = ':';
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php if ( $show_days ) : ?>
        <div class="pressiq-countdown__item" data-unit="days">
            <span class="pressiq-countdown__number" data-countdown="days">00</span>
            <?php if ( $show_labels ) : ?>
                <span class="pressiq-countdown__label"><?php echo esc_html( $label_days ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( $show_separator && $show_hours ) : ?>
            <span class="pressiq-countdown__separator"><?php echo esc_html( $separator ); ?></span>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( $show_hours ) : ?>
        <div class="pressiq-countdown__item" data-unit="hours">
            <span class="pressiq-countdown__number" data-countdown="hours">00</span>
            <?php if ( $show_labels ) : ?>
                <span class="pressiq-countdown__label"><?php echo esc_html( $label_hours ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( $show_separator && $show_minutes ) : ?>
            <span class="pressiq-countdown__separator"><?php echo esc_html( $separator ); ?></span>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( $show_minutes ) : ?>
        <div class="pressiq-countdown__item" data-unit="minutes">
            <span class="pressiq-countdown__number" data-countdown="minutes">00</span>
            <?php if ( $show_labels ) : ?>
                <span class="pressiq-countdown__label"><?php echo esc_html( $label_minutes ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( $show_separator && $show_seconds ) : ?>
            <span class="pressiq-countdown__separator"><?php echo esc_html( $separator ); ?></span>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( $show_seconds ) : ?>
        <div class="pressiq-countdown__item" data-unit="seconds">
            <span class="pressiq-countdown__number" data-countdown="seconds">00</span>
            <?php if ( $show_labels ) : ?>
                <span class="pressiq-countdown__label"><?php echo esc_html( $label_seconds ); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
