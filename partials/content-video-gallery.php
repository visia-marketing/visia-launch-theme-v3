<?php
/**
 * Video gallery — filterable grid of product-video cards.
 *
 * Shared by the single-product "Video Cards" tab layout and the
 * "Video Gallery" flexible-content block. Filtering uses the native
 * UIkit filter component (uk-filter / uk-filter-control).
 *
 * @param array $args {
 *     @type array $videos       Array of video post IDs or WP_Post objects.
 *     @type bool  $show_filters Whether to render the video-type filter buttons.
 * }
 */

$videos       = $args['videos'] ?? array();
$show_filters = ! empty( $args['show_filters'] );

// Normalize to a clean list of post IDs (accepts IDs or post objects).
$video_ids = array();
foreach ( (array) $videos as $v ) {
	if ( is_object( $v ) && isset( $v->ID ) ) {
		$video_ids[] = (int) $v->ID;
	} elseif ( is_numeric( $v ) ) {
		$video_ids[] = (int) $v;
	}
}
$video_ids = array_filter( $video_ids );

if ( ! $video_ids ) {
	return;
}

// Collect the set of video-type terms present, for the filter controls.
$filter_terms = array();
if ( $show_filters ) {
	foreach ( $video_ids as $vid ) {
		$terms = get_the_terms( $vid, 'video-type' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$filter_terms[ $term->slug ] = $term->name;
			}
		}
	}
}

$has_filters = count( $filter_terms ) > 1;

$gallery_id    = visia_unique_id( 'video-gallery' );
$filters_id    = $gallery_id . '-filters';
$heading_id    = $gallery_id . '-heading';
$status_id     = $gallery_id . '-status';
$gallery_label = $args['label'] ?? __( 'Videos', 'visia_marketing' );
?>

<section class="g1-video-gallery" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"<?php echo $has_filters ? ' uk-filter="target: .js-video-filter"' : ''; ?>>

	<?php // The gallery previously contributed no heading at all, so it was invisible in the
	      // heading outline and the section had nothing to be named by. ?>
	<h2 id="<?php echo esc_attr( $heading_id ); ?>" class="screen-reader-text"><?php echo esc_html( $gallery_label ); ?></h2>

	<?php if ( $has_filters ) : ?>
		<?php // The controls were <a href> — a valueless href, i.e. a link to the current
		      // page — that actually behaved as filter toggles. They are buttons now, they
		      // report their state through aria-pressed instead of the uk-active class
		      // alone, and the aria-label sits on a role="group" wrapper where it is
		      // actually exposed (a bare <ul> does not support naming). WCAG 4.1.2. ?>
		<div role="group" aria-label="<?php esc_attr_e( 'Filter videos by type', 'visia_marketing' ); ?>">
			<ul id="<?php echo esc_attr( $filters_id ); ?>" class="g1-video-filters">
				<li class="g1-video-filter uk-active" uk-filter-control>
					<button type="button" aria-pressed="true"><?php esc_html_e( 'All', 'visia_marketing' ); ?></button>
				</li>
				<?php foreach ( $filter_terms as $slug => $name ) : ?>
					<li class="g1-video-filter" uk-filter-control="filter: .video-type-<?php echo esc_attr( $slug ); ?>">
						<button type="button" aria-pressed="false"><?php echo esc_html( $name ); ?></button>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php // Filtering silently adds and removes cards; this announces the new count.
		      // main.js writes into it. WCAG 4.1.3. ?>
		<p id="<?php echo esc_attr( $status_id ); ?>" class="screen-reader-text" role="status" aria-live="polite"></p>
	<?php endif; ?>

	<ul class="js-video-filter g1-video-cards uk-grid uk-grid-medium"<?php echo $has_filters ? ' data-filter-status="' . esc_attr( $status_id ) . '"' : ''; ?> uk-grid uk-lightbox="animation: fade">
		<?php foreach ( $video_ids as $vid ) :
			$terms        = get_the_terms( $vid, 'video-type' );
			$slugs        = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();
			$type_classes = array_map( function ( $s ) { return 'video-type-' . $s; }, $slugs );
			?>
			<li class="g1-video-card-col uk-width-1-1 uk-width-1-2@s uk-width-1-3@l <?php echo esc_attr( implode( ' ', $type_classes ) ); ?>">
				<?php get_template_part( 'partials/content-video-card', null, array( 'video' => $vid ) ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
