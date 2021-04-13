<?php

/**

 * The template for displaying the footer

 *

 * Contains the closing of the #content div and all content after.

 *

 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials

 *

 * @package telecom-talk

 */



?>

	</div><!-- #content -->

		<footer id="colophon" class="site-footer">
			<div class="site-info">
				<div class="container">
					<div class="footer-wrap">
						<div class="rr">
							<span><?php echo get_option('footer_cpyr');?></span>
						</div>
						<div class="footer-menu">
							<?php if(has_nav_menu ('footer-menu'))
					    		wp_nav_menu( array( 
					    			'container' => '',
					    			'container_id' => '',
					    			'theme_location' => 'footer-menu',
					    			'sort_column' => 'menu_order',
					    			'menu_class' => 'footer-menu',
					    		)
					    	);
					    ?>
						</div>
					</div>
				</div>
			</div>
		</footer><!-- #colophon -->

</div><!-- #page -->
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.drawer').drawer();
	});
</script>
<?php wp_footer(); ?>

</body>

</html>