<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); 

?>

<div class="wrap">
	<div id="primary" class="content-area grt">
		<div class="container">
			<main id="main" class="site-main" role="main">
				<div class="main-cnt">
					<div class="main-left">
						<div class="left-part">
							<?php
								// Start the Loop.
								while ( have_posts() ) :
									the_post();
									//echo "left part";
									get_template_part( 'template-parts/content/content', get_post_type() );
								endwhile; // End the loop.
							?>
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

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->
	
</div><!-- .wrap -->
<?php get_footer(); ?>
