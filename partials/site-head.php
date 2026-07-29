<head>
	
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $font = get_field( 'google_typekit_font_url', 'options'); ?>
  <?php if( str_contains( $font, 'google' ) || empty($font) ): ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php endif; ?>



  <?php wp_head(); ?> 

  <?php if ( get_field('google_tag_manager_id', 'options') ):?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo get_field('google_tag_manager_id', 'options');?>');</script>
    <!-- End Google Tag Manager -->
  <?php endif; ?>

</head>
