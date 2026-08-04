<div class="page-breadcrumbs">
  <div class="uk-container">
    <div class="uk-width-1-1 ">
      <?php // Without a label this is an anonymous navigation landmark competing with the
            // header, footer and mobile menus. WCAG 2.4.1. ?>
      <nav aria-label="<?php esc_attr_e('Breadcrumb', 'visia_marketing'); ?>">
        <?php if(function_exists('bcn_display')) { bcn_display(); } ?>
      </nav>
    </div>
  </div>  
</div>