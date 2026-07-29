<?php 
  $page_header_content = get_field('page_header_content');
  $page_header_style = get_field('page_header_style');
  $show_page_header = get_field('show_page_header');

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
        echo wp_get_attachment_image( $page_heading_background_image, 'large', false, array( "class" => "page-header-image" ) );
    }
    ?>
    <div class="page-header-content-wrapper fc-section fc-section-<?php echo $page_heading_background;?> page-header-<?php echo $page_heading_size; ?>">
      <div class="uk-container uk-container-large">
        <div class="uk-width-1-1 uk-width-2-3@l uk-text-left">
          <div class="page-header-content">

              <?php if ( !empty($page_header_content) ): ?>
                <?php echo $page_header_content; ?>
              <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </header>
<?php endif; ?>