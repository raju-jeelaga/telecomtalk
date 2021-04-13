<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package webnews
 */

?>
<article <?php post_class(); ?>>
	<div class="full-width-shadow-page contact-us-page">
		<header class="entry-header">
			<div class="entry-meta contact-title-part">
				<h2><?php the_title(); ?></h2>
	        </div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content">
			<div class="contact-content-part">
				<?php the_content(); ?>
			</div><!-- /.contact-content-part -->
		</div><!-- .entry-content -->

		<footer class="entry-footer">

		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-## -->
