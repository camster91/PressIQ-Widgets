<?php
/**
 * Post Filter Block - Server-side Render
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

$query_id           = $attributes['queryId'] ?? 'main_query';
$post_type          = $attributes['postType'] ?? 'post';
$posts_per_page     = intval( $attributes['postsPerPage'] ?? 9 );
$show_search        = ! empty( $attributes['showSearch'] );
$search_placeholder = $attributes['searchPlaceholder'] ?? 'Search...';
$show_sort          = ! empty( $attributes['showSort'] );
$taxonomy_filters   = $attributes['taxonomyFilters'] ?? array();
$layout             = $attributes['layout'] ?? 'grid';
$columns            = intval( $attributes['columns'] ?? 3 );
$show_excerpt       = ! empty( $attributes['showExcerpt'] );
$show_thumbnail     = ! empty( $attributes['showThumbnail'] );
$show_meta          = ! empty( $attributes['showMeta'] );

$block_id = wp_unique_id( 'pressiq-post-filter-' );

$wrapper_attrs = get_block_wrapper_attributes( array(
    'class'         => 'pressiq-post-filter',
    'data-query-id' => esc_attr( $query_id ),
    'data-post-type' => esc_attr( $post_type ),
    'data-per-page'  => esc_attr( $posts_per_page ),
) );

// Get available taxonomies for this post type.
$available_taxonomies = get_object_taxonomies( $post_type, 'objects' );

// Build initial query.
$query_args = array(
    'post_type'      => $post_type,
    'posts_per_page' => $posts_per_page,
    'post_status'    => 'publish',
);

$query = new WP_Query( $query_args );
?>
<div <?php echo $wrapper_attrs; ?>>
    <div class="pressiq-post-filter__controls">
        <?php if ( $show_search ) : ?>
            <div class="pressiq-filter pressiq-filter--search">
                <label class="pressiq-filter__label" for="<?php echo esc_attr( $block_id ); ?>-search">
                    <?php esc_html_e( 'Search', 'pressiq-widgets' ); ?>
                </label>
                <div class="pressiq-filter__search-wrap">
                    <input type="text"
                           id="<?php echo esc_attr( $block_id ); ?>-search"
                           class="pressiq-filter__search-input"
                           placeholder="<?php echo esc_attr( $search_placeholder ); ?>"
                           data-filter-type="search"
                           data-query-id="<?php echo esc_attr( $query_id ); ?>" />
                    <button type="button" class="pressiq-filter__search-clear" aria-label="<?php esc_attr_e( 'Clear search', 'pressiq-widgets' ); ?>" hidden>&times;</button>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Render taxonomy filters.
        if ( ! empty( $taxonomy_filters ) ) :
            foreach ( $taxonomy_filters as $tax_filter ) :
                $taxonomy = $tax_filter['taxonomy'] ?? '';
                if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
                    continue;
                }
                $tax_obj = get_taxonomy( $taxonomy );
                $terms   = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                ) );
                if ( empty( $terms ) || is_wp_error( $terms ) ) {
                    continue;
                }
                $filter_type = $tax_filter['type'] ?? 'select';
                $filter_id   = 'tax_' . $taxonomy;
            ?>
                <div class="pressiq-filter pressiq-filter--<?php echo esc_attr( $filter_type ); ?>"
                     data-filter-id="<?php echo esc_attr( $filter_id ); ?>"
                     data-query-id="<?php echo esc_attr( $query_id ); ?>"
                     data-source-type="taxonomy"
                     data-filter-type="pressiq-<?php echo esc_attr( $filter_type ); ?>-filter">
                    <label class="pressiq-filter__label"><?php echo esc_html( $tax_obj->labels->singular_name ); ?></label>
                    <?php if ( $filter_type === 'select' ) : ?>
                        <select class="pressiq-filter__select" data-filter-id="<?php echo esc_attr( $filter_id ); ?>">
                            <option value=""><?php echo esc_html( sprintf( __( 'All %s', 'pressiq-widgets' ), $tax_obj->labels->name ) ); ?></option>
                            <?php foreach ( $terms as $term ) : ?>
                                <option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ( $filter_type === 'checkbox' ) : ?>
                        <div class="pressiq-filter__options">
                            <?php foreach ( $terms as $term ) : ?>
                                <label class="pressiq-filter__checkbox-label">
                                    <input type="checkbox"
                                           class="pressiq-filter__checkbox"
                                           value="<?php echo esc_attr( $term->slug ); ?>"
                                           data-filter-id="<?php echo esc_attr( $filter_id ); ?>" />
                                    <span class="pressiq-filter__checkbox-text"><?php echo esc_html( $term->name ); ?></span>
                                    <span class="pressiq-filter__count">(<?php echo esc_html( $term->count ); ?>)</span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
            endforeach;
        endif;
        ?>

        <?php if ( $show_sort ) : ?>
            <div class="pressiq-filter pressiq-filter--sort">
                <label class="pressiq-filter__label" for="<?php echo esc_attr( $block_id ); ?>-sort">
                    <?php esc_html_e( 'Sort By', 'pressiq-widgets' ); ?>
                </label>
                <select id="<?php echo esc_attr( $block_id ); ?>-sort"
                        class="pressiq-filter__select"
                        data-filter-type="sort"
                        data-query-id="<?php echo esc_attr( $query_id ); ?>">
                    <option value="date_desc"><?php esc_html_e( 'Newest First', 'pressiq-widgets' ); ?></option>
                    <option value="date_asc"><?php esc_html_e( 'Oldest First', 'pressiq-widgets' ); ?></option>
                    <option value="title_asc"><?php esc_html_e( 'Title (A-Z)', 'pressiq-widgets' ); ?></option>
                    <option value="title_desc"><?php esc_html_e( 'Title (Z-A)', 'pressiq-widgets' ); ?></option>
                </select>
            </div>
        <?php endif; ?>
    </div>

    <div class="pressiq-post-filter__results pressiq-post-filter__results--<?php echo esc_attr( $layout ); ?>"
         data-query-id="<?php echo esc_attr( $query_id ); ?>"
         style="--pressiq-columns: <?php echo esc_attr( $columns ); ?>">
        <?php if ( $query->have_posts() ) : ?>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <article class="pressiq-post-filter__item">
                    <?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
                        <div class="pressiq-post-filter__thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'medium_large' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="pressiq-post-filter__content">
                        <h3 class="pressiq-post-filter__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ( $show_meta ) : ?>
                            <div class="pressiq-post-filter__meta">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) :
                                ?>
                                    <span class="pressiq-post-filter__categories">
                                        <?php echo esc_html( $categories[0]->name ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( $show_excerpt ) : ?>
                            <div class="pressiq-post-filter__excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="pressiq-post-filter__no-results">
                <?php esc_html_e( 'No results found.', 'pressiq-widgets' ); ?>
            </p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <?php if ( $query->max_num_pages > 1 ) : ?>
        <div class="pressiq-post-filter__pagination" data-query-id="<?php echo esc_attr( $query_id ); ?>" data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>">
            <button type="button" class="pressiq-post-filter__load-more pressiq-button">
                <?php esc_html_e( 'Load More', 'pressiq-widgets' ); ?>
            </button>
        </div>
    <?php endif; ?>
</div>
