<?php
/**
 * Team Member Block - Server-side Render
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

$name         = $attributes['name'] ?? '';
$name_tag     = $attributes['nameTag'] ?? 'h3';
$role         = $attributes['role'] ?? '';
$bio          = $attributes['bio'] ?? '';
$image_url    = $attributes['imageUrl'] ?? '';
$image_id     = $attributes['imageId'] ?? 0;
$image_alt    = $attributes['imageAlt'] ?? $name;
$link_url     = $attributes['linkUrl'] ?? '';
$link_target  = $attributes['linkTarget'] ?? '';
$layout       = $attributes['layout'] ?? 'card';
$social_links = $attributes['socialLinks'] ?? array();

$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span' );
if ( ! in_array( $name_tag, $allowed_tags, true ) ) {
    $name_tag = 'h3';
}

$social_icons = array(
    'facebook'  => 'facebook-f',
    'twitter'   => 'x-twitter',
    'instagram' => 'instagram',
    'linkedin'  => 'linkedin-in',
    'youtube'   => 'youtube',
    'tiktok'    => 'tiktok',
    'pinterest' => 'pinterest-p',
    'github'    => 'github',
    'dribbble'  => 'dribbble',
    'behance'   => 'behance',
    'email'     => 'envelope',
    'website'   => 'globe',
);

$social_svg = array(
    'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 320 512"><path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg>',
    'twitter'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>',
    'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>',
    'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512"><path d="M100.3 448H7.4V148.9h92.9zM53.8 108.1C24.1 108.1 0 83.5 0 53.8a53.8 53.8 0 0 1 107.6 0c0 29.7-24.1 54.3-53.8 54.3zM447.9 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448z"/></svg>',
    'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 576 512"><path d="M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z"/></svg>',
    'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 448 512"><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>',
    'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 384 512"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>',
    'github'    => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 496 512"><path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3 .3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5 .3-6.2 2.3zm44.2-1.7c-2.9 .7-4.9 2.6-4.6 4.9 .3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.8-14.9-112.8-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8z"/></svg>',
    'dribbble'  => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M256 8C119.3 8 8 119.3 8 256s111.3 248 248 248 248-111.3 248-248S392.7 8 256 8z"/></svg>',
    'behance'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 576 512"><path d="M232 237.2c31.8-15.2 48.4-38.2 48.4-74 0-70.6-52.6-87.8-113.3-87.8H0v354.4h171.8c64.4 0 124.9-30.9 124.9-102.9 0-44.5-21.1-77.4-64.7-89.7zM77.9 135.9H151c28.1 0 53.4 7.9 53.4 40.5 0 30.1-19.7 42.2-47.5 42.2h-79V135.9zm83.3 233.7H77.9V272h84.9c34.3 0 56 14.3 56 50.6 0 35.8-25.9 47-57.6 47zm358.5-240.7H376V94h143.7v34.9zM576 305.2c0-75.9-44.4-134.2-124.3-134.2-78.8 0-132.3 56.1-132.3 134.2 0 82.2 50.7 134.2 132.3 134.2 53.2 0 92-21.5 112.2-72.7h-59.4c-7 21.5-27.6 34.5-52.8 34.5-42.4 0-62.3-28.5-62.3-68h179.5c.6-8.2 .6-16 .6-27.2h-6zm-176.8-21.5c2.6-32.6 24.4-54.2 57-54.2 31.6 0 52.2 21.5 55 54.2H399.2z"/></svg>',
    'email'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>',
    'website'   => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 512 512"><path d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 172.9 151.6zm-420.7 0c30.9-74.1 94.6-130.9 172.9-151.6C searching220.1 42.6 200.3 96.1 190.3 160H73.1zm362 256H190.3c10-63.9 29.8-117.4 55.3-151.6C167.3 285.1 103.6 341.9 73.1 416H8.1C2.8 395.5 0 374.1 0 352s2.8-43.5 8.1-64H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64zm265.1 0c-10 63.9-29.8 117.4-55.3 151.6-25.5-34.2-45.3-87.7-55.3-151.6H307.7z"/></svg>',
);

$has_link = ! empty( $link_url );

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class' => 'acst-team-member acst-team-member--' . esc_attr( $layout ),
) );
?>
<div <?php echo $wrapper_attrs; ?>>
    <?php if ( ! empty( $image_url ) ) : ?>
        <div class="acst-team-member__image">
            <?php if ( $has_link ) : ?>
                <a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target ? ' target="' . esc_attr( $link_target ) . '"' : ''; ?>>
            <?php endif; ?>

            <?php if ( $image_id ) : ?>
                <?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'alt' => esc_attr( $image_alt ) ) ); ?>
            <?php else : ?>
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
            <?php endif; ?>

            <?php if ( $has_link ) : ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="acst-team-member__content">
        <?php if ( ! empty( $name ) ) : ?>
            <<?php echo esc_attr( $name_tag ); ?> class="acst-team-member__name">
                <?php if ( $has_link ) : ?>
                    <a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_target ? ' target="' . esc_attr( $link_target ) . '"' : ''; ?>>
                        <?php echo esc_html( $name ); ?>
                    </a>
                <?php else : ?>
                    <?php echo esc_html( $name ); ?>
                <?php endif; ?>
            </<?php echo esc_attr( $name_tag ); ?>>
        <?php endif; ?>

        <?php if ( ! empty( $role ) ) : ?>
            <div class="acst-team-member__role"><?php echo esc_html( $role ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $bio ) ) : ?>
            <div class="acst-team-member__bio"><?php echo wp_kses_post( $bio ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $social_links ) ) : ?>
            <div class="acst-team-member__social">
                <?php foreach ( $social_links as $social ) :
                    $network = $social['network'] ?? '';
                    $url     = $social['url'] ?? '#';

                    if ( $network === 'email' && strpos( $url, 'mailto:' ) !== 0 && strpos( $url, '@' ) !== false ) {
                        $url = 'mailto:' . $url;
                    }

                    $icon_svg = $social_svg[ $network ] ?? $social_svg['website'];
                ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
                        <?php echo $icon_svg; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
