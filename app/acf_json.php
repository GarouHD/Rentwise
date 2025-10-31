<?php

// Save field groups JSON into your theme
add_filter('acf/settings/save_json', function ($path) {
  return get_stylesheet_directory() . '/resources/acf-json';
});

// Load field groups JSON from your theme (in addition to default)
add_filter('acf/settings/load_json', function ($paths) {
  $paths[] = get_stylesheet_directory() . '/resources/acf-json';
  return $paths;
});