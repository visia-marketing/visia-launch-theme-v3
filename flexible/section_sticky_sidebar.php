<?php

$sections = get_sub_field('sections'); // used for the nav loop

?>

<div class="uk-grid uk-grid-large sticky-sidebar" uk-grid id="sticky-sidebar-boundary">

    <div class="uk-width-1-1 uk-width-3-4@m uk-flex-last@m" id="sticky-content">

        <?php $i = 0; if( have_rows('sections') ): while( have_rows('sections') ): the_row(); ?>

            <?php $settings = get_sub_field('settings'); ?>

            <?php if( !$settings['custom_link'] ): ?>

                <?php $section_id = 'section_' . $i; ?>

                <?php // tabindex="-1" so uk-scroll's jump actually moves focus here rather
                      // than only the viewport — otherwise a keyboard user activates a nav
                      // link and their focus stays behind in the sidebar. WCAG 2.4.3. ?>
                <div class="sticky-section uk-margin-large-bottom" id="<?php echo esc_attr($section_id); ?>" tabindex="-1">

                    <?php if( have_rows('flexible_section') ): while( have_rows('flexible_section') ): the_row(); ?>

                        <?php get_template_part( 'flexible/' . get_row_layout() ); ?>

                    <?php endwhile; endif; ?>

                </div>

            <?php endif; ?>

        <?php $i++; endwhile; endif; ?>

    </div>


    <div class="uk-width-1-1 uk-width-1-4@m uk-flex-first@m">

        <div uk-sticky="offset: 100; end: #sticky-end; media: @m">
            <?php // The in-page jump list was a bare <ul>, so it was not a navigation
                  // landmark and screen-reader users had no way to find it. ?>
            <nav aria-label="<?php esc_attr_e('On this page', 'visia_marketing'); ?>">
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
                        <?php // The divider is decorative. Left as a plain <li> it announced
                              // as an empty list item and, with the <hr>, as a separator. ?>
                        <li class="uk-margin-top" aria-hidden="true">
                            <hr class="uk-margin-bottom" />
                        </li>
                    <?php endif; ?>

                    <li class="uk-margin-small-bottom">
                        <a href="#<?php echo esc_attr($section_id); ?>" uk-scroll="offset: 100" class="uk-flex uk-flex-inline uk-flex-center uk-margin-small-bottom">
                            <?php
                            // Decorative: the icon sits immediately beside the section title
                            // below, so its media-library alt text was announced as a second,
                            // conflicting name for the same link.
                            echo visia_decorative_image( $section['settings']['section_icon'], 'full', array( 'class' => 'uk-preserve', 'uk-svg' => '' ) );
                            ?>
                            <?php echo esc_html( $section['settings']['section_title'] ); ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>
            </nav>
        </div>

    </div>

</div>

<div id="sticky-end"></div>
