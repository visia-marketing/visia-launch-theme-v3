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

// The link field returns an array; fall back to a bare URL string or '#'.
$link       = $card['card_link'] ?? null;
$card_url   = is_array( $link ) ? ( $link['url'] ?? '' ) : ( $link ?? '' );
$link_label = is_array( $link ) ? ( $link['title'] ?? 'Read More' ) : 'Read More';

$description = $card['card_description'] ?? '';
$image       = wp_get_attachment_image( $card['card_icon'] ?? 0, 'thumbnail', false, array( 'class' => 'uk-width-1-1' ) );
?>

<div class="uk-card card-background--image cards-style--<?php echo $style; ?> <?php echo esc_attr( $extra_class ); ?>"<?php echo $aos_attrs; ?>>
    <div class="uk-height-1-1 uk-flex uk-flex-column uk-position-relative uk-card--inner">

        <?php if ( $image ) : ?>
            <div class="card-media uk-card-media-top">
                <?php echo $image; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo esc_url( $card_url ?: '#' ); ?>" class="card-body uk-card-body uk-flex uk-flex-column uk-flex-top uk-height-1-1">

            <h3 class="card-title uk-card-title uk-margin-remove">
                <?php echo $card['card_title'] ?? ''; ?>
            </h3>

            <div class="hover-panel">
                <?php if ( $description !== '' ) : ?>
                    <p class="card-p uk-margin-top">
                        <?php echo $description; ?>
                    </p>
                    <span class="uk-button uk-button-arrow uk-flex uk-flex-inline">
                        <?php echo $link_label; ?>
                    </span>
                <?php endif; ?>
            </div>

        </a>

    </div>
</div>
