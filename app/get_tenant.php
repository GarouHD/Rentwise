<?php
/**
 * Rentwise: AJAX handler to get tenant data
 */

add_action('wp_ajax_rentwise_get_tenant', 'rentwise_get_tenant_ajax');

function rentwise_get_tenant_ajax() {
  // Check if user is logged in
  if (!is_user_logged_in()) {
    wp_send_json_error(['message' => 'Not authenticated']);
    return;
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    wp_send_json_error(['message' => 'Insufficient permissions']);
    return;
  }

  $tenant_id = isset($_GET['tenant_id']) ? intval($_GET['tenant_id']) : 0;
  
  if (!$tenant_id) {
    wp_send_json_error(['message' => 'Invalid tenant ID']);
    return;
  }

  // Get tenant post
  $tenant = get_post($tenant_id);
  
  if (!$tenant || $tenant->post_type !== 'tenant') {
    wp_send_json_error(['message' => 'Tenant not found']);
    return;
  }

  // Get ACF fields
  $data = [
    'id' => $tenant_id,
    'name' => get_field('name', $tenant_id) ?: $tenant->post_title,
    'unit' => get_field('unit', $tenant_id) ?: '',
    'rent_amount' => get_field('rent_amount', $tenant_id) ?: '',
    'status' => get_field('status', $tenant_id) ?: 'active',
  ];

  wp_send_json_success($data);
}

