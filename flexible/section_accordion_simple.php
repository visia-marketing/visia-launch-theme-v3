<?php
$fields = get_sub_field('accordion');
$length = 0;
if( is_array($fields) ){
    $length = count($fields);
}

$accordion_layout = get_sub_field('accordion_layout');

$accordion_container_class = 'fc-section-accordion-simple '.$accordion_layout;

$accordion = $fields;

?>

<div class="fc-section-columns <?php echo $accordion_container_class;?>" id="<?php //echo $anchor;?>">
 <?php get_template_part('flexible/section_header'); ?>
 
  <?php if( is_array( $accordion ) ): ?>

    <div class="uk-container"> 

        <ul class="uk-accordion uk-accordion-default" uk-accordion>

            <?php foreach ($accordion as $item ): ?>
                <li class="uk-margin-remove-top">
                    <a class="uk-accordion-title uk-flex uk-flex-middle uk-flex-between" href><h4 class="uk-margin-top uk-margin-bottom"><?php echo $item['heading'];?></h4> <span uk-icon="icon: chevron-down; ratio: 1.5"></span></a>
                    <div class="uk-accordion-content uk-margin-medium-bottom"><?php echo $item['content'];?></div>
                </li>
            <?php endforeach; ?>

        </ul>

    </div>

  <?php endif; ?>
</div>
