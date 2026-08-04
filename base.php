<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?> class="no-js" >

  <?php get_template_part('partials/site-head'); ?>

  <body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <?php // First focusable element on the page. Visually hidden until focused — see
          // .skip-link in assets/src/styles/common/_a11y.scss. WCAG 2.4.1. ?>
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'visia_marketing'); ?></a>

    <?php if ( get_field('google_tag_manager_id', 'options') ):?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe title="Google Tag Manager" src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr(get_field('google_tag_manager_id', 'options'));?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>


    <!-- This is the off-canvas -->
    <div id="uk-off-canvas" uk-offcanvas="mode: push">
        <div class="uk-offcanvas-bar uk-flex uk-flex-column">

            <button class="uk-offcanvas-close" type="button" uk-close aria-label="<?php esc_attr_e('Close menu', 'visia_marketing'); ?>"></button>

            <?php
            if (has_nav_menu('mobile_navigation')) :
              // Wrapped in <nav> with its own label: roots_nav_menu_args() forces
              // container => false, so wp_nav_menu() alone emits a bare <ul>.
              echo '<nav aria-label="' . esc_attr__('Mobile navigation', 'visia_marketing') . '">';
              wp_nav_menu(['theme_location' => 'mobile_navigation', 'depth' => 3, 'menu_class' => 'mobile-navigation', 'items_wrap' => '<ul class="%2$s" id="mobile-navigation">%3$s</ul>' ]);
              echo '</nav>';
              endif;
            ?>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
              document.querySelectorAll('#mobile-navigation > li').forEach(function(li, index) {
                var submenu = li.querySelector('ul.submenu');
                if (!submenu) return;

                var link = li.querySelector('a');

                // The submenu needs an id so the toggle can point aria-controls at it.
                // lib/nav.php nulls out nav_menu_item_id, so nothing upstream supplies one.
                if (!submenu.id) {
                  submenu.id = 'mobile-submenu-' + index;
                }
                submenu.hidden = true;

                var row = document.createElement('div');
                row.className = 'mobile-nav-row';

                var label = link ? link.textContent.trim() : '';

                var toggle = document.createElement('button');
                toggle.className = 'mobile-submenu-toggle';
                toggle.type = 'button';
                toggle.innerHTML = '<div class="icon-container" aria-hidden="true"><span uk-icon="icon: plus;"></span> <span uk-icon="icon: minus;"></span></div>';
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-controls', submenu.id);
                // Without a name this button announces as just "button" — it is icon-only.
                toggle.setAttribute('aria-label', label ? 'Toggle submenu for ' + label : 'Toggle submenu');

                li.insertBefore(row, link);
                row.appendChild(link);
                row.appendChild(toggle);

                toggle.addEventListener('click', function() {
                  var isOpen = toggle.getAttribute('aria-expanded') === 'true';
                  // `hidden` rather than inline display so the collapsed links leave the
                  // tab order as well as the viewport.
                  submenu.hidden = isOpen;
                  toggle.classList.toggle('is-open', !isOpen);
                  toggle.setAttribute('aria-expanded', String(!isOpen));
                });
              });
            });
            </script>

            <div class="off-canvas-search">
              <?php get_search_form(); ?>
            </div>

        </div>

    </div>


    <div class="off-canvas-content" data-off-canvas-content>

      <?php do_action('get_header'); ?>

      <?php get_template_part('partials/site-header'); ?>

      <div class="wrap">
        <?php // tabindex="-1" makes <main> a valid target for the skip link above. ?>
        <main class="main" id="main" tabindex="-1">

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
