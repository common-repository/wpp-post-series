<input type="checkbox"
       name="<?php echo esc_attr($option_name); ?>[<?php echo esc_attr($args['field_id']); ?>]"
       id="<?php echo esc_attr($args['field_id']); ?>"
       value="1" <?php echo isset($option_value[$args['field_id']]) ? checked($option_value[$args['field_id']], '1', false) : ''; ?>>
<?php esc_html_e('Enabled'); ?>
<p class="description">
    <?php esc_html_e('Automatically inserts Series information before the Post content.', 'wpp-post-series'); ?>
</p>
