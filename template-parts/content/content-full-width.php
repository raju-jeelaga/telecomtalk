

<h1 class="headline">
	<?php echo get_the_title();?>
</h1>

<div class="byline">
	<span class="post_author_intro">Reported by</span> 
	<span class="post_author"><?php echo get_author_name(); //the_author_posts_link();?></span>
	<?php 
	$total_count = '';
	
	if(!empty($categories) && $categories[0]->term_id != 1){ ?>
		<span class="post_cats">
			<?php
			$total_count = count($categories);
			$i = 1;
			//print_r($categories);
			foreach ($categories as $key => $catobj) {
				if( $catobj->term_id == 1 ){
					continue;
				}
			?>
				<a href="<?php echo esc_url( get_category_link( $catobj->term_id ) );?>" rel="category tag"><?php echo esc_html( $catobj->name );?></a>
			<?php 
				if($i < 2 && $total_count > 2){
					echo ", ";
				}
				if($i == 2){
					break;
				}
				$i++;
			} 
			?>	
		</span>
	<?php 
	}
	$u_time = get_the_time('U'); 
	$u_modified_time = get_the_modified_time('U');
	$published_time = get_post_time('h:i a');
	$published_date = get_the_date( 'F jS, Y' );
	$updated_date = '';
	$updated_time = '';
	if ($u_modified_time >= $u_time + 86400) { 
		$updated_date = get_the_modified_time('F jS, Y');
		$updated_time = get_the_modified_time('h:i a'); 
	} ?>
		<span class="post_date">
			<time class="entry-date"><?php echo $published_date;?> at <?php echo $published_time; ?></time>
		</span>
		<a class="num_comments_link" href="#comments" rel="noreferrer">
			<img class="cmt-image" src="data:image/svg+xml;base64,PHN2ZyBpZD0iSWNvbnMiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNzQgNzQiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0ibTMzLjUyIDkuMjloLTIuOTFhMSAxIDAgMCAxIDAtMmgyLjkxYTEgMSAwIDEgMSAwIDJ6Ii8+PHBhdGggZD0ibTE3IDY2LjcxYTEgMSAwIDAgMSAtMS0xdi0xMS40MmgtOWE1LjAwNiA1LjAwNiAwIDAgMSAtNS01di0zN2E1LjAwNiA1LjAwNiAwIDAgMSA1LTVoMTYuNjFhMSAxIDAgMSAxIDAgMmgtMTYuNjFhMyAzIDAgMCAwIC0zIDN2MzdhMyAzIDAgMCAwIDMgM2gxMGExIDEgMCAwIDEgMSAxdjEwLjIzOWwxMi43NjctMTFhMSAxIDAgMCAxIC42NTMtLjI0MmgzNS41OGEzIDMgMCAwIDAgMy0zdi0zN2EzIDMgMCAwIDAgLTMtM2gtMTYuNTFhMSAxIDAgMCAxIDAtMmgxNi41MWE1LjAwNiA1LjAwNiAwIDAgMSA1IDV2MzdhNS4wMDYgNS4wMDYgMCAwIDEgLTUgNWgtMzUuMjA5bC0xNC4xMzggMTIuMTgxYTEgMSAwIDAgMSAtLjY1My4yNDJ6Ii8+PHBhdGggZD0ibTQzLjQ5IDkuMjloLTIuOTdhMSAxIDAgMCAxIDAtMmgyLjk3YTEgMSAwIDEgMSAwIDJ6Ii8+PHBhdGggZD0ibTU5Ljc1IDIyaC00NS41YTEgMSAwIDAgMSAwLTJoNDUuNWExIDEgMCAwIDEgMCAyeiIvPjxwYXRoIGQ9Im01OS43NSAzMmgtNDUuNWExIDEgMCAwIDEgMC0yaDQ1LjVhMSAxIDAgMCAxIDAgMnoiLz48cGF0aCBkPSJtNTkuNzUgNDJoLTQ1LjVhMSAxIDAgMCAxIDAtMmg0NS41YTEgMSAwIDAgMSAwIDJ6Ii8+PC9zdmc+" alt="" width="16" height="16"/>
			<span class="num_comments"><?php echo get_comments_number(get_the_ID());?></span>
		</a>
</div><!-- /.byline -->


<?php 
$tct_fields = get_post_meta( get_the_ID(), 'telecomtalk_custom_fields', true );
if(isset($tct_fields['enable_sub_heading']) && !empty($tct_fields['enable_sub_heading']) ){ ?>
	<h2 class="sub-title"><?php echo $tct_fields['sub_heading'];?></h2>
<?php } ?>
<?php
	$author_id = get_post_field( 'post_author', get_the_ID() );
	$author_name = get_the_author_meta('user_nicename', $author_id);
?>

<?php $below_subtitle_ad = get_option('below_subtitle_ad');
	  $below_subtitle_post_enable = get_option('below_subtitle_post_enable');
	if( $below_subtitle_post_enable && techblog_checkContentLength() ){ ?>
	    <div class="below-subtitle-ad text-center">
	        <?php echo $below_subtitle_ad;?>
	    </div>
	<?php } ?>

<div class="highlights-content-wrap">
	<div class="h-c">
		
		<div class="post_content">
			<?php
			$tct_also_read = get_option('tct_also_read',0);
			$post_full = $post->post_content;
			$post_full = do_shortcode($post_full);
			if( $tct_also_read ){
				echo wpautop(tct_also_read_section($post_full, get_the_ID()));
			}else{
				echo wpautop($post_full);
			}
			?>
			<?php if(isset($tct_fields['view_source_label']) && !empty($tct_fields['view_source_label']) ){ ?>
		    <div class="via-source">
			    	<span><?php echo $tct_fields['view_source_label'];?></span>
			    <?php if( (isset( $tct_fields['view_source_link']) && !empty($tct_fields['view_source_link']))  && (isset( $tct_fields['view_source_link']) && !empty($tct_fields['view_source_link'])) ){
			    	?>
			    	<a rel="noreferrer" target="_blank" href="<?php echo $tct_fields['view_source_link'];?>"><?php echo $tct_fields['view_source_txt'];?> </a>
			    <?php } ?>
	    	</div>
	    	 <?php } ?>

	    	 <?php $above_social_icons_ad = get_option('above_social_icons_ad');
				  $above_social_icons_post_enable = get_option('above_social_icons_post_enable');
				if( $above_social_icons_post_enable && techblog_checkContentLength() ){ ?>
				    <div class="above-social-icons-ad text-center">
				        <?php echo $above_social_icons_ad;?>
				    </div>
				<?php } ?>
			<div class="social-icons">
				<ul>
					<li><a rel="noreferrer" target="_blank" class="facebook" href="https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>">
						<span class="icon-facebook"></span></a>
					</li>
					<li><a rel="noreferrer" target="_blank" class="twitter" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>">
						<span class="icon-twitter"></span></a>
					</li>
					<li><a rel="noreferrer" target="_blank" class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=&title=&summary=&source=<?php the_permalink(); ?>"><span class="icon-linkedin2"></span></a>
					</li>
					<li><a rel="noreferrer" target="_blank" class="whatsapp" href="whatsapp://send?text=<?php the_permalink(); ?>"><span class="icon-whatsapp"></span></a>
			        </li>
			        <li><a rel="noreferrer" target="_blank" class="telegram" href="https://telegram.me/share/url?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>">
						<span class="icon-telegram"></span></a>
					</li>
					<li class="subscribe-image">
						<span>Subscribe</span>
						<a rel="noreferrer" href="https://news.google.com/publications/CAAqBwgKMIGRjQswq7KeAw?hl=en-IN&gl=IN&ceid=IN%3Aen">
							<img src="<?php echo get_template_directory_uri() . '/images/google-news.svg'; ?>" alt="" width="98" height="24"/>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!-- /.h.c -->


    
    <div class="authr-pagi">
	    <div class="author-meta">
	    	<?php 
	    	$user = wp_get_current_user();
	    	$designation = get_the_author_meta('designation');
	    	$photo_url = get_the_author_meta('photo_url');
	    	$twitterprofilelink = get_user_meta($post_author_id, 'twitterprofilelink', true);
			$linkedusername = get_user_meta($post_author_id, 'linkedusername', true);
	    	?>
	    	<div class="report-name">
	    		<h2>Reported By</h2>
	    		<div class="rp-part">
		    		<div class="reporter"><?php echo get_the_author_meta( 'display_name');?>
	    				<span class="desig"><?php echo $designation; ?></span>
	    			</div>
	    			<div class="share-icons">
						<ul>
							<li><a rel="noreferrer" target="_blank" class="fa-twitt" href="https://twitter.com/intent/user?screen_name=<?php echo $twitterprofilelink;?>"><span class="icon-twitter"></span></a></li>
								<li><a rel="noreferrer" target="_blank" class="fa-linkd" href="https://www.linkedin.com/in/<?php echo $linkedusername;?>"><span class="icon-linkedin2"></span></a></li>
						</ul>
					</div><!-- /.share-icons -->
				</div>
	    	</div>
	    	<div class="repr-by">
	    		<div class="auth-img">
	    			<?php 
	    			if(!empty($photo_url)){
	    				echo '<img src="'.$photo_url.'" alt="" width="100" height="100" />';
	    			}else{
	    				echo get_avatar( get_the_author_meta( 'ID' ), 100 ); 
	    			}
	    			?>
	    		</div>
	    		<div class="auth-info">
	    			<p><?php echo get_the_author_meta( 'description' );?></p>
	    		</div>
	    	</div>
	    </div>
	    <?php   $above_pagination_ad = get_option('above_pagination_ad');
				$above_pagination_post_enable = get_option('above_pagination_post_enable');
				if( $above_pagination_post_enable && techblog_checkContentLength() ){ ?>
				    <div class="above-pagination-ad text-center">
				        <?php echo $above_pagination_ad;?>
				    </div>
				<?php } ?>
	    <div class="nav-next-post">
			<?php 
				$next_post = get_next_post();
				$previous_post = get_previous_post();
				$next_feat_image = wp_get_attachment_url( get_post_thumbnail_id($next_post->ID) );
				$previous_feat_image = wp_get_attachment_url( get_post_thumbnail_id($previous_post->ID) );
				$next_thumb_url = telecomtalk_aq_resize( $next_feat_image, 135, 80, true, false );
				$prev_thumb_url = telecomtalk_aq_resize( $previous_feat_image, 135, 80, true, false );
				$next_img = $prev_img = '';
				if(!empty($next_thumb_url)){
					$next_img = '<img src="'.$next_thumb_url[0].'" alt="" width="135" height="80">';
				}
				if( !empty($prev_thumb_url)){
					$prev_img = '<img src="'.$prev_thumb_url[0].'" alt="" width="135" height="80">';
				}
			    the_post_navigation( array(
			        'next_text' =>
			            '<label>Next Post</label><div class="nav-cls"><span class="nxt-img">'.$next_img.'</span><span class="post-title">%title</span></div>',
			        'prev_text' => '<label>Previous Post</label><div class="nav-cls"><span class="nxt-img">'.$prev_img.'</span><span class="post-title">%title</span></div>',
			) ); ?>
		</div>
	</div><!-- /.authr-pagi -->

</div><!-- /.highlights-wrap -->
<div class="videos-section">
	<h3>Videos</h3>
	<ul>
		<?php 
		for($i=1;$i<=3;$i++){
			$video_title = "video_title_".$i;
			$cover_image = "image_url_".$i;
			$video_link = "video_link_".$i;
			$image_path = get_option($cover_image);
			$thumb_url = telecomtalk_aq_resize( $image_path, 260, 150, true, false );
			if(!empty(get_option($video_link))){
		?>
			<li>
				<div class="video-<?php echo $i;?>">
					<?php if(!empty(get_option($cover_image))){ ?>
					<a class="ov" href="<?php echo get_option($video_link);?>" rel="noreferrer" target="_blank">
						<img src="<?php echo $thumb_url[0];?>" alt="" width="260" height="150"/>
						<span class="v-oy"></span>
						<span class="v-icon"><span class="icon-play2"></span></span>
					</a>
					<?php } ?>
					<h4><a href="<?php echo get_option($video_link);?>" rel="noreferrer" target="_blank"><?php echo get_option($video_title);?></a></h4>
				</div>
			</li>
		<?php
			}
		}
		?> 
	</ul>
</div><!-- /.videos-section -->
<?php   $above_rp_ad = get_option('above_rp_ad');
		$above_rp_post_enable = get_option('above_rp_post_enable');
		if( $above_rp_post_enable && techblog_checkContentLength() ){ ?>
		    <div class="above-rp-ad text-center">
		        <?php echo $above_rp_ad;?>
		    </div>
		<?php } ?>
<?php
$post_cats = wp_get_post_categories( get_the_ID() );
$cats = array();
if($post_cats){
	foreach ($post_cats as $c) {
		$cat = get_category( $c );
		$cats[] = $cat->cat_ID;
		$args=array(
			'category__in' => $cats,
			'post__not_in' => array(get_the_ID()),
			'posts_per_page'=>4, // Number of related posts that will be shown.
			'ignore_sticky_posts'=>1
		);
		$my_query = new wp_query( $args );
		if( $my_query->have_posts() ) {
			?>
		<div class="related-posts">
			<h3>Related Posts</h3>
			<ul>
			<?php $i = 1; 
			while( $my_query->have_posts() ) {
				$my_query->the_post(); 
				$post_feat_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
				$thumb_url = telecomtalk_aq_resize( $post_feat_image, 108, 80, true, false );
				?>
				<li>
					<div class="rp-sec">
						<div class="rp-tlt">
							<h4><a href="<?php the_permalink()?>"><?php the_title(); ?></a></h4>
						</div>
						<?php if($post_feat_image){ ?>
						<div class="rp-img">
							<a href="<?php the_permalink()?>"><img src="<?php echo $thumb_url[0];?>" alt="" width="108" height="80"></a>
						</div>
						<?php } ?>
					</div>
				</li>
			<?php $i++; }  
			wp_reset_postdata();
			?>
			</ul>
		</div>
			<?php
		}
		break;
	}
} // Releated posts  ends here
?>
<?php   $below_rp_ad = get_option('below_rp_ad');
		$below_rp_post_enable = get_option('below_rp_post_enable');
		if( $below_rp_post_enable && techblog_checkContentLength() ){ ?>
		    <div class="below-rp-ad text-center">
		        <?php echo $below_rp_ad;?>
		    </div>
		<?php } ?>