<?php

/**

 * The template for displaying search results pages

 *

 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result

 *

 * @package telecom-talk

 */



get_header(); ?>



<div class="archive-category">
	<div class="container">
		<div id="main" class="site-main">
			<div class="main-cnt">
				<div class="left-part">
					<div class="breadcrumbs">
						<?php
						$obj = get_queried_object();
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
							<?php if ( is_search() ) { ?> 
								<li>You are Seaching for: <i style="font-weight: bold">" <?php echo get_search_query(); ?>"</i></li>
							<?php } else {
							 if(!empty($category)){?>
								<li><?php echo $category;?></li>
							<?php } } ?>
						</ul>
					</div>
					<header class="page-header">
						<h1 class="page-title archive-title"><?php
							printf( esc_html__( '%s', 'telecom-talk' ), '<span>' . get_search_query() . '</span>' );
						?></h1>
					</header><!-- .page-header -->
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
</div><!-- /.archive-category-->

<?php

get_footer();

