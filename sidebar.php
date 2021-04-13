<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

	<div class="right-part">
        
        <div class="editors_picks">
            <div class="editors_title widget_title">
                <h3>Editors Pick</h3>
            </div>
            <?php 
            $post_id = get_option('editors_pick_option');
            $post_data   = get_post( $post_id );
            ?>
            <div class="editors">
                <?php if(!empty($post_id)){
                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_data->ID) );
                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 340, 180, true, false );
                    ?>
                    <?php if( $post_feat_image){?>
                    <div class="featured-image">
                        <a href="<?php echo get_permalink($post_data->ID);?>"><img src="<?php echo $thumb_url[0];?>" alt="" width="140" height="180"></a>
                    </div>
                    <?php } ?>
                <h4><a href="<?php echo get_permalink($post_data->ID);?>"><?php echo $post_data->post_title;?></a></h4>
                <?php  } ?>
                <ul id="accordion">
                    <li>
                        <input type="radio" name="accordion" id="first" checked="">
                        <label for="first">News Tip</label>
                        <div class="f_content">
                            <p>Have a breaking news, inside story, scoop?</p>
                            <p>Write to us, your anonymity is our priority at news [at] telecomtalk.info</p>
                        </div>
                    </li>
                    <li>
                        <input type="radio" name="accordion" id="second">
                        <label for="second">Submit Your Story</label>
                        <div class="f_content">
                            <p>Want to be featured on TelecomTalk?</p>
                            <p>Send us your articles, stories, suggestions, feedback at news [at] telecomtalk.info</p>            
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- /.editors_picks -->
        <?php $above_pan_post = get_option('above_pan_post');
              $above_pan_post_enable = get_option('above_pan_post_enable');
            if( $above_pan_post_enable && techblog_checkContentLength() ){ ?>
                <div class="above-editorial-ad sidebar text-center">
                    <?php echo $above_pan_post;?>
                </div>
        <?php } ?>
        <div class="editors_picks">
            <div class="editors_title widget_title">
                <h3>Pan India Spectrum Details</h3>
            </div>
            <?php 
            $post_id = get_option('pan_india_option');
            $post_data   = get_post( $post_id );
            //print_r($post_id); die;

            ?>
            <div class="editors">
                <?php if(!empty($post_id)){ 
                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_data->ID) );
                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 340, 180, true, false );
                    $image_path = $thumb_url[0];
                ?>
                <?php if($thumb_url){ ?>
                <div class="featured-image">
                    <a href="<?php echo get_permalink($post_data->ID);?>">
                        <img src="<?php echo $thumb_url[0];?>" alt="" width="<?php echo $thumb_url[1];?>" height="<?php echo $thumb_url[2];?>">
                    </a>
                </div>
                <?php } ?>
                <h4><a href="<?php echo get_permalink($post_data->ID);?>"><?php echo $post_data->post_title;?></a></h4>
                <?php } ?>
            </div>
        </div><!-- /.editors_picks -->

        <div class="editors_picks">
            <div class="editors_title widget_title">
                <h3>Search</h3>
            </div>
            <div class="editors search-bar">
                <?php echo get_option('search_box_option');?>
            </div>
        </div><!-- /.editors_picks -->

        <div class="editors_picks">
            <div class="editors_title widget_title">
                <h3>Telecommunication Frequency Bands</h3>
            </div>
            <?php 
            $post_id = get_option('frequency_band_option');
            $post_data   = get_post( $post_id );
            ?>
            <div class="editors">
                <?php if(!empty($post_id)){ 
                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_data->ID) );
                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 340, 180, true, false );
                ?>
                <?php if($post_feat_image){ ?>
                <div class="featured-image">
                    <a href="<?php echo get_permalink($post_data->ID);?>"><img src="<?php echo $thumb_url[0];?>" alt="" width="340" height="180"></a>
                </div>
                <?php } ?>
                <h4><a href="<?php echo get_permalink($post_data->ID);?>"><?php echo $post_data->post_title;?></a></h4>
                <?php } ?> 
            </div>
        </div><!-- /.editors_picks -->

        <div class="editors_picks">
            <div class="editors_title widget_title">
                <h3>DTH Satellites in India</h3>
            </div>
            <?php 
            $post_id = get_option('dth_satellite_band_option');
            $post_data   = get_post( $post_id );
            ?>
            <div class="editors">
                <?php if(!empty($post_id)){ 
                    $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_data->ID) );
                    $thumb_url = telecomtalk_aq_resize( $post_feat_image, 340, 180, true, false );
                ?>
                <?php if($post_feat_image){ ?>
                <div class="featured-image">
                    <a href="<?php echo get_permalink($post_data->ID);?>"><img src="<?php echo $thumb_url[0];?>" alt="" width="340" height="180"></a>
                </div>
                <?php } ?>
                <h4><a href="<?php echo get_permalink($post_data->ID);?>"><?php echo $post->post_title;?></a></h4>
                <?php } ?>
            </div>
        </div><!-- /.editors_picks -->
        <?php $above_recent_comments = get_option('above_recent_comments');
              $above_recent_comments_post_enable = get_option('above_recent_comments_post_enable');
            if( $above_recent_comments_post_enable && techblog_checkContentLength() ){ ?>
                <div class="above-editorial-ad sidebar text-center">
                    <?php echo $above_recent_comments;?>
                </div>
        <?php } ?>
        <div class="r-c">
            <div class="editors_title widget_title">
                <h3>Recent Comments</h3>
            </div>
            <ul>
                <?php 
                $recent_comments = get_comments( array( 
                    'number'      => 5, // number of comments to retrieve.
                    'status'      => 'approve', // we only want approved comments.
                    'post_status' => 'publish' // limit to published comments.
                ) );

            if ( $recent_comments ) {
                // print_r($recent_comments); die;
                // echo '<pre>';
                foreach ( $recent_comments as $comment ) {
                    $id = $comment->comment_ID;
                    $cmt_post_id = $comment->comment_post_ID;
                    //$url     = get_comment_link($id);
                    $url     = get_permalink($cmt_post_id);
                    //echo get_avatar( $id );
                    //$comment_url = preg_replace('/(.*?)\/#comment-\d+/u', '$1/#comments', $url);
                    $comment_url = $url.'#comments';
                    $comment_link = '<a href="'.esc_url( $comment_url ).'" target="_blank" >...</a>';
                    $word_count = str_word_count($comment->comment_content);
                ?>
                <li>
                    <div class="athr-image">
                        <?php echo get_avatar( $id );?>
                    </div>
                    <div class="athr-details">
                        <div class="tlt-cmnt">
                            <h3><?php echo $comment->comment_author;?> :</h3>
                            <?php if($word_count > 15){ ?>
                                <p><?php echo wp_trim_words($comment->comment_content,20);?></p>
                            <?php }else{ ?>
                            <p><?php echo $comment->comment_content;?></p>
                            <?php } ?>
                        </div>
                        <h4 class="post-tlt">
                            <?php echo '<a href="'.esc_url( $comment_url ).'">'. wp_trim_words($comment->post_title,10).'</a>';?>
                        </h4>
                    </div>
                </li>
            <?php }
            }else{
                ?>
                <li>
                    <p>No Recent Comments....!</p>
                </li>
            <?php
            } 
            ?>
                <a class="load-more" href="https://aviationscoop.com/comments/">Load More</a>
            </ul>
        </div>

        <div id="tab-section" class="tabs">
            <button class="tablinks active" onmouseover="openCity(event, 'Home')"i d="defaultOpen"><b>Most Discussed</b></button>
            <button class="tablinks" onmouseover="openCity(event, 'News')"><b>Trending</b></button>
            <?php
	            $most_discussed = get_option('mdcomments_interval','1 week ago');
	            
	            $args = array(
				    'date_query' => array(
				        'after' => $most_discussed,
				        'before' => 'tomorrow',
				        'inclusive' => true,
				    ),
				);
	 			$most_discussed_posts = array(); 
	 			$comment_posts = array();
	 			$comment_count = $post_url = $tct_post_title = $ckey = '';
				$comments = get_comments( $args );
               
				foreach ( $comments as $comment ) {
					$most_discussed_posts[] = $comment->comment_post_ID;
				}
				$most_discussed_posts = array_unique($most_discussed_posts);

				if(count($most_discussed_posts)>0){
					foreach ($most_discussed_posts as $pval) {
						$post_comment_count = get_comments_number($pval);
						$comment_posts[$post_comment_count] = $pval;
					}
				}
				
            ?>
			<div id="Home" class="tabcontent most-discussed-post">
				<ul>
                    <?php if(!empty($comment_posts)){
                    	krsort($comment_posts);
                    	$i = 0;
                    	foreach ($comment_posts as $mdkey => $mdvalue) {
                    		$tct_post_title = get_the_title($mdvalue);
							$post_url = get_post_permalink($mdvalue);
							$comment_count = $mdkey;
							if($i == 5){
								break;
							}
                    		?>
                    		<li class="common">
						  		<span class="cmt-tlt">
						  			<a href="<?php echo $post_url; ?>"><?php echo $tct_post_title; ?></a></span>
						  		<span class="cmt-nmbr"><?php echo $comment_count;?></span>
						  	</li>
                    		<?php
                    		$i++;
                    	}
                    ?>
                    <?php }else{ ?>
                    <li class="common">
                        <span class="cmt-tlt">No Most discussed posts - <?php echo $most_discussed;?>....!</span>
                    </li>
                    <?php } ?>
			  	</ul>
			</div>
			<?php
		        $args = array(
		            'date_query' => array(
		                'after' => '1 weeks ago',
		                'before' => 'tomorrow',
		                'inclusive' => true,
		            ),
		        );
		        $commeted_posts = array();
		        $trending_posts = '';
		        $comments = get_comments( $args );
		        foreach ($comments as $key => $value) {
		           $commeted_posts[] = $value->comment_post_ID;
		        }
		        $trending_posts = array_unique($commeted_posts);
	        ?>
			<div id="News" class="tabcontent trending-posts" style="display: none;">
                <ul>
                    <?php
                    $i = 1;
                    if(count($trending_posts)>0){
                        foreach ($trending_posts as $tkey => $tvalue) {  
                        ?>
                    	<li>
                    		<span class="cmt-tlt">
                    			<a href="<?php echo get_post_permalink($tvalue);?>"><?php echo get_the_title($tvalue);?></a>
                    		</span>
                        <span class="cmt-nmbr"><?php //echo $comment_count;?></span>
                    	</li>
                    <?php
                            if($i == 5){
                                break;
                            }
                            $i++;
                        } 
                    }else{
                        ?>
                        <li style="margin-bottom: 10px;">
                            <span class="cmt-tlt">No Trending posts - 1 weeks ago....!</span>
                        </li>
                    <?php } ?>
                </ul>
			</div>
		</div><!-- /.tabs -->

		<div class="news-latter">
            <div class="editors_title widget_title">
                <h3>SUBSCRIBE TO OUR NEWSLETTER</h3>
            </div>
            <form class="nws" action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow">
                <input class="email-field" placeholder="Enter your email address.." name="email" type="text" />
                <input name="uri" type="hidden" value="telecomtalk/CIeV" />
                <input name="loc" type="hidden" value="en_US" />
                <input class="subscribe" type="submit" value="Subscribe" />
            </form>
		</div>

        <?php
        $category = get_term_by('name', 'featured', 'category');
        $featured_cat_id = $category->term_id;
        $tech_news = get_option('tech_news_widget',1);
        if($tech_news){
            ?>
            <div class="tech-news">
                <div class="editors_title widget_title">
                    <h3>Tech News</h3>
                </div>
                <ul>
                    <?php
                    $args = array(
                        'numberposts' => 5, // Number of recent posts thumbnails to display
                        'post_status' => 'publish', // Show only the published posts
                        //'category__not_in' => 507,
                    );
                    if(is_front_page()){
                        $args['category__not_in'] = $featured_cat_id;
                    }
                    $recent_posts = wp_get_recent_posts($args);
                    
                    foreach($recent_posts as $post) : 
                        $post_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post['ID']) );
                        $thumb_url = telecomtalk_aq_resize($post_feat_image, 340, 180, true, false);
                        if (has_category('technology-news',$post['ID']) && has_category('hindi',$post['ID'])){
                    ?>
                    <li>
                        
                        <a href="<?php echo get_permalink($post['ID']) ?>">
                            <?php if($post_feat_image){ ?>
                            <div class="tn-img">
                                <img src="<?php echo $thumb_url[0];?>" alt="" width="340" height="180">
                                <span><?php echo $post_comment_count = get_comments_number($post['ID']); ?></span>
                            </div>
                            <?php } ?>
                            <h4 class="tn-tlt"><?php echo $post['post_title'] ?>
                                
                            </h4>
                        </a>
                    </li>
                    <?php
                    	} 
                	endforeach; wp_reset_query(); ?>
                </ul>
            </div>
            <?php

        } ?>
        
        <?php $above_category = get_option('above_category');
              $above_category_post_enable = get_option('above_category_post_enable');
            if( $above_category_post_enable && techblog_checkContentLength() ){ ?>
                <div class="above-category-ad text-center">
                    <?php echo $above_category;?>
                </div>
        <?php } ?>
        <div class="category-section">
            <div class="editors_title widget_title">
                <h3>CATEGORIES</h3>
            </div>
            <ul>
            	<?php 
            	for($i=1;$i<=24;$i++){
            		$cat_link = get_option('cat_link_'.$i);
            		$cat_title = get_option('cat_title_'.$i);
            		if(!empty($cat_link) && !empty($cat_title)){
            		?>
            		<li><a href="<?php echo $cat_link;?>"><?php echo $cat_title;?></a></li>
            		<?php
            		}
            	}
            	?>
            </ul>
        </div>
    </div><!-- /.right-part -->
<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>

