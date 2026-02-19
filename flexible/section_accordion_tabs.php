

<div class="fc-section-accordion-tabs">
  <?php get_template_part('flexible/section_header'); ?>

  <?php
  $tabs = get_sub_field('tabs'); 
  if( is_array($tabs) ){
    $tab_count = sizeof( $tabs );
  }else{
    return;
  }
  

  $tabs_id = 'tabs-'.rand(1000,9999);

  ?>

  <?php if( have_rows('tabs') ): ?>



    <div class="uk-container columns "> 
    <div class="uk-container columns">
      <ul class="tabs  <?php echo $tab_count > 4 ? 'stretch-tabs' : ''; ?>" data-tabs id="<?php echo $tabs_id;?>">
        <?php while( have_rows('tabs') ): the_row(); ?>
       
          <li class="tabs-title<?php echo (get_row_index() === 1) ? ' is-active' : ''; ?>">
            <a href="#tab-<?php echo get_row_index(); ?>_<?php echo $tabs_id;?>">
              <?php echo get_sub_field('tab_name'); ?>
            </a>
          </li>
        <?php endwhile; ?>
      </ul>
      <div class="tabs-content" data-tabs-content="<?php echo $tabs_id;?>">
        <?php while( have_rows('tabs') ): the_row(); ?>
          <div class="tabs-panel<?php echo (get_row_index() === 1) ? ' is-active' : ''; ?>" id="tab-<?php echo get_row_index(); ?>_<?php echo $tabs_id;?>">
            <?php if( have_rows('accordion') ): ?>
              <div class="accordion in-tabs">
                <?php while( have_rows('accordion') ): the_row(); ?>
                  <div class="accordion-item">
                    <div class="accordion-topic">
                      <h4><?php echo get_sub_field('heading');?></h4>
                      <div class="accordion-aruk-container"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="accordion-response"><?php echo get_sub_field('content');?></div>
                  </div>
                <?php endwhile; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
    </div>
  <?php endif; ?>
</div>