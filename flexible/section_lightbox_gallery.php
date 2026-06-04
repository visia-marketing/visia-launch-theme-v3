<?php

$lightbox_gallery = get_sub_field('lightbox_gallery');

?>

<div class="flexible-lightbox-gallery">

    <?php if( $lightbox_gallery ): ?>
        <div class="lightbox-gallery uk-grid">
            <?php foreach( $lightbox_gallery as $image ): ?>
                <div class="uk-width-1-1 uk-width-1-2@m uk-width-1-3@l uk-margin-medium-bottom">
                    <a href="<?php echo esc_url($image['sizes']['large']); ?>" data-caption="<?php echo esc_attr($image['caption']); ?>" class="lightbox-anchor">

                        <span class="expand">
                            <i class="fa-solid fa-expand"></i><span>Expand</span>
                        </span>
                        <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>"/>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>