<?php

$column_1 = get_sub_field('number_group_1');
$column_2 = get_sub_field('number_group_2');
$column_3 = get_sub_field('number_group_3');
$column_4 = get_sub_field('number_group_4');

$number_columns = array( $column_1, $column_2, $column_3, $column_4 );

if( $column_2['number'] == "" ) {
    unset( $number_columns[1] );
}
if( $column_3['number'] == "" ) {
    unset( $number_columns[2] );
}
if( $column_4['number'] == "" ) {
    unset( $number_columns[3] );
}

?>


<div class="fc-section-columns animated-numbers">

    <div class="uk-container uk-container-large uk-flex uk-flex-center uk-flex-wrap uk-child-width-1-2@m uk-child-width-1-3@l uk-child-width-1-4@xl">

        <?php $delay = 0; ?>
        <?php foreach( $number_columns as $number_group ): ?>
            <?php $delay += 250; ?>

            <div class="number-container">
                <div class="number-ring" data-aos="zoom-out" data-aos-delay="<?php echo $delay; ?>"></div>
                <?php if( $number_group['number'] != "" ): ?>

                    <div class="countup-animated-number">
                        <?php $data_start = ( $number_group['number'] == 0 ) ? 1234 : 0; ?>

                        <?php if( $number_group['prepend']): ?><span class="prepend"><?php echo $number_group['prepend']; ?></span><?php endif; ?><span class="number-span" data-delay="<?php echo $delay; ?>" data-start="<?php echo esc_attr( $data_start ); ?>" data-target="<?php echo esc_attr( $number_group['number'] ); ?>">0</span><?php if( $number_group['append']): ?><span class="append"><?php echo $number_group['append']; ?></span><?php endif; ?>

                    </div>
                <?php endif; ?>

                <?php if( !empty( $number_group['text'] ) ): ?>
                    <div class="number-label"><?php echo esc_html( $number_group['text'] ); ?></div>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</div> 