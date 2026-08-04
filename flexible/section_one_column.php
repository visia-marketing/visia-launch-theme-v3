<?php


$column_width = get_sub_field('column_width'); 
$column_align = get_sub_field('column_align'); 




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





  <?php // Both branches of the old conditional here emitted the same string. ?>
  <div class="content uk-width-<?php echo esc_attr($column_width); ?>">
    <?php
    // NOT wp_kses_post(): WYSIWYG field, already filtered by ACF. wp_kses_post() strips
    // <form>, <input>, <script> and <iframe>, which breaks embedded Gravity Forms and
    // video embeds placed in a column.
    echo get_sub_field('column_1');
    ?>
  </div>


