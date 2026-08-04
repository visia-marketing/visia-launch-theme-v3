<?php
/**
 * Flexible layout: Video Gallery.
 *
 * Renders a filterable grid of product-video cards, sourced by category,
 * an explicit selection, or all videos. Shares the card/filter markup with
 * the single-product "Video Cards" tab via partials/content-video-gallery.php.
 */

$show_by      = get_sub_field( 'show_videos_by' );
$show_filters = (bool) get_sub_field( 'show_filters' );

$video_ids = array();

switch ( $show_by ) {

	case 'Choose Videos':
		$chosen    = get_sub_field( 'choose_videos' ); // Array of IDs (return_format: id).
		$video_ids = is_array( $chosen ) ? array_map( 'intval', $chosen ) : array();
		break;

	case 'Category':
		$term_id    = get_sub_field( 'video_type' ); // Term ID (return_format: id).
		$query_args = array(
			'post_type'      => 'product-video',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);
		// Only constrain by category when one was actually chosen; otherwise
		// fall back to all videos so the section never renders empty.
		if ( $term_id ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'video-type',
					'field'    => 'term_id',
					'terms'    => (array) $term_id,
				),
			);
		}
		$video_ids = get_posts( $query_args );
		break;

	case 'All':
	default:
		$video_ids = get_posts( array(
			'post_type'      => 'product-video',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );
		break;
}

if ( ! $video_ids ) {
	return;
}

get_template_part( 'partials/content-video-gallery', null, array(
	'videos'       => $video_ids,
	'show_filters' => $show_filters,
	// Names the gallery's section landmark and its (visually hidden) heading.
	'label'        => get_sub_field( 'gallery_label' ) ?: __( 'Videos', 'visia_marketing' ),
) );
