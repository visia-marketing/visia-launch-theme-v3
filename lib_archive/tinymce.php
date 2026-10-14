<?php

namespace Roots\Sage\Tinymce;

/**
 * Add custom colors
 */

function powerup_studio_mce_color_options($init) {

  $default_colors = trim( file_get_contents( get_template_directory() . '/tinymce/default_colors.json' ) );
  $custom_colors = trim( file_get_contents( get_template_directory() . '/tinymce/custom_colors.json' ) );

  // build colour grid default+custom colors
  $init['textcolor_map'] = '['.$default_colors.','.$custom_colors.']';

  // enable 6th uk-container for custom colours in grid
  $init['textcolor_rows'] = 7;

  return $init;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\powerup_studio_mce_color_options');



/**
 * Add styles to TinyMCE
 */

 function powerup_studio_mce_buttons_2($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}
add_filter('mce_buttons_2', __NAMESPACE__ . '\\powerup_studio_mce_buttons_2');



/*
* Callback function to filter the MCE settings
*/

function powerup_studio_mce_before_init_insert_formats( $init_array ) {

// Define the style_formats array

// fetch json object
// trim() matters: WordPress only outputs the value as raw JS when its first character is [
// and its last is ]. A trailing newline makes it wrap the JSON in unescaped quotes, which is a
// syntax error that stops every TinyMCE editor on the page from initializing.
$style_formats = trim( file_get_contents( get_template_directory() . '/tinymce/style_formats.json' ) );

// Insert the array, JSON ENCODED, into 'style_formats'
// Skip it if the JSON is invalid, so one typo can't break every editor.
if ( is_array( json_decode( $style_formats, true ) ) ) {
  $init_array['style_formats'] = $style_formats;
}

 
return $init_array;  
 
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\\powerup_studio_mce_before_init_insert_formats' );