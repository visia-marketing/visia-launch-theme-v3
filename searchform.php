<?php
/**
 * Search form
 *
 * The single source for search markup — the header, the off-canvas panel and any
 * get_search_form() call all render this file. It used to be copy-pasted into base.php and
 * partials/site-header.php, which put up to three id="search-field" on one page and broke
 * every label association on it.
 *
 * The <input> keeps its wrapping <label> (partials/_search.scss styles the pair) and also
 * carries an explicit for/id, so association survives even if the nesting changes.
 */

$visia_search_id = visia_unique_id( 'search-field' );
?>
<?php // role="search" is kept: searchform.php overrides core's html5 form, so the search
      // landmark would otherwise be lost entirely. ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label for="<?php echo esc_attr( $visia_search_id ); ?>">
    <span class="screen-reader-text"><?php esc_html_e( 'Search', 'visia_marketing' ); ?></span>
    <input
      type="search"
      class="search-field"
      id="<?php echo esc_attr( $visia_search_id ); ?>"
      placeholder="<?php esc_attr_e( 'Search…', 'visia_marketing' ); ?>"
      value="<?php echo esc_attr( get_search_query() ); ?>"
      name="s">
  </label>
  <button class="uk-button" type="submit">
    <?php echo visia_icon( 'fa-solid fa-magnifying-glass', __( 'Search', 'visia_marketing' ) ); ?>
  </button>
</form>
