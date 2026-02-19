<?php
$cards = get_sub_field('cards');
$display = get_sub_field('cards_display'); // Grid or Slider
$per_row = get_sub_field('cards_per_row'); // 3, 4, 5

$aos = get_sub_field('animate_in');
$aos_duration = 0;
$aos_step = 0;


$rand_id = $display . '_' . wp_generate_uuid4();

if ($aos == 'no_animation') {
    $aos = false;
}else{
    $aos_duration = get_sub_field('duration');
    $aos_step = get_sub_field('animation_step');

}

$class = ' uk-card uk-margin-bottom';

switch ($per_row) {
    case 2:
        $class .= ' uk-width-1-1@xs uk-width-1-2@m';
        break;
    case 3:
        $class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-3@m';
        break;
    case 4:
        $class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-3@m uk-width-1-4@l';
        break; 
    default:
        $class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-6@m'; // Default to 3 per uk-container
}


?>

<div class="fc-section-columns fc-section-cards" id="<?php echo $rand_id;?>">

  <div class="uk-container padding-row" data-equalizer>
    <?php get_template_part('flexible/section_header'); ?>
    

    <?php if($display == "carousel"): ?><div class="carousel-wrapper"  data-slides-to-show="<?php echo $per_row; ?>" data-duration="<?php echo $aos_duration; ?>" data-step="<?php echo $aos_step; ?>"><?php else: ?> <div class="grid-container uk-grid uk-grid-small uk-grid-match"> <?php endif; ?>
        <?php $delay = 0; ?>

        <?php foreach( $cards as $card ): ?>

        <?php $delay += $aos_step; ?>
        <div class="<?php echo $class; ?>" <?php if($aos != false): ?>data-aos="<?php echo $aos; ?>" data-aos-duration="<?php echo $aos_duration; ?>" data-aos-delay="<?php echo $delay; ?>"<?php endif; ?>> 
            <div class="uk-height-1-1 uk-flex uk-flex-column" >

            <?php $image = wp_get_attachment_image($card['card_icon'], 'thumbnail', false, array( 'class' => 'uk-width-1-1')); ?>
                    
            <?php if( $image ): ?>
                <div class="card-media uk-card-media-top">
                    <?php echo $image; ?>
                </div>
            <?php endif; ?>
                
                <div class="card-body uk-card-body uk-flex-1">

                    <h3 class="card-title uk-card-title">
                        <a href="<?php echo $card['card_link']; ?>">
                            <?php echo $card['card_title']; ?>
                        </a>
                    </h3>
                
                    <p class="card-p">
                        <?php echo $card['card_description']; ?>
                    </p>

                    <?php if( array_key_exists( 'card_link', $card) ): ?>
                        <?php if( is_array( $card['card_link']) ): ?>
                            <a href="<?php echo $card['card_link']['url']; ?>">
                                <?php if($card['card_link']['title']): ?>
                                    <?php echo $card['card_link']['title']; ?>
                                <?php else: ?>
                                    Read More
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>


                </div>



            
            </div>
        </div>
        <?php
            if ($delay >= ($aos_step * $per_row)) {
                $delay = 0;
            }
        ?>

        <?php endforeach; ?>

        
        </div>
    </div>

</div>

<?php if($display == "carousel"): ?>

    <style>

        #<?php echo $rand_id;?> .carousel-wrapper .slick-prev:before,
        #<?php echo $rand_id;?> .carousel-wrapper .slick-next:before{
            content: '' !important;
        }

        #<?php echo $rand_id;?> .carousel-wrapper svg *{
            stroke: #072E6E;
        }

    </style>
<?php endif; ?>