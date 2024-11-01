<?php

namespace WPPPostSeries;

/**
 * Load languages
 */
add_filter('init', function () {
    load_plugin_textdomain('wpp-post-series', false, dirname(plugin_basename(__DIR__)) . '/languages');
});

/**
 * Replace [comma] by , in Series name
 */
add_filter('get_term', function (\WP_Term $term, string $taxonomy) {
    if (
        !is_admin()
        && $taxonomy === 'post_series'
        && str_contains($term->name, '[comma]')
    ) {
        $term->name = str_replace('[comma]', ',', $term->name);
    }
    return $term;
}, 10, 2);

/**
 * Add "privacy-settings" class to admin body if plugin settings page
 */
add_filter('admin_body_class', function (string $classes) {
    global $pagenow;
    if (
        $pagenow === 'options-general.php'
        && !empty($_GET['page'])
        && $_GET['page'] === PLUGIN_SETTINGS_PAGE
    ) {
        $classes .= ' privacy-settings';
    }
    return $classes;
});

/**
 * Add Series info before Post content
 */
add_filter('the_content', function (string $content) {
    $option_value = get_option(PLUGIN_SETTINGS_OPTION_NAME);
    $ser = get_post_ser(get_the_ID());

    if (
        is_singular()
        && get_post_type() === 'post'
        && in_the_loop()
        && is_main_query()
        && $ser instanceof \WP_Term
        && isset($option_value['auto_insert'])
        && $option_value['auto_insert'] === '1'
    ) {
        $ser_posts_count = count(get_ser_posts($ser));
        $post_index = get_ser_post_index($ser, get_post());

        ob_start();
        require plugin_dir_path(PLUGIN_FILE) . 'resources/views/public/partials/_series-info.html.php';
        $html = ob_get_contents();
        ob_end_clean();

        return $html . $content;
    }

    return $content;
});