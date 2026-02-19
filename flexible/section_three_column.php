<div class="fc-section-columns">
  <?php get_template_part('flexible/section_header'); ?>
  <div class="uk-container uk-flex uk-flex-wrap uk-child-width-1-1@s uk-child-width-1-3@m" data-equalizer>

      <div class="">
        <div class="content content-columns" data-equalizer-watch>
          <?php echo get_sub_field('column_1'); ?>
        </div>
      </div>
      <div class="s">
        <div class="content content-columns" data-equalizer-watch>
          <?php echo get_sub_field('column_2'); ?>
        </div>
      </div>
      <div class="">
        <div class="content content-columns" data-equalizer-watch>
          <?php echo get_sub_field('column_3'); ?>
        </div>
      </div>

  </div>
</div>