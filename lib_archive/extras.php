<?php

namespace Roots\Sage\Extras;

/**
 * Add <body> classes
 */

function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');



/**
 * Clean up the_excerpt()
 */

function excerpt_more() {
  return ' &hellip; <a href="' . esc_url(get_permalink()) . '">' . esc_html__('Continued', 'visia_marketing') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');



/**
 * Move Yoast to Bottom
 */

 function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio',  __NAMESPACE__ . '\\yoasttobottom');