<?php
/**
 * Rentwise: handle "Delete Expense" requests
 */

add_action('admin_post_rentwise_delete_expense', 'rentwise_handle_delete_expense');

function rentwise_handle_delete_expense() {
  // --- Security & auth ---
  if (!is_user_logged_in()) {
    wp_safe_redirect( wp_login_url( wp_get_referer() ?: home_url('/') ) );
    exit;
  }

  if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'rentwise_delete_expense')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'nonce']);
  }

  $user = wp_get_current_user();
  $is_landlord = in_array('landlord', (array) $user->roles, true);
  if (!$is_landlord && !current_user_can('administrator')) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'cap']);
  }

  // --- Inputs ---
  $expense_id = isset($_POST['expense_id']) ? intval($_POST['expense_id']) : 0;

  if (!$expense_id) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'missing_id']);
  }

  // Verify the post exists and is an expense
  $expense = get_post($expense_id);
  if (!$expense || $expense->post_type !== 'expense') {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'invalid_expense']);
  }

  // --- Delete Expense ---
  $result = wp_delete_post($expense_id, true);

  if (!$result) {
    rentwise_redirect_back(['deleted' => 0, 'reason' => 'delete_error']);
  }

  // --- Redirect back to dashboard ---
  rentwise_redirect_back(['expense_deleted' => 1]);
}

