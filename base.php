<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?> class="no-js" >
  
  <?php get_template_part('partials/site-head'); ?>
  
  <body <?php body_class(); ?>>

    <?php if ( get_field('google_tag_manager_id', 'options') ):?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo get_field('google_tag_manager_id', 'options');?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>


    <!-- This is the off-canvas -->
    <div id="uk-off-canvas" uk-offcanvas="mode: push">
        <div class="uk-offcanvas-bar uk-flex uk-flex-column">

            <button class="uk-offcanvas-close" type="button" uk-close></button>

            <?php
            if (has_nav_menu('mobile_navigation')) :
              wp_nav_menu(['theme_location' => 'mobile_navigation', 'depth' => 3, 'menu_class' => 'mobile-navigation', 'items_wrap' => '<ul class="%2$s" id="mobile-navigation">%3$s</ul>' ]);
              endif;
            ?>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
              document.querySelectorAll('#mobile-navigation > li').forEach(function(li) {
                var submenu = li.querySelector('ul.submenu');
                if (!submenu) return;

                var link = li.querySelector('a');
                var row = document.createElement('div');
                row.className = 'mobile-nav-row';

                var toggle = document.createElement('button');
                toggle.className = 'mobile-submenu-toggle';
                toggle.innerHTML = '<div class="icon-container"><span uk-icon="icon: plus;"></span> <span uk-icon="icon: minus;"></span></div>';
                toggle.setAttribute('aria-expanded', 'false');

                li.insertBefore(row, link);
                row.appendChild(link);
                row.appendChild(toggle);

                toggle.addEventListener('click', function() {
                  var isOpen = submenu.style.display === 'block';
                  submenu.style.display = isOpen ? 'none' : 'block';
                  toggle.classList.toggle('is-open', !isOpen);
                  toggle.setAttribute('aria-expanded', !isOpen);
                });
              });
            });
            </script>

            <div class="off-canvas-search">
              <form role="search" method="get" class="search-form" action="<?= site_url(); ?>">
                <label>
                  <span class="screen-reader-text">Search</span>
                  <input type="search" class="search-field" id="search-field" placeholder="Search…" value="" name="s">
                </label>
                <button class="uk-button"> <i class="fa-solid fa-magnifying-glass"></i></button>
              </form>
            </div>

        </div>
        
    </div>


    <div class="off-canvas-content" data-off-canvas-content>
    
      <?php do_action('get_header'); ?>
        
      <?php get_template_part('partials/site-header'); ?>

      <div class="wrap" role="document">
        <main class="main">
          
          <?php include Wrapper\template_path(); ?>

        </main><!-- /.main -->
      </div><!-- /.wrap -->
  
      <?php
        do_action('get_footer');
        get_template_part('partials/site-footer');
        wp_footer();
      ?>

    </div>

  </body>
</html>