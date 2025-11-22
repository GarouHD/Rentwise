<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Remove first_name field from WPUM registration form.
 * This ensures the form only shows fields configured in WPUM admin (Email, Username, Password).
 *
 * @param array $fields The registration form fields
 * @param int $form_id The form ID
 * @return array
 */
add_filter('wpum_registration_form_fields', function ($fields, $form_id) {
    // Remove first_name and last_name fields if they exist
    if (isset($fields['first_name'])) {
        unset($fields['first_name']);
    }
    if (isset($fields['last_name'])) {
        unset($fields['last_name']);
    }
    
    return $fields;
}, 10, 2);

/**
 * Intercept redirects after registration to send users to dashboard instead of login.
 * This catches the case where WPUM redirects to login page after registration.
 */
add_action('template_redirect', function () {
    // Check if user just registered (has registration success message or parameter)
    if (isset($_GET['registration']) && $_GET['registration'] === 'success') {
        if (is_user_logged_in()) {
            // Find the dashboard page
            $dashboard_page = \get_pages([
                'meta_key' => '_wp_page_template',
                'meta_value' => 'template-dashboard.blade.php',
                'number' => 1,
            ]);
            
            if (!empty($dashboard_page)) {
                \wp_safe_redirect(\get_permalink($dashboard_page[0]->ID));
                exit;
            }
        }
    }
    
    // Also check if we're on a login page but user is already logged in (just registered)
    if (is_page() && (strpos($_SERVER['REQUEST_URI'], '/log-in') !== false || strpos($_SERVER['REQUEST_URI'], '/login') !== false || strpos($_SERVER['REQUEST_URI'], '/wp-login.php') !== false)) {
        if (is_user_logged_in()) {
            // Find the dashboard page
            $dashboard_page = \get_pages([
                'meta_key' => '_wp_page_template',
                'meta_value' => 'template-dashboard.blade.php',
                'number' => 1,
            ]);
            
            if (!empty($dashboard_page)) {
                \wp_safe_redirect(\get_permalink($dashboard_page[0]->ID));
                exit;
            }
            
            // Fallback to homepage if dashboard not found
            \wp_safe_redirect(\home_url());
            exit;
        }
    }
}, 5); // Higher priority to run before other redirects

/**
 * Override WPUM registration redirect URL.
 */
add_filter('wpum_registration_redirect', function ($redirect_url, $user_id) {
    // Find the dashboard page
    $dashboard_page = \get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-dashboard.blade.php',
        'number' => 1,
    ]);
    
    if (!empty($dashboard_page)) {
        return \get_permalink($dashboard_page[0]->ID);
    }
    
    return \home_url();
}, 10, 2);

/**
 * Catch registration completion and redirect immediately.
 */
add_action('wpum_after_registration', function ($user_id) {
    // Auto-login the user if not already logged in
    if (!is_user_logged_in() && $user_id) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
    }
    
    // Find the dashboard page
    $dashboard_page = \get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-dashboard.blade.php',
        'number' => 1,
    ]);
    
    if (!empty($dashboard_page)) {
        \wp_safe_redirect(\get_permalink($dashboard_page[0]->ID));
        exit;
    }
    
    \wp_safe_redirect(\home_url());
    exit;
}, 10, 1);
