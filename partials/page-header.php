<?php 
  $page_header_content = get_field('page_header_content');
  $page_header_style = get_field('page_header_style');
  $show_page_header = get_field('show_page_header');
  // if( is_front_page() ){
  //   $show_page_header = false;
  // }

  if( is_array( $page_header_content) ){

    if( array_key_exists( 'heading', $page_header_content) ){
      if( $page_header_content['heading'] ){
        $page_heading = $page_header_content['heading'];
      }    
    }
    if( array_key_exists( 'sub_heading', $page_header_content) ){
      if( $page_header_content['sub_heading'] ){
        $page_sub_heading = $page_header_content['sub_heading'];
      }    
    }
    if( array_key_exists( 'heading_text', $page_header_content) ){
      if( $page_header_content['heading_text'] ){
        $page_heading_text = $page_header_content['heading_text'];
      }    
    }
  }

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
        echo wp_get_attachment_image( $page_heading_background_image, 'large', false, array( "class" => "page-header-image" ) );
    }
    ?>
    <div class="page-header-content-wrapper fc-section fc-section-<?php echo $page_heading_background;?> page-header-<?php echo $page_heading_size; ?>">
      <div class="uk-container">
        <div class="small-12 large-8 large-offset-2 uk-text-center columns">
          <div class="page-header-content">
            <?php 
            $header_content = get_field('page_header_content');
          // if ( $page_heading || $page_sub_heading ): 
            ?>
              <?php if ( !empty($page_sub_heading) ): ?>
                <p class="g-section-subtitle">
                  <?php echo esc_html($page_sub_heading); ?>
              </p>
              <?php endif; ?>
              <h1 class="g-section-title">
                <?php if ( $page_heading ): echo esc_html($page_heading); else: the_title(); endif; ?>
              </h1>
              <?php if ( !empty($page_heading_text) ): ?>
                <p>
                    <?php echo esc_html($page_heading_text); ?>
                </p>
            <?php  endif; ?>
          </div>
        </div>
      </div>
    </div>
  </header>
<?php endif; ?>