<?php
/**
 * Rentwise: handle "Add Tenant" form submissions.
 * Now includes auto-property creation from tenant_property field.
 * Expects POST fields:
 *  - tenant_name (string, required)
 *  - tenant_unit (string)
 *  - tenant_property (string) - NEW: auto-creates property if needed
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
  $name            = sanitize_text_field($_POST['tenant_name']       ?? '');
  $unit            = sanitize_text_field($_POST['tenant_unit']       ?? '');
  $property        = sanitize_text_field($_POST['tenant_property']   ?? '');
  $rent            = isset($_POST['rent_amount']) ? (float) $_POST['rent_amount'] : 0.0;
  $status          = sanitize_text_field($_POST['tenant_status']     ?? 'active');
  $lease_start     = sanitize_text_field($_POST['lease_start_date']  ?? '');
  $lease_end       = sanitize_text_field($_POST['lease_end_date']    ?? '');

  if ($name === '') {
    rentwise_redirect_back(['added' => 0, 'reason' => 'missing_name']);
  }

  // --- Create Tenant CPT ---
  $tenant_id = wp_insert_post([
    'post_type'   => 'tenant',
    'post_status' => 'publish',
    'post_title'  => $name,
  ]);

  if (is_wp_error($tenant_id) || !$tenant_id) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'insert']);
  }

  // --- Handle Property: Find or Create ---
  $property_id = null;
  if ($property !== '') {
    $property_id = rentwise_find_or_create_property($property, $user->ID);
  }

  // --- Attach meta / ACF fields ---
  update_post_meta($tenant_id, 'name', $name);
  update_post_meta($tenant_id, 'landlord', $user->ID);
  update_post_meta($tenant_id, 'unit', $unit);
  update_post_meta($tenant_id, 'rent_amount', $rent);
  update_post_meta($tenant_id, 'status', $status);

  if ($property_id) {
    update_post_meta($tenant_id, 'property_id', $property_id);
    update_post_meta($tenant_id, 'property', $property); // Store name for easy display
  }
  
  // Save lease dates if provided
  if ($lease_start) {
    update_post_meta($tenant_id, 'lease_start_date', $lease_start);
  }
  if ($lease_end) {
    update_post_meta($tenant_id, 'lease_end_date', $lease_end);
  }

  // --- Redirect back to dashboard (page reload so ACF block re-renders) ---
  rentwise_redirect_back(['added' => 1, 'tenant' => $tenant_id]);
}

/**
 * Find existing property by name or create a new one.
 * Returns the property post ID.
 */
function rentwise_find_or_create_property($property_name, $landlord_id) {
  // Search for existing property with this name for this landlord
  $existing = get_posts([
    'post_type'   => 'property',
    'post_status' => 'publish',
    'title'       => $property_name,
    'meta_query'  => [
      [
        'key'   => 'landlord',
        'value' => $landlord_id,
      ]
    ],
    'posts_per_page' => 1,
  ]);

  if (!empty($existing)) {
    return $existing[0]->ID;
  }

  // Property doesn't exist, create it
  $property_id = wp_insert_post([
    'post_type'   => 'property',
    'post_status' => 'publish',
    'post_title'  => $property_name,
  ]);

  if (is_wp_error($property_id) || !$property_id) {
    return null;
  }

  // Set basic property metadata
  update_post_meta($property_id, 'name', $property_name);
  update_post_meta($property_id, 'landlord', $landlord_id);
  update_post_meta($property_id, 'property_type', 'apartment'); // Default
  update_post_meta($property_id, 'units', 1); // Start with 1 unit
  update_post_meta($property_id, 'property_value', 0); // Default value

  return $property_id;
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
