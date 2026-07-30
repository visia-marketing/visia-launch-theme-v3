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
add_action('acf/input/admin_head','visia_acf_admin_head');
function visia_acf_admin_head() { ?>
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
function visia_create_anchor($string) {
  $string = strtolower($string);                    // Convert to lowercase
  $string = preg_replace('/[^a-z0-9]+/', ' ', $string); // Replace non-alphanumeric with spaces
  $string = trim($string);                          // Remove leading/trailing spaces
  $string = str_replace(' ', '-', $string);         // Replace spaces wi. h hyphens
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
    echo '<div class="fc-wrapper" id="fc-wrapper-' . esc_attr(visia_create_anchor(get_the_title())) . '">';

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

      $containerWidth = get_sub_field('container_width') ?: 'uk-container uk-container-large'; // Container width class; matches ACF field default (Regular)

      // Reset per row: without this, a row set to background=image but with no
      // image selected would inherit the previous row's image.
      $background_image_url = $background_image_id
        ? wp_get_attachment_image_url($background_image_id, 'full')
        : '';

      /**
       * Build section element with dynamic classes
       * Classes include:
       * - fc-section: Base class for all sections
       * - fc-section-[index]: Numbered class for nth-child targeting
       * - fc-section-[background]: Background type class
       * - Custom classes from ACF fields
       */
      echo '<section class="fc-section fc-section-' . esc_attr(get_row_index()) . ' fc-section-' . esc_attr($background) . ' vis-rowgap-' . esc_attr($content_spacing) . ' vis-sec-pad-top-' . esc_attr($top_padding) . ' vis-sec-pad-bottom-' . esc_attr($bottom_padding) . ' ' . esc_attr($class) . '" id="' . esc_attr($id) . '">';

        if ($background === 'image' && $background_image_url) {
          echo '<div class="background-image" style="background: url(' . esc_url($background_image_url) . ')"></div>';
        }

        echo '<div class="' . esc_attr($containerWidth) . ' uk-flex uk-flex-column uk-flex-' . esc_attr($horizontal_align) . '">';


          get_template_part('flexible/'.get_row_layout() );

        echo '</div>';  
      echo '</section>';

    endwhile;

    echo '</div>';
  }
}
