<?php
// The raw field value used to be interpolated into the shortcode straight from ACF. It is
// cast here instead — but only after unwrapping an array, because this field is registered
// in the database rather than in acf-json and some Gravity Forms field types return
// ['id' => n]. absint() on an array would silently resolve to 1 and render the wrong form.
$gf_form_id = get_sub_field('gf_form_id');
if ( is_array( $gf_form_id ) ) {
  $gf_form_id = $gf_form_id['id'] ?? ( $gf_form_id['value'] ?? 0 );
}
$gf_form_id = absint( $gf_form_id );

if ( ! $gf_form_id ) {
  return;
}

// title="false" suppresses Gravity Forms' own heading, which left the form with no
// accessible name at all and no entry in the page's heading outline. The visually-hidden
// heading below restores both; the editor can override the wording with `form_label`.
$form_label    = get_sub_field('form_label');
$form_label    = ( $form_label !== '' && $form_label !== null ) ? $form_label : __( 'Form', 'visia_marketing' );
$form_label_id = visia_unique_id( 'gform-label' );
?>
<div class="fc-section-gravity-form">
  <div class="uk-width-1-1">
    <section aria-labelledby="<?php echo esc_attr( $form_label_id ); ?>">
      <h2 id="<?php echo esc_attr( $form_label_id ); ?>" class="screen-reader-text"><?php echo esc_html( $form_label ); ?></h2>
      <?php echo do_shortcode( '[gravityform id="' . $gf_form_id . '" title="false"]' ); ?>
    </section>
  </div>
</div>
