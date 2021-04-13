<?php
/*
 * Template Name: Comments Template
 * Template Post Type: post, page
 */
get_header();
// Start the Loop.
?>
	<div class="templ-pages comments-page">
		<div class="container">
			<main id="main" class="site-main" role="main">
				<div class="main-cnt">
					<div class="main-left">
						<div class="left-part">
							<h3 class="widget-title">Comments</h3>
							<ul>
							<?php  $recent_comments = get_comments( array( 
				                    'number'      => 100, // number of comments to retrieve.
				                    'status'      => 'approve', // we only want approved comments.
				                    'post_status' => 'publish' // limit to published comments.
				                ) );

					            if ( $recent_comments ) {
					                // print_r($recent_comments); die;
					                // echo '<pre>';
					                foreach ( $recent_comments as $comment ) {
					                    $id = $comment->comment_ID;
					                    $url     = get_comment_link($id);
					                    //echo get_avatar( $id );
					                    $comment_url = preg_replace('/(.*?)\/#comment-\d+/u', '$1/#comments', $url);
					                    $comment_link = '<a href="'.esc_url( $comment_url ).'" target="_blank" >...</a>';
					                    $word_count = str_word_count($comment->comment_content);
					                ?>
					                <li>
						                <div class="athr-image">
					                        <?php echo get_avatar( $id );?>
					                    </div>
					                    <div class="athr-details">
					                        <div class="tlt-cmnt">
					                            <span><?php echo $comment->comment_author;?></span>
					                            <?php if($word_count > 15){ ?>
					                            	<p><?php echo wp_trim_words($comment->comment_content);?></p>
					                            <?php }else{ ?>
					                            	<p><?php echo $comment->comment_content;?></p>
					                            <?php } ?>
					                        </div>
					                        <h4 class="post-tlt">
					                            <?php echo '<a href="'.esc_url( $comment_url ).'">'. wp_trim_words($comment->post_title).'</a>';?>
					                        </h4>
					                        <?php
					                        	$d = "F jS, Y";
												$comment_date = get_comment_date( $d, $comment_ID );
					                        	$t = get_comment_time( 'h:i:s A' );
												echo '<span class="cmnt-date">'. $comment_date . '&nbsp | ' . $t . '</span>';
					                        ?>
					                        <span><?php //echo $comment->comment_date_gmt;?></span>
					                    </div>
					                </li>
					            <?php }
					            } else { ?>
					                <li><p>No Recent Comments....!</p></li>
					            <?php
					            } ?>
					        </ul>
						</div><!-- /.left-part -->
					</div><!-- /.main-left -->
					<div class="right-part">
						<?php if ( is_active_sidebar( 'archives-sidebar' )  ) : ?>
	                        <aside class="activity-sidebar widget-area" role="complementary">
	                            <?php dynamic_sidebar( 'archives-sidebar' ); ?>
	                        </aside><!-- .sidebar .widget-area -->
                    	<?php endif; ?>
					</div><!-- /.right-part -->
				</div><!-- /.main-cnt -->
				
			</main>
		</div><!-- /.container -->
	</div><!-- /.templ-pages -->
<?php	
get_footer();
?>
