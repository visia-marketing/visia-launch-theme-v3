<?php
/**
 * Accordion/FAQ section.
 *
 * Three styles, set by the `accordion_style` radio:
 *   plain      — no accordion; every question and answer is rendered open
 *   stacked    — accordion, items flush and divided by rules
 *   separated  — accordion, each item a bordered card with a gap between
 *
 * `uk-accordion-default` is deliberately not applied: its nth-child margins and title
 * styles conflict with the per-style spacing in _flexible-accordion.scss. The
 * `uk-accordion-title` / `uk-accordion-content` classes are kept because UIkit's
 * accordion JS selects on them.
 */

$style = get_sub_field('accordion_style') ?: 'stacked';
$accord_content  = get_sub_field('accordion_content');

$is_plain = ( 'plain' === $style );

// Was hard-coded <h4> regardless of what preceded the section, which skipped a level on
// almost every page. h3 is the sensible default under a section's own h2.
$heading_tag = visia_heading_tag( get_sub_field('accordion_heading_level'), 'h3' );

$accord_uid = visia_unique_id( 'accordion' );
?>

<div class="fc-section-columns fc-accordion-section fc-accordion-section--<?php echo esc_attr( $style ); ?>">

  <?php if ( is_array( $accord_content ) && $accord_content ) : ?>

    <?php
    // UIkit's accordion looks for `> .uk-accordion-title` by default. The toggle now sits
    // inside a heading — the accessible pattern is heading-wraps-button, never
    // button-wraps-heading — so the selector is redeclared to match the new depth.
    $accordion_attr = $is_plain
      ? ''
      : 'uk-accordion="toggle: > .fc-accordion-title__heading > .uk-accordion-title"';
    ?>
    <ul class="fc-accordion-list<?php echo $is_plain ? '' : ' uk-accordion'; ?>" <?php echo $accordion_attr; ?>>

      <?php
      $index = 0;
      foreach ( $accord_content as $accordion ) :
        if ( empty( $accordion['title'] ) ) {
          continue;
        }
        $index++;
        $title      = $accordion['title'];
        $answer     = isset( $accordion['content'] ) ? wpautop( wp_kses_post( $accordion['content'] ) ) : '';
        $title_id = $accord_uid . '-title-' . $index;
        $content_id   = $accord_uid . '-content-' . $index;
        ?>
        <li class="fc-accordion-item">

          <?php if ( $is_plain ) : ?>

            <div class="fc-accordion-title">
              <<?php echo $heading_tag; ?> class="fc-accordion-title__text"><?php echo esc_html( $title ); ?></<?php echo $heading_tag; ?>>
            </div>
            <div class="fc-accordion-content"><?php echo $answer; ?></div>

          <?php else : ?>

            <?php
            // Was <a href> — a valueless href resolves to the current page, so the control
            // announced as a link and, without JS, navigated instead of toggling. A
            // disclosure is a button. aria-expanded/aria-controls declare the state and
            // the relationship that the uk-open class alone only showed visually.
            ?>
            <<?php echo $heading_tag; ?> class="fc-accordion-title__heading">
              <button class="fc-accordion-question uk-accordion-title"
                      type="button"
                      id="<?php echo esc_attr( $title_id ); ?>"
                      aria-expanded="false"
                      aria-controls="<?php echo esc_attr( $content_id ); ?>">
                <span class="fc-accordion-title__text"><?php echo esc_html( $title ); ?></span>
                <span class="fc-accordion-toggle" aria-hidden="true" uk-icon="icon: chevron-down; ratio: 1.5"></span>
              </button>
            </<?php echo $heading_tag; ?>>
            <div class="fc-accordion-answer uk-accordion-content"
                 id="<?php echo esc_attr( $content_id ); ?>"
                 role="region"
                 aria-labelledby="<?php echo esc_attr( $title_id ); ?>"><?php echo $answer; ?></div>

          <?php endif; ?>

        </li>
      <?php endforeach; ?>

    </ul>

  <?php endif; ?>
</div>
