<?php 
  $page_header_content = get_field('page_header_content');
  $page_header_style = get_field('page_header_style');
  $show_page_header = get_field('show_page_header');

  // Initialised up front: these were only assigned inside the nested conditionals below,
  // so an unstyled header emitted PHP notices and class="fc-section- page-header-".
  $page_heading_background       = '';
  $page_heading_background_image = 0;
  $page_heading_size             = '';

  // if( is_front_page() ){
  //   $show_page_header = false;
  // }

  if( is_array( $page_header_style) ){
    if( array_key_exists( 'background', $page_header_style) ){
      if( $page_header_style['background'] ){
        $page_heading_background = $page_header_style['background'];
      }    
    }
    if( array_key_exists( 'background_image', $page_header_style) ){
      if( $page_header_style['background_image'] ){
        $page_heading_background_image = $page_header_style['background_image'];
      }    
    }
    if( array_key_exists( 'header_size', $page_header_style) ){
      if( $page_header_style['header_size'] ){
        $page_heading_size = $page_header_style['header_size'];
      }    
    }
  }


 
?>


<?php if( $show_page_header  ): ?>
  <header class="fc-page-header page-header" id="page_header_<?php echo get_the_ID();?>">
    <?php
    if( $page_heading_background === 'image' ){
        // Decorative: this is the header's backdrop, not content. Rendering it with
        // wp_get_attachment_image() alone meant it announced whatever alt text happened to
        // be on the attachment, on every page using that image.
        echo visia_decorative_image( $page_heading_background_image, 'large', array( 'class' => 'page-header-image' ) );
    }
    ?>
    <div class="page-header-content-wrapper fc-section fc-section-<?php echo esc_attr($page_heading_background);?> page-header-<?php echo esc_attr($page_heading_size); ?>">
      <div class="uk-container uk-container-large">
        <div class="uk-width-1-1 uk-width-2-3@l uk-text-left">
          <div class="page-header-content">

              <?php if ( !empty($page_header_content) ): ?>
                <?php
                // NOT wp_kses_post(): WYSIWYG field, already filtered by ACF. wp_kses_post()
                // strips <form>, <input>, <script> and <iframe>, which breaks embedded
                // Gravity Forms and video embeds in the page header.
                echo $page_header_content;
                ?>
              <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </header>
<?php endif; ?>