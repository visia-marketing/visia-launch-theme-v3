<?php
$style = get_sub_field('faq_style');
$faqs  = get_sub_field('questions_and_answers');
?>

<div class="fc-section-columns fc-faq-section<?php echo $style !== 'plain' ? ' fc-section-accordion-simple' : ''; ?>">
  <?php get_template_part('flexible/section_header'); ?>

  <?php if( is_array( $faqs ) ): ?>

    <ul class="uk-accordion uk-accordion-default" uk-accordion>

      <?php foreach ($faqs as $faq ): ?>
        <li class="uk-margin-remove-top">
          <a class="uk-accordion-title uk-flex uk-flex-middle uk-flex-between" href>
            <h4 class="uk-margin-top uk-margin-bottom"><?php echo wp_kses_post($faq['question']); ?></h4>
            <span uk-icon="icon: chevron-down; ratio: 1.5"></span>
          </a>
          <div class="uk-accordion-content uk-margin-medium-bottom"><?php echo wp_kses_post($faq['answer']); ?></div>
        </li>
      <?php endforeach; ?>

    </ul>

  <?php endif; ?>
</div>

<?php
$schema_items = [];
if ( have_rows('questions_and_answers') ) {
  while ( have_rows('questions_and_answers') ) : the_row();
    $schema_items[] = [
      '@type'          => 'Question',
      'name'           => get_sub_field('question'),
      'acceptedAnswer' => [
        '@type' => 'Answer',
        'text'  => get_sub_field('answer'),
      ],
    ];
  endwhile;
}

if ( ! empty($schema_items) ):
?>
<script type="application/ld+json">
<?php echo wp_json_encode([
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'mainEntity' => $schema_items,
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>
