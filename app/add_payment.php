<?php

// Handle POST from record-payment modal
add_action('admin_post_rentwise_record_payment', 'rentwise_handle_record_payment');

function rentwise_handle_record_payment() {
    // 1. Basic security: nonce check + login check
    if (
        ! isset($_POST['_wpnonce']) ||
        ! wp_verify_nonce($_POST['_wpnonce'], 'rentwise_record_payment')
    ) {
        // Redirect with error code
        $redirect = wp_get_referer() ?: home_url('/dashboard');
        wp_safe_redirect( add_query_arg('error', 'invalid_nonce', $redirect) );
        exit;
    }

    if ( ! is_user_logged_in() ) {
        $redirect = home_url('/login');
        wp_safe_redirect( add_query_arg('error', 'not_logged_in', $redirect) );
        exit;
    }


    // 2. Get and sanitize inputs
    $tenant_id = isset($_POST['tenant_id']) ? (int) $_POST['tenant_id'] : 0;
    $amount    = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $paid_on   = isset($_POST['paid_on']) ? sanitize_text_field($_POST['paid_on']) : '';
    $status    = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'paid';

    // normalize status
    $status = in_array($status, ['paid', 'overdue', 'due'], true) ? $status : 'paid';

    // 3. Basic validation
    if ( ! $tenant_id || $amount <= 0 ) {
        $redirect = wp_get_referer() ?: home_url('/dashboard');
        wp_safe_redirect( add_query_arg('error', 'invalid_payment_data', $redirect) );
        exit;
    }

    // Validate date format (YYYY-MM-DD from <input type="date">)
    $date_ok = DateTime::createFromFormat('Y-m-d', $paid_on) !== false;
    if ( ! $date_ok ) {
        $paid_on = current_time('Y-m-d');
    }

    // 4. (Light) validation: tenant post exists and is of type 'tenant'
    $tenant_post = get_post($tenant_id);
    if ( ! $tenant_post || 'tenant' !== $tenant_post->post_type ) {
        $redirect = wp_get_referer() ?: home_url('/dashboard');
        wp_safe_redirect( add_query_arg('error', 'invalid_tenant', $redirect) );
        exit;
    }

    // 5. Create payment CPT
    $current_user_id = get_current_user_id();

    $payment_title = sprintf(
        'Payment for %s on %s',
        get_the_title($tenant_id),
        $paid_on
    );

    $payment_postarr = [
        'post_type'   => 'payment',          // your CPT slug
        'post_title'  => $payment_title,
        'post_status' => 'publish',
        'post_author' => $current_user_id,
    ];

    $payment_id = wp_insert_post($payment_postarr);

    if ( is_wp_error($payment_id) || ! $payment_id ) {
        $redirect = wp_get_referer() ?: home_url('/dashboard');
        wp_safe_redirect( add_query_arg('error', 'payment_insert_failed', $redirect) );
        exit;
    }

    // 6. Save meta fields for the payment
    // Adjust these keys to match your ACF field names if needed.
    update_post_meta($payment_id, 'tenant', $tenant_id);   // relation to tenant CPT
    update_post_meta($payment_id, 'status', $status);      // 'paid' or 'overdue'
    update_post_meta($payment_id, 'amount', $amount);      // numeric
    update_post_meta($payment_id, 'paid_on', $paid_on);    // YYYY-MM-DD
    
    // Payment method (default to 'manual' for manually recorded payments)
    $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : 'manual';
    update_post_meta($payment_id, 'payment_method', $payment_method);

    // 7. Redirect back (dashboard or referrer) with success flag
    $redirect = wp_get_referer();
    if ( ! $redirect ) {
        $dashboard_page = get_page_by_path('dashboard');
        $redirect = $dashboard_page ? get_permalink($dashboard_page) : home_url('/');
    }

    $redirect = add_query_arg('payment_saved', '1', $redirect);
    wp_safe_redirect($redirect);
    exit;
}

// function rentwise_update_tenant_balance($tenant_id, array $payments) {
//     // Example: assume monthly rent is stored on tenant meta
//     $rent = (float) get_post_meta($tenant_id, 'rent_amount', true);

//     // Start at 0; subtract each paid amount from balance
//     $balance = 0.0;

//     foreach ($payments as $p) {
//         if (isset($p['status']) && 'paid' === $p['status']) {
//             $balance -= (float) $p['amount'];
//         }
//         // You might handle 'overdue' entries differently if needed
//     }

//     // Store as meta; your AJAX "tenant details" endpoint
//     // can read this and return it to the modal.
//     update_post_meta($tenant_id, 'rentwise_balance', $balance);
// }