<?php
// Empty columns are skipped. All three used to render unconditionally, leaving empty
// <div class="content"> shells behind. (This also drops the stray class="s" that was on
// the second column.)
$columns = array();
foreach ( array( 'column_1', 'column_2', 'column_3' ) as $field ) {
  $value = get_sub_field( $field );
  if ( $value !== '' && $value !== null ) {
    $columns[] = $value;
  }
}

if ( ! $columns ) {
  return;
}
?>
<div class="fc-section-columns">
  <div class="uk-flex uk-flex-wrap uk-child-width-1-1@s uk-child-width-1-3@m">

      <?php foreach ( $columns as $column ) : ?>
        <div>
          <div class="content content-columns">
            <?php
            // NOT wp_kses_post(): WYSIWYG field, already filtered by ACF. wp_kses_post()
            // strips <form>, <input>, <script> and <iframe>, which breaks embedded Gravity
            // Forms and video embeds placed in a column.
            echo $column;
            ?>
          </div>
        </div>
      <?php endforeach; ?>

  </div>
</div>
