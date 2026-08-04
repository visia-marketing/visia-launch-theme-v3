<footer class="main-footer">
  <div class="uk-container uk-flex uk-flex-column uk-flex-row@m">
    <div class="uk-width-1-1@xs uk-width-1-5@m">
      <div class="footer-logo uk-margin-medium-right">
        <a href="<?= esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(get_field('footer_logo', 'option')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
      </div>
    </div>
    <?php
    // Each footer menu becomes its own labelled landmark. Previously all four rendered as
    // bare <ul>s (lib/nav.php forces container => false), so a screen-reader user browsing
    // landmarks saw nothing to distinguish them. The wrapper element changed div -> nav;
    // the layout classes are unchanged.
    $visia_footer_menus = array(
      'footer_navigation_1' => __('Footer navigation', 'visia_marketing'),
      'footer_navigation_2' => __('Footer navigation, second column', 'visia_marketing'),
      'footer_navigation_3' => __('Footer navigation, third column', 'visia_marketing'),
    );
    foreach ($visia_footer_menus as $visia_location => $visia_label) :
      if (!has_nav_menu($visia_location)) {
        continue;
      }
      ?>
      <nav class="uk-width-1-1@xs uk-width-1-5@m" aria-label="<?php echo esc_attr($visia_label); ?>">
        <?php wp_nav_menu(['theme_location' => $visia_location, 'depth' => 2, 'menu_class' => 'footer-menu uk-margin-medium-left uk-margin-medium-right']); ?>
      </nav>
    <?php endforeach; ?>
    <div class="uk-width-1-1@xs uk-width-1-5@m">
      <div class="uk-margin-medium-left uk-margin-medium-right">
        <?php
        // NOT wp_kses_post(): editor-authored rich text from the options page. Filtering it
        // strips <form>, <input>, <script> and <iframe>, which breaks an embedded contact
        // form or map in the footer.
        echo get_field('footer_contact', 'options');
        ?>
      </div>
    </div>
  </div>
  <div class="uk-container uk-margin-top">
    <?php if( have_rows('footer_badges', 'options') ): ?>
      <div class="footer-badges">
          <?php while (have_rows('footer_badges', 'options')): the_row();
              $image = get_sub_field('badge_image', 'options');
              $text = get_sub_field('badge_text', 'options');
              $url = get_sub_field('badge_url', 'options');

              // The badge image and its caption used to be two consecutive links to the
              // same URL — two tab stops for one destination, and the image link had no
              // accessible name whenever the attachment alt was blank. The image link is
              // now hidden from AT and removed from the tab order, leaving the captioned
              // link as the single exposed control.
              $badge_label = $text !== '' ? $text : (is_array($image) && !empty($image['alt']) ? $image['alt'] : '');
              ?>
              <div class="badge">
                <?php if($url): ?><a href="<?php echo esc_url($url); ?>" tabindex="-1" aria-hidden="true"><?php endif;?>
                  <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php if($url): ?></a><?php endif;?>
                <?php if($url): ?><a href="<?php echo esc_url($url); ?>"><?php endif;?>
                  <p>
                    <?php echo esc_html($text); ?>
                    <?php if ($url && $text === '' && $badge_label !== '') { echo visia_sr_text($badge_label); } ?>
                  </p>
                <?php if($url): ?></a><?php endif;?>
              </div>
          <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="uk-container uk-margin-top">
    <div class="footer-copyright">
      <div class="copyright">
        <?php echo wp_kses_post(get_field('copyright', 'options'));?>
      </div>
      <?php if (has_nav_menu('footer_navigation_legal')) : ?>
        <nav aria-label="<?php esc_attr_e('Legal', 'visia_marketing'); ?>">
          <?php wp_nav_menu(['theme_location' => 'footer_navigation_legal', 'depth' => 1, 'menu_class' => 'footer-menu-legal']); ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>
  <div class="uk-container">
    <div class="small-12 columns">
      <?php // Was <strong>, which announces the tagline as important content. It is a
            // slogan, so the emphasis is presentational and belongs in CSS. ?>
      <span class="footer-tagline">
        <?php echo get_field('footer_tagline', 'options'); // See the note on footer_contact. ?>
      </span>
    </div>
  </div>
</footer>
