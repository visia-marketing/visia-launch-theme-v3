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
$button_text   = get_sub_field('button_text') ?: 'Open Popup';
$popup_content = get_sub_field('popup_content');

// Unique internal id for the modal — used by this section's own button.
$modal_uid = 'button-popup-' . uniqid();

// Editor-supplied anchor (e.g. "reach-out-to-shur-tite"). Kept separate from
// the DOM id on purpose so it never clashes with a section/element id.
$modal_anchor = sanitize_title((string) get_sub_field('modal_id'));
?>
<div class="fc-section-button-with-popup button-with-popup">

    <?php if ($button_style !== 'hidden') : ?>
        <button class="uk-button uk-button-<?php echo esc_attr($button_style); ?>" type="button" uk-toggle="target: #<?php echo esc_attr($modal_uid); ?>">
            <?php echo esc_html($button_text); ?>
        </button>
    <?php endif; ?>

    <div id="<?php echo esc_attr($modal_uid); ?>"<?php echo $modal_anchor ? ' data-modal-anchor="' . esc_attr($modal_anchor) . '"' : ''; ?> uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <button class="uk-modal-close-default" type="button" uk-close></button>
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
