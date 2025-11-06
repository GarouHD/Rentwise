<?php

/**
 * Get all published tenants.
 *
 * @return WP_Post[]
 */

function lzmk_get_all_tenants($args = []) {

    $defaults = [
        'post_type'      => 'tenant',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ];
    
    $query = new WP_Query(array_merge($defaults, $args));

    return $query->posts;

}