<?php
$card_source = get_sub_field('card_source'); // Manual or Post Type
$cards = get_sub_field('cards');
$display = get_sub_field('cards_display'); // Grid or Slider
$per_row = get_sub_field('cards_per_row'); // 3, 4, 5

$aos = get_sub_field('animate_in');
$aos_duration = 0;
$aos_step = 0;

$card_style = get_sub_field('card_style');

$rand_id = $display . '_' . wp_generate_uuid4();

if ($aos == 'no_animation') {
    $aos = false;
}else{
    $aos_duration = get_sub_field('duration');
    $aos_step = get_sub_field('animation_step');

}

// $card_style = 'primary';

$class = ' uk-card uk-margin-bottom card-background--image cards-style--'.$card_style;

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

// Carousel slides are sized by Slick (slidesToShow), so they don't carry the
// grid's responsive uk-width classes.
$slide_class = 'uk-card card-background--image cards-style--' . $card_style;

// Per-instance Slick settings. Responsive breakpoints mirror the grid's
// per-row columns and are read natively from the data-slick attribute.
$per_row_int = (int) $per_row ?: 3;
$slick_responsive = [
    2 => [
        ['breakpoint' => 960, 'settings' => ['slidesToShow' => 1]],
    ],
    3 => [
        ['breakpoint' => 960, 'settings' => ['slidesToShow' => 2]],
        ['breakpoint' => 640, 'settings' => ['slidesToShow' => 1]],
    ],
    4 => [
        ['breakpoint' => 1200, 'settings' => ['slidesToShow' => 3]],
        ['breakpoint' => 960,  'settings' => ['slidesToShow' => 2]],
        ['breakpoint' => 640,  'settings' => ['slidesToShow' => 1]],
    ],
];
$slick_opts = [
    'slidesToShow'   => $per_row_int,
    'slidesToScroll' => 1,
    'responsive'     => $slick_responsive[ $per_row_int ] ?? $slick_responsive[3],
];

?>


    <?php get_template_part('flexible/section_header'); ?>
    



    <?php if($display == "carousel"): ?>

        <div id="<?php echo $rand_id;?>" class="fc-section-cards carousel-wrapper">
            <div class="cards-slick" data-slick="<?php echo esc_attr( wp_json_encode( $slick_opts ) ); ?>">

                <?php foreach( $cards ?? [] as $card ): ?>

                <?php
                    $link      = $card['card_link'] ?? null;
                    $card_url  = is_array($link) ? ( $link['url']   ?? '' ) : ( $link ?? '' );
                    $card_title = is_array($link) ? ( $link['title'] ?? 'Read More' ) : 'Read More';
                ?>
                <div class="card-slide">
                    <div class="<?php echo $slide_class; ?>">
                        <div class="uk-height-1-1 uk-flex uk-flex-column uk-position-relative uk-card--inner">

                            <?php $image = wp_get_attachment_image($card['card_icon'] ?? 0, 'thumbnail', false, array( 'class' => 'uk-width-1-1')); ?>

                            <?php if( $image ): ?>
                                <div class="card-media uk-card-media-top">
                                    <?php echo $image; ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($card_url ?: '#'); ?>" class="card-body uk-card-body uk-flex uk-flex-column uk-flex-right uk-flex-top uk-height-1-1">

                                <h3 class="card-title uk-card-title uk-margin-remove">
                                    <?php echo $card['card_title']; ?>
                                </h3>

                                <div class="hover-panel">

                                    <?php if( $card['card_description'] != ''): ?>
                                    <p class="card-p uk-margin-top">
                                        <?php echo $card['card_description']; ?>
                                    </p>
                                    <?php endif; ?>

                                    <?php if( $card['card_description'] != ''): ?>
                                        <span class="uk-button uk-button-arrow uk-flex uk-flex-inline">
                                            <?php echo $card_title ; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                            </a>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div><!-- .cards-slick -->
        </div><!-- .carousel-wrapper -->



    <?php else: ?>

            <div id="<?php echo $rand_id;?>" class="fc-section-cards grid-container uk-grid uk-grid-medium uk-grid-match">
                <?php $delay = 0; ?>

                <?php foreach( $cards ?? [] as $card ): ?>

                <?php
                    $delay += $aos_step;
                    $link      = $card['card_link'] ?? null;
                    $card_url  = is_array($link) ? ( $link['url']   ?? '' ) : ( $link ?? '' );
                    $card_title = is_array($link) ? ( $link['title'] ?? 'Read More' ) : 'Read More';
                ?>
                <div class="<?php echo $class; ?>" <?php if($aos != false): ?>data-aos="<?php echo $aos; ?>" data-aos-duration="<?php echo $aos_duration; ?>" data-aos-delay="<?php echo $delay; ?>"<?php endif; ?>>
                    <div class="uk-height-1-1 uk-flex uk-flex-column uk-position-relative uk-card--inner">

                        <?php $image = wp_get_attachment_image($card['card_icon'] ?? 0, 'thumbnail', false, array( 'class' => 'uk-width-1-1')); ?>

                        <?php if( $image ): ?>
                            <div class="card-media uk-card-media-top">
                                <?php echo $image; ?>
                            </div>
                        <?php endif; ?>

                            <a href="<?php echo esc_url($card_url ?: '#'); ?>" class="card-body uk-card-body uk-flex uk-flex-column uk-flex-right uk-flex-top uk-height-1-1">


                                <h3 class="card-title uk-card-title uk-margin-remove">
                                    <?php echo $card['card_title']; ?>
                                </h3>

                                <div class="hover-panel">
                            
                                    <?php if( $card['card_description'] != ''): ?>
                                    <p class="card-p uk-margin-top">
                                        <?php echo $card['card_description']; ?>
                                    </p>
                                    <?php endif; ?>

                                    <?php if( $card['card_description'] != ''): ?>
                                        <span class="uk-button uk-button-arrow uk-flex uk-flex-inline">
                                            <?php echo $card_title ; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                            </a>


                    </div>
                </div>
                <?php
                    if ($delay >= ($aos_step * $per_row)) {
                        $delay = 0;
                    }
                ?>

                <?php endforeach; ?>

            </div><!-- .grid-container -->

    <?php endif; ?>