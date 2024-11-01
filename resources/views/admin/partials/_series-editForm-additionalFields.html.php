<tr class="form-field">
    <th scope="row">
        <label><?php _e('Order Posts', 'wpp-post-series'); ?></label>
        <p class="series-count"><?php printf(_n('%s Post', '%s Posts', $ser->count), $ser->count); ?></p>
    </th>
    <td>
        <ul class="series-list">
            <?php foreach ($posts as $post): ?>
                <li id="<?php esc_attr_e($post->ID); ?>">
                    <div class="menu-item-bar">
                        <div class="menu-item-handle">
                            <label class="item-title">
                                <span class="menu-item-title">
                                    <a href="<?php echo esc_url(get_edit_post_link($post)); ?>" target="_blank">
                                        <?php esc_html_e($post->post_title); ?>
                                    </a>
                                </span>
                            </label>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </td>
    <input type="hidden" name="posts_ids" value="<?php esc_attr_e($posts_ids); ?>">
</tr>