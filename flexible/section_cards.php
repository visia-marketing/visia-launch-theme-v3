<?php
/**
 * Cards section — grid or Slick carousel of cards.
 *
 * This template owns the section: display mode, per-row widths, animation and
 * the carousel wrapper. The card markup itself lives in partials/content-card-*.php
 * — see the $card_parts map below — each of which renders its own `.uk-card` root
 * and `cards-style--*` modifier.
 */

$cards = get_sub_field('cards');
$display = get_sub_field('cards_display'); // Grid or Carousel
$per_row = get_sub_field('cards_per_row'); // 2, 3, 4

$aos = get_sub_field('animate_in');
$aos_duration = 0;
$aos_step = 0;

// Each style maps to the partial that renders it. Simple and plain share one
// template — their markup is identical, and only the modifier the partial emits
// (and its SCSS) differs. Unknown or empty values — e.g. rows saved before the
// styles were renamed — fall back to simple, so a section always renders cards
// rather than nothing.
$card_parts = array(
    'simple'  => 'partials/content-card-simple',
    'plain'   => 'partials/content-card-simple',
    'overlay' => 'partials/content-card-overlay',
);
$card_style = get_sub_field('cards_style');
$card_style = isset($card_parts[$card_style]) ? $card_style : 'simple';
$card_part  = $card_parts[$card_style];

$rand_id = $display . '_' . wp_generate_uuid4();

if ($aos == 'no_animation') {
    $aos = false;
}else{
    $aos_duration = get_sub_field('duration');
    $aos_step = get_sub_field('animation_step');

}

// Layout classes for the card root in the grid. Carousel slides are sized by
// Slick (slidesToShow), so they don't carry the responsive uk-width classes.
$grid_class = 'uk-margin-bottom';

switch ($per_row) {
    case 2:
        $grid_class .= ' uk-width-1-1@xs uk-width-1-2@m';
        break;
    case 3:
        $grid_class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-3@m';
        break;
    case 4:
        $grid_class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-3@m uk-width-1-4@l';
        break;
    default:
        $grid_class .= ' uk-width-1-1@xs uk-width-1-2@s uk-width-1-6@m'; // Default to 3 per uk-container
}

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


    <?php if($display == "carousel"): ?>

        <div id="<?php echo $rand_id;?>" class="fc-section-cards carousel-wrapper">
            <div class="cards-slick" data-slick="<?php echo esc_attr( wp_json_encode( $slick_opts ) ); ?>">

                <?php foreach( $cards ?? [] as $card ): ?>

                <div class="card-slide">
                    <?php get_template_part( $card_part, null, array(
                        'card'  => $card,
                        'class' => '',
                        'aos'   => '',
                        'style' => $card_style,
                    ) ); ?>
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

                    $aos_attrs = $aos ? sprintf(
                        ' data-aos="%s" data-aos-duration="%s" data-aos-delay="%s"',
                        esc_attr($aos),
                        esc_attr($aos_duration),
                        esc_attr($delay)
                    ) : '';
                ?>

                <?php get_template_part( $card_part, null, array(
                    'card'  => $card,
                    'class' => $grid_class,
                    'aos'   => $aos_attrs,
                    'style' => $card_style,
                ) ); ?>

                <?php
                    if ($delay >= ($aos_step * $per_row)) {
                        $delay = 0;
                    }
                ?>

                <?php endforeach; ?>

            </div><!-- .grid-container -->

    <?php endif; ?>
