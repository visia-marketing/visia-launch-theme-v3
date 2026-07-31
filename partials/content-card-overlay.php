<?php
/**
 * Overlay card — the image fills the card and the body sits over it as a
 * gradient panel; the description and button live in a collapsed `.hover-panel`
 * that main.js expands on hover.
 *
 * Rendered by flexible/section_cards.php for the "Overlay" card style; styled by
 * `.cards-style--overlay` in assets/src/styles/flexible/_flexible-cards.scss and
 * driven by hoverCardsInit() in assets/src/scripts/main.js, which binds to
 * `.cards-style--overlay` on this root element.
 *
 * @param array $args {
 *     @type array  $card  Cards repeater row (card_icon, card_title, card_description, card_link).
 *     @type string $class Extra layout classes for the card root — grid widths and
 *                         spacing; empty inside a carousel, where Slick sizes the slide.
 *     @type string $aos   Pre-built AOS attribute string for the card root; empty
 *                         when the section's animation is off.
 * }
 */

$card = $args['card'] ?? null;
if ( ! $card ) return;

$extra_class = $args['class'] ?? '';
$aos_attrs   = $args['aos'] ?? '';

// The link field returns an array; fall back to a bare URL string or '#'.
$link       = $card['card_link'] ?? null;
$card_url   = is_array( $link ) ? ( $link['url'] ?? '' ) : ( $link ?? '' );
$link_label = is_array( $link ) ? ( $link['title'] ?? 'Read More' ) : 'Read More';

$description = $card['card_description'] ?? '';
$image       = wp_get_attachment_image( $card['card_icon'] ?? 0, 'thumbnail', false, array( 'class' => 'uk-width-1-1' ) );
?>

<div class="uk-card card-background--image cards-style--overlay <?php echo esc_attr( $extra_class ); ?>"<?php echo $aos_attrs; ?>>
    <div class="uk-height-1-1 uk-flex uk-flex-column uk-position-relative uk-card--inner">

        <?php if ( $image ) : ?>
            <div class="card-media uk-card-media-top">
                <?php echo $image; ?>
            </div>
        <?php endif; ?>

        <?php // uk-flex-right bottom-aligns the body's content over the image. ?>
        <a href="<?php echo esc_url( $card_url ?: '#' ); ?>" class="card-body uk-card-body uk-flex uk-flex-column uk-flex-right uk-flex-top uk-height-1-1">

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
