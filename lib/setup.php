<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 * 
 * Configures theme defaults and registers support for various WordPress features.
 * This function runs during the 'after_setup_theme' action hook.
 */
function setup() {
  /**
   * Soil Plugin Support
   * 
   * Enables features from the Soil plugin when activated.
   * Soil is a WordPress plugin that contains a collection of modules to apply
   * theme-agnostic front-end modifications.
   * 
   * @link https://roots.io/plugins/soil/
   */
  add_theme_support('soil-clean-up');      // Cleaner WordPress markup
  add_theme_support('soil-nav-walker');    // Bootstrap-compatible nav walker
  add_theme_support('soil-nice-search');   // Redirects /?s=query to /search/query/ for cleaner URLs
  add_theme_support('soil-jquery-cdn');    // Loads jQuery from Google's CDN with local fallback
  add_theme_support('soil-relative-urls'); // Makes URLs relative for portability

  /**
   * Internationalization
   * 
   * Makes the theme available for translation.
   * Translation files should be placed in the /lang directory.
   * Community translations available at: https://github.com/roots/sage-translations
   */
  load_theme_textdomain('visia_starter_theme', get_template_directory() . '/lang');

  /**
   * Title Tag Support
   * 
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head.
   * 
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
   */
  add_theme_support('title-tag');

  /**
   * Navigation Menus
   * 
   * Registers custom navigation menu locations.
   * These can be assigned in WordPress admin under Appearance > Menus.
   * 
   * @link http://codex.wordpress.org/Function_Reference/register_nav_menus
   */
  register_nav_menus([
    'primary_navigation'      => __('Primary Navigation', 'visia_starter_theme'),      // Main site navigation
    //'store_navigation'        => __('Store Navigation', 'visia_starter_theme'),      // Commented out - likely for WooCommerce
    'top_navigation'          => __('Top Navigation', 'visia_starter_theme'),          // Top bar navigation
    'mobile_navigation'       => __('Mobile Navigation', 'visia_starter_theme'),       // Mobile-specific menu
    'footer_navigation_1'     => __('Footer Navigation 1', 'visia_starter_theme'),     // First footer column
    'footer_navigation_2'     => __('Footer Navigation 2', 'visia_starter_theme'),     // Second footer column
    'footer_navigation_3'     => __('Footer Navigation 3', 'visia_starter_theme'),     // Third footer column
    'footer_navigation_4'     => __('Footer Navigation 4', 'visia_starter_theme'),     // Fourth footer column
    'footer_navigation_legal' => __('Footer Navigation Legal', 'visia_starter_theme'), // Legal links (privacy, terms, etc.)
  ]);

  /**
   * Featured Images Support
   * 
   * Enables featured images (post thumbnails) for posts and pages.
   * Additional image sizes can be defined using add_image_size().
   * 
   * @link http://codex.wordpress.org/Post_Thumbnails
   * @link http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
   * @link http://codex.wordpress.org/Function_Reference/add_image_size
   */
  add_theme_support('post-thumbnails');
  //set_post_thumbnail_size( 300, 190, true ); // Set featured image size (width, height, crop)


  update_option( 'thumbnail_size_w', 300 ); // Set your desired width
	update_option( 'thumbnail_size_h', 190 );  // Set your desired height
	update_option( 'thumbnail_size_crop', 1 ); // 0 for soft crop (resize), 1 for hard crop (crop to exact dimensions)

	update_option( 'large_size_w', 1440 ); // Set your desired width
	update_option( 'large_size_h', 1100 );  // Set your desired height
	update_option( 'large_size_crop', 0 ); // 0 for soft crop (resize), 1 for hard crop (crop to exact dimensions)
  
	update_option( 'medium_size_w', 768 ); // Set your desired width
	update_option( 'medium_size_h', 768 );  // Set your desired height
	update_option( 'medium_size_crop', 0 ); // 0 for soft crop (resize), 1 for hard crop (crop to exact dimensions)


  /**
   * Post Formats
   * 
   * Enables support for Post Formats.
   * Post Formats allow users to format posts differently based on content type.
   * 
   * @link http://codex.wordpress.org/Post_Formats
   */
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  /**
   * HTML5 Markup
   * 
   * Switches default core markup to output valid HTML5.
   * Affects: captions, comment forms, comment lists, galleries, and search forms.
   * 
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
   */
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  /**
   * Editor Stylesheet
   * 
   * Adds main theme stylesheet to the visual editor.
   * This ensures the editor matches the front-end styling.
   * Custom editor styles can be added in /assets/styles/layouts/_tinymce.scss
   */
  add_editor_style(Assets\asset_path('styles/main.css'));
}

// Hook the setup function to run after the theme is initialized
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register Sidebars and Widget Areas
 * 
 * Creates widget-ready areas that can be populated through the WordPress admin.
 * Each sidebar can contain multiple widgets.
 */
function widgets_init() {
  /**
   * Primary Sidebar
   * Main sidebar displayed on blog posts and pages (when enabled)
   */
  register_sidebar([
    'name'          => __('Primary', 'visia_starter_theme'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">', // Wraps each widget in a section with dynamic classes
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',                               // Widget title wrapped in h3
    'after_title'   => '</h3>'
  ]);

  /**
   * Footer Widget Area
   * Widgets displayed in the site footer
   */
  register_sidebar([
    'name'          => __('Footer', 'visia_starter_theme'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  /**
   * Shop Category Sidebar
   * Widget area for WooCommerce category pages
   */
  register_sidebar([
    'name'          => __('Shop Category', 'visia_starter_theme'),
    'id'            => 'sidebar-shop-category',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  /**
   * Shop Archive Sidebar
   * Widget area for WooCommerce archive/shop pages
   */
  register_sidebar([
    'name'          => __('Shop Archive', 'visia_starter_theme'),
    'id'            => 'sidebar-shop-archive',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
  
}
// Hook widget initialization to the widgets_init action
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Sidebar Display Logic
 * 
 * Determines which pages should NOT display the sidebar.
 * Uses static variable to cache the result for performance.
 * 
 * @return bool True if sidebar should be displayed, false otherwise
 */
function display_sidebar() {
  static $display; // Cache the result to avoid recalculation

  // Only calculate if not already cached
  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),                                    // Hide on 404 error pages
    is_front_page(),                            // Hide on the front page
    is_page_template('template-custom.php'),    // Hide on custom page template
  ]);

  // Allow other plugins/themes to filter this decision
  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Enqueue Theme Assets
 * 
 * Loads all CSS and JavaScript files needed by the theme.
 * Hooked with priority 100 to ensure it runs after other plugins.
 */
function assets() {

  /**
   * Dynamic Font Loading
   * Checks for custom Google/Typekit font URL from theme options
   * Falls back to Source Sans 3 if no custom font is set
   */
  $global_font = get_field('google_typekit_font_url', 'options'); // ACF options field

  if ($global_font) {
    $fonts = $global_font; // Use custom font from options
  } else{
    // Default font: Source Sans 3 with full weight range and italics
    $fonts = 'https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap';
  }

  /**
   * Enqueue Stylesheets
   */
  wp_enqueue_script( 'jquery' );
  wp_enqueue_style('theme-fonts', $fonts , false, null);          // Google/Typekit fonts
  wp_enqueue_style('sage/css', Assets\asset_path('/dist/styles/main.min.css'), false, null); // Compiled theme styles
  wp_enqueue_style('font-awesome-cdn', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', false, null); // Font Awesome icons
  wp_enqueue_style('default-css', get_stylesheet_uri() , false, null); // WordPress default stylesheet (style.css)




  /**
   * Comment Reply Script
   * Loads WordPress comment-reply script on single posts with comments enabled
   */
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  /**
   * Main Theme JavaScript
   * Note: There's a duplicate enqueue here that should be removed
   */
  wp_enqueue_script('sage/js', Assets\asset_path('dist/scripts/main.min.js'), ['jquery'], null, true);

}
// Hook with priority 100 to load after most other scripts/styles
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);