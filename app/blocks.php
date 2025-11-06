<?php

/**
 * Register ACF custom blocks.
 */

if (! function_exists('add_action')) {
    return;
}

add_action('acf/init', function () {
    if (function_exists('acf_register_block_type')) {

        acf_register_block_type([
            'name'              => 'tenant-card',
            'title'             => __('Tenant Card'),
            'description'       => __('Reusable block showing tenant overview info.'),
            'render_template'   => get_theme_file_path('/resources/views/blocks/tenant-card.blade.php'),
            'category'          => 'formatting',
            'icon'              => 'id', // WP dashicon
            'keywords'          => ['tenant', 'card', 'profile'],
            'mode'              => 'preview',
            'supports'          => [
                'align' => false,
                'jsx'   => true,
            ],
        ]);
    }
});
