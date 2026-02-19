<?php /*
$footer_form = get_field('footer_form');
if ( $footer_form && (!empty($footer_form['heading']) || !empty($footer_form['title']) || !empty($footer_form['form_id'])) ): ?>
  <div class="site-footer-form">
    <div class="uk-container columns">
      <?php if ( $footer_form['heading'] ): echo '<h2 class="g-style-section-heading">' . $footer_form['heading'] . '</h2>'; endif; ?>
      <?php if ( $footer_form['title'] ): echo '<h3 class="g-style-section-title">' . $footer_form['title'] . '</h3>'; endif; ?>
      <?php echo do_shortcode('[gravityform id="' . $footer_form['form_id'] . '" title="false"]');?>
    </div>
  </div>
<?php endif; */ ?>

<footer class="main-footer">
  <div class="uk-container uk-flex">   
    <div class="uk-width-1-1@xs uk-width-1-5@m">
      <div class="footer-logo uk-margin-medium-right">
        <a href="<?= esc_url(home_url('/')); ?>"><img src="<?php the_field('footer_logo', 'option');?>" alt="<?php bloginfo('name'); ?>"></a>
      </div>
    </div>
    <div class="uk-width-1-1@xs uk-width-1-5@m ">   
      <?php
      if (has_nav_menu('footer_navigation_1')) :
      wp_nav_menu(['theme_location' => 'footer_navigation_1', 'depth' => 2, 'menu_class' => 'footer-menu uk-margin-medium-left uk-margin-medium-right' ]); 
      endif;
      ?>
    </div>
    <div class="uk-width-1-1@xs uk-width-1-5@m">   
      <?php
      if (has_nav_menu('footer_navigation_2')) :
      wp_nav_menu(['theme_location' => 'footer_navigation_2', 'depth' => 2, 'menu_class' => 'footer-menu uk-margin-medium-left uk-margin-medium-right' ]); 
      endif;
      ?>
    </div>
    <div class="uk-width-1-1@xs uk-width-1-5@m ">   
      <?php
      if (has_nav_menu('footer_navigation_3')) :
      wp_nav_menu(['theme_location' => 'footer_navigation_3', 'depth' => 2, 'menu_class' => 'footer-menu uk-margin-medium-left uk-margin-medium-right' ]); 
      endif;
      ?>
    </div>
    <div class="uk-width-1-1@xs uk-width-1-5@m">
      <div class="uk-margin-medium-left uk-margin-medium-right">
        <?php echo get_field('footer_contact', 'options');?>
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
              ?>
              <div class="badge">
                <?php if($url): echo '<a href="' . esc_url($url) . '">'; endif;?>
                  <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                <?php if($url): echo '</a>'; endif;?>
                <?php if($url): echo '<a href="' . esc_url($url) . '">'; endif;?>
                  <p><?php echo esc_html($text); ?></p>
                <?php if($url): echo '</a>'; endif;?>
              </div>
          <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="uk-container uk-margin-top">
    <div class="footer-copyright">
      <div class="copyright">
        <?php echo get_field('copyright', 'options');?>
      </div>
      <?php
        if (has_nav_menu('footer_navigation_legal')) :
        wp_nav_menu(['theme_location' => 'footer_navigation_legal', 'depth' => 1, 'menu_class' => 'footer-menu-legal' ]); 
        endif;
      ?>
    </div>
  </div>
  <div class="uk-container">
    <div class="small-12 columns">
      <strong class="footer-tagline">
        <?php echo get_field('footer_tagline', 'options');?>
      </strong>
    </div>
  </div>
</footer>