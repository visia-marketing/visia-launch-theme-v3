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
                            <a href="<?php echo esc_url($card_url); ?>">
                                <?php echo $card_image; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="product-card-body uk-card-body">
                        <h3 class="product-card-title">
                            <a href="<?php echo esc_url($card_url); ?>">
                                <?php echo esc_html($card_title); ?>
                            </a>
                        </h3>
                        <?php if ($card_excerpt) : ?>
                            <p class="product-card-excerpt">
                                <?php echo wp_kses_post($card_excerpt); ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($card_url); ?>" class="uk-button uk-button-primary uk-margin-top">
                            <?php esc_html_e('Learn More', 'visia_marketing'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
