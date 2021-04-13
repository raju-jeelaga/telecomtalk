<?php

/**

 * The template for displaying archive pages

 *

 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/

 *

 * @package telecom-talk

 */



get_header(); ?>

<div class="archive-category">
	<div class="container">
		<div id="main" class="site-main">
			<div class="main-cnt">
				<div class="left-part">
					<?php 
					$obj = get_queried_object();
					$cat_slug = $obj->slug;
	                if ( is_author() ) { ?>
	                <header class="page-header">
						<h1 class="archive_title"><?php echo get_the_author();?></h1>
					</header><!-- .page-header -->
	                <?php } else { ?>
	                <header class="page-header">
							<h1 class="archive_title"><?php echo single_cat_title();?></h1>
					</header><!-- .page-header -->
	                <?php } ?>
	                <?php if ($cat_slug == "air-vistara"){ ?>
						<ul class="archive-top">
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/airtel-digital-tv.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/asianet-cable-tv.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/dd-free-dish.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/den-digital.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/dishtv.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/hathway-digital.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/rcom.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/sun-direct.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/tatasky.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/videocon-d2h.png" alt="" width="100" height="100"></a></li>
						</ul>
					<?php } if ($cat_slug == "news"){?>
						<ul class="archive-top">
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/act-fibernet.png" alt="" width="100" height="100">
							</a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/asianet-broadband.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/bsnl-broadband.png" alt="" width="100" height="100"></a></li>
							<li><a href="#" style="background-color: #a1a1a1;"><img src="<?php bloginfo('template_url'); ?>/logo-images/connect-broadband.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/den-broadband.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/hathway.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/jiofiber.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/tatasky-broadband-1.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/tikona-broadband.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/you-broadband.png" alt="" width="100" height="100"></a></li>
						</ul>
					<?php } if ($cat_slug == "air-india"){ ?>
						<ul class="archive-top">
							<li><a href="#">3G in India</a></li>
							<li><a href="#">4G in India</a></li>
							<li><a href="#"><span>Bharti Airtel</span></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/bsnl.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/mtnl.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/rcom.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/jio.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/vodafone-idea.png" alt="" width="100" height="100"></a></li>
							<li><a href="#"><img src="<?php bloginfo('template_url'); ?>/logo-images/vodafone.png" alt="" width="100" height="100"></a></li>
						</ul>
					<?php }  ?>
	                <div class="breadcrumbs">
						<?php
						$category = "";
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							for($i=0;$i<count($categories);$i++){
						    	$category = esc_html( $categories[1]->name );   
							}
						}
						$category = $obj->name;
						?>
						<ul>
							<li><a href="<?php echo home_url();?>">Home</a></li>
							<?php if ( is_author() ) { ?> 
								<li><?php echo get_author_name(); ?></li>
							<?php } else {
							 if(!empty($category)){?>
								<li><?php echo $category;?></li>
							<?php } } ?>
						</ul>
					</div>
					<div class="archive-news">
						<?php 
		                if ( is_author() ) { ?>   
		                	<div class="author-info">
		                       <div class="author-image">
		                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
		                       </div><!-- /.author-image -->
		                      <div class="author-description">
		                        <!-- <h4><?php the_author_posts_link(); ?></h4> -->
		                        <p>About the author: <?php the_author_meta('description'); ?></p>
		                      </div><!-- /.author-description -->
		                    </div><!-- /.author-info -->
		               <?php  } ?> 
					</div><!-- /.archive-header -->

					<div class="post-wrapper archive">
						<?php
						 $args = array(
							'base'               => '%_%',
							'format'             => '?paged=%#%',
							'total'              => 1,
							'current'            => 0,
							'show_all'           => false,
							'end_size'           => 1,
							'mid_size'           => 2,
							'prev_next'          => true,
							'prev_text'          => __('« Previous Page'),
							'next_text'          => __('Next Page »'),
							'type'               => 'plain',
							'add_args'           => false,
							'add_fragment'       => '',
							'before_page_number' => '',
							'after_page_number'  => ''
						); 
						if ( have_posts() ) : 
							/* Start the Loop */
							while ( have_posts() ) : the_post();
								get_template_part( 'template-parts/content/content', 'archive-news' );
							endwhile;?>
							<div class="pagination-archive">
								<?php tt_number_pagination(); ?>
							</div>
					    	<?php else : ?>
						    <div class="no-posts">
						    	<span><?php esc_html_e ('no posts are Avaliable', 'telecomtalk' ); ?></span>
						    </div><!-- /.no-posts -->
				       <?php endif; ?>
						
			    	</div><!-- /.post-wrapper -->
			    </div><!-- /.left-part -->
			    <?php get_sidebar(); ?>
			</div><!-- /.archive-page-->
		</div><!-- .content-area -->
	</div><!-- /.container -->
</div><!-- /.single-page-shadow-->

<?php
get_footer();

