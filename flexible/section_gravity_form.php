<div class="fc-section-gravity-form">
  <?php get_template_part('flexible/section_header'); ?>
  <div class="uk-container">
    <div class="uk-width-1-1">
      <?php echo do_shortcode('[gravityform id="' . get_sub_field('gf_form_id') . '" title="false"]');?>
    </div>
  </div>
</div>