<?php

/* Template Name: Contact Us */ 

	

get_header(); ?>

<div class="singlepage-wrapper content-area">

	<div class="pg">

		<div class="container">

			<?php if(function_exists('seopress_display_breadcrumbs')) { ?>
	            <div class="breadcrumbs">
	                <?php seopress_display_breadcrumbs(); ?>
	            </div>
        	<?php } ?>

			<?php

				get_template_part( 'template-parts/content', 'contact-us' );

				

				if(have_posts()):

				

				while(have_posts()): the_post();

				

				// Include the page content template.

				endwhile;

				endif;

			?>

		</div><!-- .container -->

	</div>

</div><!-- singlepage-wrapper-->

<?php get_footer(); ?>