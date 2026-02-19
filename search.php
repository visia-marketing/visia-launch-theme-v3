<article class="page page-search">

  <section class="page-content">
    <div class="uk-container">
      <div class="small-12 columns">
        
        <br>
        <p><br><?php _e( 'Search Results Found For', 'locale' ); ?>: "<?php the_search_query(); ?>" </p>

        <?php if ( have_posts() ) { ?>

            <div class="search-results-wrapper">

            <?php while ( have_posts() ) { the_post(); ?>

              <?php

              if ( get_post_meta( $post->ID, '_yoast_wpseo_title', true ) ):
                $title = get_post_meta( $post->ID, '_yoast_wpseo_title', true );
              elseif ( get_sub_field('title') ):
                $title = get_sub_field('title');
              else: 
                $title = get_the_title(); 
              endif; 

              if ( has_excerpt() ):
                $excerpt = get_the_excerpt();
              else:
                $excerpt = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
              endif;

              ?>

              <div class="search-results-cell">
                <?php if ( get_the_post_thumbnail() ): ?>
                <div class="search-results-cell-image">
                  <?php  the_post_thumbnail( array(100, 100) ); ?>
                </div>
                 <?php endif; ?>
                <div class="search-results-cell-content">
                  <h2><a href="<?php echo get_permalink(); ?>"><?php echo $title; ?></a></h2>
                  <span class="search-permalink"><?php the_permalink(); ?></span>
                  <p><?php echo $excerpt; ?></p>
                  <?php 
                    echo '<a href="' . get_the_permalink() . '" class="read-more">Read More <i class="fa-solid fa-aruk-container-right"></i></a>';
                  ?>
                </div>
              </div>      

            <?php } ?>

            </div>

            <div class="search-pagination">
              <?php echo paginate_links(); ?>
            </div>

        <?php } else { ?>

          <div class="search-results-wrapper">
            <div class="search-results-none">
              <div class="uk-container">
                <div class="small-12 columns">
                  <?php the_field('no_results_message', 'options'); ?>
                </div>            
              </div>
            </div>  
          </div>

        <?php } ?>

      </div>
    </div>
  </section>

</article>