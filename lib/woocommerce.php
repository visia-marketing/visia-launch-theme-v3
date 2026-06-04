<?php
/**
 * WooCommerce customisations
 *
 * Hook adjustments for the single product page layout.
 */

// Bail early if WooCommerce is not active
if ( ! class_exists( 'WooCommerce' ) ) {
    return;
}

// ── Summary hook reordering ───────────────────────────────────────────────────

// ── Breadcrumb container ──────────────────────────────────────────────────────
add_action( 'woocommerce_before_main_content', function () {
    echo '<div class="uk-width-expand"><div class="uk-container uk-container-large">';
}, 15 );
add_action( 'woocommerce_before_main_content', function () {
    echo '</div></div>';
}, 25 );

// Remove results count and sort order from archive loop
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove default meta block (SKU + categories + tags at priority 40)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Remove rating (not in design)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

// SKU just after title (5), before price (10)
add_action( 'woocommerce_single_product_summary', 'visia_single_product_sku', 7 );
function visia_single_product_sku() {
    global $product;
    if ( ! $product || ! wc_product_sku_enabled() || ! $product->get_sku() ) {
        return;
    }
    echo '<p class="g1-product-sku">' . esc_html( $product->get_sku() ) . '</p>';
}

// Divider after SKU, before price
add_action( 'woocommerce_single_product_summary', function () {
    echo '<hr class="g1-product-divider">';
}, 10 );

// Full description instead of short description/excerpt
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', function () {
    global $product;
    echo '<div class="woocommerce-product-details__short-description">';
    echo apply_filters( 'the_content', $product->get_description() );
    echo '</div>';
}, 20 );


// ── Thumbnail columns: 5 per row ─────────────────────────────────────────────
add_filter( 'woocommerce_product_thumbnails_columns', function () {
    return 5;
} );


add_action( 'woocommerce_before_add_to_cart_button', 'visia_start_button_buffer' );
add_action( 'woocommerce_after_add_to_cart_button', 'visia_end_button_buffer' );

function visia_start_button_buffer() { ob_start(); }

function visia_end_button_buffer() {
    $output = ob_get_clean();
    // Replace default WooCommerce classes with your own
    echo str_replace( 'single_add_to_cart_button button alt', 'uk-button', $output );
}



// Remove all default WC tabs and suppress the empty tab panel output
add_filter( 'woocommerce_product_tabs', 'visia_remove_product_tabs', 98 );
function visia_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );
    unset( $tabs['reviews'] );
    unset( $tabs['additional_information'] );
    return $tabs;
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


// ── Square gallery viewport ───────────────────────────────────────────────────
add_action( 'wp_footer', function () {
    if ( ! is_product() ) {
        return;
    }
    ?>
    <script>
    (function() {
        function squareGallery() {
            var viewport = document.querySelector('.woocommerce-product-gallery .flex-viewport');
            if ( ! viewport ) return;
            viewport.style.height = viewport.offsetWidth + 'px';
        }

        // Run once Flexslider has initialised (it fires wc-product-gallery-after-init)
        jQuery(document.body).on('wc-product-gallery-after-init', function() {
            squareGallery();
        });

        // Keep square on resize
        if ( window.ResizeObserver ) {
            var gallery = document.querySelector('.woocommerce-product-gallery');
            if ( gallery ) {
                new ResizeObserver( squareGallery ).observe( gallery );
            }
        } else {
            window.addEventListener('resize', squareGallery);
        }

        // Fallback: run after page load
        window.addEventListener('load', squareGallery);
    })();
    </script>
    <?php
} );

// ── Mini-cart fragments ───────────────────────────────────────────────────────
// Keep the cart icon badge and dropdown state in sync on every cart change
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    $cart_count     = WC()->cart->get_cart_contents_count();
    $cart_has_items = $cart_count > 0;

    ob_start();
    ?>
    <a class="cart-icon-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
        <i class="fa-light fa-bag-shopping fa-2xl"></i>
        <?php if ( $cart_has_items ) : ?>
            <span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
        <?php endif; ?>
    </a>
    <?php
    $fragments['a.cart-icon-link'] = ob_get_clean();

    ob_start();
    ?>
    <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['div.widget_shopping_cart_content'] = ob_get_clean();

    return $fragments;
} );

// ── Quantity stepper + ATC JS ─────────────────────────────────────────────────
add_action( 'wp_footer', function () {
    if ( ! is_product() ) {
        return;
    }
    ?>
    <script>
    (function($) {
        function initQtyStepper() {
            var $qty = $('form.cart .quantity input.qty');
            if ( ! $qty.length || $qty.closest('.g1-qty-stepper').length ) return;

            $qty.wrap('<div class="g1-qty-stepper"></div>');
            $qty.before('<button type="button" class="g1-qty-btn g1-qty-minus" aria-label="Decrease quantity">&minus;</button><span class="g1-qty-divider"></span>');
            $qty.after('<span class="g1-qty-divider"></span><button type="button" class="g1-qty-btn g1-qty-plus" aria-label="Increase quantity">&plus;</button>');

            $(document).on('click', '.g1-qty-minus', function() {
                var $input = $(this).closest('.g1-qty-stepper').find('input.qty');
                var val    = parseInt( $input.val(), 10 ) || 1;
                var min    = parseInt( $input.attr('min'), 10 ) || 1;
                if ( val > min ) {
                    $input.val( val - 1 ).trigger('change');
                }
            });

            $(document).on('click', '.g1-qty-plus', function() {
                var $input = $(this).closest('.g1-qty-stepper').find('input.qty');
                var val    = parseInt( $input.val(), 10 ) || 1;
                var max    = parseInt( $input.attr('max'), 10 );
                if ( ! max || val < max ) {
                    $input.val( val + 1 ).trigger('change');
                }
            });
        }

        $(document).ready( initQtyStepper );
        $(document.body).on( 'wc_variation_form updated_checkout', initQtyStepper );
    }(jQuery));
    </script>
    <?php
} );



// ── Use full-size image in mini cart ─────────────────────────────────────────
add_filter( 'woocommerce_cart_item_thumbnail', function ( $thumbnail, $cart_item ) {
    $_product = $cart_item['data'];
    $image_id = $_product->get_image_id();
    if ( $image_id ) {
        return wp_get_attachment_image( $image_id, 'full' );
    }
    return $thumbnail;
}, 10, 2 );

// ── Restyle buttons in all WooCommerce notices (success, error, info) ────────
add_filter( 'wc_add_to_cart_message_html', 'visia_replace_notice_button_classes' );
add_filter( 'woocommerce_add_error',       'visia_replace_notice_button_classes' );
add_filter( 'woocommerce_add_notice',      'visia_replace_notice_button_classes' );
add_filter( 'woocommerce_add_success',     'visia_replace_notice_button_classes' );

function visia_replace_notice_button_classes( $message ) {
    return str_replace( 'class="button wc-forward', 'class="uk-button uk-button-small', $message );
}
