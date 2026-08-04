

  <?php
  $columns_ratio = get_sub_field('columns_ratio');
  $vertical_align_col_1 = get_sub_field('vertical_align_col_1');
  $vertical_align_col_2 = get_sub_field('vertical_align_col_2');

  $left_col = '';
  $right_col = '';


switch ($columns_ratio) {
  case '3-9': // 25 / 75
    $left_col = 'uk-width-1-4@m';
    $right_col = 'uk-width-3-4@m';
    break;
  case '4-8': // 33 / 66
    $left_col = 'uk-width-1-3@m';
    $right_col = 'uk-width-2-3@m';
    break;
  case '5-7': // 40 / 60
    $left_col = 'uk-width-2-5@m';
    $right_col = 'uk-width-3-5@m';
    break;
  case '7-5': // 60 / 40
    $left_col = 'uk-width-3-5@m';
    $right_col = 'uk-width-2-5@m';
    break;
  case '8-4': // 66 / 33
    $left_col = 'uk-width-2-3@m';
    $right_col = 'uk-width-1-3@m';
    break;
  case '9-3': // 75 / 25
    $left_col = 'uk-width-3-4@m';
    $right_col = 'uk-width-1-4@m';
    break;
  default: // 6-6 — 50 / 50
    $left_col = 'uk-width-1-2@m';
    $right_col = 'uk-width-1-2@m';
}


  ?>

  <div class="uk-grid uk-grid-large uk-child-width-1-1@s" uk-grid>

      <div class="<?php echo esc_attr($left_col); ?> uk-flex-stretch content column">
        <div class="content uk-width-1-1 uk-flex uk-flex-column uk-height-1-1 uk-flex-stretch uk-flex-<?php echo esc_attr($vertical_align_col_1); ?>">
          <?php
          // NOT wp_kses_post(): WYSIWYG field, already filtered by ACF. wp_kses_post()
          // strips <form>, <input>, <script> and <iframe>, which breaks embedded Gravity
          // Forms and video embeds placed in a column.
          echo get_sub_field('column_1');
          ?>
        </div>
      </div>
      <div class="<?php echo esc_attr($right_col); ?> uk-flex-stretch column">
        <div class="content  uk-width-1-1 uk-flex uk-flex-column uk-height-1-1  uk-flex-<?php echo esc_attr($vertical_align_col_2); ?>">
          <?php echo get_sub_field('column_2'); // See the note on column_1 above. ?>
        </div>
      </div>

  </div>
