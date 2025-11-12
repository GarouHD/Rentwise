<?php
/**
 * Rentwise: handle "Delete Tenant" form submissions.
 * Expects POST fields:
 *  - tenant_id (int, required)
 * Hidden fields:
 *  - action=rentwise_delete_tenant
 *  - _wpnonce=wp_create_nonce('rentwise_delete_tenant')
 */

add_action('admin_post_rentwise_delete_tenant', 'rentwise_handle_delete_tenant');

function rentwise_handle_delete_tenant() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_delete_tenant')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $tenant_id = isset($_POST['tenant_id']) ? intval($_POST['tenant_id']) : 0;

  if (!$tenant_id) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'missing_id']);
  }

  // Verify the post exists and is a tenant
  $tenant = get_post($tenant_id);
  if (!$tenant || $tenant->post_type !== 'tenant') {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'invalid_tenant']);
  }

  // --- Delete Tenant ---
  // Use wp_delete_post with force_delete = true to permanently delete
  $result = wp_delete_post($tenant_id, true);

  if (!$result) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'delete_error']);
  }

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['deleted' => 1]);
}

