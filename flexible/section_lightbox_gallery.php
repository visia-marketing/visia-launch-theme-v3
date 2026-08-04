<?php

$lightbox_gallery = get_sub_field('lightbox_gallery');

?>

<div class="flexible-lightbox-gallery">

    <?php if( $lightbox_gallery ): ?>
        <div class="lightbox-gallery uk-grid">
            <?php foreach( $lightbox_gallery as $image ):
                // Every link in the gallery announced as "Expand" whenever the attachment
                // had no alt text — the visible label is the same on all of them, so the
                // image is the only thing that distinguishes one from the next. Fall back
                // through alt, then caption, then title for a usable name. WCAG 2.4.4.
                $image_name = '';
                foreach ( array( 'alt', 'caption', 'title' ) as $field ) {
                    if ( ! empty( $image[ $field ] ) ) {
                        $image_name = $image[ $field ];
                        break;
                    }
                }
                $link_label = $image_name !== ''
                    ? sprintf( __( 'Expand image: %s', 'visia_marketing' ), wp_strip_all_tags( $image_name ) )
                    : __( 'Expand image', 'visia_marketing' );
                ?>
                <div class="uk-width-1-1 uk-width-1-2@m uk-width-1-3@l uk-margin-medium-bottom">
                    <figure class="uk-margin-remove">
                        <a href="<?php echo esc_url($image['sizes']['large']); ?>" data-caption="<?php echo esc_attr($image['caption']); ?>" class="lightbox-anchor" aria-label="<?php echo esc_attr($link_label); ?>" aria-haspopup="dialog">

                            <span class="expand" aria-hidden="true">
                                <?php echo visia_icon('fa-solid fa-expand'); ?><span><?php esc_html_e('Expand', 'visia_marketing'); ?></span>
                            </span>
                            <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"/>
                        </a>
                        <?php // The caption existed only as a data- attribute, so it was
                              // never exposed outside the lightbox. ?>
                        <?php if ( ! empty( $image['caption'] ) ) : ?>
                            <figcaption class="screen-reader-text"><?php echo esc_html($image['caption']); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>