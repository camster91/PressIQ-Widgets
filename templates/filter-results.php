<?php
/**
 * Filter Results Template
 *
 * This template is used to render filtered results via AJAX.
 * You can override this template by copying it to your theme:
 * your-theme/ac-starter-toolkit/filter-results.php
 *
 * @package AC_Starter_Toolkit
 * @var array $posts Array of WP_Post objects
 * @var array $filter_data Active filter data
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( empty( $posts ) ) {
    /**
     * Filter the no results message
     *
     * @param string $message Default no results message.
     */
    $no_results_message = apply_filters(
        'acst/no_results_message',
        __( 'No results found matching your criteria.', 'ac-starter-toolkit' )
    );
    ?>
    <div class="acst-no-results">
        <p><?php echo esc_html( $no_results_message ); ?></p>
    </div>
    <?php
    return;
}

// Determine post type for appropriate template
$first_post = $posts[0];
$post_type  = get_post_type( $first_post );

foreach ( $posts as $post ) :
    setup_postdata( $post );

    // Check if this is a WooCommerce product
    if ( $post_type === 'product' && function_exists( 'wc_get_product' ) ) :
        $product = wc_get_product( $post );
        ?>
        <article <?php post_class( 'acst-filter-item acst-filter-item--product' ); ?>>
            <div class="acst-filter-item__image">
                <a href="<?php the_permalink(); ?>">
                    <?php
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'woocommerce_thumbnail' );
                    } else {
                        echo wc_placeholder_img( 'woocommerce_thumbnail' );
                    }
                    ?>
                </a>
            </div>
            <div class="acst-filter-item__content">
                <h3 class="acst-filter-item__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <?php if ( $product ) : ?>
                    <div class="acst-filter-item__price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ( $product->get_average_rating() ) : ?>
                        <div class="acst-filter-item__rating">
                            <?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </article>
        <?php
    else :
        // Standard post template
        ?>
        <article <?php post_class( 'acst-filter-item' ); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="acst-filter-item__image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'medium' ); ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="acst-filter-item__content">
                <h3 class="acst-filter-item__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <?php if ( has_excerpt() || get_the_content() ) : ?>
                    <div class="acst-filter-item__excerpt">
                        <?php
                        if ( has_excerpt() ) {
                            echo wp_kses_post( get_the_excerpt() );
                        } else {
                            echo wp_kses_post( wp_trim_words( get_the_content(), 20, '...' ) );
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <div class="acst-filter-item__meta">
                    <span class="acst-filter-item__date">
                        <?php echo get_the_date(); ?>
                    </span>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) :
                    ?>
                        <span class="acst-filter-item__categories">
                            <?php
                            $cat_links = array();
                            foreach ( array_slice( $categories, 0, 2 ) as $cat ) {
                                $cat_links[] = sprintf(
                                    '<a href="%s">%s</a>',
                                    esc_url( get_category_link( $cat ) ),
                                    esc_html( $cat->name )
                                );
                            }
                            echo implode( ', ', $cat_links );
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php
    endif;
endforeach;

wp_reset_postdata();
