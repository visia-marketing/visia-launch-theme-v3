<?php
/**
 * Simple card — white surface, image on top, padded body with the description
 * and arrow button always visible.
 *
 * Serves both the "Simple" and "Plain" card styles, which share this markup and
 * most of their rules; plain only drops the card surface and the body padding,
 * via `.cards-style--plain` in assets/src/styles/flexible/_flexible-cards.scss.
 * flexible/section_cards.php maps both styles here.
 *
 * @param array $args {
 *     @type array  $card  Cards repeater row (card_icon, card_title, card_description, card_link).
 *     @type string $class Extra layout classes for the card root — grid widths and
 *                         spacing; empty inside a carousel, where Slick sizes the slide.
 *     @type string $aos   Pre-built AOS attribute string for the card root; empty
 *                         when the section's animation is off.
 *     @type string $style Which modifier to emit — 'simple' (default) or 'plain'.
 * }
 */

$card = $args['card'] ?? null;
if ( ! $card ) return;

$extra_class = $args['class'] ?? '';
$aos_attrs   = $args['aos'] ?? '';
$style       = ( ( $args['style'] ?? '' ) === 'plain' ) ? 'plain' : 'simple';

// The link field returns an array; fall back to a bare URL string.
$link       = $card['card_link'] ?? null;
$card_url   = is_array( $link ) ? ( $link['url'] ?? '' ) : ( $link ?? '' );
$link_title = is_array( $link ) ? ( $link['title'] ?? '' ) : '';
$link_label = $link_title !== '' ? $link_title : __( 'Read More', 'visia_marketing' );
$new_window = is_array( $link ) ? ( $link['target'] ?? '' ) : '';

$card_title  = $card['card_title'] ?? '';
$description = $card['card_description'] ?? '';

// Decorative: the field is a card icon, and the card title beside it already carries the
// meaning. Left to wp_get_attachment_image() it announced the media library's alt text from
// inside the card link, padding the link's accessible name with unrelated words.
$image = visia_decorative_image( $card['card_icon'] ?? 0, 'thumbnail', array( 'class' => 'uk-width-1-1' ) );

// With no destination configured the body used to render as <a href="#"> — a focusable
// link that goes nowhere. Without a URL it is now plain markup.
$is_link = $card_url !== '';

// The body wraps the heading, description and a button-styled span, so the link's
// accessible name was all three concatenated. Naming it after the card keeps the
// announcement short and unique. WCAG 2.4.4.
$link_name = trim( wp_strip_all_tags( $card_title ) );
if ( $link_name === '' ) {
	$link_name = $link_label;
}
// aria-label replaces the link's content as its name, so the new-window warning has to be
// folded into it rather than added as a visually-hidden child. WCAG 3.2.5.
if ( $new_window === '_blank' ) {
	$link_name .= ' ' . __( '(opens in a new window)', 'visia_marketing' );
}
?>

<div class="uk-card card-background--image cards-style--<?php echo esc_attr( $style ); ?> <?php echo esc_attr( $extra_class ); ?>"<?php echo $aos_attrs; ?>>
    <div class="uk-height-1-1 uk-flex uk-flex-column uk-position-relative uk-card--inner">

        <?php if ( $image ) : ?>
            <div class="card-media uk-card-media-top">
                <?php echo $image; ?>
            </div>
        <?php endif; ?>

        <?php if ( $is_link ) : ?>
        <a href="<?php echo esc_url( $card_url ); ?>" class="card-body uk-card-body uk-flex uk-flex-column uk-flex-top uk-height-1-1" aria-label="<?php echo esc_attr( $link_name ); ?>"<?php echo visia_new_window_attrs( $new_window ); ?>>
        <?php else : ?>
        <div class="card-body uk-card-body uk-flex uk-flex-column uk-flex-top uk-height-1-1">
        <?php endif; ?>

            <h3 class="card-title uk-card-title uk-margin-remove">
                <?php echo esc_html( $card_title ); ?>
            </h3>

            <div class="hover-panel">
                <?php if ( $description !== '' ) : ?>
                    <p class="card-p uk-margin-top">
                        <?php echo wp_kses_post( $description ); ?>
                    </p>
                    <?php // Styled to look like a button but it is a span inside the card
                          // link — not separately focusable and not a control. Hidden from
                          // AT so it does not read as a second, unreachable affordance. ?>
                    <span class="uk-button uk-button-arrow uk-flex uk-flex-inline" aria-hidden="true">
                        <?php echo esc_html( $link_label ); ?>
                    </span>
                <?php endif; ?>
            </div>

        <?php if ( $is_link ) : ?>
        </a>
        <?php else : ?>
        </div>
        <?php endif; ?>

    </div>
</div>
