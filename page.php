<?php while (have_posts()) : the_post(); ?>
  
  <article class="page page-<?php global $post; echo $post->post_name; ?>">

    
    <?php get_template_part('templates/page-header'); ?>

		
    <section class="page-content-wrapper">
      <div class="uk-container">
        <div class="small-12 columns">
					
          <div class="page-content">
            <?php the_content(); ?>
          </div>
          
        </div>
      </div>
    </section>

  </article>
  
<?php endwhile; ?>