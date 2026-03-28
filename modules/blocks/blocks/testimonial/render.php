<?php
/**
 * Testimonial Block - Server-side Render
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

$testimonial_content = $attributes['content'] ?? '';
$name                = $attributes['name'] ?? '';
$title               = $attributes['title'] ?? '';
$image_url           = $attributes['imageUrl'] ?? '';
$image_id            = $attributes['imageId'] ?? 0;
$rating              = floatval( $attributes['rating'] ?? 5 );
$show_rating         = ! empty( $attributes['showRating'] );
$layout              = $attributes['layout'] ?? 'default';

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class' => 'acst-testimonial acst-testimonial--' . esc_attr( $layout ),
) );

// Build rating HTML.
$rating_html = '';
if ( $show_rating ) {
    $rating_html .= '<div class="acst-testimonial__rating" role="img" aria-label="' . esc_attr( sprintf( __( 'Rated %s out of 5', 'ac-starter-toolkit' ), $rating ) ) . '">';
    for ( $i = 1; $i <= 5; $i++ ) {
        if ( $i <= $rating ) {
            $rating_html .= '<span class="acst-star acst-star--filled">&#9733;</span>';
        } elseif ( $i - 0.5 <= $rating ) {
            $rating_html .= '<span class="acst-star acst-star--half">&#9733;</span>';
        } else {
            $rating_html .= '<span class="acst-star acst-star--empty">&#9734;</span>';
        }
    }
    $rating_html .= '</div>';
}

// Build image HTML.
$image_html = '';
if ( $image_id ) {
    $image_html = '<div class="acst-testimonial__image">' . wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'alt' => esc_attr( $name ) ) ) . '</div>';
} elseif ( ! empty( $image_url ) ) {
    $image_html = '<div class="acst-testimonial__image"><img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $name ) . '" /></div>';
}
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php if ( 'bubble' !== $layout ) : ?>
        <?php echo $image_html; ?>
    <?php endif; ?>

    <div class="acst-testimonial__content-wrap">
        <?php echo $rating_html; ?>
        <div class="acst-testimonial__content">
            <?php echo wp_kses_post( $testimonial_content ); ?>
        </div>
    </div>

    <?php if ( 'bubble' === $layout ) : ?>
        <div class="acst-testimonial__author-wrap">
            <?php echo $image_html; ?>
            <div class="acst-testimonial__author">
                <?php if ( ! empty( $name ) ) : ?>
                    <div class="acst-testimonial__name"><?php echo esc_html( $name ); ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $title ) ) : ?>
                    <div class="acst-testimonial__title"><?php echo esc_html( $title ); ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="acst-testimonial__author">
            <?php if ( ! empty( $name ) ) : ?>
                <div class="acst-testimonial__name"><?php echo esc_html( $name ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $title ) ) : ?>
                <div class="acst-testimonial__title"><?php echo esc_html( $title ); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
