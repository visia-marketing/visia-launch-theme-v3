<?php

/***************************************************************************
 * M I S C  S T U F F 
 ***************************************************************************/


/**
 * Disables Download Monitor's XHR functionality/ removes download spinner
 * Used for download monitor - used on most sites
 */
add_filter( 'dlm_do_xhr', '__return_false' );



/**
 * Recommended and Required Plugins
 */
function mlcl_register_required_plugins() {
  $plugins = array(
    array(
			'name'   => 'Advanced Custom Fields Pro',
            'slug'   => 'advanced-custom-fields-pro',
			'source' => 'https://github.com/pronamic/advanced-custom-fields-pro/archive/refs/heads/main.zip',
            'required'     => true,
            'force_activation'  => true,
            'external_url' => 'https://github.com/pronamic/advanced-custom-fields-pro', // If set, overrides default API URL and points to an external URL.
	),
    array(
			'name'     => 'Classic Editor',
			'slug'     => 'classic-editor',
			'required' => true,
            'force_activation'  => true,
	),
    array(
			'name'     => 'SVG Support',
			'slug'     => 'svg-support',
			'required' => true,
    ),
    array(
			'name'     => 'Classic Widgets',
			'slug'     => 'classic-widgets',
			'required' => true,
            'force_activation'  => true,
	),
    array(
			'name'     => 'Query Monitor',
			'slug'     => 'query-monitor',
			'required' => false,
	),
    array(
			'name'     => 'Really Simple Security',
			'slug'     => 'really-simple-ssl',
			'required' => false,
	),
    array(
			'name'     => 'Disable Comments',
			'slug'     => 'disable-comments',
			'required' => false,
	),
	array(
			'name'     => 'Regenerate Thumbnails',
			'slug'     => 'regenerate-thumbnails',
			'required' => false,
	),
	array(
			'name'     => 'Download Monitor',
			'slug'     => 'download-monitor',
			'required' => false,
	),
  );

  $config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
        'page_title'  => __( 'Install Standard Plugins', 'visia_marketing' ),
        'menu_title'  => __( 'Standard Plugins', 'visia_marketing' ),
        )
  );

  tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'mlcl_register_required_plugins' );

/**
 * Assigns a specific custom template to newly created pages by default.
 */
add_action('save_post', 'set_default_template_for_new_pages', 10, 3);

function set_default_template_for_new_pages($post_id, $post, $update) {
    // Exit if it's not a page, or if the page is being updated (not newly created)
    if ($post->post_type !== 'page' || $update) {
        return;
    }

    // Set the path to your custom template relative to the theme root
    $default_template = 'template-flexible.php'; 
    
    update_post_meta($post_id, '_wp_page_template', $default_template);
}

/**
 * Custom Font Awesome icons for post type admin menu items.
 * Map: post type slug => FA unicode codepoint (solid weight).
 */
function admin_post_type_icons() {
  $icons = [
    'posts'            => '\e212', // fa-memo
    'news'             => '\f1ea', // fa-newspaper
    'video'            => '\f144', // fa-circle-play
	'practice-area'    => '\f24e',
	'professional'     => '\f508',
	'experience'       => '\f5db'
  ];

  // Default Posts menu uses #menu-posts; CPTs use #menu-posts-{slug}.
  $menu_id = fn($slug) => $slug === 'posts' ? '#menu-posts' : '#menu-posts-' . $slug;

  $selectors = implode(', ', array_map(
    fn($slug) => $menu_id($slug) . ' .wp-menu-image',
    array_keys( $icons )
  ));

  $css = "{$selectors} { display: flex !important; align-items: center !important; justify-content: center !important; }";

  foreach ( $icons as $post_type => $unicode ) {
    $id = $menu_id($post_type);

    $css .= $id . ' .wp-menu-image::before {'
      . ' font-family: "Font Awesome 7 Pro", "Font Awesome 7 Free" !important;'
      . ' content: "' . $unicode . '" !important;'
      . ' font-weight: 400 !important;'
      . ' font-size: 18px !important;'
      . ' background-image: none !important;'
      . ' width: auto !important; height: auto !important;'
      . ' color: #ffffff !important;'
      . ' }';

    // $css .= $id . ':not(:hover) .wp-menu-image::before {'
    //   . ' color: #D63638 !important;'
    //   . ' }';

    $css .= $id . '.wp-menu-open .wp-menu-image::before {'
    . 'color: #fff !important;'
    . ' }';
  }

  echo '<style>' . $css . '</style>';
}
add_action('admin_head', __NAMESPACE__ . '\\admin_post_type_icons');

/**
 * Rename the default "Posts" post type to "Blogs" throughout the admin.
 */
function rename_posts_to_blogs() {
  global $wp_post_types;

  if ( ! isset( $wp_post_types['post'] ) ) {
    return;
  }

  $labels = $wp_post_types['post']->labels;
  $labels->name                     = 'Blogs';
  $labels->singular_name            = 'Blog';
  $labels->add_new                  = 'Add Blog';
  $labels->add_new_item             = 'Add New Blog';
  $labels->edit_item                = 'Edit Blog';
  $labels->new_item                 = 'New Blog';
  $labels->view_item                = 'View Blog';
  $labels->view_items               = 'View Blogs';
  $labels->search_items             = 'Search Blogs';
  $labels->not_found                = 'No blogs found.';
  $labels->not_found_in_trash       = 'No blogs found in Trash.';
  $labels->all_items                = 'All Blogs';
  $labels->archives                 = 'Blog Archives';
  $labels->attributes               = 'Blog Attributes';
  $labels->insert_into_item         = 'Insert into blog';
  $labels->uploaded_to_this_item    = 'Uploaded to this blog';
  $labels->filter_items_list        = 'Filter blogs list';
  $labels->items_list_navigation    = 'Blogs list navigation';
  $labels->items_list               = 'Blogs list';
  $labels->menu_name                = 'The More Report';
  $labels->name_admin_bar           = 'Blog';
}
add_action('init', __NAMESPACE__ . '\\rename_posts_to_blogs');
