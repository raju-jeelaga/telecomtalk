<?php
/**
 * The template for displaying Search form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package telecom-talk
 */
 ?>

    <form role="search" method="get" class="telecom-talk-fullscreen-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label class="src-bx">
          <input type="search" class="search-field"
              placeholder="<?php echo esc_attr_x( 'Search...', 'label', 'telecom-talk' ) ?>"
              value="<?php echo esc_attr( get_search_query() ); ?>" name="s"
              title="<?php echo esc_attr_x( 'Search for:', 'label', 'telecom-talk' ) ?>" id="telecom-talk-fullscreen-search-input"/>
      </label>
      <label class="search-button search-overlay">
          <i aria-hidden="true"></i>
      <input type="submit" class="search-submit"
          value="<?php echo esc_attr_x( '', 'label', 'telecom-talk' ) ?>" />
      </label>
      <!-- <div class="overlay-search"></div> -->
    </form>
    <a class="lb-x" href="#"></a>