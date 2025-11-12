<?php
/**
 * Rentwise: AJAX handler to get property data
 */

add_action('wp_ajax_rentwise_get_property', 'rentwise_get_property_ajax');

function rentwise_get_property_ajax() {
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

  $property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;
  
  if (!$property_id) {
    wp_send_json_error(['message' => 'Invalid property ID']);
    return;
  }

  // Get property post
  $property = get_post($property_id);
  
  if (!$property || $property->post_type !== 'property') {
    wp_send_json_error(['message' => 'Property not found']);
    return;
  }

  // Get ACF fields / meta
  $data = [
    'id' => $property_id,
    'name' => get_field('name', $property_id) ?: $property->post_title,
    'address' => get_field('address', $property_id) ?: '',
    'units' => get_field('units', $property_id) ?: '',
    'type' => get_field('type', $property_id) ?: 'apartment',
  ];

  wp_send_json_success($data);
}

