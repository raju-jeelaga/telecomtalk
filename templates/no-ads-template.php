<?php
/*
 * Template Name: No ads Template
 * Template Post Type: post
 */
get_header();
// Start the Loop.
?>
	<div class="templ-pages no-ads">
		<div class="container">
			<main id="main" class="site-main" role="main">
				<div class="main-cnt">
					<div class="main-left">
						<div class="left-part">
						<?php while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/content', 'with-no-ads' );
						endwhile; // End the loop. ?>
						</div><!-- /.left-part -->
						
					</div>
					<?php get_sidebar();?>
					<div class="comments-wrapper section-inner">
						<?php 
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
						?>
					</div>
				</div>
			</main>
		</div><!-- /.container -->
	</div><!-- /.templ-pages -->
<?php	
get_footer();
?>
