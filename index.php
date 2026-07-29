<article class="post-archive">

  <div class="post-header">
    <div class="uk-container">
      <div class="uk-width-1-1">
        <strong>Post Header -> Should be H1</strong>
      </div>
    </div>
  </div>

  <section class="post-content">
    <div class="uk-container">
      
      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'visia_marketing'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <div class="uk-width-1-1 uk-width-1-3@m">
          <?php // TODO: partials/content does not exist — archive cards render nothing until a partial is created
          get_template_part('partials/content', get_post_type() != 'post' ? get_post_type() : get_post_format()); ?>
        </div>
      <?php endwhile; ?>

      <?php the_posts_navigation(); ?>

    </div>
  </section>

</article>





