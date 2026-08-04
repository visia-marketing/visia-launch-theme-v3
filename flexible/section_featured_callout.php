<?php

$source = get_sub_field('callout_source') ?: 'custom';

if ( $source === 'global' ) {
    $index    = get_sub_field('global_callout_index');
    $callouts = get_field('global_featured_callouts', 'options');
    $callout  = isset( $callouts[ $index ] ) ? $callouts[ $index ] : null;

    if ( ! $callout ) return;

    // Null-coalescing throughout: an unconfigured design sub-group returns null and these
    // were read unconditionally.
    $design           = $callout['callout_design'] ?? array();
    $background_color = $design['background'] ?? '';
    $image            = $design['callout_image'] ?? 0;
    $content          = $callout['callout_content'] ?? '';
} else {
    $design           = get_sub_field('callout_design') ?: array();
    $background_color = $design['background'] ?? '';
    $image            = $design['callout_image'] ?? 0;
    $content          = get_sub_field('callout_content');
}

?>


<div class="featured-callout">

    <div class="callout-background callout-background--<?php echo esc_attr($background_color); ?>">

        <div class="uk-padding">

            <div class="uk-flex">

                <div class="uk-width-1-1 uk-width-1-2@m">
                    <?php if( $content ): ?>
                        <?php echo wp_kses_post($content); ?>
                    <?php endif; ?>
                </div>


                <div class="uk-width-1-1 uk-width-1-2@m">
                    <?php if( $image ): ?>
                        <?php
                        // Decorative: this is the callout's illustration and the copy beside
                        // it carries the message. Left to wp_get_attachment_image() it
                        // announced whatever alt happened to be on the media item.
                        echo visia_decorative_image( $image, 'full' );
                        ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>


    </div>

</div>
