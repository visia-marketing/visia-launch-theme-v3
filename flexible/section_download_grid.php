<?php
$source = get_sub_field('source');

if ($source === 'category') {
  // Pull all downloads in the selected download category/categories.
  $cat        = get_sub_field('dlm_download_catgory'); // term object(s) (return: object)
  $term_ids   = array();
  $term_names = array();
  foreach ((array) $cat as $t) {
    if (is_object($t)) {
      $term_ids[]   = (int) $t->term_id;
      $term_names[] = $t->name;
    } elseif (is_numeric($t)) {
      $term_ids[]   = (int) $t;
      $term         = get_term((int) $t, 'dlm_download_category');
      if ($term && !is_wp_error($term)) $term_names[] = $term->name;
    }
  }
  $category_title = implode(', ', $term_names);

  $downloads = $term_ids ? get_posts(array(
    'post_type'      => 'dlm_download',
    'posts_per_page' => -1,
    'tax_query'      => array(array(
      'taxonomy' => 'dlm_download_category',
      'field'    => 'term_id',
      'terms'    => $term_ids,
    )),
  )) : array();
} else {
  // Explicitly chosen downloads (return: object).
  $downloads = get_sub_field('download_ids');
}

$heading_tag = visia_heading_tag( get_sub_field('heading_level'), 'h2' );

if ($downloads) : ?>

  <div class="fc-section-downloads">

    <?php if (!empty($category_title)) : ?>
      <?php // Level is configurable rather than always h2 — this section is often nested
            // under another section's heading. ?>
      <<?php echo $heading_tag; ?> class="download-grid-category-title"><?php echo esc_html($category_title); ?></<?php echo $heading_tag; ?>>
    <?php endif; ?>

    <div class="download-grid">

      <?php foreach ($downloads as $download) :
        if (!$download) continue;

        $title     = get_the_title($download->ID);
        $permalink = get_permalink($download->ID);
        $thumbnail = get_the_post_thumbnail($download->ID, 'large');
      ?>

        <div class="download-item">
          <?php // Two adjacent links to the same file. The thumbnail link has no accessible
                // name whenever the featured image alt is blank, so it is hidden from AT and
                // taken out of the tab order; the titled link below stays. ?>
          <a href="<?php echo esc_url($permalink); ?>" target="_blank" rel="noopener noreferrer" tabindex="-1" aria-hidden="true">
            <div class="download-thumbnail">
              <?php echo get_the_post_thumbnail($download->ID, 'large', array('alt' => '')); ?>
            </div>
          </a>
          <a href="<?php echo esc_url($permalink); ?>" target="_blank" rel="noopener noreferrer" class="download-title"><?php echo visia_icon('fa-sharp fa-solid fa-download'); ?><?php echo esc_html($title); ?><?php echo visia_new_window_notice('_blank'); ?></a>
        </div>

      <?php endforeach; ?>

    </div>
  </div>

<?php endif; ?>
