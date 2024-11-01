<?php

namespace WPPPostSeries;

function get_series(): array
{
    return get_terms([
        'taxonomy' => 'post_series'
    ]);
}

function get_post_ser(int $post_id): ?\WP_Term
{
    $terms = get_the_terms($post_id, 'post_series');

    if (
        is_array($terms)
        && !empty($terms)
        && $terms[0] instanceof \WP_Term
    ) {
        return $terms[0];
    }

    return null;
}

function get_ser_posts(\WP_Term $ser, string $return = 'OBJECTS'): array
{
    $posts_sorted_ids = get_term_meta($ser->term_id, 'posts_sorted', true);

    if (!empty($posts_sorted_ids)) {
        $posts_sorted_ids = get_posts([
            'numberposts' => -1,
            'post__in' => $posts_sorted_ids,
            'orderby' => 'post__in',
            'fields' => 'ids'
        ]);
    } else {
        $posts_sorted_ids = [];
    }

    $posts_all_ids = get_posts([
        'numberposts' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'post_series',
                'field' => 'term_id',
                'terms' => $ser->term_id
            ]
        ],
        'fields' => 'ids'
    ]);

    $posts_ids = array_unique(array_merge($posts_sorted_ids, $posts_all_ids));

    if ($return === 'IDS') {
        return $posts_ids;
    } else {
        return empty($posts_ids)
            ? []
            : get_posts([
                'numberposts' => -1,
                'post__in' => $posts_ids,
                'orderby' => 'post__in'
            ]);
    }
}

function get_ser_post_index(\WP_Term $ser, \WP_Post $post): ?int
{
    $ser_posts = get_ser_posts($ser);

    foreach ($ser_posts as $k => $ser_post) {
        if ($ser_post->ID === $post->ID) {
            return $k + 1;
        }
    }

    return null;
}
