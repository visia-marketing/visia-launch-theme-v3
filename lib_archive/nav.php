<?php
/**
 * Custom Navigation Walker for WordPress Menus
 * 
 * Creates cleaner HTML markup for navigation menus with Foundation framework support.
 * Extends WordPress's Walker_Nav_Menu class to customize menu output.
 */

class Roots_Nav_Walker extends Walker_Nav_Menu {
  /**
   * Start Level Output
   * 
   * Outputs the opening HTML for a new submenu level.
   * Uses Foundation framework's submenu classes for styling.
   * 
   * @param string $output Passed by reference. Used to append additional content
   * @param int $depth Depth of menu item. 0 for top level
   * @param array $args Array of wp_nav_menu() arguments
   */
  function start_lvl(&$output, $depth = 0, $args = array()) {
    // Foundation-specific classes for vertical dropdown submenus
    $output .= "\n<ul class=\"submenu menu vertical\" data-submenu>\n";
  }

  /**
   * Start Element Output
   * 
   * Generates the HTML for individual menu items.
   * Handles special cases like dropdowns, dividers, and headers.
   * 
   * @param string $output Passed by reference. Used to append additional content
   * @param object $item Menu item data object
   * @param int $depth Depth of menu item. 0 for top level
   * @param array $args Array of wp_nav_menu() arguments
   * @param int $id Current menu item ID
   */
  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    $item_html = '';
    // Let parent class build the initial HTML
    parent::start_el($item_html, $item, $depth, $args);

    /**
     * Handle divider menu items
     * Removes the anchor tag for menu items marked as dividers
     */
    if (stristr($item_html, 'li class="divider')) {
      $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
    }
    /**
     * Handle dropdown header items
     * Removes the anchor tag but keeps the text for header items
     */
    elseif (stristr($item_html, 'li class="dropdown-header')) {
      $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
    }

    // Apply custom filters to allow further modification
    $item_html = apply_filters('roots/wp_nav_menu_item', $item_html);
    $output .= $item_html;
  }

  /**
   * Display Element
   * 
   * Determines if a menu item has children and adds appropriate classes.
   * Called before the element and its children are processed.
   * 
   * @param object $element Data object for menu item
   * @param array $children_elements List of elements that are children of the current element
   * @param int $max_depth Maximum depth to traverse
   * @param int $depth Current depth
   * @param array $args Array of wp_nav_menu() arguments
   * @param string $output Passed by reference. Used to append additional content
   */
  function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
    // Check if this element has children and we haven't reached max depth.
    // Kept as a local: assigning to $element would create a dynamic property on
    // WP_Post, which is deprecated as of PHP 8.2.
    $is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    // Add Foundation's 'has-submenu' class to parent items
    if ($is_dropdown) {
      $element->classes[] = 'has-submenu'; // Foundation class (Bootstrap would use 'has-dropdown')
    }

    // Let parent class handle the rest of the display
    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
}

/**
 * Clean up navigation menu item classes
 * 
 * Removes WordPress default classes and adds cleaner, more semantic ones.
 * Converts various "current" states to a single "active" class.
 * 
 * @param array $classes Array of CSS classes applied to menu item
 * @param object $item Current menu item object
 * @return array Modified array of classes
 */
function roots_nav_menu_css_class($classes, $item) {
  // Create a URL-friendly slug from the menu item title
  $slug = sanitize_title($item->title);
  
  // Replace various WordPress "current" classes with a single "active" class
  $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
  
  // Remove WordPress auto-generated menu classes but keep user-defined custom classes
  $classes = preg_replace('/^(menu-item(-\d+|-type-\w+|-object-\w+|-has-children)?|page[-_]item[-_]\d+|page_item)$/', '', $classes);

  // Add semantic class based on menu item title
  $classes[] = 'menu-' . $slug;

  // Remove duplicate classes
  $classes = array_unique($classes);

  // Filter out empty values using anonymous function
  // (Replaces older is_element_empty function)
  return array_filter($classes, function ($element) {
      $element = trim($element);
      return !empty($element);
    });
}
add_filter('nav_menu_css_class', 'roots_nav_menu_css_class', 10, 2);

/**
 * Remove ID attributes from navigation menu items
 * 
 * IDs are often unnecessary and can cause conflicts.
 * Returns null to completely remove the ID attribute.
 */
add_filter('nav_menu_item_id', '__return_null');

/**
 * Modify default WordPress navigation menu arguments
 * 
 * Sets sensible defaults for cleaner markup:
 * - Removes container div
 * - Sets default markup structure
 * - Sets default depth
 * - Uses custom walker class
 * 
 * @param array $args Original menu arguments
 * @return array Modified menu arguments
 */
function roots_nav_menu_args($args = '') {
  // Remove the default container div wrapper
  $roots_nav_menu_args['container'] = false;

  // Set default list wrapper if not specified
  if (!$args['items_wrap']) {
    $roots_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';
  }

  // Set default menu depth to 2 levels if not specified
  if (!$args['depth']) {
    $roots_nav_menu_args['depth'] = 2;
  }

  // Use custom walker class if none specified
  if (!$args['walker']) {
    $roots_nav_menu_args['walker'] = new Roots_Nav_Walker();
  }

  // Merge custom defaults with provided arguments (provided args take precedence)
  return array_merge($args, $roots_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'roots_nav_menu_args');

/**
 * Add UIkit button classes to top navigation menu links
 */
function visia_top_nav_link_classes($atts, $item, $args) {
  if (isset($args->theme_location) && $args->theme_location === 'top_navigation') {
    $existing = isset($atts['class']) ? $atts['class'] . ' ' : '';
    $atts['class'] = $existing . 'uk-button uk-button-text';
  }
  return $atts;
}
add_filter('nav_menu_link_attributes', 'visia_top_nav_link_classes', 10, 3);