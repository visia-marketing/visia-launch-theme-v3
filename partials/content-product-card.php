<?php
if( array_key_exists( 'prod_id', $args ) ){
    $id = $args['prod_id'];
}
?>

<div class="uk-card cards-style--product">

    <div class="card-body">
        <h3 class="card-title"><a href="<?php echo esc_url(get_the_permalink($id)); ?>"><?php echo esc_html(get_the_title($id)); ?></a></h3>
        <p class="uk-margin-xsmall-bottom"><?php
            $excerpt = get_post_field('post_excerpt', $id);
            if ( ! $excerpt ) {
                $excerpt = wp_trim_words(get_post_field('post_content', $id), 35);
            }
            echo wp_kses_post($excerpt);
        ?></p>

            <div class="uk-card-media uk-margin-auto-top">
                <?php
                // Third link to the same product on one card. Its only content is the
                // thumbnail, so with no featured image it was an entirely empty link, and
                // with one it just repeated the title link. Hidden from AT and taken out of
                // the tab order — the title and the CTA below remain reachable.
                ?>
                <a href="<?php echo esc_url(get_the_permalink($id)); ?>" class="uk-flex uk-width-1-1" tabindex="-1" aria-hidden="true">
                    <?php echo get_the_post_thumbnail($id, 'woocommerce_thumbnail', array('class' => 'uk-width-1-1', 'alt' => '')) ?: '<div class="placeholder"></div>'; ?>
                </a>
            </div>

        <div class="uk-flex uk-width-1-1 uk-flex-between uk-flex-middle uk-margin-top">
            <?php
            $price = get_post_meta($id, '_price', true);
            if ( $price ) {
                echo '<p class="price">' . wc_price($price) . '</p>';
            }
            ?>
            <?php // "View Product" repeats verbatim on every card in the grid, so the links
                  // are indistinguishable when listed out of context. The product name is
                  // appended as visually-hidden text. WCAG 2.4.4. ?>
            <a class="uk-button uk-button-outline uk-margin-remove-top uk-margin-remove-right" href="<?php echo esc_url(get_the_permalink($id)); ?>"><?php echo visia_cta_label(__('View Product', 'visia_marketing'), get_the_title($id)); ?></a>
        </div>
    </div>

</div>
