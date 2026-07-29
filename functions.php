<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */

/**
 * Theme Module Includes
 * 
 * This array defines all the required library files that make up the core
 * functionality of the theme. Each file handles a specific aspect of the theme's
 * features and capabilities.
 */
$sage_includes = [
  'lib/class-tgm-plugin-activation.php', // TGM Plugin Activation - Handles required and recommended plugin notifications
  'lib/acf.php',       // Advanced Custom Fields integration and configuration
  'lib/assets.php',    // Enqueues and manages scripts and stylesheets
  'lib/extras.php',    // Custom helper functions and additional theme functionality
  'lib/nav.php',       // Navigation menu setup and configuration (adapted from FoundationPress)
  'lib/setup.php',     // Core theme setup, support declarations, and initialization
  'lib/shortcodes.php',// Custom WordPress shortcode definitions
  'lib/tinymce.php',   // TinyMCE editor customizations and enhancements
  'lib/titles.php',    // Dynamic page title generation and management
  'lib/wrapper.php',   // Theme wrapper class for template hierarchy manipulation
  'lib/customizer.php', // WordPress Customizer API settings and controls
  'lib/misc.php',       // Miscellaneous utility functions and features
  'lib/woocommerce.php', // WooCommerce customisations and hook adjustments
];

/**
 * Load Theme Modules
 * 
 * Iterates through each file in the includes array and safely loads them.
 * Uses locate_template() to find files in child/parent theme hierarchy.
 * Triggers a fatal error if any required file cannot be found.
 */
foreach ($sage_includes as $file) {
  // Attempt to locate the file within the theme directory structure
  if (!$filepath = locate_template($file)) {
    // If file cannot be found, trigger a fatal error with a translatable message
    trigger_error(sprintf(__('Error locating %s for inclusion', 'visia_marketing'), $file), E_USER_ERROR);
  }

  // Include the located file (require_once prevents duplicate inclusions)
  require_once $filepath;
}

// Clean up variables from global scope to prevent potential conflicts
unset($file, $filepath);