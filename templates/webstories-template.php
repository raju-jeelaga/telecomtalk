<?php
/*
 * Template Name: Web Stories
 * Template Post Type: post
 */
get_header();
// Start the Loop.
?>
	<div class="templ-pages full-width web-stories">
		<div class="container">
			<main id="main" class="site-main" role="main">
				<div class="main-cnt">
					<div class="left-part">
					<?php while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content/content', 'with-westories' );
					endwhile; // End the loop. ?>
					</div><!-- /.left-part -->
				</div>
				<div class="comments-wrapper section-inner">
					<?php 
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					?>
				</div>
				
			</main>
		</div><!-- /.container -->
	</div><!-- /.templ-pages -->
<?php	
get_footer();
?>
