<?php

namespace Roots\Sage\Tinymce;

/**
 * Read one of the JSON config files in /tinymce
 *
 * Returns the decoded array, or null (and logs a notice) when the file is
 * missing or invalid. The colour files are bare, bracket-less lists, so
 * callers can ask for them to be wrapped in [] before decoding.
 *
 * The callers re-encode the result with wp_json_encode() instead of passing
 * the raw file contents through. WordPress only inlines a TinyMCE setting as
 * raw JS when the string starts with "[" and ends with "]"; raw file contents
 * with a trailing newline get wrapped in quotes instead, which breaks the
 * whole tinyMCEPreInit script so no editor on the page initialises.
 */
function visia_read_tinymce_json($file, $wrap_in_array = false) {
  $path = get_template_directory() . '/tinymce/' . $file;
  $raw  = is_readable($path) ? trim((string) file_get_contents($path)) : '';

  if ($wrap_in_array && $raw !== '' && $raw[0] !== '[') {
    $raw = '[' . $raw . ']';
  }

  $data = json_decode($raw, true);

  if (!is_array($data) || !$data) {
    error_log(sprintf('[visia-launch-theme] tinymce/%s is missing or invalid JSON: %s', $file, json_last_error_msg()));
    return null;
  }

  return $data;
}

/**
 * Add custom colors
 *
 * Merges the stock WordPress palette (default_colors.json) with the brand
 * palette (custom_colors.json). Both are flat lists of alternating
 * "hex", "name" entries, TinyMCE's textcolor_map format.
 */
function visia_mce_color_options($init) {
  $default = visia_read_tinymce_json('default_colors.json', true) ?: [];
  $custom  = visia_read_tinymce_json('custom_colors.json', true) ?: [];
  $colors  = array_merge($default, $custom);

  if (!$colors) {
    return $init;
  }

  $init['textcolor_map']  = wp_json_encode($colors);
  $init['textcolor_cols'] = 8;
  // Two entries per colour, 8 swatches per row.
  $init['textcolor_rows'] = max(1, (int) ceil((count($colors) / 2) / 8));

  return $init;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\visia_mce_color_options');

/**
 * Add the Formats dropdown to the second toolbar row
 */
function visia_mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', __NAMESPACE__ . '\\visia_mce_buttons_2');

/**
 * Populate the Formats dropdown from tinymce/style_formats.json
 */
function visia_mce_before_init_insert_formats($init) {
  $formats = visia_read_tinymce_json('style_formats.json');

  if ($formats !== null) {
    $init['style_formats'] = wp_json_encode($formats);
  }

  return $init;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\visia_mce_before_init_insert_formats');
