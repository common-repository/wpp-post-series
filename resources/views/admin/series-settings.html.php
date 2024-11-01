<div class="series-settings-header privacy-settings-header">
    <div class="series-settings-title-section privacy-settings-title-section">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    </div>
    <nav class="series-settings-tabs-wrapper privacy-settings-tabs-wrapper hide-if-no-js"
         aria-label="<?php esc_attr_e('Secondary menu'); ?>">
        <a href="<?php echo esc_url(admin_url('options-general.php?page=wpp-post-series')); ?>"
           class="series-settings-tab privacy-settings-tab active"
           aria-current="true">
            <?php _e('General'); ?>
        </a>
    </nav>
</div>

<hr class="wp-header-end">

<div class="notice notice-error hide-if-js">
    <p><?php _e('The Post Series Settings require JavaScript.'); ?></p>
</div>

<div class="series-settings-body privacy-settings-body hide-if-no-js">
    <form action="options.php" method="post">
        <?php
        settings_fields(WPPPostSeries\PLUGIN_SLUG);
        do_settings_sections(WPPPostSeries\PLUGIN_SETTINGS_PAGE);
        submit_button('Save Settings');
        ?>
    </form>
</div>
