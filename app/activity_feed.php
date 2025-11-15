<?php
/**
 * Rentwise Activity Feed Helper
 * Generates recent activity timeline for dashboard
 */

/**
 * Get recent activities (payments, new tenants, lease expirations)
 * @return array Array of activity items sorted by timestamp (newest first)
 */
function rentwise_get_recent_activities($limit = 10) {
    if (!is_user_logged_in()) {
        return [];
    }

    $user_id = wp_get_current_user()->ID;
    $activities = [];
    $now = current_time('timestamp');
    $thirty_days_ago = $now - (30 * DAY_IN_SECONDS);

    // 1. Get Recent Payments (last 30 days)
    $payments = get_posts([
        'post_type' => 'payment',
        'post_status' => 'publish',
        'posts_per_page' => 50,
        'date_query' => [
            [
                'after' => date('Y-m-d', $thirty_days_ago),
                'inclusive' => true,
            ]
        ],
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,
                'compare' => '='
            ]
        ]
    ]);

    foreach ($payments as $payment) {
        $tenant_id = get_field('tenant', $payment->ID);
        $amount = get_field('amount', $payment->ID);
        $tenant_name = $tenant_id ? get_field('name', $tenant_id) : 'Unknown Tenant';
        
        $activities[] = [
            'type' => 'payment',
            'timestamp' => strtotime($payment->post_date),
            'icon' => '💰',
            'color' => 'green',
            'title' => 'Payment Received',
            'description' => sprintf('$%s from %s', number_format($amount, 2), $tenant_name),
            'time_ago' => human_time_diff(strtotime($payment->post_date), $now) . ' ago'
        ];
    }

    // 2. Get New Tenants (last 30 days)
    $new_tenants = get_posts([
        'post_type' => 'tenant',
        'post_status' => 'publish',
        'posts_per_page' => 20,
        'date_query' => [
            [
                'after' => date('Y-m-d', $thirty_days_ago),
                'inclusive' => true,
            ]
        ],
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,
                'compare' => '='
            ]
        ]
    ]);

    foreach ($new_tenants as $tenant) {
        $tenant_name = get_field('name', $tenant->ID) ?: $tenant->post_title;
        $property = get_field('property', $tenant->ID);
        
        $activities[] = [
            'type' => 'new_tenant',
            'timestamp' => strtotime($tenant->post_date),
            'icon' => '👤',
            'color' => 'blue',
            'title' => 'New Tenant Added',
            'description' => $property ? sprintf('%s at %s', $tenant_name, $property) : $tenant_name,
            'time_ago' => human_time_diff(strtotime($tenant->post_date), $now) . ' ago'
        ];
    }

    // 3. Get Lease Expirations (within next 60 days)
    $all_tenants = get_posts([
        'post_type' => 'tenant',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,
                'compare' => '='
            ],
            [
                'key' => 'status',
                'value' => 'active',
                'compare' => '='
            ]
        ]
    ]);

    $sixty_days_future = $now + (60 * DAY_IN_SECONDS);

    foreach ($all_tenants as $tenant) {
        $lease_end = get_field('lease_end_date', $tenant->ID);
        
        if ($lease_end) {
            $lease_end_timestamp = strtotime($lease_end);
            
            // Only show if lease expires within next 60 days and hasn't expired yet
            if ($lease_end_timestamp > $now && $lease_end_timestamp <= $sixty_days_future) {
                $tenant_name = get_field('name', $tenant->ID) ?: $tenant->post_title;
                $property = get_field('property', $tenant->ID);
                $days_until = floor(($lease_end_timestamp - $now) / DAY_IN_SECONDS);
                
                $urgency = $days_until <= 30 ? 'red' : 'orange';
                $urgency_icon = $days_until <= 30 ? '⚠️' : '📅';
                
                $activities[] = [
                    'type' => 'lease_expiring',
                    'timestamp' => $lease_end_timestamp,
                    'icon' => $urgency_icon,
                    'color' => $urgency,
                    'title' => 'Lease Expiring Soon',
                    'description' => sprintf('%s%s - %d days remaining', 
                        $tenant_name,
                        $property ? ' at ' . $property : '',
                        $days_until
                    ),
                    'time_ago' => sprintf('Expires %s', date('M j, Y', $lease_end_timestamp))
                ];
            }
        }
    }

    // Sort by timestamp (newest first)
    usort($activities, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    // Limit results
    return array_slice($activities, 0, $limit);
}

