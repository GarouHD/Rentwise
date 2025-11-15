<?php
/**
 * Rentwise: handle "Update Expense" form submissions
 */

add_action('admin_post_rentwise_update_expense', 'rentwise_handle_update_expense');

function rentwise_handle_update_expense() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_update_expense')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $expense_id  = isset($_POST['expense_id']) ? intval($_POST['expense_id']) : 0;
  $description = sanitize_text_field($_POST['expense_description'] ?? '');
  $amount      = isset($_POST['expense_amount']) ? (float) $_POST['expense_amount'] : 0.0;
  $category    = sanitize_text_field($_POST['expense_category'] ?? 'other');
  $property_id = isset($_POST['expense_property']) ? intval($_POST['expense_property']) : 0;
  $date        = sanitize_text_field($_POST['expense_date'] ?? date('Y-m-d'));

  if (!$expense_id) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_id']);
  }

  if ($description === '') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'missing_description']);
  }

  // Verify the post exists and is an expense
  $expense = get_post($expense_id);
  if (!$expense || $expense->post_type !== 'expense') {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'invalid_expense']);
  }

  // --- Update Expense ---
  $result = wp_update_post([
    'ID'         => $expense_id,
    'post_title' => $description,
    'post_date'  => $date . ' 00:00:00',
  ]);

  if (is_wp_error($result)) {
    rentwise_redirect_back(['updated' => 0, 'reason' => 'update_error']);
  }

  // --- Update meta fields ---
  update_post_meta($expense_id, 'description', $description);
  update_post_meta($expense_id, 'amount', $amount);
  update_post_meta($expense_id, 'category', $category);
  update_post_meta($expense_id, 'date', $date);
  
  if ($property_id > 0) {
    update_post_meta($expense_id, 'property_id', $property_id);
  } else {
    delete_post_meta($expense_id, 'property_id');
  }

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['expense_updated' => 1, 'expense' => $expense_id]);
}

