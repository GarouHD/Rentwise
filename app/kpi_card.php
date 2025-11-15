<?php

function rentwise_current_landlord_id(): ?int {
  if (!is_user_logged_in()) return null;
  $u = wp_get_current_user();
  return in_array('landlord', (array) $u->roles, true) ? $u->ID : null;
}

function rentwise_kpi_active_tenants(): int {
  $landlord = rentwise_current_landlord_id(); if (!$landlord) return 0;
  $q = new WP_Query([
    'post_type' => 'tenant', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => -1,
    'meta_query' => [[ 'key'=>'landlord', 'value'=>$landlord, 'compare'=>'=' ]],
  ]);
  return (int) $q->found_posts;
}

function rentwise_kpi_monthly_revenue(): float {
  $landlord = rentwise_current_landlord_id(); if (!$landlord) return 0;
  $q = new WP_Query([
    'post_type' => 'tenant', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => -1,
    'meta_query' => [[ 'key'=>'landlord', 'value'=>$landlord, 'compare'=>'=' ]],
  ]);
  $sum = 0;
  foreach ($q->posts as $pid) {
    $sum += (float) get_field('rent_amount', $pid); // adjust field name
  }
  return $sum;
}

// not implmented yet
function rentwise_kpi_overdue(): int {
//   $landlord = rentwise_current_landlord_id(); if (!$landlord) return 0;
//   $q = new WP_Query([
//     'post_type' => 'tenant', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => -1,
//     'meta_query' => [
//       [ 'key'=>'landlord', 'value'=>$landlord, 'compare'=>'=' ],
//       [ 'key'=>'is_overdue', 'value'=>1, 'compare'=>'=' ], // adjust field name
//     ],
//   ]);
//   return (int) $q->found_posts;
    return 0;
}

function rentwise_kpi_properties(): int {
  $landlord = rentwise_current_landlord_id(); if (!$landlord) return 0;
  $q = new WP_Query([
    'post_type' => 'property', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => -1,
    'meta_query' => [[ 'key'=>'landlord', 'value'=>$landlord, 'compare'=>'=' ]],
  ]);
  return (int) $q->found_posts;
}

function rentwise_kpi_monthly_expenses(): float {
  $landlord = rentwise_current_landlord_id(); if (!$landlord) return 0;
  // Get expenses from current month
  $start_date = date('Y-m-01');
  $end_date = date('Y-m-t');
  return rentwise_get_total_expenses(null, $start_date, $end_date);
}

function rentwise_kpi_net_profit(): float {
  $revenue = rentwise_kpi_monthly_revenue();
  $expenses = rentwise_kpi_monthly_expenses();
  return $revenue - $expenses;
}

function rentwise_kpi_value(string $metric) {
  return match ($metric) {
    'active_tenants'   => rentwise_kpi_active_tenants(),
    'monthly_revenue'  => rentwise_kpi_monthly_revenue(),
    'overdue_payments' => rentwise_kpi_overdue(),
    'properties'       => rentwise_kpi_properties(),
    'monthly_expenses' => rentwise_kpi_monthly_expenses(),
    'net_profit'       => rentwise_kpi_net_profit(),
    default            => 0,
  };
}
