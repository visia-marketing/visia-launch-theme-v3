<?php

$sections = get_sub_field('sections'); // used for the nav loop

?>

<div class="uk-grid uk-grid-large sticky-sidebar" uk-grid id="sticky-sidebar-boundary">

    <div class="uk-width-1-1 uk-width-3-4@m uk-flex-last@m" id="sticky-content">

        <?php $i = 0; if( have_rows('sections') ): while( have_rows('sections') ): the_row(); ?>

            <?php $settings = get_sub_field('settings'); ?>

            <?php if( !$settings['custom_link'] ): ?>

                <?php $section_id = 'section_' . $i; ?>

                <div class="sticky-section uk-margin-large-bottom" id="<?php echo esc_attr($section_id); ?>">

                    <?php if( have_rows('flexible_section') ): while( have_rows('flexible_section') ): the_row(); ?>

                        <?php get_template_part( 'flexible/' . get_row_layout() ); ?>

                    <?php endwhile; endif; ?>

                </div>

            <?php endif; ?>

        <?php $i++; endwhile; endif; ?>

    </div>


    <div class="uk-width-1-1 uk-width-1-4@m uk-flex-first@m">

        <div uk-sticky="offset: 100; end: #sticky-end; media: @m">
            <ul class="uk-nav uk-nav-default" uk-scrollspy-nav="closest: li; scroll: true">

                <?php foreach( $sections as $i => $section ): ?>

                    <?php
                    if( $section['settings']['custom_link'] ){
                        $section_id = $section['settings']['custom_link_section'];
                    } else {
                        $section_id = 'section_' . $i;
                    }
                    ?>

                    <?php if( $section['settings']['divider'] ): ?>
                        <li class="uk-margin-top">
                            <hr class="uk-margin-bottom" />
                        </li>
                    <?php endif; ?>

                    <li class="uk-margin-small-bottom">
                        <a href="#<?php echo esc_attr($section_id); ?>" uk-scroll="offset: 100" class="uk-flex uk-flex-inline uk-flex-center uk-margin-small-bottom">
                            <?php echo wp_get_attachment_image( $section['settings']['section_icon'], 'full', true, array( 'class' => 'uk-preserve', 'uk-svg' => '' ) ); ?>
                            <?php echo esc_html( $section['settings']['section_title'] ); ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
        </div>

    </div>

</div>

<div id="sticky-end"></div>
