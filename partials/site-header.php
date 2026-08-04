<?php if (has_nav_menu('top_navigation')) : ?>
<div class="top-header">
	<div class="uk-container uk-container-large">
		<div class="uk-width-1-1">
      <div class="uk-flex uk-flex-between uk-flex-middle">

        <?php get_search_form(); ?>

        <?php $top_header_text = get_field('top_header_text', 'options'); if ($top_header_text) : ?>
          <span class="top-header-text"><?php echo esc_html($top_header_text); ?></span>
        <?php endif; ?>

        <?php
          // <nav> + label added here rather than via wp_nav_menu(): roots_nav_menu_args()
          // in lib/nav.php forces container => false and wins the array_merge, so a
          // per-call container argument is discarded.
        ?>
        <?php // uk-width-expand moves from the <ul> to the new <nav> so the flex row keeps
              // the same proportions it had before the landmark was introduced. ?>
        <nav class="uk-width-expand" aria-label="<?php esc_attr_e('Utility navigation', 'visia_marketing'); ?>">
          <?php
            wp_nav_menu(['theme_location' => 'top_navigation', 'depth' => 1, 'menu_class' => 'top-header-navigation top-header-navigation-right']);
          ?>
        </nav>


        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
          <?php
            $cart_has_items = WC()->cart && WC()->cart->get_cart_contents_count() > 0;
            $cart_count     = $cart_has_items ? WC()->cart->get_cart_contents_count() : 0;

            // Ids so the trigger links can point aria-controls at the panels they open.
            $mini_cart_id = 'mini-cart-dropdown';
            $account_id   = 'user-account-dropdown';
          ?>
          <?php // aria-expanded is kept in sync by the uk-drop handler in main.js. ?>
          <a class="cart-icon-link uk-button uk-button-text uk-flex uk-flex-center uk-flex-middle" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-haspopup="true" aria-expanded="false" aria-controls="<?php echo esc_attr($mini_cart_id); ?>">

            <?php echo visia_icon('fa-light fa-bag-shopping'); ?>
            <?php if ($cart_count == 1) : ?>
                <?php echo esc_html($cart_count); ?> item in cart
            <?php elseif( $cart_count > 1): ?>
                <?php echo esc_html($cart_count); ?> items in cart
            <?php else: ?>
                Shopping Cart
            <?php endif; ?>

            </a>
            <div id="<?php echo esc_attr($mini_cart_id); ?>" class="uk-card uk-card-body uk-card-default mini-cart-dropdown uk-width-medium <?php echo $cart_has_items ? ' cart-has-items' : ''; ?>" uk-drop="pos: bottom-right; animation: uk-animation-slide-top-small; animate-out: true; offset: 6px; " aria-label="<?php esc_attr_e('Shopping cart', 'visia_marketing'); ?>">
                <div class="uk-flex">
                  <div class="uk-width-expand">
                    <button class="uk-drop-close uk-position-top-right uk-padding-small" type="button" uk-close aria-label="<?php esc_attr_e('Close cart', 'visia_marketing'); ?>"></button>
                    <div class="widget_shopping_cart_content">
                      <span><strong>Your Shopping Cart</strong></span>
                      <hr/>
                      <?php woocommerce_mini_cart(); ?>
                    </div>
                  </div>
                </div>
            </div>


            <a class="account-icon-link uk-button uk-button-text uk-flex uk-flex-center uk-flex-middle" href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" aria-haspopup="true" aria-expanded="false" aria-controls="<?php echo esc_attr($account_id); ?>">
                <?php echo visia_icon('fa-light fa-user'); ?> My Account
            </a>
            <div id="<?php echo esc_attr($account_id); ?>" class="uk-card uk-card-body uk-card-default user-account-dropdown uk-width-medium" uk-drop="pos: bottom-right; animation: uk-animation-slide-top-small; animate-out: true; offset: 6px; " aria-label="<?php esc_attr_e('My account', 'visia_marketing'); ?>">

                <div class="uk-flex">

                  <div class="uk-width-expand">
                    <div class="widget_shopping_cart_content">
                      <button class="uk-drop-close uk-position-top-right uk-padding-small " type="button" uk-close aria-label="<?php esc_attr_e('Close account menu', 'visia_marketing'); ?>"></button>
                      <?php // login form
                      if (is_user_logged_in()) {

                        echo '<p>Welcome, ' . esc_html(wp_get_current_user()->display_name) . '!</p>';
                        woocommerce_account_navigation();

                      } else {
                        ob_start();
                        // Namespaced ids: wp_login_form() hard-codes user_login/user_pass,
                        // which collides with any other login form on the page and breaks
                        // the label/field association on both.
                        wp_login_form(array(
                          'id_username'    => 'header-user-login',
                          'id_password'    => 'header-user-pass',
                          'id_remember'    => 'header-rememberme',
                          'id_submit'      => 'header-wp-submit',
                          'label_username' => __('Username or Email Address', 'visia_marketing'),
                        ));
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
        <a href="<?= esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(get_field('main_logo', 'option')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
      </div>
    </div>
    <div class="uk-width-expand uk-flex uk-flex-right uk-flex-middle hide-for-medium">
      <?php // Icon-only hamburger: without a label it announces as just "button".
            // aria-expanded is synced by the offcanvas handler in main.js. ?>
      <button class="menu-icon" type="button" uk-toggle="target: #uk-off-canvas" aria-label="<?php esc_attr_e('Open menu', 'visia_marketing'); ?>" aria-expanded="false" aria-controls="uk-off-canvas"></button>
		</div>
    <div class="uk-width-expand@m show-for-medium">
      <?php // Element changed div -> nav so the primary menu is a labelled landmark; the
            // class is unchanged so partials/_site-header.scss still matches. ?>
      <nav class="primary-navigation-wrapper" aria-label="<?php esc_attr_e('Primary navigation', 'visia_marketing'); ?>">
        <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'depth' => 2, 'menu_class' => 'vertical medium-horizontal menu primary-navigation', 'items_wrap' => '<ul class="%2$s" id="primary-navigation" data-responsive-menu="drilldown medium-dropdown">%3$s</ul>' ]);
          endif;
        ?>
      </nav>
    </div>
  </div>
</header>
