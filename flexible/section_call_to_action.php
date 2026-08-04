<?php $source = get_sub_field('cta_content_source');?>
<?php // rand() can collide across rows, and duplicate ids break both the <style> block
      // below and any ARIA that references them. ?>
<?php $cta_id = visia_unique_id('call-to-action'); ?>
<?php
// Content position for this row. The button is placed on the opposite side
// (or below, when centered). Applies to both the Default and Custom sources,
// since it's a layout choice for the section rather than part of the content.
$alignment = get_sub_field('cta_content_alignment');
$alignment = in_array($alignment, ['left', 'center', 'right'], true) ? $alignment : 'left';

// Reset per row: these are only assigned in the `background == image` branch
// below, but are read unconditionally further down — without this they'd raise
// undefined-variable notices and leak from a previous CTA row on the same page.
$image = null;
$content_color = '';

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
        $content_color = get_sub_field('cta_text_color') ?? '';
        $bg_image = get_sub_field('cta_background_image');
        $image = wp_get_attachment_image_src( $bg_image, 'large' );
    }

    $content = get_sub_field('cta_content');
    $button = get_sub_field('cta_link');

}
?>

<style>
    <?php echo '#' . esc_attr($cta_id); ?>.call-to-action-image {
        <?php if( $image ): ?>
        background-image: url('<?php echo esc_url($image[0]);?>');
        background-size: cover;
        background-position: center;
        <?php endif; ?>
    }
</style>

<div class="fc-section-cta fc-section-columns call-to-action call-to-action--<?php echo esc_attr($source);?> call-to-action-<?php echo esc_attr($background); ?> background--<?php echo esc_attr($background);?> background--<?php echo esc_attr($content_color);?>" id="<?php echo esc_attr($cta_id); ?>">

    <div class="call-to-action--inner <?php echo esc_attr( get_sub_field('container_width') ?: 'uk-container' ); ?>">

        <div class="call-to-action--layout cta-align--<?php echo esc_attr( $alignment ); ?>">

            <div class="call-to-action--content">
                <?php
                // NOT wp_kses_post(): this is a WYSIWYG field, and ACF has already run it
                // through the_content filters — shortcodes expanded, embeds resolved. Passing
                // it through wp_kses_post() strips <form>, <input>, <label>, <select>,
                // <button>, <script> and <iframe>, which destroys embedded Gravity Forms and
                // video embeds. Same trust boundary as the_content(), which core echoes raw.
                echo $content;
                ?>
            </div>

            <?php if( is_array($button) && ! empty($button['url']) ): ?>
                <div class="call-to-action--action">
                    <?php // target="_blank" previously carried no rel and no warning: the
                          // window change was unannounced and the new tab kept a handle on
                          // window.opener. WCAG 3.2.5. ?>
                    <a href="<?php echo esc_url( $button['url'] ); ?>" class="uk-button uk-button-green"<?php echo visia_new_window_attrs( $button['target'] ?? '' ); ?>>
                        <?php echo esc_html( $button['title'] ); ?><?php echo visia_new_window_notice( $button['target'] ?? '' ); ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>

    </div>

</div>