<?php
/**
 * Rentwise: handle "Delete Property" form submissions.
 * Expects POST fields:
 *  - property_id (int, required)
 * Hidden fields:
 *  - action=rentwise_delete_property
 *  - _wpnonce=wp_create_nonce('rentwise_delete_property')
 */

add_action('admin_post_rentwise_delete_property', 'rentwise_handle_delete_property');

function rentwise_handle_delete_property() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_delete_property')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;

  if (!$property_id) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'missing_id']);
  }

  // Verify the post exists and is a property
  $property = get_post($property_id);
  if (!$property || $property->post_type !== 'property') {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'invalid_property']);
  }

  // --- Delete Property ---
  $result = wp_delete_post($property_id, true);

  if (!$result) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'delete_error']);
  }

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['property_deleted' => 1]);
}

