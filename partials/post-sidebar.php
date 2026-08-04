<?php
global $post;

$related = get_posts( array(
	'category__in' => wp_get_post_categories( $post->ID ),
	'numberposts'  => 5,
	'post__not_in' => array( $post->ID ),
) );

// Nothing to expose when there are no related posts — an empty complementary landmark is
// just noise in the landmark rotor.
if ( ! $related ) {
	wp_reset_postdata();
	return;
}
?>

<?php // <aside> so the sidebar is a labelled complementary landmark rather than an
      // anonymous div sitting beside the article. ?>
<aside class="post-sidebar" aria-labelledby="related-posts-heading">

	<div class="post-sidebar-related-posts">

		<?php // Was <h5> wrapping <h3> items — the children outranked their own section
		      // heading, and both skipped levels down from the post's <h1>. WCAG 1.3.1. ?>
		<h2 id="related-posts-heading">Related Posts</h2>

		<?php // The <ul> used to sit inside the loop, so five related posts produced five
		      // separate single-item lists and "list, 5 items" was never announced. ?>
		<ul>
			<?php foreach ( $related as $post ) { ?>
				<?php setup_postdata( $post ); ?>
				<li>
					<?php // The title= attribute here duplicated the link text verbatim,
					      // which some screen readers announce twice. ?>
					<h3><a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php echo esc_html( get_the_title() ); ?></a></h3>
				</li>
			<?php } ?>
		</ul>

	</div>

	<?php wp_reset_postdata(); ?>

</aside>
