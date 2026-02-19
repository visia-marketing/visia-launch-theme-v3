<?php $source = get_sub_field('cta_content_source');?>
<?php $cta_id = 'call-to-action-' . rand(); ?>
<?php
if ( $source === 'default' ){

    $background = get_field('cta_background', 'option');

    if( $background == 'image'){
        $content_color = get_field('cta_text_color', 'option') ?? '';
        $bg_image = get_field('cta_background_image', 'option');
        $image = wp_get_attachment_image_src( $bg_image, 'large' );
  
    }

    $content = get_field('cta_content', 'option');
    $button = get_field('cta_link', 'option');

}else{

    $background = get_sub_field('cta_background');
   
    if( $background == 'image'){
        $content_color = get_field('cta_text_color') ?? '';
        $bg_image = get_sub_field('cta_background_image');
        $image = wp_get_attachment_image_src( $bg_image, 'large' );
    }

    $content = get_sub_field('cta_content');
    $button = get_sub_field('cta_link');

}
?>

<style>
    <?php echo '#'.$cta_id; ?>.call-to-action-image {
        <?php if( $image ): ?>
        background-image: url('<?php echo $image[0];?>');
        background-size: cover;
        background-position: center;
        <?php endif; ?>
    }
</style>

<div class="fc-section-cta fc-section-columns call-to-action call-to-action--<?php echo $source;?> call-to-action-<?php echo $background; ?> background--<?php echo $background;?> background--<?php echo $content_color;?>" id="<?php echo $cta_id; ?>">

    <div class="call-to-action--inner uk-container">

        <div class="column small-12 large-10 large-offset-1">
            <?php echo $content; ?>


            <?php if( is_array($button) ): ?>
                <?php if( array_key_exists('url', $button) ): ?>
                    <a href="<?php echo esc_url( $button['url'] ); ?>" class="button" <?php if( $button['target'] ): ?> target="<?php echo esc_attr( $button['target'] ); ?>" <?php endif; ?>>
                        <?php echo esc_html( $button['title'] ); ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

        </div>

        

        
    </div>

</div>