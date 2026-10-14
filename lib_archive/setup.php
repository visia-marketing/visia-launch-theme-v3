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
   * Internationalization
   *
   * Makes the theme available for translation. WordPress looks for
   * WP_LANG_DIR/themes/visia_marketing-{locale}.mo first, then the theme's own
   * /lang directory, so translations can be dropped in without touching the theme.
   */
  load_theme_textdomain('visia_marketing', get_template_directory() . '/lang');

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
    'primary_navigation'      => __('Primary Navigation', 'visia_marketing'),      // Main site navigation
    'top_navigation'          => __('Top Navigation', 'visia_marketing'),          // Top bar navigation
    'mobile_navigation'       => __('Mobile Navigation', 'visia_marketing'),       // Mobile-specific menu
    'footer_navigation_1'     => __('Footer Navigation 1', 'visia_marketing'),     // First footer column
    'footer_navigation_2'     => __('Footer Navigation 2', 'visia_marketing'),     // Second footer column
    'footer_navigation_3'     => __('Footer Navigation 3', 'visia_marketing'),     // Third footer column
    'footer_navigation_4'     => __('Footer Navigation 4', 'visia_marketing'),     // Fourth footer column
    'footer_navigation_legal' => __('Footer Navigation Legal', 'visia_marketing'), // Legal links (privacy, terms, etc.)
  ]);

  /**
   * Featured Images Support
   *
   * Enables featured images (post thumbnails) for posts and pages.
   *
   * @link http://codex.wordpress.org/Post_Thumbnails
   */
  add_theme_support('post-thumbnails');

  /**
   * Default Image Sizes
   *
   * One-shot migration of the WordPress media settings to the sizes this theme
   * expects. Guarded by an option so it only ever runs once per install.
   */
  if ( ! get_option( 'visia_image_sizes_set' ) ) {
		update_option( 'thumbnail_size_w', 300 );
		update_option( 'thumbnail_size_h', 190 );
		update_option( 'thumbnail_size_crop', 1 );

		update_option( 'large_size_w', 1440 );
		update_option( 'large_size_h', 1100 );
		update_option( 'large_size_crop', 0 );

		update_option( 'medium_size_w', 768 );
		update_option( 'medium_size_h', 768 );
		update_option( 'medium_size_crop', 0 );

		update_option( 'visia_image_sizes_set', true );
	}

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
   * Loads the theme's editor styles into the TinyMCE iframe, including every ACF
   * WYSIWYG field, so the visual editor matches the front end.
   *
   * This is a dedicated, much smaller bundle than the front-end stylesheet; the
   * source is /assets/src/styles/editor.scss. add_editor_style() takes no version
   * argument, so the cache-busting version is appended to the URL by hand.
   */
  $editor_css = 'dist/styles/editor.min.css';
  $editor_version = Assets\asset_version($editor_css);

  add_editor_style(Assets\asset_path($editor_css) . ($editor_version ? '?ver=' . $editor_version : ''));
}

// Hook the setup function to run after the theme is initialized
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

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
   *
   * Remote third-party URLs pass null for $ver; theme assets are versioned by
   * file modification time so a rebuild always busts the cache.
   */
  wp_enqueue_style('theme-fonts', $fonts, false, null);          // Google/Typekit fonts
  wp_enqueue_style('sage/css', Assets\asset_path('dist/styles/main.min.css'), false, Assets\asset_version('dist/styles/main.min.css')); // Compiled theme styles
  wp_enqueue_script('font-awesome-kit', 'https://kit.fontawesome.com/f71e020b2c.js', [], null, true); // Font Awesome 7 Kit

  /**
   * Comment Reply Script
   * Loads WordPress comment-reply script on single posts with comments enabled
   */
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  /**
   * Main Theme JavaScript
   *
   * Declaring 'jquery' as a dependency is what loads WordPress core's jQuery;
   * it does not need to be enqueued separately.
   */
  wp_enqueue_script('sage/js', Assets\asset_path('dist/scripts/main.min.js'), ['jquery'], Assets\asset_version('dist/scripts/main.min.js'), true);

}
// Hook with priority 100 to load after most other scripts/styles
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);
