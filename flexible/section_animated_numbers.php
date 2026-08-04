<?php

$column_1 = get_sub_field('number_group_1');
$column_2 = get_sub_field('number_group_2');
$column_3 = get_sub_field('number_group_3');
$column_4 = get_sub_field('number_group_4');

$number_columns = array( $column_1, $column_2, $column_3, $column_4 );

// isset() guards: an unfilled group returns null, and $column_N['number'] on null raised a
// warning on every render.
foreach ( array( 1 => $column_2, 2 => $column_3, 3 => $column_4 ) as $index => $column ) {
    if ( ! is_array( $column ) || ( $column['number'] ?? '' ) === '' ) {
        unset( $number_columns[ $index ] );
    }
}

?>


<div class="fc-section-columns animated-numbers">

    <div class="uk-flex uk-flex-center uk-flex-wrap uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl">

        <?php $delay = 0; ?>
        <?php foreach( $number_columns as $number_group ): ?>
            <?php $delay += 250; ?>

            <?php
            $stat_id    = visia_unique_id( 'stat' );
            $label_id   = $stat_id . '-label';
            $prepend    = $number_group['prepend'] ?? '';
            $append     = $number_group['append'] ?? '';
            $label_text = $number_group['text'] ?? '';

            // The visible figure counts up from 0, so the DOM literally contains "0" until
            // the animation finishes — a screen reader reading the page (or a user with the
            // animation disabled) got "0". The finished value is exposed here instead, and
            // the animating span is hidden from assistive technology entirely.
            $spoken_value = trim( $prepend . ' ' . $number_group['number'] . ' ' . $append );
            ?>
            <div class="number-container">
                <div class="number-ring" aria-hidden="true" data-aos="zoom-out" data-aos-delay="<?php echo esc_attr( $delay ); ?>"></div>
                <?php if( ( $number_group['number'] ?? '' ) != "" ): ?>

                    <?php // role="img" + aria-label lets the whole stat announce once, as its
                          // final value, rather than as a stream of changing digits. ?>
                    <div class="countup-animated-number" role="img" aria-label="<?php echo esc_attr( trim( $spoken_value . ' ' . wp_strip_all_tags( $label_text ) ) ); ?>">
                        <?php $data_start = ( $number_group['number'] == 0 ) ? 1234 : 0; ?>

                        <span aria-hidden="true"><?php if( $prepend ): ?><span class="prepend"><?php echo esc_html( $prepend ); ?></span><?php endif; ?><span class="number-span" data-delay="<?php echo esc_attr( $delay ); ?>" data-start="<?php echo esc_attr( $data_start ); ?>" data-target="<?php echo esc_attr( $number_group['number'] ); ?>">0</span><?php if( $append ): ?><span class="append"><?php echo esc_html( $append ); ?></span><?php endif; ?></span>

                    </div>
                <?php endif; ?>

                <?php if( !empty( $label_text ) ): ?>
                    <?php // The label is already folded into the stat's aria-label above, so
                          // exposing it a second time would announce it twice. ?>
                    <div class="number-label" id="<?php echo esc_attr( $label_id ); ?>" aria-hidden="true"><?php echo esc_html( $label_text ); ?></div>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</div> 