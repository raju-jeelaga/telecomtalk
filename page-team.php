<?php

/* Template Name: Team Template */ 

	

get_header(); ?>

<div class="team-page">
		<div class="container">
			<div id="main" class="site-main team-page">
				<div class="post-wrapper archive">
					<?php
					if ( have_posts() ) : 
						/* Start the Loop */
						while ( have_posts() ) : the_post();
							get_template_part( 'template-parts/content/content', 'team' );
						endwhile;?>
				    	<?php else : ?>
					    <div class="no-posts">
					    	<span><?php esc_html_e ('no posts are Avaliable', 'telecomtalk' ); ?></span>
					    </div><!-- /.no-posts -->
			       <?php endif; ?>
		    	</div><!-- /.post-wrapper -->
			</div><!-- .site-main -->
		</div><!-- .container -->
</div><!-- team-page-->

<?php get_footer(); ?>