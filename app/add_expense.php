<?php
/**
 * Rentwise: handle "Add Expense" form submissions
 */

add_action('admin_post_rentwise_add_expense', 'rentwise_handle_add_expense');

function rentwise_handle_add_expense() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_add_expense')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $description = sanitize_text_field($_POST['expense_description'] ?? '');
  $amount      = isset($_POST['expense_amount']) ? (float) $_POST['expense_amount'] : 0.0;
  $category    = sanitize_text_field($_POST['expense_category'] ?? 'other');
  $property_id = isset($_POST['expense_property']) ? intval($_POST['expense_property']) : 0;
  $date        = sanitize_text_field($_POST['expense_date'] ?? date('Y-m-d'));

  if ($description === '') {
    rentwise_redirect_back(['added' => 0, 'reason' => 'missing_description']);
  }

  if ($amount <= 0) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'invalid_amount']);
  }

  // --- Create Expense CPT ---
  $expense_id = wp_insert_post([
    'post_type'   => 'expense',
    'post_status' => 'publish',
    'post_title'  => $description,
    'post_date'   => $date . ' 00:00:00',
  ]);

  if (is_wp_error($expense_id) || !$expense_id) {
    rentwise_redirect_back(['added' => 0, 'reason' => 'insert']);
  }

  // --- Attach meta fields ---
  update_post_meta($expense_id, 'description', $description);
  update_post_meta($expense_id, 'amount', $amount);
  update_post_meta($expense_id, 'category', $category);
  update_post_meta($expense_id, 'landlord', $user->ID);
  update_post_meta($expense_id, 'date', $date);
  
  if ($property_id > 0) {
    update_post_meta($expense_id, 'property_id', $property_id);
  }

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['expense_added' => 1, 'expense' => $expense_id]);
}

