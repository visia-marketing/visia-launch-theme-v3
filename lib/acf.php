<?php

//namespace Roots\Sage\Acf;

/**
 * Auto-Collapse ACF Flexible Content Layouts in Admin
 * 
 * Improves the admin UI experience by automatically collapsing all flexible content
 * layouts when the page loads. This prevents long, unwieldy edit screens when there
 * are many flexible content blocks.
 * 
 * Note: '.clones .layout' are excluded as these are the template/clone fields
 * that ACF uses internally and shouldn't be collapsed.
 */
add_action('acf/input/admin_head','my_acf_admin_head');
function my_acf_admin_head() { ?>
  <script type="text/javascript">
  jQuery(document).ready(function($) {
    // Add the collapsed class to all layouts except clone templates
    $('.layout').not('.clones .layout').addClass('-collapsed');
  });
  </script>
  <?php
}



/**
 * Convert String to URL-Safe Anchor
 * 
 * Transforms any string into a valid HTML anchor/ID by:
 * - Converting to lowercase
 * - Removing special characters
 * - Replacing spaces with hyphens
 * 
 * Example: "My Section Title!" becomes "my-section-title"
 * 
 * @param string $string The string to convert
 * @return string URL-safe anchor string
 */
function create_anchor($string) {
  $string = strtolower($string);                    // Convert to lowercase
  $string = preg_replace('/[^a-z0-9]+/', ' ', $string); // Replace non-alphanumeric with spaces
  $string = trim($string);                          // Remove leading/trailing spaces
  $string = str_replace(' ', '-', $string);         // Replace spaces with hyphens
  return $string;
}



/**
 * Render ACF Flexible Content Sections
 * 
 * Main function for outputting flexible content layouts.
 * Loops through all flexible content rows and renders each with appropriate
 * template parts and wrapper elements.
 * 
 * This creates a modular page builder system where editors can add/reorder
 * content sections through the WordPress admin.
 * 
 * Structure:
 * - Wrapper div with page-specific ID
 * - Individual sections for each flexible content row
 * - Dynamic classes and IDs for styling/JavaScript targeting
 * - Optional background images for sections
 */
function get_flexible_content() {
  // Check if the flexible_content field has any rows


  get_template_part('partials/page-header');
  

  if (have_rows('flexible_content')) {
    // Create main wrapper with unique ID based on page title
    echo '<div class="fc-wrapper" id="fc-wrapper-' . esc_attr(create_anchor(get_the_title())) . '">';

    // Loop through each flexible content row
    while (have_rows('flexible_content')) : the_row();
      /**
       * Get layout type and field values for current row
       */
      $id = get_sub_field('id') ?: 'fc-section-' . get_row_index();
      $class = get_sub_field('class') ?: '';
      $background = get_sub_field('background') ?: '';
      $background_image_id = get_sub_field('background_image');
      
      $top_padding = get_sub_field('top_padding') ?: 0;
      $bottom_padding = get_sub_field('bottom_padding') ?: 0;
      $content_spacing = get_sub_field('content_spacing') ?: 0;
      $horizontal_align = get_sub_field('horizontal_align') ?: '';

      $containerWidth = get_sub_field('container_width') ?: 'uk-container-expand uk-width-1-1';

      if ($background_image_id){
        $background_image_url = wp_get_attachment_image_url($background_image_id, 'full');
      }

      $padding_top_rem = $top_padding * 1.5;
      $padding_bottom_rem = $bottom_padding * 1.5;
      $gap_rem = $content_spacing * 1.5;
      
      $inline_style = "padding-top: {$padding_top_rem}rem; padding-bottom: {$padding_bottom_rem}rem; --fc-gap: {$gap_rem}rem;";
      if ($background === 'image' && $background_image_id) {
        $inline_style .= " background-image: url(" . esc_url($background_image_url) . "); background-size: cover; background-position: center;";
      }

      echo '<section class="fc-section fc-section-' . esc_attr(get_row_index()) . ' fc-section-' . esc_attr($background) . ' ' . esc_attr($class) . '" id="' . esc_attr($id) . '" style="' . esc_attr($inline_style) . '">';

        echo '<div class="' . esc_attr($containerWidth) . ' uk-flex uk-flex-column uk-flex-' . esc_attr($horizontal_align) . '">';

          get_template_part('flexible/'.get_row_layout() );

        echo '</div>';  
      echo '</section>';

    endwhile;

    echo '</div>';
  }
}



/**
 * Dynamic Accent Color Management
 * 
 * Generates CSS file with accent color CSS variable from ACF options page.
 * This allows theme-wide color customization without hardcoding values.
 */
add_action('acf/save_post', function ($post_id) {
    if ($post_id !== 'options') {
        return;
    }

    $accent_color = get_field('accent_color', 'option');

    if (!$accent_color) {
        return;
    }

    $css = ":root {\n    --accent-color: {$accent_color};\n}\n";

    file_put_contents(
        get_stylesheet_directory() . '/accent-color.css',
        $css
    );
}, 20);

/**
 * Dynamically populate the "Select Global Callout" dropdown
 * from the global_featured_callouts repeater on the options page.
 */
add_filter('acf/load_field/key=field_69fc_global_callout_select', function( $field ) {
    $field['choices'] = [];

    $callouts = get_field('global_featured_callouts', 'options');

    if ( $callouts ) {
        foreach ( $callouts as $i => $callout ) {
            $label = ! empty( $callout['callout_label'] ) ? $callout['callout_label'] : 'Callout ' . ( $i + 1 );
            $field['choices'][ $i ] = $label;
        }
    }

    return $field;
});


/**
 * ACF Helper Functions
 * 
 * Collection of utility functions for common ACF output patterns.
 * Note: These are marked as "not currently in use" but kept for potential future use.
 */


/**
 * Output ACF Image Attachment
 * 
 * Helper to display an image from an ACF image field that returns an attachment ID.
 * Wraps wp_get_attachment_image() with ACF-specific defaults.
 * 
 * @param int $image Attachment ID from ACF image field
 * @param string $size WordPress image size (thumbnail, medium, large, full, or custom)
 * @param string $class CSS class to apply to the image element
 */
function acf_attachment_img($image, $size = 'full', $class = 'nc'){
  if( $image ) {
      echo wp_get_attachment_image( $image, $size,'', array( "class" => $class ) );
  }
}

/**
 * Wrap ACF Field in HTML Element
 * 
 * Utility function to wrap any ACF field value in an HTML element.
 * Useful for consistent markup when outputting text fields.
 * 
 * @param mixed $field The ACF field value to output
 * @param string $element HTML element to wrap content in (div, p, h2, etc.)
 * @param string $class CSS class for the wrapper element
 */
function acf_field($field, $element = 'div', $class='nc'){
  if( !$field ){ return; } // Exit if field is empty
  echo '<' . $element . ' class="' . $class . '">' . $field . '</' . $element . '>';
}

/**
 * Output ACF Link Field as Button
 * 
 * Renders an ACF link field as a styled button element.
 * Handles all link attributes (URL, target, title) and optional arrow icon.
 * 
 * @param array $link ACF link field array with url, title, and target
 * @param bool $has_arrow Whether to append a right arrow icon
 * @param string $class Additional CSS classes for the button
 * @param string $attr Additional HTML attributes as a string
 */
function acf_button($link, $has_arrow=false, $class='nc', $attr=false){
  if( !$link ) return; // Exit if no link provided
  
  // Set default target to _self if not specified
  $link['target'] = $link['target'] ?: '_self'; ?>

  <a href="<?= esc_url($link['url']); ?>" 
     target="<?= $link['target'] ?>" 
     class="btn <?= $class ?>"
     <?php if($attr) echo ' ' . $attr ?>>
    <?= $link['title']; ?>
    <?php if($has_arrow){ echo '<i class="fa-solid fa-angle-right"></i>'; }; ?>
  </a>
  <?php
}
