<?php
/**
 * Single video "cover" card — a poster image that opens the video in a
 * UIkit lightbox. Designed to live inside a [uk-lightbox] container.
 *
 * @param array $args {
 *     @type int|WP_Post $video Video post ID or object (post type: product-video).
 * }
 */

$video = $args['video'] ?? null;
if ( ! $video ) return;

// Accept either a post ID or a WP_Post object.
$video_id = is_object( $video ) ? (int) $video->ID : (int) $video;
if ( ! $video_id ) return;

$title       = get_the_title( $video_id );
$format      = get_field( 'video_format', $video_id );
$description = get_field( 'video_description', $video_id );

// Resolve the lightbox source URL and a cover for the grid.
$lightbox_url = '';
$poster       = '';      // Static cover image (YouTube thumbnail).
$poster_video = '';      // MP4 used as its own first-frame cover.

if ( 'MP4' === $format ) {
	$mp4          = get_field( 'mp4_video', $video_id );
	$lightbox_url = is_array( $mp4 ) ? ( $mp4['url'] ?? '' ) : $mp4;
	$poster_video = $lightbox_url; // No static thumbnail — use the first frame.
} else {
	// YouTube — fetch the raw URL (format_value = false) to derive the ID.
	$youtube_url  = get_field( 'youtube_video', $video_id, false );
	$lightbox_url = $youtube_url;
	if ( $youtube_url && preg_match( '%(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/|v/))([\w-]{11})%', $youtube_url, $m ) ) {
		$poster = 'https://img.youtube.com/vi/' . $m[1] . '/maxresdefault.jpg';
	}
}

if ( ! $lightbox_url ) return;
?>

<div class="g1-video-card">

	<a class="g1-video-card__cover"
	   href="<?php echo esc_url( $lightbox_url ); ?>"
	   data-caption="<?php echo esc_attr( $title ); ?>"
	   aria-label="<?php echo esc_attr( $title ? 'Play video: ' . $title : 'Play video' ); ?>">

		<?php if ( $poster_video ) : ?>
			<video class="g1-video-card__poster" muted playsinline preload="metadata">
				<source src="<?php echo esc_url( $poster_video ); ?>#t=0.1" type="video/mp4">
			</video>
		<?php elseif ( $poster ) : ?>
			<img class="g1-video-card__poster" src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
		<?php endif; ?>

		<span class="g1-video-card__play" aria-hidden="true">
			<span class="fa-stack">
				<i class="fa-solid fa-play fa-stack-1x"></i>
				<i class="fa-light fa-expand fa-stack-2x"></i>
			</span>
		</span>
	</a>

	<?php if ( $title ) : ?>
		<p class="g1-video-card__title"><?php echo esc_html( $title ); ?></p>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<div class="g1-video-card__description"><?php echo nl2br( esc_html( $description ) ); ?></div>
	<?php endif; ?>

</div>
