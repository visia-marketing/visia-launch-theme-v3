<?php

$selected  = get_sub_field('post_ids');
$per_row   = get_sub_field('per_row') ?: 3;
$post_type = get_sub_field('post_type') ?: 'post';

$col_class = $per_row == 4 ? 'uk-width-1-2@s uk-width-1-4@m' : ($per_row == 2 ? 'uk-width-1-2@s uk-width-1-2@m' : 'uk-width-1-2@s uk-width-1-3@m');

$args = array(
    'post_type'      => $post_type,
    'posts_per_page' => -1,
    'orderby'        => 'post__in',
    'post__in'       => (array) $selected,
);

$query = new WP_Query( $args );

$heading_tag = visia_heading_tag( get_sub_field('heading_level'), 'h3' );

?>

    <div class="uk-flex uk-grid fc-section-cards product-cards">
        <?php foreach( $query->posts as $post ): ?>
            <div class="uk-width-1-1 <?php echo esc_attr($col_class); ?>">
                <?php 
                    $card_title = get_the_title($post->ID);
                    $card_image = get_the_post_thumbnail($post->ID, 'large');
                    $card_excerpt = wp_trim_words(get_the_excerpt($post->ID), 15);
                    $card_url = get_permalink($post->ID);
                ?>
                <div class="product-card uk-card uk-card-default uk-overflow-hidden">
                    <div class="product-card-image">
                        <?php if ($card_image) : ?>
                            <?php // One of three links to the same post on this card. Its only
                                  // content is the thumbnail, whose alt is empty for most
                                  // uploads, so it read as an empty link. Hidden from AT and
                                  // removed from the tab order; the title link below is the
                                  // exposed one. ?>
                            <a href="<?php echo esc_url($card_url); ?>" tabindex="-1" aria-hidden="true">
                                <?php echo get_the_post_thumbnail($post->ID, 'large', array('alt' => '')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="product-card-body uk-card-body">
                        <?php // Was a hard-coded h3 with no guarantee an h2 preceded it. ?>
                        <<?php echo $heading_tag; ?> class="product-card-title">
                            <a href="<?php echo esc_url($card_url); ?>">
                                <?php echo esc_html($card_title); ?>
                            </a>
                        </<?php echo $heading_tag; ?>>
                        <?php if ($card_excerpt) : ?>
                            <p class="product-card-excerpt">
                                <?php echo wp_kses_post($card_excerpt); ?>
                            </p>
                        <?php endif; ?>
                        <?php // "Learn More" is identical on every card in the grid, so the
                              // links are indistinguishable when listed out of context. The
                              // post title is appended as visually-hidden text. WCAG 2.4.4. ?>
                        <a href="<?php echo esc_url($card_url); ?>" class="uk-button uk-button-primary uk-margin-top">
                            <?php echo visia_cta_label(__('Learn More', 'visia_marketing'), $card_title); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
