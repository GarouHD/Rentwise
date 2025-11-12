<?php
/**
 * Rentwise: handle "Add Property" form submissions.
 * Expects POST fields:
 *  - property_name (string, required)
 *  - property_address (string, required)
 *  - property_units (int)
 *  - property_type (string)
 * Hidden fields:
 *  - action=rentwise_add_property
 *  - _wpnonce=wp_create_nonce('rentwise_add_property')
 */

add_action('admin_post_rentwise_add_property', 'rentwise_handle_add_property');

function rentwise_handle_add_property() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_add_property')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $name    = sanitize_text_field($_POST['property_name']    ?? '');
  $address = sanitize_text_field($_POST['property_address'] ?? '');
  $units   = isset($_POST['property_units']) ? (int) $_POST['property_units'] : 0;
  $type    = sanitize_text_field($_POST['property_type']    ?? 'apartment');

  if ($name === '') {
    rentwise_redirect_back(['added' => 0, 'reason' => 'missing_name']);
  }

  if ($address === '') {
    rentwise_redirect_back(['added' => 0, 'reason' => 'missing_address']);
  }

  // --- Create Property CPT ---
  $post_id = wp_insert_post([
    'post_type'   => 'property',
    'post_status' => 'publish',
    'post_title'  => $name,
  ]);

  if (is_wp_error($post_id) || !$post_id) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'insert']);
  }

  // --- Attach meta / ACF fields ---
  update_post_meta($post_id, 'name', $name);
  update_post_meta($post_id, 'landlord', $user->ID);
  update_post_meta($post_id, 'address', $address);
  update_post_meta($post_id, 'units', $units);
  update_post_meta($post_id, 'type', $type);

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['property_added' => 1, 'property' => $post_id]);
}

