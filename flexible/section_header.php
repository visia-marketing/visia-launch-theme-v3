<?php $top_border = (get_sub_field('top_border') === 'yes') ? "top-border" : ""; ?>

<?php if ( get_sub_field('section_heading_title') || get_sub_field('section_heading') ): ?>
  <div class="uk-container columns <?php echo $top_border; ?>">
    <div class="fc-section-heading"> 
      <div class="uk-container">
        <div class="small-12 columns">
          <?php if ( get_sub_field('section_heading_title') ): ?>
            <h2 class="g-section-title"><?php echo get_sub_field('section_heading_title'); ?></h2>
          <?php endif; ?>
          
          <?php if ( get_sub_field('section_heading') ): ?>
            <p class="g-section-intro"><?php echo get_sub_field('section_heading'); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>  
  </div>
<?php endif; ?>