<?php
/**
 * Single Product content template override.
 *
 * Adds uk-container wrapper and two-column layout structure.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'g1-single-product', $product ); ?>>

	<?php
	$product_cats = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $product_cats && ! is_wp_error( $product_cats ) ) :
		$cat_name = $product_cats[0]->name;
	?>
	<div class="g1-product-cat-banner">
		<div class="uk-container uk-container-large">
			<h2 class="g1-product-cat-banner__title"><?php echo esc_html( $cat_name ); ?></h2>
		</div>
	</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_before_single_product' ); ?>

	<div class="g1-product-hero">
	<div class="uk-container uk-container-large g1-product-inner">

		<?php
		/**
		 * Hook: woocommerce_before_single_product_summary.
		 * Outputs the product gallery.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images    - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>

		<div class="summary entry-summary">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title      - 5
			 * @hooked visia_single_product_sku               - 7  (custom)
			 * @hooked woocommerce_template_single_price      - 10
			 * @hooked woocommerce_template_single_excerpt    - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>
		</div>

	</div>
	</div><!-- /.g1-product-hero -->

	<?php
	// ── ACF Product Tabs ───────────────────────────────────────────────────────
	$tabs = get_field( 'tabs' );
	if ( $tabs ) :
	?>

	<div class="g1-product-tabs">

		<?php // Completed tabs pattern: role="tab" needs a role="tablist" parent to be valid,
		      // aria-selected to report state (the is-active class alone is visual only), and
		      // aria-controls / aria-labelledby to pair each tab with its panel. WCAG 4.1.2. ?>
		<nav class="g1-tabs-nav" aria-label="Product sections">
			<ul class="g1-tabs-nav-list" role="tablist">
				<?php foreach ( $tabs as $index => $tab ) : ?>
					<li role="presentation">
						<button type="button"
							class="g1-tab-nav-item<?php echo $index === 0 ? ' is-active' : ''; ?>"
							data-tab="g1-tab-<?php echo esc_attr( $index ); ?>"
							id="g1-tab-label-<?php echo esc_attr( $index ); ?>"
							role="tab"
							aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
							aria-controls="g1-tab-<?php echo esc_attr( $index ); ?>"
							tabindex="<?php echo $index === 0 ? '0' : '-1'; ?>">
							<?php echo esc_html( $tab['tab_title'] ); ?>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="g1-tabs-content">
			<?php foreach ( $tabs as $index => $tab ) : ?>

				<div class="g1-tab-panel<?php echo $index === 0 ? ' is-active' : ''; ?>"
					 id="g1-tab-<?php echo esc_attr( $index ); ?>"
					 role="tabpanel"
					 aria-labelledby="g1-tab-label-<?php echo esc_attr( $index ); ?>"
					 tabindex="0"
					 <?php echo $index === 0 ? '' : 'hidden'; ?>>

					<div class="uk-container uk-container-large">
						<?php
						$content_rows = isset( $tab['tab_content'] ) ? $tab['tab_content'] : array();
						foreach ( $content_rows as $content ) :
							$layout = isset( $content['acf_fc_layout'] ) ? $content['acf_fc_layout'] : '';
							switch ( $layout ) :

								// ── One Column Text ───────────────────────────────────────
								case 'product_tabs__one_col_text':
									if ( ! empty( $content['wysiwyg_content'] ) ) :
										echo '<div class="g1-tab-wysiwyg">';
										echo wp_kses_post( $content['wysiwyg_content'] );
										echo '</div>';
									endif;
									break;

								// ── Two Column Text ──────────────────────────────────────
								case 'product_tabs__two_col_text': ?>
									<div class="g1-tab-wysiwyg-cols uk-grid uk-grid-medium" uk-grid>
										<?php if ( ! empty( $content['wysiwyg_content_1'] ) ) : ?>
											<div class="uk-width-1-2@m">
												<?php echo wp_kses_post( $content['wysiwyg_content_1'] ); ?>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $content['wysiwyg_content_2'] ) ) : ?>
											<div class="uk-width-1-2@m">
												<?php echo wp_kses_post( $content['wysiwyg_content_2'] ); ?>
											</div>
										<?php endif; ?>
									</div>
									<?php break;

								// ── Three Column Text ─────────────────────────────────────
								case 'product_tabs__three_col_text': ?>
									<div class="g1-tab-wysiwyg-cols uk-grid uk-grid-medium" uk-grid>
										<?php if ( ! empty( $content['wysiwyg_content_1'] ) ) : ?>
											<div class="uk-width-1-3@m">
												<?php echo wp_kses_post( $content['wysiwyg_content_1'] ); ?>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $content['wysiwyg_content_2'] ) ) : ?>
											<div class="uk-width-1-3@m">
												<?php echo wp_kses_post( $content['wysiwyg_content_2'] ); ?>
											</div>
										<?php endif; ?>
										<?php if ( ! empty( $content['wysiwyg_content_3'] ) ) : ?>
											<div class="uk-width-1-3@m">
												<?php echo wp_kses_post( $content['wysiwyg_content_3'] ); ?>
											</div>
										<?php endif; ?>
									</div>
									<?php break;

								// ── Text Cards (specs) ────────────────────────────────────
								case 'product_tabs__text_cards':
									if ( ! empty( $content['text_cards'] ) ) : ?>
										<div class="g1-text-cards uk-grid uk-grid-medium" uk-grid>
											<?php foreach ( $content['text_cards'] as $card ) : ?>
												<div class="uk-width-1-1 uk-width-1-2@s uk-width-1-3@m">
													<?php get_template_part( 'partials/content-text-card', null, array( 'card' => $card ) ); ?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif;
									break;

								// ── Download Cards ────────────────────────────────────────
								case 'product_tabs__download_cards':
									if ( ! empty( $content['choose_downloads'] ) ) : ?>
										<div class="g1-download-cards uk-grid uk-grid-medium" uk-grid>
											<?php foreach ( $content['choose_downloads'] as $download ) :
												if ( ! $download ) continue; ?>
												<div class="uk-width-1-2@xs uk-width-1-3@s uk-width-1-4@m">
													<?php get_template_part( 'partials/content-download-card', null, array( 'download' => $download ) ); ?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif;
									break;

								// ── Video Cards ───────────────────────────────────────────
								case 'product_tabs__video_cards':
									if ( ! empty( $content['choose_videos'] ) ) : ?>
										<div class="g1-video-cards uk-grid uk-grid-medium" uk-grid>
											<?php foreach ( $content['choose_videos'] as $video ) :
												if ( ! $video ) continue; ?>
												<div class="uk-width-1-1 uk-width-1-2@s uk-width-1-3@m">
													<?php get_template_part( 'partials/content-video-card', null, array( 'video' => $video ) ); ?>
												</div>
											<?php endforeach; ?>
										</div>
									<?php endif;
									break;

							endswitch;
						endforeach;
						?>
					</div>

				</div>

			<?php endforeach; ?>
		</div>

	</div>

	<script>
	(function() {
		var navItems = Array.prototype.slice.call(document.querySelectorAll('.g1-tab-nav-item'));
		var panels   = document.querySelectorAll('.g1-tab-panel');

		function select(item, moveFocus) {
			navItems.forEach(function(n) {
				n.classList.remove('is-active');
				n.setAttribute('tabindex', '-1');
				n.setAttribute('aria-selected', 'false');
			});
			panels.forEach(function(p) {
				p.classList.remove('is-active');
				// `hidden` as well as the class: an inactive panel that is only visually
				// hidden still exposes its content and its links to the tab order.
				p.hidden = true;
			});

			item.classList.add('is-active');
			item.setAttribute('tabindex', '0');
			item.setAttribute('aria-selected', 'true');
			if (moveFocus) item.focus();

			var panel = document.getElementById(item.dataset.tab);
			if (panel) {
				panel.classList.add('is-active');
				panel.hidden = false;
			}
		}

		navItems.forEach(function(item, index) {
			// Enter/Space are handled natively now that these are <button>s.
			item.addEventListener('click', function() { select(item, false); });

			// Roving tabindex: the WAI-ARIA tabs pattern moves between tabs with the arrow
			// keys, not Tab — Tab is what leaves the tablist for the panel.
			item.addEventListener('keydown', function(e) {
				var target = null;

				if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
					target = navItems[(index + 1) % navItems.length];
				} else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
					target = navItems[(index - 1 + navItems.length) % navItems.length];
				} else if (e.key === 'Home') {
					target = navItems[0];
				} else if (e.key === 'End') {
					target = navItems[navItems.length - 1];
				}

				if (target) {
					e.preventDefault();
					select(target, true);
				}
			});
		});
	})();
	</script>

	<?php endif; ?>

	<?php
	// ── Related Products ──────────────────────────────────────────────────────
	$related_ids = wc_get_related_products( $product->get_id(), 8 );
	if ( $related_ids ) :
	?>
	<div class="g1-related-products">
		<div class="uk-container uk-container-large">
			<div class="g1-related-slider carousel-wrapper" data-slides-to-show="4" data-duration="500">
				<?php foreach ( $related_ids as $related_id ) : ?>
					<div class="g1-related-slide">
						<?php get_template_part( 'partials/content', 'product-card', array( 'prod_id' => $related_id ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php
	// ── FAQs ──────────────────────────────────────────────────────────────────
	$faqs = get_field( 'frequently_asked_questions' );
	if ( $faqs ) :
	?>
	<div class="g1-product-faqs">
		<div class="uk-container uk-container-large">

			<h2 class="g1-faqs-heading">Frequently Asked Questions</h2>

			<?php // Same treatment as flexible/section_faqs.php: the trigger was an <a> with a
			      // valueless href, which announces as a link and navigates without JS. It is
			      // a <button> inside a heading now, with the toggle selector redeclared to
			      // match the extra nesting. ?>
			<ul class="g1-faqs-list" uk-accordion="collapsible: true; toggle: > .g1-faq-question__heading > .uk-accordion-title">
				<?php
				$wc_faq_index = 0;
				foreach ( $faqs as $faq ) :
					if ( empty( $faq['question'] ) ) continue;
					$wc_faq_index++;
					$wc_q_id = 'wc-faq-question-' . $wc_faq_index;
					$wc_a_id = 'wc-faq-answer-' . $wc_faq_index;
				?>
				<li class="g1-faq-item">
					<h3 class="g1-faq-question__heading">
						<button type="button"
								class="uk-accordion-title g1-faq-question"
								id="<?php echo esc_attr( $wc_q_id ); ?>"
								aria-expanded="false"
								aria-controls="<?php echo esc_attr( $wc_a_id ); ?>">
							<span class="g1-faq-question__text"><?php echo esc_html( $faq['question'] ); ?></span>
							<span class="g1-faq-toggle" aria-hidden="true"></span>
						</button>
					</h3>
					<div class="uk-accordion-content g1-faq-answer"
						 id="<?php echo esc_attr( $wc_a_id ); ?>"
						 role="region"
						 aria-labelledby="<?php echo esc_attr( $wc_q_id ); ?>">
						<p><?php echo nl2br( esc_html( $faq['answer'] ) ); ?></p>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>

			<?php
			// ── FAQ Schema (JSON-LD) ───────────────────────────────────────────
			$schema_faqs = array_values( array_filter( $faqs, fn( $f ) => ! empty( $f['question'] ) && ! empty( $f['answer'] ) ) );
			if ( $schema_faqs ) :
				$entities = array_map( function( $f ) {
					return [
						'@type'          => 'Question',
						'name'           => $f['question'],
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => $f['answer'],
						],
					];
				}, $schema_faqs );
				$schema = [
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $entities,
				];
			?>
			<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?></script>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>


	<?php // -- CTA -- ?>
	<?php get_template_part( 'partials/cta-default' ); ?>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_upsell_display           - 15
	 * @hooked woocommerce_output_related_products  - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
