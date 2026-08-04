<?php

$images  = get_sub_field('product_images');
$content = get_sub_field('product_content');

if ( ! $images && ! $content ) return;

$main_image  = $images ? $images[0] : null;
$thumbnails  = $images && count($images) > 1 ? array_slice($images, 1) : [];

?>

<div class="fc-section-product-intro">

    <div class="uk-grid uk-grid-large uk-flex-middle" uk-grid>

        <div class="uk-width-1-1 uk-width-1-2@m">
            <div class="product-intro-gallery">

                <?php if ( $main_image ) : ?>
                    <div class="product-intro-main-image">
                        <img
                            src="<?php echo esc_url( $main_image['sizes']['large'] ); ?>"
                            alt="<?php echo esc_attr( $main_image['alt'] ); ?>"
                            class="product-intro-featured"
                        >
                    </div>
                <?php endif; ?>

                <?php if ( $images && count($images) > 1 ) : ?>
                    <?php
                    // NOTE: the data-full / data-alt attributes below were added for an
                    // image-switcher that was never built — no script in the theme binds to
                    // .product-intro-thumb, and there is no CSS for it either. The strip is
                    // therefore inert, and marking it up as buttons would advertise controls
                    // that do nothing. It is exposed as a decorative preview instead.
                    //
                    // If the switcher is implemented later these must become
                    // <button type="button" aria-pressed> with a live region announcing the
                    // swap, and the alt text below must be restored.
                    ?>
                    <div class="product-intro-thumbnails" aria-hidden="true">
                        <?php foreach ( $images as $i => $thumb ) : ?>
                            <div class="product-intro-thumb<?php echo $i === 0 ? ' active' : ''; ?>"
                                 data-full="<?php echo esc_url( $thumb['sizes']['large'] ); ?>"
                                 data-alt="<?php echo esc_attr( $thumb['alt'] ); ?>">
                                <img
                                    src="<?php echo esc_url( $thumb['sizes']['medium'] ); ?>"
                                    alt=""
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="uk-width-1-1 uk-width-1-2@m">
            <?php if ( $content ) : ?>
                <div class="product-intro-content">
                    <?php echo wp_kses_post( $content ); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>
