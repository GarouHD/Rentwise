<?php
/**
 * Rentwise: handle "Update Property" form submissions.
 * Expects POST fields:
 *  - property_id (int, required)
 *  - property_name (string, required)
 *  - property_address (string, required)
 *  - property_units (int)
 *  - property_type (string)
 * Hidden fields:
 *  - action=rentwise_update_property
 *  - _wpnonce=wp_create_nonce('rentwise_update_property')
 */

add_action('admin_post_rentwise_update_property', 'rentwise_handle_update_property');

function rentwise_handle_update_property() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_update_property')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
  $name    = sanitize_text_field($_POST['property_name']    ?? '');
  $address = sanitize_text_field($_POST['property_address'] ?? '');
  $units   = isset($_POST['property_units']) ? (int) $_POST['property_units'] : 0;
  $type    = sanitize_text_field($_POST['property_type']    ?? 'apartment');

  if (!$property_id) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_id']);
  }

  if ($name === '') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_name']);
  }

  if ($address === '') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_address']);
  }

  // Verify the post exists and is a property
  $property = get_post($property_id);
  if (!$property || $property->post_type !== 'property') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'invalid_property']);
  }

  // --- Update Property ---
  $result = wp_update_post([
    'ID'         => $property_id,
    'post_title' => $name,
  ]);

  if (is_wp_error($result)) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'update_error']);
  }

  // --- Update meta / ACF fields ---
  update_post_meta($property_id, 'name', $name);
  update_post_meta($property_id, 'address', $address);
  update_post_meta($property_id, 'units', $units);
  update_post_meta($property_id, 'type', $type);

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['property_updated' => 1, 'property' => $property_id]);
}

