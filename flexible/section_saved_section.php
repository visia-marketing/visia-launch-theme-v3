<?php
/**
 * Saved Section
 *
 * Renders a `repeatable-section` post's own flexible_content rows inline, as if they had
 * been authored directly on the host page.
 *
 * Deliberately emits no wrapper of its own, and visia_render_flexible_row() skips the
 * wrapper it would normally add for this layout — the rows below carry their own
 * <section class="fc-section"> elements, and nesting those inside another section's
 * uk-container stops their full-bleed backgrounds reaching the viewport edges.
 *
 * The rendering loop lives in visia_render_flexible_rows() (lib/acf.php); this file used
 * to hold a second copy of it, which is how the two versions drifted apart.
 */

// post_object field, return_format = id.
$saved_section = get_sub_field('saved_section');

if (!$saved_section) {
  return;
}

$saved_section = (int) $saved_section;

/**
 * Namespaced fallback id: row indexes restart inside the saved post, so the default
 * 'fc-section-1' would collide with a host page row's 'fc-section-1' now that the two
 * render as siblings. Ids typed into the editor's `id` field are passed through untouched.
 */
visia_render_flexible_rows($saved_section, 'saved-' . $saved_section . '-section-');
