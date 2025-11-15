<?php
/**
 * Rentwise: AJAX handler to get expense data
 */

add_action('wp_ajax_rentwise_get_expense', 'rentwise_get_expense_ajax');

function rentwise_get_expense_ajax() {
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

  $expense_id = isset($_GET['expense_id']) ? intval($_GET['expense_id']) : 0;
  
  if (!$expense_id) {
    wp_send_json_error(['message' => 'Invalid expense ID']);
    return;
  }

  // Get expense post
  $expense = get_post($expense_id);
  
  if (!$expense || $expense->post_type !== 'expense') {
    wp_send_json_error(['message' => 'Expense not found']);
    return;
  }

  // Get fields
  $data = [
    'id' => $expense_id,
    'description' => get_field('description', $expense_id) ?: $expense->post_title,
    'amount' => get_field('amount', $expense_id) ?: 0,
    'category' => get_field('category', $expense_id) ?: 'other',
    'property_id' => get_field('property_id', $expense_id) ?: '',
    'date' => get_field('date', $expense_id) ?: date('Y-m-d'),
  ];

  wp_send_json_success($data);
}

