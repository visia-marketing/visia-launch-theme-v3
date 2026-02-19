<?php if (has_nav_menu('top_navigation')) : ?>
<div class="top-header">
	<div class="uk-container">
		<div class="small-12 columns">
      <div class="top-header-flex">
        <div class="top-header-search show-for-medium"><?php get_template_part('searchform'); ?></div>
        <?php
          wp_nav_menu(['theme_location' => 'top_navigation', 'depth' => 1, 'menu_class' => 'top-header-navigation top-header-navigation-right']); 
        ?>
      </div>
		</div>
	</div>
</div>
<?php endif; ?>

<header class="main-header">
	<div class="uk-container uk-flex ">
    <div class="uk-width-1-6@m">
      <div class="main-logo">
        <a href="<?= esc_url(home_url('/')); ?>"><img src="<?php the_field('main_logo', 'option');?>" alt="<?php bloginfo('name'); ?>"></a>
      </div>
    </div>
    <div class="uk-width-expand uk-flex uk-flex-right uk-flex-middle hide-for-medium">
      <button class="menu-icon" type="button" uk-toggle="target: #uk-off-canvas"></button>
		</div>
    <div class="uk-width-expand@m show-for-medium">
      <div class="primary-navigation-wrapper">
        <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'depth' => 2, 'menu_class' => 'vertical medium-horizontal menu primary-navigation', 'items_wrap' => '<ul class="%2$s" id="primary-navigation" data-responsive-menu="drilldown medium-dropdown">%3$s</ul>' ]); 
          endif;
        ?>
      </div>
    </div>
  </div>
</header>