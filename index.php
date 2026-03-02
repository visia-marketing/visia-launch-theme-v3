<article class="post-archive">

  <div class="post-header">
    <div class="uk-container">
      <div class="uk-width-1-1">
        <strong>Post Header -> Should be H1</strong>
      </div>
    </div>
  </div>

  <section class="post-content">
    <div class="uk-container" data-equalizer data-equalize-by-row="true">
      
      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'visia_starter_theme'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <div class="uk-width-1-1 uk-width-1-3@m">
          <?php get_template_part('templates/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
        </div>
      <?php endwhile; ?>

      <?php the_posts_navigation(); ?>

    </div>
  </section>

</article>





