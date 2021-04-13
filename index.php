<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<main id="content-area">
    <div class="container">
        <div class="hbp">
            <?php
            $post_ids = get_option('hbp_option');
            $posts_arr = explode(',', $post_ids);
            $post_category = '';
            if(!empty($posts_arr)){
                for($i=0;$i<count($posts_arr);$i++){
                    $post = get_post( $posts_arr[$i] );
                    $category_detail=get_the_category($posts_arr[$i]);
                    foreach($category_detail as $cd){
                    	if($cd->cat_ID == 1){
                    		continue;
                    	}
						$post_category = $cd->cat_name;
					}

                    $post_content = $post->post_content;
                    $post_title = $post->post_title;
                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 115, 75, true, false );
                    ?>
                    <div class="hbp-list">
                        <div class="hbp-img">
                            <a href="<?php echo get_permalink($post->ID);?>">
                                <img src="<?php echo $thumb_url[0];?>" alt="" width="115" height="75">
                            </a>
                            <?php if(!empty($post_category)){?>
                            <span><?php echo $post_category;?></span>
                        	<?php } ?>
                        </div>
                        <h4><a href="<?php echo get_permalink($post->ID);?>"><?php echo wp_trim_words( $post_title );?></a></h4>
                    </div>
                    <?php
                }
            }   
            ?>
        </div>
        <?php 
            $category = get_term_by('name', 'featured', 'category');
            $featured_cat_id = $category->term_id;
        ?>
        <div class="main-cnt">
            <div class="left-part">
                <div class="blog-posts">
                    <div class="lnbp">
                        <div class="left-lnbp desk-view">
                    	   <?php
        				        $recent_posts = wp_get_recent_posts(array(
        				        'numberposts' => 1, // Number of recent posts thumbnails to display
        				        'post_status' => 'publish', // Show only the published posts
                                'category__not_in' => $featured_cat_id
        				    ));
                            $first_post  = '';
        				    foreach($recent_posts as $post) :
                                if (!has_category('technology-news',$post['ID']) && !has_category('hindi',$post['ID'])){ 
            				    	$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post['ID']) );
                                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 438, 292, true, false ); 
                                    $first_post = $post['ID'];
                                    $published_date = get_the_date( 'M dS Y', $post['ID'] );
                                    
            				    	?>
            				    	<?php if($post_feat_image){?>
            			    		<div class="lnbp-img">
                                        <a href="<?php echo get_permalink($post['ID']) ?>">
                                            <!-- <img src="<?php //echo $post_feat_image;?>" alt="" width="438" height="292"> -->
                                            <img src="<?php echo $thumb_url[0];?>" alt="" width="<?php echo $thumb_url[1];?>" height="<?php echo $thumb_url[2];?>">
                                        </a>
                                    </div>
            				    	<?php }	?>
                                    <div class="lnbp-cnt">
                                        <h1>
                                            <span>Just in</span>
                                            <a href="<?php echo get_permalink($post['ID']) ?>"><?php echo $post['post_title'] ?></a>
                                        </h1>
                                        <?php $latest_content = wp_trim_words(get_the_content(),31,'....'); 
                                               $content = strip_shortcodes( $latest_content );
                                        ?>
                                        <p><?php echo $content;?><a href="<?php echo get_permalink($post['ID']) ?>">Continue Reading </a></p>
                                        <div class="authr-info">
                                            <div class="authr-left">By
                                                <span class="athr-nm"><?php the_author(); ?></span>
                                                <span class="athr-dt"><?php echo $published_date; ?></span>
                                                <a class="athr-cmts" href="<?php echo get_comments_link( $post['ID'] );?>"><?php echo get_comments_number($post['ID']);?></a>
                                            </div>
                                            <ul class="authr-rght">
                                                <li class="st"><a href="https://twitter.com/intent/tweet?url=<?php echo get_permalink($post['ID']) ?>&text=<?php echo $post['post_title']; ?>">
                                                    <span class="icon-twitter"></span></a>
                                                </li>
                                                <li class="sw"><a href="whatsapp://send?text=<?php echo get_permalink($post['ID']); ?>"><span class="icon-whatsapp"></span></a></li>
                                            </ul>
                                        </div>
                                </div>
                                <?php 
                                } 
                            endforeach; 
                            wp_reset_query(); 
                            ?>
                            <?php 
                            $myposts = get_posts( array(
    					        'posts_per_page' => 2,
    					        'category'       => 3934,
    					        'order'          => 'DESC',
    					    ) );
                            ?>
                        </div>
                        <?php $above_editoral_post = get_option('above_editoral_post');
                              $above_editoral_post_enable = get_option('above_editoral_post_enable');
                            if( $above_editoral_post_enable ){ ?>
                                <div class="above-editorial-ad desk-view text-center">
                                    <?php echo $above_editoral_post;?>
                                </div>
                        <?php } ?>
                        <div class="category-posts c-1">
                            <div class="category t_c">
                            	<?php $category_link = get_category_link( 3934 );?>
                                <a title="Editorial" href="<?php echo $category_link;?>">Editorial</a>
                            </div>
                            <div class="cat-pst">
                                <div class="left-cp">
                                    <ul class="l-big-post">
                                    	<?php
                                    	if ( $myposts ) { 
											foreach ( $myposts as $post ) {
						            			setup_postdata( $post );
						            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false ); 
						            		?>
											<li>
												<?php if($post_feat_image){ ?>
	                                            <a href="<?php echo get_permalink($post->ID);?>">
	                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
	                                            </a>
	                                        	<?php } ?>
	                                            <h2>
	                                                <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
	                                            </h2>
	                                        </li>
											<?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="r-small-posts">
                                    	<?php
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 5,
									        'category'       => 3934,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 3934 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
							            	?>
	                                        <li>
	                                            <div class="sp-f-m">
		                                            <?php if($post_feat_image){ ?>
			                                            <a class="f-m" href="<?php echo get_permalink($post->ID);?>">
			                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="80" height="52">
			                                            </a>
		                                        	<?php } ?>
	                                            </div>
	                                            <h2>
	                                                <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
	                                            </h2>
	                                        </li>
                                    	<?php }
                                    		wp_reset_postdata();
                                    	}
                                    	?>
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->
                        <?php $above_interview = get_option('above_interview');
                              $above_interview_post_enable = get_option('above_interview_post_enable');
                            if( $above_interview_post_enable ){ ?>
                                <div class="above-editorial-ad text-center">
                                    <?php echo $above_interview;?>
                                </div>
                        <?php } ?>
                        <div class="category-posts c-2">
                            <div class="category t_c int">
                            	<?php $category_link = get_category_link( 3910 );?>
                                <a title="Editorial" href="<?php echo $category_link;?>">Interview</a>
                            </div>
                            <div class="cat-pst">
                                <div class="left-cp">
                                	<?php 
			                        $myposts = get_posts( array(
								        'posts_per_page' => 2,
								        'category'       => 3910,
								        'order'          => 'DESC',
								    ) );
			                        ?>
                                    <ul class="l-big-post">
                                    	<?php
                                    	if ( $myposts ) { 
											foreach ( $myposts as $post ) {
						            			setup_postdata( $post );
						            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false ); 
						            		?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
	                                            <a href="<?php echo get_permalink($post->ID);?>">
	                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
	                                            </a>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="r-small-posts">
                                    	<?php
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 5,
									        'category'       => 3910,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 3910 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
							            	?>
                                        <li>
                                            <h2>
                                            	<?php if($post_feat_image){ ?>
                                                	<a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
                                            	<?php } ?>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->

                        <div class="category-posts c-3">
                            <div class="category f_c">
                            	<?php $category_link = get_category_link( 1141 );?>
                                <a title="Editorial" href="<?php echo $category_link;?>">Analysis</a>
                            </div>
                            <div class="cat-pst">
                                <div class="left-cp">
                                    <ul class="l-big-post">
                                    	<?php 
				                        $myposts = get_posts( array(
									        'posts_per_page' => 2,
									        'category'       => 1141,
									        'order'          => 'DESC',
									    ) );
				                        ?>
				                        <?php
                                    	if ( $myposts ) { 
											foreach ( $myposts as $post ) {
						            			setup_postdata( $post );
						            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false ); 
						            		?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
                                            <a href="<?php echo get_permalink($post->ID);?>">
                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
                                            </a>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="r-small-posts">
                                    	<?php
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 5,
									        'category'       => 1141,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 1141 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
							            	?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
                                            <div class="sp-f-m">
	                                            <a class="f-m" href="<?php echo get_permalink($post->ID);?>">
	                                                <img src="<?php echo $thumb_url[0];?>" alt=""width="80" height="52">
	                                            </a>
                                            </div>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->

                        <div class="category-posts c-4">
                            <div class="category s_c">
                            	<?php $category_link = get_category_link( 505 );?>
                                <a title="Voice-Data" href="<?php echo $category_link;?>">Voice & Data</a>
                            </div>
                            <div class="cat-pst vd-pst">
                                <div class="left-cp">
                                    <div class="vd-big-post">
                                    	<?php 
				                        $myposts = get_posts( array(
									        'posts_per_page' => 1,
									        'category'       => 505,
									        'order'          => 'DESC',
									    ) );
				                        ?>
				                        <?php
                                    	if ( $myposts ) { 
											foreach ( $myposts as $post ) {
						            			setup_postdata( $post );
						            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false );

						            		?>
						            	<?php if($post_feat_image){ ?>
						            		<div class="vd-fm">
	                                            <a href="<?php echo get_permalink($post->ID); ?>">
	                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
	                                            </a>
	                                        </div>
						            	<?php  } ?>
                                        <h2>
                                            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                        </h2>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </div><!-- /.vd-fm -->
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="vd r-small-posts">
                                    	<?php 
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 4,
									        'category'       => 505,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 505 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
	                    							
							            	?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
                                            <div class="sp-f-m">
	                                            <a class="f-m" href="<?php echo get_permalink($post->ID); ?>">
	                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="80" height="52">
	                                            </a>
                                            </div>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->
                        <?php $above_mobile = get_option('above_mobile');
                              $above_mobile_post_enable = get_option('above_mobile_post_enable');
                            if( $above_mobile_post_enable ){ ?>
                                <div class="above-mobile-cat-ad text-center">
                                    <?php echo $above_mobile;?>
                                </div>
                        <?php } ?>
                        <div class="category-posts c-5">
                            <div class="category for_c">
                            	<?php $category_link = get_category_link( 505 );?>
                                <a title="Mobiles-Tablets" href="<?php echo $category_link;?>">Mobiles & Tablets</a>
                            </div>
                            <div class="cat-pst vd-pst">
                                <div class="left-cp m-t">
                                	<?php 
			                        $myposts = get_posts( array(
								        'posts_per_page' => 2,
								        'category'       => 505,
								        'order'          => 'DESC',
								    ) );
			                        ?>
			                        <?php
                                	if ( $myposts ) { 
										foreach ( $myposts as $post ) {
					            			setup_postdata( $post );
					            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false );

					            		?>
                                    <div class="vd-big-post mt">
                                    	<?php if($post_feat_image){ ?>
                                        <div class="vd-fm">
                                            <a href="<?php echo get_permalink($post->ID); ?>">
                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
                                            </a>
                                        </div>
                                    	<?php } ?>
                                        <h2>
                                            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                        </h2>
                                    </div><!-- /.vd-fm -->
                                    <?php 
										}
										wp_reset_postdata();
									}
									?>
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="vd r-small-posts">
                                    	<?php 
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 4,
									        'category'       => 505,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 505 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
	                    							
							            	?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
                                            <div class="sp-f-m">
                                            <a class="f-m" href="#">
                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="80" height="52">
                                            </a>
                                            </div>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                            	<?php $category_link = get_category_link( 505 );?>
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->

                        <div class="category-posts c-6">
                            <div class="category s_c">
                            	<?php $category_link = get_category_link( 505 );?>
                                <a title="Editorial" href="<?php echo $category_link;?>">Broadband</a>
                            </div>
                            <div class="cat-pst vd-pst">
                                <div class="left-cp">
                                    <div class="vd-big-post">
                                    	<?php 
				                        $myposts = get_posts( array(
									        'posts_per_page' => 1,
									        'category'       => 505,
									        'order'          => 'DESC',
									    ) );
				                        ?>
				                        <?php
	                                	if ( $myposts ) { 
											foreach ( $myposts as $post ) {
						            			setup_postdata( $post );
						            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 216, 140, true, false );

						            	?>
						            	<?php if($post_feat_image){ ?>
                                        <div class="vd-fm">
                                            <a href="<?php echo get_permalink($post->ID); ?>">
                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="216" height="140">
                                            </a>
                                        </div>
                                    	<?php } ?>
                                        <h2>
                                            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                        </h2>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                    </div><!-- /.vd-fm -->
                                </div><!-- /.left-cp -->
                                <div class="right-cp">
                                    <ul class="vd r-small-posts">
                                    	<?php 
                                    	$myposts_list = get_posts( array(
									        'posts_per_page' => 4,
									        'category'       => 505,
									        'offset'         => 2,
									        'order'          => 'DESC',
									    ) );
                                    	$category_link = get_category_link( 505 );
                                    	if ( $myposts_list ) { 
	                                    	foreach ( $myposts_list as $post ) {
							            			setup_postdata( $post );
							            			$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	                    							$thumb_url = telecomtalk_aq_resize( $post_feat_image, 80, 52, true, false );
	                    							
							            	?>
                                        <li>
                                        	<?php if($post_feat_image){ ?>
                                            <div class="sp-f-m">
                                            <a class="f-m" href="<?php echo get_permalink($post->ID); ?>">
                                                <img src="<?php echo $thumb_url[0];?>" alt="" width="80" height="52">
                                            </a>
                                            </div>
                                        	<?php } ?>
                                            <h2>
                                                <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title;?></a>
                                            </h2>
                                        </li>
                                        <?php 
											}
											wp_reset_postdata();
										}
										?>
                                        
                                    </ul>
                                </div><!-- /.right-cp -->
                            </div><!-- /.cat-pst -->
                            <div class="viewmore">
                            	<?php $category_link = get_category_link( 505 );?>
                                <a href="<?php echo $category_link;?>">View More...</a>
                            </div>
                        </div><!-- /.category-posts -->
                    </div><!-- /.lnbp -->
                    <?php
                    $u_time = get_the_time('U', $first_post);
                    $u_modified_time = get_the_modified_time('U', $first_post);
                    $last_updated_date = human_time_diff(get_the_time ( 'U', $first_post ), current_time( 'timestamp' ) ) . ' ago';
                    if ($u_modified_time >= $u_time + 86400) { 
                        $updated_date = get_the_modified_time('Y-m-d', $first_post);
                        $updated_time = get_the_modified_time('H:i:s', $first_post);
                        $post_time = strtotime($updated_date.' '.$updated_time);
                        $diff = time() - $post_time;
                        $hours = date('h', $diff);
                    }
                    ?>
                    <div class="lnsp">
                        <div class="left-lnbp mobile-view">
                           <?php
                                $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 1, // Number of recent posts thumbnails to display
                                'post_status' => 'publish', // Show only the published posts
                                'category__not_in' => $featured_cat_id
                            ));
                            $first_post  = '';
                            foreach($recent_posts as $post) :
                                if (!has_category('technology-news',$post['ID']) && !has_category('hindi',$post['ID'])){ 
                                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post['ID']) );
                                    $first_post = $post['ID'];
                                    $published_date = get_the_date( 'M dS Y', $post['ID'] );
                                    ?>
                                    <?php if($post_feat_image){?>
                                    <div class="lnbp-img"> 
                                        <a href="<?php echo get_permalink($post['ID']) ?>">
                                            <img src="<?php echo $post_feat_image;?>" alt="" width="382" height="254"> 
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <div class="lnbp-cnt">
                                        <h1>
                                            <span>Just in</span>
                                            <a href="<?php echo get_permalink($post['ID']) ?>"><?php echo $post['post_title'] ?></a>
                                        </h1>
                                        <?php $latest_content = wp_trim_words(get_the_content(),31,'....'); ?>
                                        <p><?php echo do_shortcode($latest_content);?><a href="<?php echo get_permalink($post['ID']) ?>">Continue Reading </a></p>
                                        <div class="authr-info">
                                            <div class="authr-left">By
                                                <span class="athr-nm"><?php the_author(); ?></span>
                                                <span class="athr-dt"><?php echo $published_date; ?></span>
                                                <a class="athr-cmts" href="<?php echo get_comments_link( $post['ID'] );?>"><?php echo get_comments_number($post['ID']);?></a>
                                            </div>
                                            <ul class="authr-rght">
                                                <li class="st"><a href="https://twitter.com/intent/tweet?url=<?php echo get_permalink($post['ID']) ?>&text=<?php echo $post['post_title']; ?>">
                                                    <span class="icon-twitter"></span></a>
                                                </li>
                                                <li class="sw"><a href="whatsapp://send?text=<?php echo get_permalink($post['ID']); ?>"><span class="icon-whatsapp"></span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php 
                                } 
                            endforeach; 
                            wp_reset_query(); 
                            ?>
                            <?php 
                            $myposts = get_posts( array(
                                'posts_per_page' => 2,
                                'category'       => 504,
                                'order'          => 'DESC',
                            ) );
                            ?>
                        </div>
                        <?php $above_editoral_post = get_option('above_editoral_post');
                              $above_editoral_post_enable = get_option('above_editoral_post_enable');
                            if( $above_editoral_post_enable ){ ?>
                                <div class="above-editorial-ad mobile-view text-center">
                                    <?php echo $above_editoral_post;?>
                                </div>
                        <?php } ?>
                        <div class="lat_title">
                            <h3>Latest News</h3>
                        </div>
                        <?php
                        $paged = max(1, get_query_var('paged'));
						$args = array(
						   'posts_per_page' => 10,
						   'paged' => $paged,
						   'post_type' => 'post',
                           'category__not_in' => $featured_cat_id
						);
                        
                        if($paged == 1){
                            $args['offset'] = 1;
                        }
                        
						$custom_query = new WP_Query( $args );
                        $i = 1;
						while($custom_query->have_posts()) : 
						   	$custom_query->the_post();
						   	$featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
						   	$thumb_url = telecomtalk_aq_resize( $featured_img_url, 120, 85, true, false );
                            if (!has_category('technology-news',get_the_ID()) && !has_category('hindi',get_the_ID())){
                            $above_8_post_enable = get_option('above_8_post_enable');
                            $above_8_post_content = get_option('above_8_post');
                            if( $i == 8 && $above_8_post_enable ){
                                ?>
                                <div class="latest_news_ads text-center">
                                    <?php echo $above_8_post_content;?>
                                </div>
                                <?php
                            }
						?>
                        <div class="lat-news-sp">
                            <h1><a href="<?php the_permalink(); ?>"><?php echo get_the_title();?></a></h1>
                            <div class="lat-news-cnt">
                                <div class="featured-image">
                                    <img src="<?php echo $thumb_url[0];?>" alt="" width="<?php echo $thumb_url[1];?>" height="<?php echo $thumb_url[2];?>">
                                </div>
                                <div class="sp-cnt">
                                    <?php $latest_content = wp_trim_words(get_the_content(),31,'....'); ?>
                                    <p><?php echo do_shortcode($latest_content);?><a href="<?php the_permalink(); ?>">Continue Reading</a></p>
                                    <div class="authr-info">
                                        <div class="authr-left">By
                                            <span class="athr-nm"><?php the_author(); ?></span>
                                            <span class="athr-dt"><?php echo get_the_date( 'M dS Y', get_the_ID() ); ?></span>
                                            <a class="athr-cmts" href="<?php echo get_comments_link();?>"><?php echo get_comments_number();?></a>
                                        </div>
                                        <ul class="authr-rght">
                                            <li class="st"><a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>"><span class="icon-twitter"></span></a></li>
                                            <li class="sw"><a href="whatsapp://send?text=<?php the_permalink(); ?>"><span class="icon-whatsapp"></span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    	<?php
                            $below_2_post_enable = get_option('below_2_post_enable');
                            $below_2_post_content = get_option('below_2_post');
                            if( $i == 2 && $below_2_post_enable ){
                                ?>
                                <div class="latest_news_ads text-center">
                                    <?php echo $below_2_post_content;?>
                                </div>
                                <?php
                            }
                            $below_3_post_enable = get_option('below_3_post_enable');
                            $below_3_post_content = get_option('below_3_post');

                            if( $i == 4 && $below_3_post_enable ){
                                ?>
                                <div class="latest_news_ads text-center">
                                    <?php echo $below_3_post_content;?>
                                </div>
                                <?php
                            }
                            $i++; 
                        } 
                        endwhile;
                        wp_reset_query();?>
                        <div class="pagination">
                            <?php
                                $total_pages = $custom_query->max_num_pages;
                                if ($total_pages > 1){

                                    $current_page = max(1, get_query_var('paged'));

                                    echo paginate_links(array(
                                        'base' => get_pagenum_link(1) . '%_%',
                                        'format' => '/page/%#%',
                                        'current' => $current_page,
                                        'total' => $total_pages,
                                        'prev_text'    => __('« Prev Page'),
                                        'next_text'    => __('Next Page »'),
                                        'type'  => 'list',
                                        'add_args'  => array()
                                    ));
                                }
                            ?>
                        </div>
                    </div> <!-- /.lnsp -->

                </div>
            </div><!-- /.left-part -->
            <?php get_sidebar();?>
        </div><!-- /.main-cnt -->
    </div><!-- /.container -->     
</main>
<?php get_footer(); ?>

