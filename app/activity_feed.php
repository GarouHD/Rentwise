<?php
/**
 * Rentwise Activity Feed Helper
 * Generates recent activity timeline for dashboard
 * 
 * This file contains the backend logic that:
 * 1. Fetches recent payments, new tenants, and lease expirations
 * 2. Formats them into a unified activity array
 * 3. Sorts by timestamp and returns the most recent activities
 */

/**
 * Get recent activities (payments, new tenants, lease expirations)
 * 
 * @param int $limit Maximum number of activities to return (default: 10)
 * @return array Array of activity items sorted by timestamp (newest first)
 *               Each activity contains: type, timestamp, icon, color, title, description, time_ago
 */
function rentwise_get_recent_activities($limit = 10) {
    // Security check: Only logged-in users can see activities
    if (!is_user_logged_in()) {
        return [];
    }

    // Get current user's ID to filter activities (only show this landlord's data)
    $user_id = wp_get_current_user()->ID;
    
    // Initialize empty array to store all activities we'll collect
    $activities = [];
    
    // Get current timestamp for date comparisons
    $now = current_time('timestamp');
    
    // Calculate timestamp for 30 days ago (used to filter recent activities)
    $thirty_days_ago = $now - (30 * DAY_IN_SECONDS);

    // ============================================
    // STEP 1: Get Recent Payments (last 30 days)
    // ============================================
    // Query WordPress for payment posts created in the last 30 days
    $payments = get_posts([
        'post_type' => 'payment',           // Only get 'payment' custom post type
        'post_status' => 'publish',         // Only published payments (not drafts)
        'posts_per_page' => 50,             // Get up to 50 payments (more than we need, but safe)
        'date_query' => [                   // Filter by date range
            [
                'after' => date('Y-m-d', $thirty_days_ago),  // Only payments from last 30 days
                'inclusive' => true,                          // Include payments from exactly 30 days ago
            ]
        ],
        'meta_query' => [                   // Filter by custom field (landlord ID)
            [
                'key' => 'landlord',        // Check the 'landlord' custom field
                'value' => $user_id,        // Must match current user's ID
                'compare' => '='            // Exact match
            ]
        ]
    ]);

    // Loop through each payment and add it to activities array
    foreach ($payments as $payment) {
        // Get the tenant ID associated with this payment (from ACF field)
        $tenant_id = get_field('tenant', $payment->ID);
        
        // Get the payment amount (from ACF field)
        $amount = get_field('amount', $payment->ID);
        
        // Get tenant name: if tenant exists, get their name, otherwise use fallback
        $tenant_name = $tenant_id ? get_field('name', $tenant_id) : 'Unknown Tenant';
        
        // Add this payment as an activity item
        $activities[] = [
            'type' => 'payment',                                    // Activity type identifier
            'timestamp' => strtotime($payment->post_date),          // When payment was made (Unix timestamp)
            'icon' => '💰',                                         // Emoji icon for display
            'color' => 'green',                                     // Color theme (green = money in)
            'title' => 'Payment Received',                          // Activity title
            'description' => sprintf('$%s from %s', number_format($amount, 2), $tenant_name),  // Format: "$222.00 from James"
            'time_ago' => human_time_diff(strtotime($payment->post_date), $now) . ' ago'  // Human-readable: "3 days ago"
        ];
    }

    // ============================================
    // STEP 2: Get New Tenants (last 30 days)
    // ============================================
    // Query WordPress for tenant posts created in the last 30 days
    $new_tenants = get_posts([
        'post_type' => 'tenant',            // Only get 'tenant' custom post type
        'post_status' => 'publish',         // Only published tenants
        'posts_per_page' => 20,              // Get up to 20 new tenants
        'date_query' => [                   // Filter by date range
            [
                'after' => date('Y-m-d', $thirty_days_ago),  // Only tenants added in last 30 days
                'inclusive' => true,
            ]
        ],
        'meta_query' => [                   // Filter by landlord
            [
                'key' => 'landlord',
                'value' => $user_id,        // Only this landlord's tenants
                'compare' => '='
            ]
        ]
    ]);

    // Loop through each new tenant and add it to activities array
    foreach ($new_tenants as $tenant) {
        // Get tenant name from ACF field, or fallback to post title
        $tenant_name = get_field('name', $tenant->ID) ?: $tenant->post_title;
        
        // Get property name if tenant has one assigned
        $property = get_field('property', $tenant->ID);
        
        // Add this tenant addition as an activity item
        $activities[] = [
            'type' => 'new_tenant',                                 // Activity type identifier
            'timestamp' => strtotime($tenant->post_date),          // When tenant was added
            'icon' => '👤',                                         // Emoji icon
            'color' => 'blue',                                     // Color theme (blue = new addition)
            'title' => 'New Tenant Added',                          // Activity title
            'description' => $property ? sprintf('%s at %s', $tenant_name, $property) : $tenant_name,  // "James at Sunset Apartments" or just "James"
            'time_ago' => human_time_diff(strtotime($tenant->post_date), $now) . ' ago'  // "5 days ago"
        ];
    }

    // ============================================
    // STEP 3: Get Lease Expirations (within next 60 days)
    // ============================================
    // This is different - we look FORWARD in time, not backward
    // Get ALL active tenants (not just recent ones) to check their lease end dates
    $all_tenants = get_posts([
        'post_type' => 'tenant',
        'post_status' => 'publish',
        'posts_per_page' => -1,             // Get ALL tenants (-1 = no limit)
        'meta_query' => [
            [
                'key' => 'landlord',
                'value' => $user_id,        // Only this landlord's tenants
                'compare' => '='
            ],
            [
                'key' => 'status',          // Only active tenants (not inactive)
                'value' => 'active',
                'compare' => '='
            ]
        ]
    ]);

    // Calculate timestamp for 60 days in the future
    $sixty_days_future = $now + (60 * DAY_IN_SECONDS);

    // Loop through all active tenants and check their lease end dates
    foreach ($all_tenants as $tenant) {
        // Get the lease end date from ACF field
        $lease_end = get_field('lease_end_date', $tenant->ID);
        
        // Only process if tenant has a lease end date set
        if ($lease_end) {
            // Convert lease end date string to Unix timestamp for comparison
            $lease_end_timestamp = strtotime($lease_end);
            
            // Only show if lease expires within next 60 days AND hasn't expired yet
            // Condition 1: $lease_end_timestamp > $now = Lease hasn't expired yet
            // Condition 2: $lease_end_timestamp <= $sixty_days_future = Expires within 60 days
            if ($lease_end_timestamp > $now && $lease_end_timestamp <= $sixty_days_future) {
                // Get tenant name
                $tenant_name = get_field('name', $tenant->ID) ?: $tenant->post_title;
                
                // Get property name if available
                $property = get_field('property', $tenant->ID);
                
                // Calculate how many days until lease expires
                // floor() rounds down to whole number (e.g., 45.7 days = 45 days)
                $days_until = floor(($lease_end_timestamp - $now) / DAY_IN_SECONDS);
                
                // Determine urgency level based on days remaining
                // ≤30 days = RED (urgent), 31-60 days = ORANGE (warning)
                $urgency = $days_until <= 30 ? 'red' : 'orange';
                $urgency_icon = $days_until <= 30 ? '⚠️' : '📅';
                
                // Add this lease expiration as an activity item
                $activities[] = [
                    'type' => 'lease_expiring',                     // Activity type identifier
                    'timestamp' => $lease_end_timestamp,           // Use lease end date as timestamp (for sorting)
                    'icon' => $urgency_icon,                        // ⚠️ for urgent, 📅 for warning
                    'color' => $urgency,                            // red or orange
                    'title' => 'Lease Expiring Soon',              // Activity title
                    'description' => sprintf('%s%s - %d days remaining', 
                        $tenant_name,
                        $property ? ' at ' . $property : '',        // Include property if available
                        $days_until                                // "45 days remaining"
                    ),
                    'time_ago' => sprintf('Expires %s', date('M j, Y', $lease_end_timestamp))  // "Expires Dec 15, 2024"
                ];
            }
        }
    }

    // ============================================
    // STEP 4: Sort and Limit Results
    // ============================================
    // Sort all activities by timestamp (newest first)
    // usort() sorts array in place using custom comparison function
    usort($activities, function($a, $b) {
        // Return $b['timestamp'] - $a['timestamp'] means descending order (newest first)
        // If we did $a - $b, it would be ascending (oldest first)
        return $b['timestamp'] - $a['timestamp'];
    });

    // Limit results to the requested number (default: 10)
    // array_slice() takes first $limit items from sorted array
    return array_slice($activities, 0, $limit);
}
