<?php

/**

 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package telecom-talk
 */
get_header(); ?>
<div id="primary" class="content-area container">
	<div id="main" class="site-main">
		<div class="main-cnt error-404">
			<div class="left-part">
				<header class="page-header">
					<h1 class="archive_title"><?php esc_html_e( '404 page not found', 'telecom-talk' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'telecom-talk' ); ?>
					</p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
				<div class="recent-news">
					<span class="archive_title">Recent News</span>
					<div class="post-wrapper archive">
						<?php
						$argsment=array(
				        //'post_status' => 'publish',

				        'posts_per_page' =>6,
				        'paged' => $paged,
						'post_type' => 'post',
				        //'offset' => 4, 
				         );		
				        $my_query = new WP_Query($argsment);
				        if( $my_query->have_posts() ) :?>
					       <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<div class="left_news">
										<div class="author-info">
											<span class="author-link"><?php the_author_posts_link(); ?></span>
											<span class="posted-date"><?php the_time('F jS, Y') ?> <?php the_time('g:i A'); ?></span>
											<a class="athr-cmts" href="<?php echo get_comments_link();?>">
												<?php echo get_comments_number();?> COMMENTS</a>
										</div><!-- /.author-info -->
										<ul class="authr-rght archive-social-link">
								            <li class="st"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>"><span class="icon-twitter"></span></a></li>
								            <li class="sf"><a href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>"><span class="icon-facebook"></span></a></li>
								            <li class="sw"><a href="whatsapp://send?text=<?php the_permalink(); ?>"><span class="icon-whatsapp"></span></a></li>
								            <li class="stg"><a href="https://telegram.me/share/url?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>"><span class="icon-telegram"></span></a></li>
								        </ul>
									</div>
									<div class="right_news">
										<?php
											$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
											$thumb_url = telecomtalk_aq_resize( $post_feat_image, 150, 83, true, false );
											if($thumb_url){ ?>
											<div class="arc-img">
												<a href="<?php the_permalink(); ?>">
									    			<img src="<?php echo $thumb_url[0]?>" alt="">
												</a>
											</div><!-- /.post-image-part -->
										<?php } ?>
										<h2 class="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
										<p><?php echo wp_trim_words( get_the_content(), 70, '...'  ); ?>
										<a href="<?php the_permalink(); ?>">Read More</a></p>
									</div>
								</article>
							<?php endwhile; ?>
							<div class="pagination-archive">
								<?php tt_number_pagination(); ?>
							</div>
					    	<?php else : ?>
						    <div class="no-posts">
						    	<span><?php esc_html_e ('no posts are Avaliable', 'telecomtalk' ); ?></span>
						    </div><!-- /.no-posts -->
							<?php endif; 
						wp_reset_postdata(); ?>
					</div><!-- /.post-list -->
				</div><!-- /.recent-new -->
			</div><!-- /left-part -->
			<?php get_sidebar(); ?>
		</div>
	</div><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
