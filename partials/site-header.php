<?php if (has_nav_menu('top_navigation')) : ?>
<div class="top-header">
	<div class="uk-container uk-container-large">
		<div class="uk-width-1-1">
      <div class="uk-flex uk-flex-between uk-flex-middle">
        <?php $top_header_text = get_field('top_header_text', 'options'); if ($top_header_text) : ?>
          <span class="top-header-text"><?php echo esc_html($top_header_text); ?></span>
        <?php endif; ?>

        <?php
          wp_nav_menu(['theme_location' => 'top_navigation', 'depth' => 1, 'menu_class' => 'top-header-navigation top-header-navigation-right uk-width-expand']);
        ?>


            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
            <?php
            $cart_has_items = WC()->cart && WC()->cart->get_cart_contents_count() > 0;
            $cart_count     = $cart_has_items ? WC()->cart->get_cart_contents_count() : 0;
            ?>
            <a class="cart-icon-link uk-button uk-button-text uk-flex uk-flex-center uk-flex-middle" href="<?php echo esc_url(wc_get_cart_url()); ?>">

                <i class="fa-light fa-bag-shopping"></i>
                <?php if ($cart_count == 1) : ?>
                    <?php echo esc_html($cart_count); ?> item in cart
                <?php elseif( $cart_count > 1): ?>
                   <?php echo esc_html($cart_count); ?> items in cart
                <?php else: ?>
                    Shopping Cart
                <?php endif; ?>



            </a>
            <div class="uk-card uk-card-body uk-card-default mini-cart-dropdown uk-width-medium <?php echo $cart_has_items ? ' cart-has-items' : ''; ?>" uk-drop="pos: bottom-right; animation: uk-animation-slide-top-small; animate-out: true; offset: 6px; ">
                <div class="uk-flex">
                  <div class="uk-width-expand">
                    <button class="uk-drop-close uk-position-top-right uk-padding-small" type="button" uk-close></button>
                    <div class="widget_shopping_cart_content">
                      <span><strong>Your Shopping Cart</strong></span>
                      <hr/>
                      <?php woocommerce_mini_cart(); ?>
                    </div>
                  </div>
                </div>
            </div>


            <a class="account-icon-link uk-button uk-button-text uk-flex uk-flex-center uk-flex-middle" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
                <i class="fa-light fa-user"></i> My Account
            </a>
            <div class="uk-card uk-card-body uk-card-default user-account-dropdown uk-width-medium" uk-drop="pos: bottom-right; animation: uk-animation-slide-top-small; animate-out: true; offset: 6px; ">

                <div class="uk-flex">

                  <div class="uk-width-expand">
                    <div class="widget_shopping_cart_content">
                      <button class="uk-drop-close uk-position-top-right uk-padding-small " type="button" uk-close></button>
                      <?php // login form
                      if (is_user_logged_in()) {

                        echo '<p>Welcome, ' . esc_html(wp_get_current_user()->display_name) . '!</p>';
                        woocommerce_account_navigation();

                      } else {
                        ob_start();
                        wp_login_form();
                        $form = ob_get_clean();
                        $form = str_replace('type="text" name="log"', 'type="text" name="log" class="uk-input"', $form);
                        $form = str_replace('type="password" name="pwd"', 'type="password" name="pwd" class="uk-input"', $form);
                        $form = str_replace('type="checkbox" name="rememberme"', 'type="checkbox" name="rememberme" class="uk-checkbox"', $form);
                        $form = str_replace('type="submit" name="wp-submit"', 'type="submit" name="wp-submit" class="uk-button uk-button-primary"', $form);
                        echo $form;

                      }
                       ?>
                    </div>
                  </div>


                </div>

            </div>
            <?php endif; ?>

      </div>
		</div>
	</div>
</div>
<?php endif; ?>

<header class="main-header">
	<div class="uk-container uk-container-large uk-flex ">
    <div class="uk-width-1-4 uk-width-1-6@m">
      <div class="main-logo uk-width-xsmall uk-margin-small-top uk-margin-small-bottom">
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