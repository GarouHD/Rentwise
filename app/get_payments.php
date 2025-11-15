<?php

add_action('wp_ajax_rentwise_get_payments', 'rentwise_get_payments_ajax');

function rentwise_get_payments_ajax() {
    // 1. Security: nonce + logged-in (you can add a nonce later if you want)
    if ( ! is_user_logged_in() ) {
        wp_send_json_error(['message' => 'Not logged in.'], 401);
    }

    $tenant_id = isset($_POST['tenant_id']) ? (int) $_POST['tenant_id'] : 0;

    if ( ! $tenant_id ) {
        wp_send_json_error(['message' => 'Missing tenant id.'], 400);
    }

    $tenant_post = get_post($tenant_id);
    if ( ! $tenant_post || 'tenant' !== $tenant_post->post_type ) {
        wp_send_json_error(['message' => 'Invalid tenant.'], 400);
    }

    // 2. Query payment CPTs for this tenant
    $payments_query = new WP_Query([
        'post_type'      => 'payment',           // your CPT slug
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'meta_query'     => [
            [
                'key'   => 'tenant',            // meta key that stores tenant ID
                'value' => $tenant_id,
                'compare' => '=',
            ],
        ],
        'meta_key'       => 'paid_on',           // sort by date
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
    ]);

    $payments = [];
    $balance  = 0.0;

    if ( $payments_query->have_posts() ) {
        foreach ( $payments_query->posts as $post ) {
            $amount = (float) get_post_meta($post->ID, 'amount', true);
            $paid_on = get_post_meta($post->ID, 'paid_on', true);
            $status = get_post_meta($post->ID, 'status', true);

            $payments[] = [
                'amount'  => $amount,
                'paid_on' => $paid_on,
                'status'  => $status,
            ];

            if ($status === 'paid') {
                $balance += $amount; // super simple example
            }
        }
    }

    // 3. Render Blade partial to HTML
    ob_start();
    echo \Roots\view('partials.tenant-payments', [
        'payments' => $payments,
    ])->render();
    $html = ob_get_clean();

    wp_send_json_success([
        'html'    => $html,
        'balance' => $balance,
    ]);
}
