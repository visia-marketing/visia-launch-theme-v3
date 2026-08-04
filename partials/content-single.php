<div class="post-header">
  <?php
    if ( is_single() ) {
        $post_type = get_post_type();
        echo '<span class="post-type">' . esc_html($post_type === 'post' ? 'blog' : $post_type) . '</span>';
    }
  ?>
  <h1 class="post-title"><?php echo esc_html(get_the_title()); ?></h1>
  <?php
  // The subhead reads as a heading but was marked up as a plain div, so it never appeared
  // in the heading outline. h2 keeps it directly under the post title.
  if (get_field('post_subhead')): ?>
    <h2 class="post-subhead"><?php echo wp_kses_post(get_field('post_subhead')); ?></h2>
  <?php endif;?>
  <div class="post-featured-image">
    <?php
    if ( has_post_thumbnail() ) {
      // The hero repeats the post title visually; announcing the attachment's alt on top of
      // the <h1> is redundant, so it is rendered as decorative.
      the_post_thumbnail('post-thumbnail', array('alt' => ''));
    }
    ?>
  </div>
  <div class="post-meta">
    <?php // class was "updated", which claims this is a modification date; it is the
          // publish date. The datetime attribute carries the machine-readable value
          // because the visible "08 . 04 . 26" format is ambiguous. ?>
    <time class="published" datetime="<?= esc_attr(get_post_time('c', true)); ?>"><?= esc_html(get_the_date('m . d . y')); ?></time>
    <?php /*<p class="byline author vcard"><?= __('By', 'visia_marketing'); ?> <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?= get_the_author(); ?></a></p>*/?>
    <!-- Will social go here? -->
  </div>
</div>
<div class="post-content">
  <?php the_content();?>
</div>