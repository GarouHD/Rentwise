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
 * Redirect logged-in users away from login page to dashboard.
 * This prevents logged-in users from seeing the login page.
 */
add_action('template_redirect', function () {
    // Only run on login page
    if (is_page() && (strpos($_SERVER['REQUEST_URI'], '/log-in') !== false || strpos($_SERVER['REQUEST_URI'], '/login') !== false)) {
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
}, 10);
