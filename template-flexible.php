<?php
/**
 * Template Name: Flexible Template
 * Template Post Type: post, page
 */
?>

<?php while (have_posts()) : the_post(); ?>

  <article class="page page-flexible page-flexible-<?php echo get_post_type( $post->ID ); ?> page-<?php global $post; echo $post->post_name; ?>" id="overview">

    <?php get_flexible_content(); // also renders partials/page-header ?>

  </article>

<?php endwhile; ?>