<?php
/**
 * Accessibility helpers
 *
 * Small, single-purpose renderers shared by the templates in /partials and /flexible.
 * Before these existed every layout inlined its own icon/image/button markup, which is why
 * decorative images leaked media-library alt text and icon-only controls had no name.
 *
 * Text domain matches the rest of the theme: visia_marketing.
 */

if ( ! function_exists( 'visia_sr_text' ) ) {
  /**
   * Visually-hidden text.
   *
   * Pairs with .screen-reader-text in assets/src/styles/common/_helpers.scss. Use to give an
   * icon-only control a name, or to add context to a repeated CTA ("Learn More" x12).
   *
   * @param string $text  Text to expose to assistive technology only.
   * @return string
   */
  function visia_sr_text( $text ) {
    if ( '' === trim( (string) $text ) ) {
      return '';
    }

    return '<span class="screen-reader-text">' . esc_html( $text ) . '</span>';
  }
}

if ( ! function_exists( 'visia_heading_tag' ) ) {
  /**
   * Resolve a heading level to a safe tag name.
   *
   * Templates take a level from ACF (or hard-code a sensible default) and pass it through
   * here so an editor can never produce an <h1> inside a section or an arbitrary tag.
   *
   * @param string|int $level    Requested level: 'h3', '3', or 3.
   * @param string     $default  Fallback when $level is empty or out of range.
   * @return string  One of h2..h6.
   */
  function visia_heading_tag( $level, $default = 'h2' ) {
    $allowed = array( 'h2', 'h3', 'h4', 'h5', 'h6' );

    $tag = strtolower( trim( (string) $level ) );

    // Accept a bare number as well as a tag name.
    if ( '' !== $tag && ctype_digit( $tag ) ) {
      $tag = 'h' . $tag;
    }

    if ( in_array( $tag, $allowed, true ) ) {
      return $tag;
    }

    return in_array( $default, $allowed, true ) ? $default : 'h2';
  }
}

if ( ! function_exists( 'visia_icon' ) ) {
  /**
   * Decorative icon, hidden from assistive technology.
   *
   * Font Awesome <i> elements announce as stray characters (or nothing) when left exposed.
   * Pass $label when the icon is the *only* content of a control and needs to supply its name.
   *
   * @param string $classes  Icon classes, e.g. 'fa-light fa-bag-shopping'.
   * @param string $label    Optional visually-hidden label rendered alongside the icon.
   * @return string
   */
  function visia_icon( $classes, $label = '' ) {
    if ( '' === trim( (string) $classes ) ) {
      return '';
    }

    return '<i class="' . esc_attr( $classes ) . '" aria-hidden="true"></i>' . visia_sr_text( $label );
  }
}

if ( ! function_exists( 'visia_decorative_image' ) ) {
  /**
   * Render an attachment as a decorative image.
   *
   * wp_get_attachment_image() emits whatever alt text the media library holds, which turns
   * background art and card icons into announced content. Forcing alt="" marks them as
   * presentational instead.
   *
   * @param int|array $image  Attachment ID, or an ACF image array.
   * @param string    $size   Registered image size.
   * @param array     $attr   Extra attributes merged over the defaults.
   * @return string
   */
  function visia_decorative_image( $image, $size = 'full', $attr = array() ) {
    // ACF returns an array when the field's return format is "Image Array".
    if ( is_array( $image ) ) {
      $image = isset( $image['ID'] ) ? $image['ID'] : ( isset( $image['id'] ) ? $image['id'] : 0 );
    }

    $image = (int) $image;

    if ( ! $image ) {
      return '';
    }

    $attr = wp_parse_args( $attr, array( 'alt' => '', 'role' => 'presentation' ) );

    return wp_get_attachment_image( $image, $size, false, $attr );
  }
}

if ( ! function_exists( 'visia_new_window_attrs' ) ) {
  /**
   * Attributes for a link that opens in a new window.
   *
   * Returns an empty string unless the link actually targets a new window, so call sites can
   * echo it unconditionally. Pair with visia_new_window_notice() for the AT-facing warning.
   *
   * @param string $target  Link target, typically ACF's $link['target'].
   * @return string
   */
  function visia_new_window_attrs( $target ) {
    if ( '_blank' !== trim( (string) $target ) ) {
      return '';
    }

    return ' target="_blank" rel="noopener noreferrer"';
  }
}

if ( ! function_exists( 'visia_new_window_notice' ) ) {
  /**
   * Visually-hidden "opens in a new window" warning. WCAG 3.2.5.
   *
   * Goes inside the link, after its visible text.
   *
   * @param string $target  Link target, typically ACF's $link['target'].
   * @return string
   */
  function visia_new_window_notice( $target ) {
    if ( '_blank' !== trim( (string) $target ) ) {
      return '';
    }

    return visia_sr_text( __( '(opens in a new window)', 'visia_marketing' ) );
  }
}

if ( ! function_exists( 'visia_cta_label' ) ) {
  /**
   * Visible CTA text plus hidden context.
   *
   * Card grids repeat the same label on every card ("Learn More", "View Product"), which
   * fails WCAG 2.4.4 because the links are indistinguishable out of context. Appending the
   * item title as visually-hidden text makes each one unique without changing the design.
   *
   * @param string $label    Visible link text.
   * @param string $context  Item name, e.g. the product or post title.
   * @return string
   */
  function visia_cta_label( $label, $context = '' ) {
    $label = (string) $label;

    if ( '' === trim( $context ) ) {
      return esc_html( $label );
    }

    /* translators: %s: name of the item the link points at. */
    $hidden = sprintf( __( 'about %s', 'visia_marketing' ), wp_strip_all_tags( $context ) );

    return esc_html( $label ) . ' ' . visia_sr_text( $hidden );
  }
}

if ( ! function_exists( 'visia_unique_id' ) ) {
  /**
   * Sequential DOM id, unique per request.
   *
   * Several templates used rand()/uniqid() for ids that ARIA then has to reference. A simple
   * counter is collision-free and keeps markup diffable between builds.
   *
   * @param string $prefix
   * @return string
   */
  function visia_unique_id( $prefix = 'visia' ) {
    static $counts = array();

    $prefix = sanitize_html_class( $prefix );

    if ( ! isset( $counts[ $prefix ] ) ) {
      $counts[ $prefix ] = 0;
    }

    $counts[ $prefix ]++;

    return $prefix . '-' . $counts[ $prefix ];
  }
}
