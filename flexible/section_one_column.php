<?php $column_width = get_sub_field('column_width'); 


// convert column width (1-6) to uk-width classes
switch ($column_width) {
  case 1:
    $column_width = '1-6';
    break;
  case 2:
    $column_width = '1-3';
    break;
  case 3:
    $column_width = '1-2';
    break;
  case 4:
    $column_width = '2-3';
    break;
  case 5:
    $column_width = '5-6';
    break;
  case 6:
    $column_width = '1-1';
    break;
}

?>



<div class="fc-section-columns">
  <?php get_template_part('flexible/section_header'); ?>
  <div class="uk-container uk-flex">
      <div class="<?php if ( get_sub_field('column_width') ): echo 'uk-width-'.$column_width; else: echo 'medium-'.$column_width; endif; ?> columns">
        <div class="content">
          <?php echo get_sub_field('column_1'); ?>
        </div>
      </div> 
  </div>
</div>