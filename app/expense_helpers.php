<?php
/**
 * Rentwise Expense Helper Functions
 */

/**
 * Get all expenses for the current landlord
 * @param array $args Additional query arguments
 * @return array Array of expense posts
 */
function rentwise_get_all_expenses($args = []) {
    if (!is_user_logged_in()) {
        return [];
    }

    $user_id = wp_get_current_user()->ID;

    $defaults = [
        'post_type' => 'expense',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,
                'compare' => '='
            ]
        ],
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $query_args = array_merge($defaults, $args);
    return get_posts($query_args);
}

/**
 * Get total expenses for a landlord
 * @param int|null $property_id Optional: filter by property
 * @param string|null $start_date Optional: filter by date range start (Y-m-d)
 * @param string|null $end_date Optional: filter by date range end (Y-m-d)
 * @return float Total expense amount
 */
function rentwise_get_total_expenses($property_id = null, $start_date = null, $end_date = null) {
    if (!is_user_logged_in()) {
        return 0.0;
    }

    $args = [];

    // Filter by property
    if ($property_id) {
        $args['meta_query'][] = [
            'key' => 'property_id',
            'value' => $property_id,
            'compare' => '='
        ];
    }

    // Filter by date range
    if ($start_date || $end_date) {
        $date_query = [];
        if ($start_date) {
            $date_query['after'] = $start_date;
        }
        if ($end_date) {
            $date_query['before'] = $end_date;
            $date_query['inclusive'] = true;
        }
        $args['date_query'] = [$date_query];
    }

    $expenses = rentwise_get_all_expenses($args);
    $total = 0.0;

    foreach ($expenses as $expense) {
        $amount = (float) get_field('amount', $expense->ID);
        $total += $amount;
    }

    return $total;
}

/**
 * Calculate profit/loss for a property or landlord
 * @param int|null $property_id Optional: specific property ID
 * @param string|null $start_date Optional: start date (Y-m-d)
 * @param string|null $end_date Optional: end date (Y-m-d)
 * @return array ['revenue' => float, 'expenses' => float, 'profit' => float]
 */
function rentwise_calculate_profit_loss($property_id = null, $start_date = null, $end_date = null) {
    $revenue = rentwise_get_total_revenue($property_id, $start_date, $end_date);
    $expenses = rentwise_get_total_expenses($property_id, $start_date, $end_date);
    $profit = $revenue - $expenses;

    return [
        'revenue' => $revenue,
        'expenses' => $expenses,
        'profit' => $profit,
        'margin' => $revenue > 0 ? ($profit / $revenue) * 100 : 0
    ];
}

/**
 * Get total revenue from payments
 * @param int|null $property_id Optional: filter by property
 * @param string|null $start_date Optional: start date (Y-m-d)
 * @param string|null $end_date Optional: end date (Y-m-d)
 * @return float Total revenue
 */
function rentwise_get_total_revenue($property_id = null, $start_date = null, $end_date = null) {
    if (!is_user_logged_in()) {
        return 0.0;
    }

    $user_id = wp_get_current_user()->ID;
    $args = [
        'post_type' => 'payment',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,
                'compare' => '='
            ]
        ]
    ];

    // Filter by property (via tenant)
    if ($property_id) {
        $tenants_in_property = get_posts([
            'post_type' => 'tenant',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'property_id',
                    'value' => $property_id,
                    'compare' => '='
                ]
            ]
        ]);

        if (empty($tenants_in_property)) {
            return 0.0;
        }

        $args['meta_query'][] = [
            'key' => 'tenant',
            'value' => $tenants_in_property,
            'compare' => 'IN'
        ];
    }

    // Filter by date range
    if ($start_date || $end_date) {
        $date_query = [];
        if ($start_date) {
            $date_query['after'] = $start_date;
        }
        if ($end_date) {
            $date_query['before'] = $end_date;
            $date_query['inclusive'] = true;
        }
        $args['date_query'] = [$date_query];
    }

    $payments = get_posts($args);
    $total = 0.0;

    foreach ($payments as $payment) {
        $amount = (float) get_field('amount', $payment->ID);
        $total += $amount;
    }

    return $total;
}

/**
 * Get expense categories
 * @return array List of expense categories
 */
function rentwise_get_expense_categories() {
    return [
        'repairs' => 'Repairs & Maintenance',
        'utilities' => 'Utilities',
        'insurance' => 'Insurance',
        'taxes' => 'Property Taxes',
        'hoa' => 'HOA Fees',
        'mortgage' => 'Mortgage Payment',
        'legal' => 'Legal Fees',
        'advertising' => 'Advertising',
        'supplies' => 'Supplies',
        'landscaping' => 'Landscaping',
        'cleaning' => 'Cleaning',
        'other' => 'Other'
    ];
}

