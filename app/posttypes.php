<?php

add_action('init', function() {

    register_post_type('tenant', [
        'label' => 'Tenants',
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'thumbnail'],
        'menu_icon' => 'dashicons-groups',
    ]);

    register_post_type('payment', [
        'label' => 'Payments',
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-money-alt',
    ]);
    
});
