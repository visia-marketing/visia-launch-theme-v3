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
?>

<div class="fc-section-columns fc-faq-section fc-faq-section--<?php echo esc_attr( $style ); ?>">

  <?php if ( is_array( $faqs ) && $faqs ) : ?>

    <ul class="fc-faq-list<?php echo $is_plain ? '' : ' uk-accordion'; ?>" <?php echo $is_plain ? '' : 'uk-accordion'; ?>>

      <?php foreach ( $faqs as $faq ) :
        if ( empty( $faq['question'] ) ) {
          continue;
        }
        $answer = isset( $faq['answer'] ) ? wpautop( wp_kses_post( $faq['answer'] ) ) : '';
        ?>
        <li class="fc-faq-item">

          <?php if ( $is_plain ) : ?>

            <div class="fc-faq-question">
              <h4 class="fc-faq-question__text"><?php echo wp_kses_post( $faq['question'] ); ?></h4>
            </div>
            <div class="fc-faq-answer"><?php echo $answer; ?></div>

          <?php else : ?>

            <a class="fc-faq-question uk-accordion-title" href>
              <h4 class="fc-faq-question__text"><?php echo wp_kses_post( $faq['question'] ); ?></h4>
              <span class="fc-faq-toggle" uk-icon="icon: chevron-down; ratio: 1.5"></span>
            </a>
            <div class="fc-faq-answer uk-accordion-content"><?php echo $answer; ?></div>

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
