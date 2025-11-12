<?php
/**
 * Rentwise: handle "Update Tenant" form submissions.
 * Expects POST fields:
 *  - tenant_id (int, required)
 *  - tenant_name (string, required)
 *  - tenant_unit (string)
 *  - rent_amount (float)
 *  - tenant_status (string: active|inactive|overdue|pending)
 * Hidden fields:
 *  - action=rentwise_update_tenant
 *  - _wpnonce=wp_create_nonce('rentwise_update_tenant')
 */

add_action('admin_post_rentwise_update_tenant', 'rentwise_handle_update_tenant');

function rentwise_handle_update_tenant() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_update_tenant')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $tenant_id = isset($_POST['tenant_id']) ? intval($_POST['tenant_id']) : 0;
  $name   = sanitize_text_field($_POST['tenant_name']  ?? '');
  $unit   = sanitize_text_field($_POST['tenant_unit']  ?? '');
  $rent   = isset($_POST['rent_amount']) ? (float) $_POST['rent_amount'] : 0.0;
  $status = sanitize_text_field($_POST['tenant_status'] ?? 'active');

  if (!$tenant_id) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_id']);
  }

  if ($name === '') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_name']);
  }

  // Verify the post exists and is a tenant
  $tenant = get_post($tenant_id);
  if (!$tenant || $tenant->post_type !== 'tenant') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'invalid_tenant']);
  }

  // --- Update Tenant ---
  $result = wp_update_post([
    'ID'         => $tenant_id,
    'post_title' => $name,
  ]);

  if (is_wp_error($result)) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'update_error']);
  }

  // --- Update meta / ACF fields ---
  update_post_meta($tenant_id, 'name', $name);
  update_post_meta($tenant_id, 'unit', $unit);
  update_post_meta($tenant_id, 'rent_amount', $rent);
  update_post_meta($tenant_id, 'status', $status);

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['updated' => 1, 'tenant' => $tenant_id]);
}

