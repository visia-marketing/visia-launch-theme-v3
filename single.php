<article class="post-single">
  <?php //get_template_part('templates/page-breadcrumbs'); ?>
  <section class="post-content-wrapper">
    <div class="uk-container">
      <div class="small-12 medium-11 medium-centered large-10 columns">
        <?php get_template_part('templates/content-single', get_post_type()); ?>
        <?php //the_posts_navigation(); ?>
      </div>
      <div class="small-12 columns">
        <?php //get_template_part('templates/post-sidebar'); ?>
      </div>
    </div>
  </section>
</article>