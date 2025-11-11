<?php
/**
 * Rentwise: handle "Add Tenant" form submissions.
 * Expects POST fields:
 *  - tenant_name (string, required)
 *  - tenant_unit (string)
 *  - rent_amount (float)
 *  - tenant_status (string: active|inactive|overdue|pending)
 * Hidden fields provided in the form:
 *  - action=rentwise_add_tenant
 *  - _wpnonce=wp_create_nonce('rentwise_add_tenant')
 */

add_action('admin_post_rentwise_add_tenant', 'rentwise_handle_add_tenant');

function rentwise_handle_add_tenant() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_add_tenant')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $name   = sanitize_text_field($_POST['tenant_name']  ?? '');
  $unit   = sanitize_text_field($_POST['tenant_unit']  ?? '');
  $rent   = isset($_POST['rent_amount']) ? (float) $_POST['rent_amount'] : 0.0;
  $status = sanitize_text_field($_POST['tenant_status'] ?? 'active');

  if ($name === '') {
    rentwise_redirect_back(['added' => 0, 'reason' => 'missing_name']);
  }

  // --- Create Tenant CPT ---
  // Make sure your CPT 'tenant' is registered elsewhere.
  $post_id = wp_insert_post([
    'post_type'   => 'tenant',
    'post_status' => 'publish',
    'post_title'  => $name,
  ]);

  if (is_wp_error($post_id) || !$post_id) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'insert']);
  }

  // --- Attach meta / ACF fields ---
  update_post_meta($post_id, 'name', $name);
  update_post_meta($post_id, 'landlord', $user->ID);
  update_post_meta($post_id, 'unit', $unit);
  update_post_meta($post_id, 'rent_amount', $rent);
  update_post_meta($post_id, 'status', $status);

  // You can also set defaults for any other fields here.

  // --- Redirect back to dashboard (page reload so ACF block re-renders) ---
  rentwise_redirect_back(['added' => 1, 'tenant' => $post_id]);
}

/**
 * Helper: safe redirect back with query args.
 */
function rentwise_redirect_back(array $args = []) {
  $back = wp_get_referer();
  if (!$back) {
    // fallback to a known dashboard page; change slug if needed
    $back = home_url('/dashboard');
  }
  wp_safe_redirect(add_query_arg($args, $back));
  exit;
}
