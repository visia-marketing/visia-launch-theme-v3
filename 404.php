<?php
/**
 * 404 template.
 *
 * When a page is selected in Theme Settings → Global → "404 Page" (`id_404_page`),
 * that page is rendered here in place of the hard-coded markup below. The response
 * keeps its 404 status — only the body content is swapped.
 */
$page_404_id = (int) get_field('id_404_page', 'options');

if ($page_404_id && get_post_status($page_404_id) === 'publish') {
    global $wp_query;

    $original_query = $wp_query;

    // Run the selected page through a real loop so the_content(), get_field()
    // and get_flexible_content() all resolve against it. is_404() is false
    // inside this block, which is what the page templates expect; body_class
    // and the document title already ran with the 404 context intact.
    $wp_query = new WP_Query([
        'page_id'             => $page_404_id,
        'post_type'           => 'page',
        'ignore_sticky_posts' => true,
    ]);

    $assigned = get_page_template_slug($page_404_id);
    $template = $assigned ? locate_template($assigned) : '';

    if (!$template) {
        $template = locate_template('page.php');
    }

    include $template;

    $wp_query = $original_query;
    wp_reset_postdata();

    return;
}
?>

<article class="page page-404">

  <?php get_template_part('partials/page-header'); ?>

  <section class="page-content-wrapper">
    <div class="uk-container">
      <div class="uk-width-1-1">

      <?php // Every page needs exactly one <h1>; this template previously had none, and the
            // page-header partial above only renders when the editor filled it in. ?>
      <h1><?php esc_html_e('Page not found', 'visia_marketing'); ?></h1>

      <div class="alert alert-warning">
        <?php _e('Sorry, but the page you were trying to view does not exist.', 'visia_marketing'); ?>
      </div>

      <?php get_search_form(); ?>

      </div>
    </div>
  </section>

</article>
