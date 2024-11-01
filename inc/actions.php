<?php

namespace WPPPostSeries;

use WPPPostSeries\Admin\Controller\PostController;

/**
 * Register post_series taxonomy
 */
add_action('init', function () {
    register_taxonomy(
        'post_series',
        'post',
        [
            'labels' => [
                'name'                       => __('Series', 'wpp-post-series'),
                'singular_name'              => _x('Series', 'singular', 'wpp-post-series'),
                'search_items'               => __('Search Series', 'wpp-post-series'),
                'popular_items'              => __('Popular Series', 'wpp-post-series'),
                'all_items'                  => __('All Series', 'wpp-post-series'),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __('Edit Series', 'wpp-post-series'),
                'view_item'                  => __('View Series', 'wpp-post-series'),
                'update_item'                => __('Update Series', 'wpp-post-series'),
                'add_new_item'               => __('Add New Series', 'wpp-post-series'),
                'new_item_name'              => __('New Series Name', 'wpp-post-series'),
                'separate_items_with_commas' => __('Separate series with commas', 'wpp-post-series'),
                'add_or_remove_items'        => __('Add or remove series', 'wpp-post-series'),
                'choose_from_most_used'      => __('Choose from the most used series', 'wpp-post-series'),
                'not_found'                  => __('No series found.', 'wpp-post-series'),
                'no_terms'                   => __('No series', 'wpp-post-series'),
                'filter_by_item'             => null,
                'items_list_navigation'      => __('Series list navigation', 'wpp-post-series'),
                'items_list'                 => __('Series list', 'wpp-post-series'),
                'most_used'                  => __('Most Used', 'wpp-post-series'),
                'back_to_items'              => __('&larr; Go to Series', 'wpp-post-series'),
                'item_link'                  => __('Series Link', 'wpp-post-series'),
                'item_link_description'      => __('A link to a series.', 'wpp-post-series'),
            ],
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => [
                'slug' => 'series'
            ]
        ]
    );
});

/**
 * Add admin scripts and styles
 */
add_action('admin_enqueue_scripts', function (string $hook_suffix) {
    if ($hook_suffix === 'term.php') {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script(
            'wpp-post-series-admin-js',
            plugin_dir_url(PLUGIN_FILE) . 'resources/assets/js/admin.js',
            ['jquery-ui-sortable'],
            null
        );
    }

    wp_enqueue_style(
        'wpp-post-series-admin-css',
        plugin_dir_url(PLUGIN_FILE) . 'resources/assets/css/admin.css',
        [],
        null
    );
});

/**
 * Sort Posts in Series Archive
 */
add_action('pre_get_posts', function (\WP_Query $query) {
    if (
        $query->is_main_query()
        && !is_admin()
        && $query->is_tax('post_series')
    ) {
        $ser = get_queried_object();
        $posts_ids = get_ser_posts($ser, 'IDS');
        if (!empty($posts_ids)) {
            $query->set('post__in', $posts_ids);
            $query->set('orderby', 'post__in');
        }
    }
});

/**
 * Add Posts ordering in Series edit form
 */
add_action('post_series_edit_form_fields', function (\WP_Term $ser) {
    $posts = \WPPPostSeries\get_ser_posts($ser);
    $posts_ids = implode(',', array_map(function($post) {
        return $post->ID;
    }, $posts));

    ob_start();
    require plugin_dir_path(PLUGIN_FILE) . 'resources/views/admin/partials/_series-editForm-additionalFields.html.php';
    ob_end_flush();
});

/**
 * Save Posts sorted when submit Series edit form
 */
add_action('edit_terms', function (int $term_id, string $taxonomy) {
    if ($taxonomy !== 'post_series' || empty($_POST['posts_ids'])) {
        return;
    }

    $posts_ids = sanitize_text_field($_POST['posts_ids']);

    if (preg_match('/^\d+(?:,\d+)*$/', $posts_ids) !== 1) {
        return;
    }

    $posts_ids = explode(',', $posts_ids);

    update_term_meta($term_id, 'posts_sorted', $posts_ids);
}, 10, 2);

/**
 * Add plugin settings page in admin
 */
add_action('admin_menu', function () {
    add_options_page(
        __('Series Settings', 'wpp-post-series'),
        __('Series', 'wpp-post-series'),
        'manage_options',
        PLUGIN_SETTINGS_PAGE,
        function () {
            ob_start();
            require plugin_dir_path(PLUGIN_FILE) . 'resources/views/admin/series-settings.html.php';
            ob_end_flush();
        }
    );
});

/**
 * Register plugin settings
 */
add_action('admin_init', function () {
    register_setting(
        PLUGIN_SLUG,
        PLUGIN_SETTINGS_OPTION_NAME
    );

    // Sections
    add_settings_section(
        sprintf('%s_section_general', PLUGIN_SLUG),
        __('General'),
        function (array $args) {
        },
        PLUGIN_SETTINGS_PAGE
    );

    // Fields
    $field_id = 'auto_insert';
    add_settings_field(
        $field_id,
        __('Auto Insert', 'wpp-post-series'),
        function (array $args) {
            $option_name = PLUGIN_SETTINGS_OPTION_NAME;
            $option_value = get_option(PLUGIN_SETTINGS_OPTION_NAME);
            ob_start();
            require plugin_dir_path(PLUGIN_FILE) . 'resources/views/admin/partials/_series-settings-autoInsert.html.php';
            ob_end_flush();
        },
        PLUGIN_SETTINGS_PAGE,
        sprintf('%s_section_general', PLUGIN_SLUG),
        [
            'field_id' => $field_id,
            'label_for' => $field_id
        ]
    );
});
