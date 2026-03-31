<article class="post-single">
  <?php //get_template_part('templates/page-breadcrumbs'); ?>
  <section class="post-content-wrapper">
    <div class="uk-container">
      <div class="uk-width-1-1 uk-width-4-5@m">
        <?php get_template_part('templates/content-single', get_post_type()); ?>
        <?php //the_posts_navigation(); ?>
      </div>
      <div class="uk-width-1-1 uk-width-1-5@m">
        <?php //get_template_part('templates/post-sidebar'); ?>
      </div>
    </div>
  </section>
</article>