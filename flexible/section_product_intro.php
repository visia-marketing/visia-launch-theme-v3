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
                    <div class="product-intro-thumbnails">
                        <?php foreach ( $images as $i => $thumb ) : ?>
                            <div class="product-intro-thumb<?php echo $i === 0 ? ' active' : ''; ?>"
                                 data-full="<?php echo esc_url( $thumb['sizes']['large'] ); ?>"
                                 data-alt="<?php echo esc_attr( $thumb['alt'] ); ?>">
                                <img
                                    src="<?php echo esc_url( $thumb['sizes']['medium'] ); ?>"
                                    alt="<?php echo esc_attr( $thumb['alt'] ); ?>"
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
