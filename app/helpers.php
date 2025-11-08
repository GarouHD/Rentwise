<?php

/**
 * Get all published tenants.
 *
 * @return WP_Post[]
 */

function rentwise_get_all_tenants($args = []) {

    // dont return anything if the user is not logged in
    if (!is_user_logged_in()) {
        return [];
    }

    // get the id of the user logged in
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    // only return the tenants of the current user

    $defaults = [
        'post_type'      => 'tenant',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'   => 'landlord', // acf field in tenant
                'value' => $user_id,
                'compare' => '=',
            ],
        ]
    ];
    
    $query = new WP_Query(array_merge($defaults, $args));

    return $query->posts;

}