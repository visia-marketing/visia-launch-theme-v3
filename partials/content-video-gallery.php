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
?>

<div class="g1-video-gallery"<?php echo $has_filters ? ' uk-filter="target: .js-video-filter"' : ''; ?>>

	<?php if ( $has_filters ) : ?>
		<ul class="g1-video-filters" aria-label="Filter videos by type">
			<li class="g1-video-filter uk-active" uk-filter-control>
				<a href>All</a>
			</li>
			<?php foreach ( $filter_terms as $slug => $name ) : ?>
				<li class="g1-video-filter" uk-filter-control="filter: .video-type-<?php echo esc_attr( $slug ); ?>">
					<a href><?php echo esc_html( $name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<ul class="js-video-filter g1-video-cards uk-grid uk-grid-medium" uk-grid uk-lightbox="animation: fade">
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
</div>
