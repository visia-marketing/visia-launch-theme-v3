<article class="page page-search">

  <section class="page-content">
    <div class="uk-container">
      <div class="uk-width-1-1">
        
        <?php // Was a <p>, so the results page had no <h1> and nothing to orient a screen
              // reader arriving from the search form. ?>
        <h1><?php esc_html_e( 'Search Results Found For', 'visia_marketing' ); ?>: "<?php echo esc_html( get_search_query() ); ?>"</h1>

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
                  <h2><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( $title ); ?></a></h2>
                  <span class="search-permalink"><?php echo esc_html( get_permalink() ); ?></span>
                  <p><?php echo wp_kses_post( $excerpt ); ?></p>
                  <?php
                    // "Read More" repeats on every result, so the result title is appended
                    // as visually-hidden text to keep each link distinguishable. WCAG 2.4.4.
                    printf(
                      '<a href="%s" class="uk-button">%s %s</a>',
                      esc_url( get_the_permalink() ),
                      visia_cta_label( __( 'Read More', 'visia_marketing' ), $title ),
                      visia_icon( 'fa-solid fa-arrow-right' )
                    );
                  ?>
                </div>
              </div>      

            <?php } ?>

            </div>

            <nav class="search-pagination" aria-label="<?php esc_attr_e( 'Search results pages', 'visia_marketing' ); ?>">
              <?php echo paginate_links(); ?>
            </nav>

        <?php } else { ?>

          <div class="search-results-wrapper">
            <div class="search-results-none">
              <div class="uk-container">
                <div class="uk-width-1-1">
                  <?php
                  // NOT wp_kses_post(): editor-authored rich text from the options page,
                  // and a likely home for an embedded "can't find it? contact us" form.
                  echo get_field('no_results_message', 'options');
                  ?>
                </div>            
              </div>
            </div>  
          </div>

        <?php } ?>

      </div>
    </div>
  </section>

</article>