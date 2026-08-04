<?php
/**
 * Accordion/FAQ section.
 *
 * Three styles, set by the `faq_style` radio:
 *   plain      — no accordion; every question and answer is rendered open
 *   stacked    — accordion, items flush and divided by rules
 *   separated  — accordion, each item a bordered card with a gap between
 *
 * `uk-accordion-default` is deliberately not applied: its nth-child margins and title
 * styles conflict with the per-style spacing in _flexible-accordion.scss. The
 * `uk-accordion-title` / `uk-accordion-content` classes are kept because UIkit's
 * accordion JS selects on them.
 */

$style = get_sub_field('faq_style') ?: 'stacked';
$faqs  = get_sub_field('questions_and_answers');

$is_plain = ( 'plain' === $style );

/*
 * The toggle was added after this component shipped. Rows saved before it existed
 * return null and were emitting schema unconditionally, so "never set" is treated as
 * enabled to avoid silently dropping structured data from existing pages.
 */
$enable_schema = get_sub_field('enable_faq_schema');
$enable_schema = ( null === $enable_schema ) ? true : (bool) $enable_schema;

// Was hard-coded <h4> regardless of what preceded the section, which skipped a level on
// almost every page. h3 is the sensible default under a section's own h2.
$heading_tag = visia_heading_tag( get_sub_field('faq_heading_level'), 'h3' );

$faq_uid = visia_unique_id( 'faq' );
?>

<div class="fc-section-columns fc-faq-section fc-faq-section--<?php echo esc_attr( $style ); ?>">

  <?php if ( is_array( $faqs ) && $faqs ) : ?>

    <?php
    // UIkit's accordion looks for `> .uk-accordion-title` by default. The toggle now sits
    // inside a heading — the accessible pattern is heading-wraps-button, never
    // button-wraps-heading — so the selector is redeclared to match the new depth.
    $accordion_attr = $is_plain
      ? ''
      : 'uk-accordion="toggle: > .fc-faq-question__heading > .uk-accordion-title"';
    ?>
    <ul class="fc-faq-list<?php echo $is_plain ? '' : ' uk-accordion'; ?>" <?php echo $accordion_attr; ?>>

      <?php
      $faq_index = 0;
      foreach ( $faqs as $faq ) :
        if ( empty( $faq['question'] ) ) {
          continue;
        }
        $faq_index++;
        $answer      = isset( $faq['answer'] ) ? wpautop( wp_kses_post( $faq['answer'] ) ) : '';
        $question_id = $faq_uid . '-question-' . $faq_index;
        $answer_id   = $faq_uid . '-answer-' . $faq_index;
        ?>
        <li class="fc-faq-item">

          <?php if ( $is_plain ) : ?>

            <div class="fc-faq-question">
              <<?php echo $heading_tag; ?> class="fc-faq-question__text"><?php echo wp_kses_post( $faq['question'] ); ?></<?php echo $heading_tag; ?>>
            </div>
            <div class="fc-faq-answer"><?php echo $answer; ?></div>

          <?php else : ?>

            <?php
            // Was <a href> — a valueless href resolves to the current page, so the control
            // announced as a link and, without JS, navigated instead of toggling. A
            // disclosure is a button. aria-expanded/aria-controls declare the state and
            // the relationship that the uk-open class alone only showed visually.
            ?>
            <<?php echo $heading_tag; ?> class="fc-faq-question__heading">
              <button class="fc-faq-question uk-accordion-title"
                      type="button"
                      id="<?php echo esc_attr( $question_id ); ?>"
                      aria-expanded="false"
                      aria-controls="<?php echo esc_attr( $answer_id ); ?>">
                <span class="fc-faq-question__text"><?php echo wp_kses_post( $faq['question'] ); ?></span>
                <span class="fc-faq-toggle" aria-hidden="true" uk-icon="icon: chevron-down; ratio: 1.5"></span>
              </button>
            </<?php echo $heading_tag; ?>>
            <div class="fc-faq-answer uk-accordion-content"
                 id="<?php echo esc_attr( $answer_id ); ?>"
                 role="region"
                 aria-labelledby="<?php echo esc_attr( $question_id ); ?>"><?php echo $answer; ?></div>

          <?php endif; ?>

        </li>
      <?php endforeach; ?>

    </ul>

  <?php endif; ?>
</div>

<?php
if ( $enable_schema && is_array( $faqs ) ) {

  $schema_items = [];

  foreach ( $faqs as $faq ) {
    if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
      continue;
    }
    $schema_items[] = [
      '@type'          => 'Question',
      'name'           => wp_strip_all_tags( $faq['question'] ),
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text'  => wp_strip_all_tags( $faq['answer'] ),
      ],
    ];
  }

  if ( $schema_items ) {
    ?>
<script type="application/ld+json">
<?php echo wp_json_encode([
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'mainEntity' => $schema_items,
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
    <?php
  }
}
