<?php
/**
 * Button with Popup (Lightbox Popup)
 *
 * A "Section" content layout: renders an optional button that opens a UIkit
 * modal containing WYSIWYG content. Loaded by section_flexible_section.php,
 * which provides the surrounding markup.
 *
 * The modal element gets a guaranteed-unique DOM id so it can never collide
 * with a section id (or another popup) on the page. The editor-supplied
 * anchor is stored separately as data-modal-anchor — any button or link whose
 * href points at #{anchor} opens the modal, resolved by initModalLinks() in
 * assets/src/scripts/main.js.
 */

$button_style  = get_sub_field('button_style') ?: 'primary';
$button_text   = get_sub_field('button_text') ?: __('Open Popup', 'visia_marketing');
$popup_content = get_sub_field('popup_content');

// Unique internal id for the modal — used by this section's own button.
$modal_uid = 'button-popup-' . uniqid();

// Editor-supplied anchor (e.g. "reach-out-to-shur-tite"). Kept separate from
// the DOM id on purpose so it never clashes with a section/element id.
$modal_anchor = sanitize_title((string) get_sub_field('modal_id'));

// The dialog needs a name. The button's own text is the only reliable description of what
// the popup contains, so it doubles as the dialog title — rendered visually hidden so the
// design is unchanged.
$modal_title_id = $modal_uid . '-title';
$modal_title    = $button_text !== '' ? $button_text : __('Dialog', 'visia_marketing');
?>
<div class="fc-section-button-with-popup button-with-popup">

    <?php if ($button_style !== 'hidden') : ?>
        <button class="uk-button uk-button-<?php echo esc_attr($button_style); ?>" type="button" uk-toggle="target: #<?php echo esc_attr($modal_uid); ?>" aria-haspopup="dialog">
            <?php echo esc_html($button_text); ?>
        </button>
    <?php endif; ?>

    <?php // role/aria-modal/aria-labelledby are declared here rather than left to UIkit:
          // without them the modal opens as an unnamed group and screen-reader users get no
          // indication that the rest of the page is inert. WCAG 4.1.2. ?>
    <div id="<?php echo esc_attr($modal_uid); ?>"<?php echo $modal_anchor ? ' data-modal-anchor="' . esc_attr($modal_anchor) . '"' : ''; ?> uk-modal role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modal_title_id); ?>">
        <div class="uk-modal-dialog uk-modal-body">
            <h2 id="<?php echo esc_attr($modal_title_id); ?>" class="screen-reader-text"><?php echo esc_html($modal_title); ?></h2>
            <?php // uk-close renders an SVG x with no accessible name of its own. ?>
            <button class="uk-modal-close-default" type="button" uk-close aria-label="<?php esc_attr_e('Close', 'visia_marketing'); ?>"></button>
            <div class="popup-content">
                <?php
                // Output the WYSIWYG content as-is. ACF has already run it
                // through the content filters (shortcodes, embeds, wpautop),
                // so it may legitimately contain <script> tags from embedded
                // forms — don't re-sanitize with wp_kses_post(), which would
                // strip the <script> wrapper and dump raw JS onto the page.
                echo $popup_content;
                ?>
            </div>
        </div>
    </div>

</div>
