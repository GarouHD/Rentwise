<?php

use Roots\Acorn\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

Application::configure()
    ->withProviders([
        App\Providers\ThemeServiceProvider::class,
    ])
    ->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters', 'blocks'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file));
        }
    });

    
require_once get_theme_file_path('app/helpers.php');

// Rentwise tenant management files
require_once get_theme_file_path('app/add_tenant.php');
require_once get_theme_file_path('app/get_tenant.php');
require_once get_theme_file_path('app/update_tenant.php');
require_once get_theme_file_path('app/delete_tenant.php');

// Rentwise property management files
require_once get_theme_file_path('app/add_property.php');
require_once get_theme_file_path('app/get_property.php');
require_once get_theme_file_path('app/update_property.php');
require_once get_theme_file_path('app/delete_property.php');

// Rentwise activity feed
require_once get_theme_file_path('app/activity_feed.php');

// Rentwise expense tracking
require_once get_theme_file_path('app/expense_helpers.php');
require_once get_theme_file_path('app/add_expense.php');
require_once get_theme_file_path('app/get_expense.php');
require_once get_theme_file_path('app/update_expense.php');
require_once get_theme_file_path('app/delete_expense.php');