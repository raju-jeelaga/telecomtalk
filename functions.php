<?php

function tct_popular_posts(){
	$args = array( 
		'no_found_rows' => 1, 
		'post_status' => 'publish',		
		'orderby' => 'comment_count',		
		'posts_per_page' => 5 
	);
	$popular_posts = new WP_Query( $args );
	$comment_post_list = '';
	if($popular_posts->have_posts()){
		$comment_post_list .= '<ul>';
		while ( $popular_posts->have_posts() ) : $popular_posts->the_post();
			$comment_count = get_comments_number(get_the_ID());
			$comment_post_list .= '<li class="common">';
			$comment_post_list .= '<span class="cmt-tlt">
				  			<a href="'.get_permalink().'">'.get_the_title().'</a></span>
				  		<span class="cmt-nmbr">'.$comment_count.'</span>';
			$comment_post_list .= '</li>';
		endwhile;
		$comment_post_list .= '</ul>';
		wp_reset_query();
	}else{
		$comment_post_list .= '<ul>';
		$comment_post_list .= '<li class="common">
                        <span class="cmt-tlt">No Most discussed posts - 0....!</span>
                    </li>';
		$comment_post_list .= '</ul>';
	}
	return $comment_post_list;
}
/*Comments Submit in Ajax*/

function has_comment_children( $comment_id ) {
    return get_comments( [ 'parent' => $comment_id, 'count' => true ] ) > 0;
}

//add_action( 'wp_ajax_ajaxcomments', 'tct_submit_ajax_comment' );
//add_action( 'wp_ajax_nopriv_ajaxcomments', 'tct_submit_ajax_comment' );
function tct_submit_ajax_comment(){
	/*
	 * Wow, this cool function appeared in WordPress 4.4.0, before that my code was muuuuch mooore longer
	 *
	 * @since 4.4.0
	 */
	$comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
	if ( is_wp_error( $comment ) ) {
		$error_data = intval( $comment->get_error_data() );
		if ( ! empty( $error_data ) ) {
			wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $error_data, 'back_link' => true ) );
		} else {
			wp_die( 'Unknown error' );
		}
	}
 
	/*
	 * Set Cookies
	 */
	$user = wp_get_current_user();
	do_action('set_comment_cookies', $comment, $user);
 
	/*
	 * If you do not like this loop, pass the comment depth from JavaScript code
	 */
	$comment_depth = 1;
	$comment_parent = $comment->comment_parent;
	while( $comment_parent ){
		$comment_depth++;
		$parent_comment = get_comment( $comment_parent );
		$comment_parent = $parent_comment->comment_parent;
	}
 
 	/*
 	 * Set the globals, so our comment functions below will work correctly
 	 */
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $comment_depth;
	$args['max_depth'] = get_option( 'thread_comments_depth' );
	/*
	 * Here is the comment template, you can configure it for your website
	 * or you can try to find a ready function in your theme files
	 */
	$tag = 'div';
	?>
	<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?> id="comment-<?php get_comment_ID(); ?>" <?php comment_class( has_comment_children(get_comment_ID()) ? 'parent' : '', $comment ); ?>>
				<article id="div-comment-<?php get_comment_ID(); ?>" class="comment-body">
					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$comment_author_url = get_comment_author_url( $comment );
							$comment_author     = get_comment_author( $comment );
							$avatar             = get_avatar( $comment, 120 );
							if ( 0 !== $args['avatar_size'] ) {
								if ( empty( $comment_author_url ) ) {
									echo wp_kses_post( $avatar );
								} else {
									printf( '<a href="%s" rel="external nofollow" class="url">', $comment_author_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Escaped in https://developer.wordpress.org/reference/functions/get_comment_author_url/
									echo wp_kses_post( $avatar );
								}
							}

							printf(
								'<span class="fn">%1$s</span>:<span class="screen-reader-text says">%2$s</span>',
								esc_html( $comment_author ),
								__( 'says:', 'twentytwenty' )
							);

							if ( ! empty( $comment_author_url ) ) {
								echo '</a>';
							}
							?>
						</div><!-- .comment-author -->

						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<?php
								/* translators: 1: Comment date, 2: Comment time. */
								$comment_timestamp = sprintf( __( '%1$s at %2$s', 'twentytwenty' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
								<time datetime="<?php comment_time( 'c' ); ?>" title="<?php echo esc_attr( $comment_timestamp ); ?>">
									<?php echo esc_html( $comment_timestamp ); ?>
								</time>
							</a>
							<?php
							if ( get_edit_comment_link() ) {
								echo ' <span aria-hidden="true">&bull;</span> <a class="comment-edit-link" href="' . esc_url( get_edit_comment_link() ) . '">' . __( 'Edit', 'twentytwenty' ) . '</a>';
							}
							?>
						</div><!-- .comment-metadata -->
						<?php $city = get_comment_meta( $comment->comment_ID, 'city', true ); ?>
						<div class="athr-city">
							<?php
							if(!empty($city)){ ?>
								<span class="city-name"><?php echo esc_html($city);?></span>
							<?php } ?>
						</div>
					</footer><!-- .comment-meta -->
					<?php
						$tct_comment_image = get_comment_meta( $comment->comment_ID, 'tct_comment_image', true );
					?>
					<div class="comment-content entry-content">
						
						<?php
						echo wpautop($comment->comment_content);

						if ( '0' === $comment->comment_approved ) {
							?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwenty' ); ?></p>
							<?php
						}

						?>
						<?php if(!empty($tct_comment_image)): ?>
							<div class="cmt-image">
								<a href="#open-modal-<?php echo $comment->comment_ID;?>"><img src="<?php echo $tct_comment_image;?>" alt="" width="120px" height="120px"></a>
							</div>
							<div id="open-modal-<?php echo $comment->comment_ID;?>" class="modal-window">
							  <div class="popup-model">
							    <a href="#modal-close" title="Close" class="modal-close"><i class="far fa-times-circle"></i></a>
							    <img src="<?php echo $tct_comment_image;?>" alt="" width="350px" height="350px">
							  </div>
							</div>
						<?php endif; ?>
					</div><!-- .comment-content -->

					<?php

					$comment_reply_link = get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $comment_depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="comment-reply">',
								'after'     => '</span>',
							)
						)
					);

					$comment_quote_link = get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $comment_depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<span class="comment-quote">',
								'after'     => '</span>',
								'reply_text' => 'Quote'
							)
						)
					);

					$by_post_author = twentytwenty_is_comment_by_post_author( $comment );

					if ( $comment_reply_link || $by_post_author ) {
						?>

						<footer class="comment-footer-meta">
							<div class="like-dslike">
								<?php
									ob_start();
						            do_action('tct_like_dislike_output', $comment, get_the_ID());
						            $like_dislike_html = ob_get_contents();
						            ob_end_clean();
						            echo $like_dislike_html; 
					            ?>
							</div>
							<div class="right-part">
								<?php
								if ( $comment_reply_link ) {
									echo $comment_reply_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Link is escaped in https://developer.wordpress.org/reference/functions/get_comment_reply_link/
								}
								if( $comment_quote_link ){
									echo $comment_quote_link;
								} ?>
							</div>
							<?php
								if ( $by_post_author ) {
									//echo '<span class="by-post-author">' . __( 'By Post Author', 'twentytwenty' ) . '</span>';
								}
							?>

						</footer>

						<?php
					}
					?>
				</article>
	<?php	
	//echo $comment_html;
	die();
}
// Hamberger menu Scripts files
function telecom_talk_scripts() {
	wp_enqueue_script( 'drawer-min', get_template_directory_uri() . '/js/drawer.min.js', array( 'jquery' ), '', true );
	wp_enqueue_script( 'iscroll', get_template_directory_uri() . '/js/iscroll.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'fonts-css', get_template_directory_uri() . '/fonts/stylesheet.css', array(), '1.4');
}
add_action( 'wp_enqueue_scripts', 'telecom_talk_scripts' );



add_action( 'wp_enqueue_scripts', 'tct_ajax_comments_scripts' );
 
function tct_ajax_comments_scripts() {
	// I think jQuery is already included in your theme, check it yourself
	//wp_enqueue_script('jquery');
 
	// just register for now, we will enqueue it below
	wp_register_script( 'ajax_comment', get_stylesheet_directory_uri() . '/js/tct-ajax-comment.js', array('jquery') );
 
	// let's pass ajaxurl here, you can do it directly in JavaScript but sometimes it can cause problems, so better is PHP
	wp_localize_script( 'ajax_comment', 'tct_ajax_comment_params', array(
		'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'
	) );
 	wp_enqueue_script( 'ajax_comment' );
}

/*Comments Submit in Ajax*/
function twentynineteen_get_icon_svg( $icon, $size = 24 ) {
	return TwentyNineteen_SVG_Icons::get_svg( 'ui', $icon, $size );
}
add_action ( 'wp', 'tct_construct_frontend_interface', PHP_INT_MAX - 200, 0 );
function tct_construct_frontend_interface(){
	if ( is_singular() && comments_open()  ) {
		add_action( 'wp_enqueue_scripts', 'tct_add_fields' );
	}
}
function tct_add_fields() {
	wp_register_script( 'tctemojis',get_template_directory_uri().'/js/tct-emojis.js',  array( 'jquery' ) );
	wp_enqueue_script('tctemojis');
}
function twentyseventeen_get_svg( $args = array() ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return __( 'Please define default parameters in the form of an array.', 'twentyseventeen' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return __( 'Please define an SVG icon filename.', 'twentyseventeen' );
	}

	// Set defaults.
	$defaults = array(
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id       = twentyseventeen_unique_id();
		$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
		}
	}

	// Begin SVG markup.
	$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}

	$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
	}

	$svg .= '</svg>';

	return $svg;
}
function twentytwenty_is_comment_by_post_author( $comment = null ) {

	if ( is_object( $comment ) && $comment->user_id > 0 ) {

		$user = get_userdata( $comment->user_id );
		$post = get_post( $comment->comment_post_ID );

		if ( ! empty( $user ) && ! empty( $post ) ) {

			return $comment->user_id === $post->post_author;

		}
	}
	return false;

}
function telecomtalk_videos_page() {
   	add_menu_page( 
        __( 'Video Settings', 'textdomain' ),
        'Videos Option',
        'manage_options',
        'tctvideos_settings',
        'telecomtalk_videos_html',
        '',
        6
    );

    add_action( 'admin_init', 'telecomtalk_video_settings' );
}
add_action("admin_menu", "telecomtalk_videos_page");

function telecomtalk_video_settings() {
	for($i=1;$i<=3;$i++){
		$video_title = "video_title_".$i;
		$cover_image = "image_url_".$i;
		$video_link = "video_link_".$i;
		$attachment_id = "attachment_id_".$i;
		register_setting( 'tctvideo_settings', $video_title );
		register_setting( 'tctvideo_settings', $cover_image );
		register_setting( 'tctvideo_settings', $video_link );
		register_setting( 'tctvideo_settings', $attachment_id );
	}
}

function telecomtalk_videos_html(){
	?>
	<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post" name="tctvideos" enctype="multipart/form-data" class="tctvideos" id="tctvideos">
			<?php 
			settings_fields( 'tctvideo_settings' );
			do_settings_sections( 'tctvideo_settings' );
			?>
			<table class="form-table">
				<?php
				for($i=1;$i<=3;$i++){
					$video_title = "video_title_".$i;
					$cover_image = "image_url_".$i;
					$video_link = "video_link_".$i;
					$attachment_id = "attachment_id_".$i;
					if(!empty(get_option($attachment_id)) ){
						$attachment = true;
					}else{
						$attachment = false;
					}
					?>
			    <tr valign="middle">
			    <th scope="row">Video Title <?php echo $i;?></th>
			    <td><input type="text" name="<?php echo $video_title;?>" value="<?php echo esc_attr( get_option($video_title) ); ?>" size="50"/>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Video Cover Image <?php echo $i;?></th>
			    <td>
			    	<div class="upload_file_data">
				    	<input type="text" class="image_path" name="<?php echo $cover_image;?>" value="<?php echo esc_attr( get_option($cover_image) ); ?>" size="50" readonly/>
				    	<input type="hidden" class="attachment_id" name="<?php echo $attachment_id;?>" value="<?php echo esc_attr( get_option($attachment_id) ); ?>" />
				    	<input type="button" value="Upload Image" class="upload_image button-primary" class="upload_image"/>
				    	<div class="show_upload_preview">
					        <?php if($attachment){
					        ?>
					        <img src="<?php echo esc_attr(get_option($cover_image)) ; ?>" alt="" width="300px" height="200px">
					        <input type="button" name="remove" value="Remove Image" class="button-primary remove_image"/>
					        <?php } ?>
					    </div>
				    </div>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Video Link <?php echo $i;?></th>
			    <td><input type="text" name="<?php echo $video_link;?>" value="<?php echo esc_attr( get_option($video_link) ); ?>" size="50"/>
			    </td>
			    </tr>
			    <tr><td colspan="2"><hr></td></tr>
				<?php } ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>

	<?php
}
function media_uploader_enqueue() {
    wp_enqueue_media();
    //wp_enqueue_script('tct-media-uploader');
    wp_enqueue_script( 'tct-media-uploader', get_template_directory_uri() . '/js/media-uploader.js', array(), '1.0.0', true );
    //wp_enqueue_style( 'stylesheet', plugins_url( 'stylesheet.css', __FILE__ ));
}
add_action('admin_enqueue_scripts', 'media_uploader_enqueue');

add_filter('comment_form_default_fields', 'tct_unset_url_field');
function tct_unset_url_field($fields){
    if(isset($fields['url']))
       unset($fields['url']);
       return $fields;
}
add_filter('comment_form_default_fields', 'telecomtalk_comments_custom_fields');
function telecomtalk_comments_custom_fields($fields) {
	$fields['author'] = '<div class="other-fields-wrapper"><p class="comment-form-author"><label for="author">Name <span class="required">*</span></label> <input id="author" name="author" type="text" value="" size="30" maxlength="245" required="required" /></p>';
	$fields[ 'city' ] = '<p class="comment-form-city">'.
      '<label for="city">' . __( 'City' ) . ' </label>'.
      '<input id="city" name="city" type="text" size="30"  tabindex="4" required/></p>';
  //   $fields[ 'cmt_attach' ] = '<div class="image-upload">
		//     <label for="cmt_attach">
		//         <i class="fa fa-paperclip" aria-hidden="true"></i>
		//     </label>
		//     <input name="cmt_attach" id="cmt_attach" class="cmt_attach" type="file"/>
		// </div>';
    return $fields;
}
add_action( 'comment_post', 'save_comment_meta_data', 3, 10 );
function save_comment_meta_data( $comment_id, $comment_approved, $comment ) {
	$image_url = $city = '';
  	if ( ( isset( $_POST['city'] ) ) && !empty($_POST['city']) ){
  		$city = wp_filter_nohtml_kses($_POST['city']);
  	}
  	if(!empty($_POST['cmt_img_path'])){
  		$image_url = wp_filter_nohtml_kses($_POST['cmt_img_path']);
  	}
  	
	update_comment_meta( $comment_id, 'tct_comment_image', $image_url );
  	update_comment_meta( $comment_id, 'city', $city );
}
add_filter( 'comment_text', 'telecomtalk_modify_comment');
function telecomtalk_modify_comment( $text ){
	if( $commentcity = get_comment_meta( get_comment_ID(), 'city', true ) ) {
		$commentcity = '<strong>' . esc_attr( $commentcity ) . '</strong><br/>';
		$text = $commentcity . $text;
	}
	return $text;
}

// Add an edit option to comment editing screen  
add_action( 'add_meta_boxes_comment', 'telecomtalk_extend_comment_add_meta_box' );
function telecomtalk_extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Comment Metadata - Extend Comment' ), 'telecomtalk_extend_comment_meta_box', 'comment', 'normal', 'high' );
}
function telecomtalk_extend_comment_meta_box($comment){
	$city = get_comment_meta( $comment->comment_ID, 'city', true );
	$tct_comment_image = get_comment_meta( $comment->comment_ID, 'tct_comment_image', true );
	$attachment = get_comment_meta( $comment->comment_ID, 'cmt_attach_id', true );
	wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
	?>
	 <table class="form-table">
    	<tr>
        	<th><label for="city"><?php _e( 'City' ); ?></label></th>
        	<td><input type="text" name="city" value="<?php echo esc_attr( $city ); ?>" class="widefat" /></td>
        </tr>
        <tr valign="middle">
	    	<th scope="row"><?php esc_html_e('Comment Image', 'comments-like-dislike'); ?></th>
		    <td>
		        <!--<p><input type="text" name="tct_comment_image" value="<?php //echo esc_attr($tct_comment_image); ?>" size="50"/></p><br/>-->
		        <div class="upload_file_data">
			    	<input type="text" class="image_path" name="tct_comment_image" value="<?php echo esc_attr($tct_comment_image); ?>" size="50" readonly/>
			    	<input type="hidden" class="attachment_id" name="cmt_attach_id" value="<?php echo esc_attr( get_comment_meta( $comment->comment_ID, 'cmt_attach_id', true ) ); ?>" />
			    	<input type="button" value="Attach Image" class="upload_image button-primary" class="upload_image"/>
			    	<div class="show_upload_preview">
				        <?php if($attachment){
				        ?>
				        <img src="<?php echo $tct_comment_image; ?>" alt="" width="300px" height="auto">
				        <input type="button" name="remove" value="Remove Image" class="button-primary remove_image"/>
				        <?php } ?>
				    </div>
			    </div>
		    </td>
		</tr>
    </table>
	<?php 
}
add_action( 'edit_comment', 'telecomtalk_comment_edit_metafields' );

function telecomtalk_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

	if ( ( isset( $_POST['city'] ) ) && ( $_POST['city'] != '') ):
	$city = wp_filter_nohtml_kses($_POST['city']);
	update_comment_meta( $comment_id, 'city', $city );
	else :
	delete_comment_meta( $comment_id, 'city');
	endif;

	if ( ( isset( $_POST['tct_comment_image'] ) ) && ( $_POST['tct_comment_image'] != '') ):
	$cmt_attach = wp_filter_nohtml_kses($_POST['tct_comment_image']);
	update_comment_meta( $comment_id, 'tct_comment_image', $cmt_attach );
	else :
	delete_comment_meta( $comment_id, 'tct_comment_image');
	endif;

	if ( ( isset( $_POST['cmt_attach_id'] ) ) && ( $_POST['cmt_attach_id'] != '') ):
	$cmt_attach_id = wp_filter_nohtml_kses($_POST['cmt_attach_id']);
	update_comment_meta( $comment_id, 'cmt_attach_id', $cmt_attach_id );
	else :
	delete_comment_meta( $comment_id, 'cmt_attach_id');
	endif;
}

add_action('add_meta_boxes','telecomtalk_add_special_template_box');
function telecomtalk_add_special_template_box(){
	global $post;
	$screens = ['post'];
	$pageTemplate = basename(get_post_meta($post->ID, '_wp_page_template', true));
	//if($pageTemplate == 'state-template.php' ){
	    foreach ($screens as $screen) {
	        add_meta_box(
	            'telecomtalk_special_id',           // Unique ID
	            'Telecomtalk Special Template Custom Fields',  // Box title
	            'telecomtalk_special_temp_custom_box_html',  // Content callback, must be of type callable
	            $screen                   // Post type
	        );
	    }
	//}
}
function telecomtalk_special_temp_custom_box_html($post){
	$tct_sp_fields = get_post_meta( $post->ID, 'telecomtalk_special_fields', true );
	$sp_fields = 'none';
	if($tct_sp_fields['enable_sp_fields']){
		$sp_fields = "table-row";
	}
	$special_template = basename( get_page_template() );
	$show_hide_special_data = "none";
   	if( $special_template == 'state-template.php'){
   		$show_hide_special_data = "block";
   	}
	?>
	<style type="text/css">
    .switch { position: relative;  display: inline-block;  width: 54px;  height: 28px;}
	.switch input { opacity: 0; width: 0; height: 0;}
	.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s;}
	.slider:before {  position: absolute;  content: "";  height: 20px;  width: 20px;  left: 4px;
  bottom: 4px;  background-color: white;  -webkit-transition: .4s;  transition: .4s;}
	input:checked + .slider {background-color: #2196F3;}
	input:focus + .slider {  box-shadow: 0 0 1px #2196F3;}
	input:checked + .slider:before {  -webkit-transform: translateX(26px);  -ms-transform: translateX(26px);  transform: translateX(26px);}
	/* Rounded sliders */
	.slider.round { border-radius: 34px;}
	.slider.round:before { border-radius: 50%;}
	#telecomtalk_special_id{ display: <?php echo $show_hide_special_data;?> }
    </style>
	<table class="form-table">
		<tr>
			<td style="width:30%;"><label for="wporg_field">Enable Special Fields</label></td>
			<td style="width:70%;"><label class="switch"><input type="checkbox" id="enable_sp_fields" name="enable_sp_fields" value="<?php echo ($tct_sp_fields['enable_sp_fields']) ? '1':'0';?>" <?php echo ($tct_sp_fields['enable_sp_fields']) ? 'checked':'';?>><span class="slider round"></span></label></td>
		</tr>
		<tr class="special_fields" style="display:<?php echo $sp_fields;?>;">
			<td style="width:30%;vertical-align: top;"><label for="wporg_field">Special Content</label></td>
			<td>
				<textarea name="tct_special_content" cols="70" rows="6" value="<?php echo esc_attr( $tct_sp_fields['tct_special_content'] ); ?>"><?php echo esc_attr( $tct_sp_fields['tct_special_content'] ); ?></textarea>
			</td>
		</tr>
		<tr class="special_fields" style="display:<?php echo $sp_fields;?>">
    		<td style="width:30%;"><label for="wporg_field">Special Title</label></td>
    		<td style="width:70%;"><input type="text" name="tct_sp_title" class="tct_sp_title" value="<?php echo $tct_sp_fields['tct_sp_title'];?>" style="width:100%"></td>
    	</tr>
	<?php
    	for($i=1;$i<=5;$i++){
	    	?>
	    	<tr class="special_fields" valign="middle" style="display:<?php echo $sp_fields;?>;">
		    	<td style="width:30%;"><label for="tct_sp_txt_<?php echo $i;?>">Special Link <?php echo $i;?></label></td>
		    	<td style="width:70%;">
		    		<input type="text" name="tct_sp_txt_<?php echo $i;?>" placeholder="Enter Text" class="tct_sp_txt_<?php echo $i;?>" id="tct_sp_txt_<?php echo $i;?>" value="<?php echo $tct_sp_fields['tct_sp_txt_'.$i]; ?>" size="20"/>
		    		<input type="text" name="tct_sp_link_<?php echo $i;?>" placeholder="Enter Link" class="tct_sp_link_<?php echo $i;?>" id="tct_sp_link_<?php echo $i;?>" value="<?php echo $tct_sp_fields['tct_sp_link_'.$i]; ?>" size="50"/>
		    	</td>
		    </tr>
	    	<?php
	    }
	?>
	</table>
	<script type="text/javascript">
    	jQuery(document).ready(function($){
	        $('#enable_sp_fields').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.special_fields').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.special_fields').hide();
	            }
	        });
	    });
    </script>
	<?php
}
function telecomtalk_add_custom_box(){
    $screens = ['post'];
    foreach ($screens as $screen) {
        add_meta_box(
            'telecomtalk_box_id',           // Unique ID
            'Telecomtalk Custom Fields',  // Box title
            'telecomtalk_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'telecomtalk_add_custom_box');
function telecomtalk_custom_box_html($post){
	$tct_fields = get_post_meta( $post->ID, 'telecomtalk_custom_fields', true );
	$sub_heading = 'none';
	if($tct_fields['enable_sub_heading']){
		$sub_heading = "table-row";
	}
	$heightlights = $show_also_read = 'none';
	if($tct_fields['enable_hightlights']){
		$heightlights = "table-row";
	}
	if($tct_fields['enable_also_read']){
		$show_also_read = "table-row";
	}
    ?>
    <style type="text/css">
    .switch { position: relative;  display: inline-block;  width: 54px;  height: 28px;}
	.switch input { opacity: 0; width: 0; height: 0;}
	.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s;}
	.slider:before {  position: absolute;  content: "";  height: 20px;  width: 20px;  left: 4px;
  bottom: 4px;  background-color: white;  -webkit-transition: .4s;  transition: .4s;}
	input:checked + .slider {background-color: #2196F3;}
	input:focus + .slider {  box-shadow: 0 0 1px #2196F3;}
	input:checked + .slider:before {  -webkit-transform: translateX(26px);  -ms-transform: translateX(26px);  transform: translateX(26px);}
	/* Rounded sliders */
	.slider.round { border-radius: 34px;}
	.slider.round:before { border-radius: 50%;}
    </style>
    <table class="form-table">
    	<tr>
    		<td style="width:30%;"><label for="wporg_field">Enable Sub Heading</label></td>
    		<td style="width:70%;"><label class="switch"><input type="checkbox" id="enable_sub_heading" name="enable_sub_heading" value="<?php echo ($tct_fields['enable_sub_heading']) ? '1':'0';?>" <?php echo ($tct_fields['enable_sub_heading']) ? 'checked':'';?>><span class="slider round"></span></label></td>
    	</tr>
    	<tr class="sub_heading_field" style="display:<?php echo $sub_heading;?>">
    		<td style="width:30%;"><label for="wporg_field">Sub Heading</label></td>
    		<td style="width:70%;"><input type="text" name="sub_heading" class="sub_heading" value="<?php echo $tct_fields['sub_heading'];?>" style="width:100%"></td>
    	</tr>
    	<tr>
    		<td style="width:30%;"><label for="wporg_field">Enable Highlights</label></td>
    		<td style="width:70%;"><label class="switch"><input type="checkbox" id="enable_hightlights" name="enable_hightlights" value="<?php echo ($tct_fields['enable_hightlights']) ? '1':'0';?>" <?php echo ($tct_fields['enable_sub_heading']) ? 'checked':'';?>><span class="slider round"></span></label></td>
    	</tr>
    	<tr class="hightlight_fields" style="display:<?php echo $heightlights;?>;">
    		<td style="width:30%;"><label for="wporg_field">Highlights Heading</label></td>
    		<td style="width:70%;"><input type="text" name="highlights_heading" class="highlights_heading" value="<?php echo ($tct_fields['highlights_heading']?$tct_fields['highlights_heading']:'Highlights');?>" style="width:100%"></td>
    	</tr>
    	<tr class="hightlight_fields" style="display:<?php echo $heightlights;?>;">
    		<td style="width:30%;"><label for="wporg_field">Heading 1</label></td>
    		<td style="width:70%;"><input type="text" name="heading_1" class="heading_1" value="<?php echo $tct_fields['heading_1'];?>" style="width:100%"></td>
    	</tr>
    	<tr class="hightlight_fields" style="display:<?php echo $heightlights;?>;">
    		<td style="width:30%;"><label for="wporg_field">Heading 2</label></td>
    		<td style="width:70%;"><input type="text" name="heading_2" class="heading_2" value="<?php echo $tct_fields['heading_2'];?>" style="width:100%"></td>
    	</tr>
    	<tr class="hightlight_fields" style="display:<?php echo $heightlights;?>;">
    		<td style="width:30%;"><label for="wporg_field">Heading 3</label></td>
    		<td style="width:70%;"><input type="text" name="heading_3" class="heading_3" value="<?php echo $tct_fields['heading_3'];?>" style="width:100%"></td>
    	</tr>
    	<tr>
    		<td style="width:30%;"><label for="wporg_field">Also Read</label></td>
    		<td style="width:70%;"><label class="switch">
    			<input type="checkbox" id="enable_also_read" name="enable_also_read" value="<?php echo ($tct_fields['enable_also_read']) ? '1':'0';?>" <?php echo ($tct_fields['enable_also_read']) ? 'checked':'';?>><span class="slider round"></span></label></td>
    	</tr>
    	<?php
    	for($i=1;$i<=3;$i++){
	    	?>
	    	<tr class="also_read_fields" valign="middle" style="display: <?php echo $show_also_read;?>">
		    	
		    	<td style="width:30%;"><label for="tct_ar_txt_<?php echo $i;?>">Link <?php echo $i;?></label></td>
		    	<td style="width:70%;">
		    		<input type="text" name="tct_ar_txt_<?php echo $i;?>" placeholder="Enter Text" class="tct_ar_txt_<?php echo $i;?>" id="tct_ar_txt_<?php echo $i;?>" value="<?php echo $tct_fields['tct_ar_txt_'.$i]; ?>" size="20"/>
		    		<input type="text" name="tct_ar_link_<?php echo $i;?>" placeholder="Enter Link" class="tct_ar_link_<?php echo $i;?>" id="tct_ar_link_<?php echo $i;?>" value="<?php echo $tct_fields['tct_ar_link_'.$i]; ?>" size="50"/>
		    	</td>
		    </tr>
	    	<?php
	    }
	    ?>
    	<tr class="view_source">
    		<td style="width:30%;"><label for="view_source_label">View Source Label</label></td>
    		<td style="width:70%;"><input type="text" name="view_source_label" class="view_source_label" value="<?php echo $tct_fields['view_source_label'];?>" style="width:100%"></td>
    	</tr>
    	<tr class="view_source">
    		<td style="width:30%;"><label for="view_source_txt">View Source Text</label></td>
    		<td style="width:70%;"><input type="text" name="view_source_txt" class="view_source_txt" value="<?php echo $tct_fields['view_source_txt'];?>" style="width:100%"></td>
    	</tr>
    	<tr class="view_source">
    		<td style="width:30%;"><label for="view_source_link">View Source Link</label></td>
    		<td style="width:70%;"><input type="text" name="view_source_link" class="view_source_link" value="<?php echo $tct_fields['view_source_link'];?>" style="width:100%"></td>
    	</tr>
    	
    	
    </table>
    <script type="text/javascript">
    	jQuery(document).ready(function($){
    		
	        $('#enable_sub_heading').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.sub_heading_field').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.sub_heading_field').hide();
	            }
	        });
	        $('#enable_also_read').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.also_read_fields').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.also_read_fields').hide();
	            }
	        });
	        $('#enable_hightlights').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.hightlight_fields').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.hightlight_fields').hide();
	            }
	        });
	    });
    </script>
    
    <?php
}
function telecomtalk_save_postdata($post_id){
	//enable_sub_heading,sub_heading,enable_hightlights,highlights_heading,heading_1 
	 
	$post_data['enable_sub_heading'] = isset($_POST['enable_sub_heading'])? $_POST['enable_sub_heading']:''; 
	$post_data['enable_also_read'] = isset($_POST['enable_also_read'])? $_POST['enable_also_read']:''; 
	$post_data['sub_heading'] = isset($_POST['sub_heading'])? $_POST['sub_heading']:''; 
	$post_data['enable_hightlights'] = isset($_POST['enable_hightlights'])? $_POST['enable_hightlights']:''; 
	$post_data['highlights_heading'] = isset($_POST['highlights_heading'])? $_POST['highlights_heading']:''; 
	$post_data['heading_1'] = isset($_POST['heading_1'])? $_POST['heading_1']:''; 
	$post_data['heading_2'] = isset($_POST['heading_2'])? $_POST['heading_2']:'';
	$post_data['heading_3'] = isset($_POST['heading_3'])? $_POST['heading_3']:''; 
	$post_data['heading_4'] = isset($_POST['heading_4'])? $_POST['heading_4']:''; 
	$post_data['heading_5'] = isset($_POST['heading_5'])? $_POST['heading_5']:''; 
	$post_data['heading_6'] = isset($_POST['heading_6'])? $_POST['heading_6']:'';
	
	for($i=1;$i<=4;$i++){
		$tct_ar_txt = 'tct_ar_txt_'.$i;
		$tct_ar_link = 'tct_ar_link_'.$i;
		$post_data[$tct_ar_txt] = isset($_POST[$tct_ar_txt])? $_POST[$tct_ar_txt]:'';
		$post_data[$tct_ar_link] = isset($_POST[$tct_ar_link])? $_POST[$tct_ar_link]:'';
	}

	$post_data['view_source_label'] = isset($_POST['view_source_label'])? $_POST['view_source_label']:'';
	$post_data['view_source_txt'] = isset($_POST['view_source_txt'])? $_POST['view_source_txt']:'';
	$post_data['view_source_link'] = isset($_POST['view_source_link'])? $_POST['view_source_link']:'';
    //if (array_key_exists('sub_heading', $_POST)) {
    update_post_meta(
        $post_id,
        'telecomtalk_custom_fields',  $post_data
    );
    //}
    //Special Template Custom Fields
    $special_template = basename( get_page_template() );
    $special_post_data =  array();
   	if( $special_template == 'state-template.php'){
   		$special_post_data['enable_sp_fields'] = isset($_REQUEST['enable_sp_fields'])? $_POST['enable_sp_fields']:'';
	    $special_post_data['tct_special_content'] = isset($_REQUEST['tct_special_content'])? $_POST['tct_special_content']:'';
	    $special_post_data['tct_sp_title'] = isset($_REQUEST['tct_sp_title'])? $_POST['tct_sp_title']:'';
	    for($i=1;$i<=5;$i++){
			$tct_sp_txt = 'tct_sp_txt_'.$i;
			$tct_sp_link = 'tct_sp_link_'.$i;
			$special_post_data[$tct_sp_txt] = isset($_REQUEST[$tct_sp_txt])? $_POST[$tct_sp_txt]:'';
			$special_post_data[$tct_sp_link] = isset($_REQUEST[$tct_sp_link])? $_POST[$tct_sp_link]:'';
		}
	    update_post_meta(
	        $post_id,
	        'telecomtalk_special_fields',  $special_post_data
	    );
   	}
}
add_action('save_post', 'telecomtalk_save_postdata');
function telecomtalk_widgets_init(){
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'telecomtalk' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'telecomtalk' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	register_sidebar( 
		array(
		'name'          => esc_html__( 'Archives', 'telecom-talk' ),
		'id'            => 'archives-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'telecom-talk' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s arch">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
	register_sidebar( 
		array(
		'name'          => esc_html__( 'Critics/Analysts', 'telecom-talk' ),
		'id'            => 'critics-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'telecom-talk' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s critics-analysts">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );
}
add_action( 'widgets_init', 'telecomtalk_widgets_init' );

add_action( 'wp_enqueue_scripts', 'tct_load_more_comments_ajax_script', 1 );
function tct_load_more_comments_ajax_script() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'load_more_comments', get_template_directory_uri() . '/js/load-more-comments.js', array('jquery') );
 	
}


function telecom_frontend_script(){
	wp_enqueue_style('telecom_style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css' ), 'all' );
}

add_action('wp_enqueue_scripts', 'telecom_frontend_script');

if ( ! function_exists( 'telecom_talk_setup' ) ) :

	function telecom_talk_setup() {
		load_theme_textdomain( 'telecom-talk', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.

		register_nav_menus( array(
			'header-menu' => esc_html__( 'Header Menu', 'telecom-talk' ),
			'primary_nav' => esc_html__( 'Primary', 'telecom-talk' ),
			'footer-menu' => esc_html__( 'Footer', 'telecom-talk' ),
			'mobile-menu' => esc_html__( 'Mobile Menu', 'telecom-talk' ),

		) );

		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		register_nav_menus( array(
			'primary_nav' => esc_html__( 'Primary', 'telecom-talk' ),
			'footer-menu' => esc_html__( 'Footer', 'telecom-talk' ),
			'mobile-menu' => esc_html__( 'Mobile Menu', 'telecom-talk' ),
		) );

	}

endif;

add_action( 'after_setup_theme', 'telecom_talk_setup' );

require get_template_directory() . '/includes/class-twentynineteen-svg-icons.php';
require get_template_directory() . '/classes/class-twentytwenty-walker-comment.php';
require get_template_directory() . '/inc/custom-css.php';


function tct_also_read_section( $content = '', $post_id){
	$contentTemp = strip_tags( $content );
    $total_counts = str_word_count( $contentTemp );
    $fifty = round($total_counts*(50/100));
    $contentTempArray = array_filter(explode(" ", $contentTemp));
    $contentTempfirst = array_slice($contentTempArray, 0, $fifty);
    $contentTempsecond = array_slice( $contentTempArray, $fifty );
    $firstPreText = end( $contentTempfirst );
    $needleOccueance = substr_count( implode(" ", $contentTempfirst), $firstPreText);
    $actualContent = '';
    $tct_fields = get_post_meta( $post_id, 'telecomtalk_custom_fields', true );
    $lastPos = 0;
    //$insertion = '<div class="tct_also_read_cntr">';
    //$insertion .= '<ul class="tct_also_read_list">';
    $insertion = '';
    $tct_ar_link = $tct_ar_txt = '';
    $paragraph_id = 2;
    $closing_p = '</p>';
	$paragraphs = explode( $closing_p, wpautop($content,true) );
	
	for($i=1;$i<=3;$i++){
		$tct_ar_link = $tct_fields['tct_ar_link_'.$i];
		$tct_ar_txt = $tct_fields['tct_ar_txt_'.$i];
		if(!empty($tct_ar_link) && !empty($tct_ar_txt)){
			$insertion = '<div class="also-read-txt">Also Read: <a href="'.$tct_ar_link.'">'.$tct_ar_txt.'</a></div>';
		}else{
			$insertion = '';
		}
		foreach ($paragraphs as $index => $paragraph) {
			if ( trim( $paragraph ) ) {
				$paragraphs[$index] .= $closing_p;
			}
			$pos = strpos($paragraph, '<p');
			if ( $paragraph_id == $index + 1 && $pos !== false ) {
				$paragraphs[$index] .= $insertion;
			}
		}
		$paragraph_id = $paragraph_id + 2;
	}
	$content = implode( '', $paragraphs );
    return $content;
}

function twentytwenty_get_elements_array() {

	// The array is formatted like this:
	// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].
	$elements = array(
		'content'       => array(
			'accent'     => array(
				'color'            => array( '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ),
				'border-color'     => array( 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ),
				'background-color' => array( 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ),
				'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),
			),
			'background' => array(
				'color'            => array( ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ),
				'background-color' => array( ':root .has-background-background-color' ),
			),
			'text'       => array(
				'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),
				'background-color' => array( ':root .has-primary-background-color' ),
			),
			'secondary'  => array(
				'color'            => array( 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ),
				'background-color' => array( ':root .has-secondary-background-color' ),
			),
			'borders'    => array(
				'border-color'        => array( 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ),
				'background-color'    => array( 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ),
				'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),
				'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),
				'color'               => array( ':root .has-subtle-background-color' ),
			),
		),
		'header-footer' => array(
			'accent'     => array(
				'color'            => array( 'body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ),
				'background-color' => array( '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ),
			),
			'background' => array(
				'color'            => array( '.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ),
				'background-color' => array( '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ),
			),
			'text'       => array(
				'color'               => array( '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ),
				'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),
				'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),
				'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),
			),
			'secondary'  => array(
				'color' => array( '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ),
			),
			'borders'    => array(
				'border-color'     => array( '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ),
				'background-color' => array( '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ),
			),
		),
	);

	/**
	* Filters Twenty Twenty theme elements
	*
	* @since Twenty Twenty 1.0
	*
	* @param array Array of elements
	*/
	return apply_filters( 'twentytwenty_get_elements_array', $elements );
}


function twentytwenty_get_color_for_area( $area = 'content', $context = 'text' ) {

	// Get the value from the theme-mod.
	$settings = get_theme_mod(
		'accent_accessible_colors',
		array(
			'content'       => array(
				'text'      => '#000000',
				'accent'    => '#cd2653',
				'secondary' => '#6d6d6d',
				'borders'   => '#dcd7ca',
			),
			'header-footer' => array(
				'text'      => '#000000',
				'accent'    => '#cd2653',
				'secondary' => '#6d6d6d',
				'borders'   => '#dcd7ca',
			),
		)
	);

	// If we have a value return it.
	if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {
		return $settings[ $area ][ $context ];
	}

	// Return false if the option doesn't exist.
	return false;
}

//add_filter( 'wp_title', 'wpdocs_hack_wp_title_for_home' ); 
function wpdocs_hack_wp_title_for_home( $title )
{
  	if ( is_single() ) {
    	$title .= ' - '.date("F jS, Y");
  	}
  return $title;
}

function telecomtalk_is_comment_by_post_author( $comment = null ) {

	if ( is_object( $comment ) && $comment->user_id > 0 ) {

		$user = get_userdata( $comment->user_id );
		$post = get_post( $comment->comment_post_ID );

		if ( ! empty( $user ) && ! empty( $post ) ) {

			return $comment->user_id === $post->post_author;

		}
	}
	return false;

}
function telecomtalk_theme_comment($comment, $args, $depth){
	 $GLOBALS['comment'] = $comment; 
	 //print_r($comment);
	 //die;
	 $city = get_comment_meta( $comment->comment_ID, 'city', true );
	 ?>
    <div class="cmts">
		<div class="cmts-athr-img">
			<?php echo get_avatar( $comment, 32 ); ?>
		</div>
		<div class="cmts-cntn">
			<h4>
				<?php  echo get_comment_author_link( $comment->comment_ID );?>
				<span class="city-name"><?php echo $city;?></span>
			</h4>
			<?php if ($comment->comment_approved == '0') : ?>
                <em><php _e('Your comment is awaiting moderation.') ?></em><br />
            <?php endif; ?>
			<p>
				<?php 
				echo apply_filters( 'conver_code_to_emojies', 'tct_convert_content_to_emoji', $comment->comment_content );
				?>
			</p>
			<div class="rght-prt">
				<a class="lk-dlk"href="#">Like</a>
				<!-- <a class="rply"href="#">Reply</a> -->
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div>
	</div>
<?php
}

// Google Fonts
//add_filter('tct_like_dislike_html',"tct_add_like_dilike_text_after",10,1);
function tct_add_like_dilike_text_after($like_dislike_html){
	$like_dislike_html = preg_replace('/<a\shref="(.*?)"(.*?)>\s+<i(.*?)><\/i>\s+<\/a>/s', '<a href="$1"$2><i$3></i> Like</a>', $like_dislike_html);
	return $like_dislike_html;
}

// function telecomtalk_google_fonts() {
// 	wp_enqueue_style('googleFonts',
// 		'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Open+Sans+Condensed:ital,wght@0,300;0,700;1,300&family=Titillium+Web:wght@300;400;600;700&display=swap'); 
// 	}
	
// add_action('wp_enqueue_scripts', 'telecomtalk_google_fonts');


/* Excerpt */
function telecom_talk_custom_excerpt_length( $length ) {
    return 40;
}
add_filter( 'excerpt_length', 'telecom_talk_custom_excerpt_length', 999 );

include( get_template_directory() . '/includes/aq_resizer.php' );
/**/
function telecomtalk_register_ads_options_page(){
	add_menu_page( 
        __( 'Custom Ads Settings', 'textdomain' ),
        'Advertisement Settings',
        'manage_options',
        'custom_ads_options',
        'telecomtalk_advertisement_page',
        '',
        6
    );

    add_action( 'admin_init', 'telecomtalk_advertisement_settings' );
}
add_action( 'admin_menu', 'telecomtalk_register_ads_options_page' );
function telecomtalk_advertisement_settings(){
	register_setting( 'telecomtalk-ads-group', 'ad_1' );
	register_setting( 'telecomtalk-ads-group', 'below_2_post' );
	register_setting( 'telecomtalk-ads-group', 'below_3_post' );
	register_setting( 'telecomtalk-ads-group', 'above_8_post' );
	register_setting( 'telecomtalk-ads-group', 'above_editoral_post' );
	register_setting( 'telecomtalk-ads-group', 'above_pan_post' );
	register_setting( 'telecomtalk-ads-group', 'above_interview' );
	register_setting( 'telecomtalk-ads-group', 'above_mobile' );
	register_setting( 'telecomtalk-ads-group', 'above_recent_comments' );
	register_setting( 'telecomtalk-ads-group', 'above_category' );
	register_setting( 'telecomtalk-ads-group', 'below_subtitle_ad' );
	register_setting( 'telecomtalk-ads-group', 'above_social_icons_ad' );
	register_setting( 'telecomtalk-ads-group', 'above_pagination_ad' );
	register_setting( 'telecomtalk-ads-group', 'above_rp_ad' );
	register_setting( 'telecomtalk-ads-group', 'below_rp_ad' );



	register_setting( 'telecomtalk-ads-group', 'ad_1_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'below_2_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'below_3_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_8_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_editoral_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_pan_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_interview_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_mobile_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_recent_comments_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_category_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'below_subtitle_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_social_icons_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_pagination_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'above_rp_post_enable' );
	register_setting( 'telecomtalk-ads-group', 'below_rp_post_enable' );
}

function telecomtalk_advertisement_page(){
	?>
	<style type="text/css">
    .switch { position: relative;  display: inline-block;  width: 54px;  height: 28px;}
	.switch input { opacity: 0; width: 0; height: 0;}
	.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s;}
	.slider:before {  position: absolute;  content: "";  height: 20px;  width: 20px;  left: 4px;
  bottom: 4px;  background-color: white;  -webkit-transition: .4s;  transition: .4s;}
	input:checked + .slider {background-color: #2196F3;}
	input:focus + .slider {  box-shadow: 0 0 1px #2196F3;}
	input:checked + .slider:before {  -webkit-transform: translateX(26px);  -ms-transform: translateX(26px);  transform: translateX(26px);}
	/* Rounded sliders */
	.slider.round { border-radius: 34px;}
	.slider.round:before { border-radius: 50%;}
    </style>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php settings_fields( 'telecomtalk-ads-group' ); ?>
			<?php do_settings_sections( 'telecomtalk-ads-group' ); ?>
			<?php
				$ad_1_post_enable = get_option('ad_1_post_enable',0);
				$below_2_post_enable = get_option('below_2_post_enable',0);
				$below_3_post_enable = get_option('below_3_post_enable',0);
				$above_8_post_enable = get_option('above_8_post_enable',0);
				$above_editoral_post_enable = get_option('above_editoral_post_enable',0);
				$above_pan_post_enable = get_option('above_pan_post_enable',0);
				$above_interview_post_enable = get_option('above_interview_post_enable',0);
				$above_mobile_post_enable = get_option('above_mobile_post_enable',0);
				$above_recent_comments_post_enable = get_option('above_recent_comments_post_enable',0);
				$above_category_post_enable = get_option('above_category_post_enable',0);
				$below_subtitle_post_enable = get_option('below_subtitle_post_enable',0);
				$above_social_icons_post_enable = get_option('above_social_icons_post_enable',0);
				$above_pagination_post_enable = get_option('above_pagination_post_enable',0);
				$above_rp_post_enable = get_option('above_rp_post_enable',0);
				$below_rp_post_enable = get_option('below_rp_post_enable',0);

				$ad_below_1 = "none";
                if($ad_1_post_enable){
                    $ad_below_1 = 'table-row';
                }
				$hide_below_2 = "none";
                if($below_2_post_enable){
                    $hide_below_2 = 'table-row';
                }
                $hide_below_3 = "none";
                if($below_3_post_enable){
                    $hide_below_3 = 'table-row';
                }
                $hide_above_8 = "none";
                if($above_8_post_enable){
                    $hide_above_8 = 'table-row';
                }
                $hide_above_edit = "none";
                if($above_editoral_post_enable){
                    $hide_above_edit = 'table-row';
                }
                $hide_above_pan = "none";
                if($above_pan_post_enable){
                    $hide_above_pan = 'table-row';
                }

                $hide_above_interview = "none";
                if($above_interview_post_enable){
                    $hide_above_interview = 'table-row';
                }
                $hide_above_mobile = "none";
                if($above_mobile_post_enable){
                    $hide_above_mobile = 'table-row';
                }
                $hide_above_recent_comments = "none";
                if($above_recent_comments_post_enable){
                    $hide_above_recent_comments = 'table-row';
                }
                $hide_above_category = "none";
                if($above_category_post_enable){
                    $hide_above_category = 'table-row';
                }

                $hide_below_subtitle = "none";
                if($below_subtitle_post_enable){
                    $hide_below_subtitle = 'table-row';
                }

                $hide_above_social_icons = "none";
                if($above_social_icons_post_enable){
                    $hide_above_social_icons = 'table-row';
                }

                $hide_above_pagination = "none";
                if($above_pagination_post_enable){
                    $hide_above_pagination = 'table-row';
                }

                $hide_above_rp = "none";
                if($above_rp_post_enable){
                    $hide_above_rp = 'table-row';
                }

                $hide_below_rp = "none";
                if($below_rp_post_enable){
                    $hide_below_rp = 'table-row';
                }

		    ?>
			<table class="form-table">
				<tr valign="middle">
			    	<th scope="row">Below the Header</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="ad_1_post_enable" name="ad_1_post_enable" value="<?php echo ($ad_1_post_enable) ? '1':'0';?>" <?php echo ($ad_1_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $ad_below_1;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="ad_1" cols="70" rows="6" value="<?php echo esc_attr( get_option('ad_1') ); ?>"><?php echo esc_attr( get_option('ad_1') ); ?></textarea>
			    </td>
			    </tr>

				<tr valign="middle">
			    	<th scope="row">Below 2nd post in Latest News Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="below_2_post_enable" name="below_2_post_enable" value="<?php echo ($below_2_post_enable) ? '1':'0';?>" <?php echo ($below_2_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_below_2;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="below_2_post" cols="70" rows="6" value="<?php echo esc_attr( get_option('below_2_post') ); ?>"><?php echo esc_attr( get_option('below_2_post') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Below 3rd post in Latest News Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="below_3_post_enable" name="below_3_post_enable" value="<?php echo ($below_3_post_enable) ? '1':'0';?>" <?php echo ($below_3_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_below_3;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="below_3_post" cols="70" rows="6" value="<?php echo esc_attr( get_option('below_3_post') ); ?>"><?php echo esc_attr( get_option('below_3_post') ); ?></textarea>
			    </td>
			    </tr>
			    <tr valign="middle">
			    	<th scope="row">Above 8th post in Latest News Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_8_post_enable" name="above_8_post_enable" value="<?php echo ($above_8_post_enable) ? '1':'0';?>" <?php echo ($above_8_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_8;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_8_post" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_8_post') ); ?>"><?php echo esc_attr( get_option('above_8_post') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Editorial Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_editoral_post_enable" name="above_editoral_post_enable" value="<?php echo ($above_editoral_post_enable) ? '1':'0';?>" <?php echo ($above_editoral_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_edit;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_editoral_post" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_editoral_post') ); ?>"><?php echo esc_attr( get_option('above_editoral_post') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above PanIndia Spectrum Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_pan_post_enable" name="above_pan_post_enable" value="<?php echo ($above_8_post_enable) ? '1':'0';?>" <?php echo ($above_pan_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_pan;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_pan_post" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_pan_post') ); ?>"><?php echo esc_attr( get_option('above_pan_post') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Interview Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_interview_post_enable" name="above_interview_post_enable" value="<?php echo ($above_interview_post_enable) ? '1':'0';?>" <?php echo ($above_interview_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_interview;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_interview" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_interview') ); ?>"><?php echo esc_attr( get_option('above_interview') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Mobiles&Tablets Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_mobile_post_enable" name="above_mobile_post_enable" value="<?php echo ($above_mobile_post_enable) ? '1':'0';?>" <?php echo ($above_mobile_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_mobile;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_mobile" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_mobile') ); ?>"><?php echo esc_attr( get_option('above_mobile') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Recent Comments Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_recent_comments_post_enable" name="above_recent_comments_post_enable" value="<?php echo ($above_recent_comments_post_enable) ? '1':'0';?>" <?php echo ($above_recent_comments_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_recent_comments;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_recent_comments" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_recent_comments') ); ?>"><?php echo esc_attr( get_option('above_recent_comments') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Categories Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_category_post_enable" name="above_category_post_enable" value="<?php echo ($above_category_post_enable) ? '1':'0';?>" <?php echo ($above_category_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_category;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_category" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_category') ); ?>"><?php echo esc_attr( get_option('above_category') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Below Subtitle Ad(728x90)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="below_subtitle_post_enable" name="below_subtitle_post_enable" value="<?php echo ($below_subtitle_post_enable) ? '1':'0';?>" <?php echo ($below_subtitle_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_below_subtitle;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="below_subtitle_ad" cols="70" rows="6" value="<?php echo esc_attr( get_option('below_subtitle_ad') ); ?>"><?php echo esc_attr( get_option('below_subtitle_ad') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Social Icons Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_social_icons_post_enable" name="above_social_icons_post_enable" value="<?php echo ($above_social_icons_post_enable) ? '1':'0';?>" <?php echo ($above_social_icons_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_social_icons;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_social_icons_ad" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_social_icons_ad') ); ?>"><?php echo esc_attr( get_option('above_social_icons_ad') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Pagination Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_pagination_post_enable" name="above_pagination_post_enable" value="<?php echo ($above_pagination_post_enable) ? '1':'0';?>" <?php echo ($above_pagination_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_pagination;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_pagination_ad" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_pagination_ad') ); ?>"><?php echo esc_attr( get_option('above_pagination_ad') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Above Related Posts Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="above_rp_post_enable" name="above_rp_post_enable" value="<?php echo ($above_rp_post_enable) ? '1':'0';?>" <?php echo ($above_rp_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_above_rp;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="above_rp_ad" cols="70" rows="6" value="<?php echo esc_attr( get_option('above_rp_ad') ); ?>"><?php echo esc_attr( get_option('above_rp_ad') ); ?></textarea>
			    </td>
			    </tr>

			    <tr valign="middle">
			    	<th scope="row">Below Related Posts Ad(300x250)</th>
			    	<td style="width:70%;">
			    		<label class="switch"><input type="checkbox" id="below_rp_post_enable" name="below_rp_post_enable" value="<?php echo ($below_rp_post_enable) ? '1':'0';?>" <?php echo ($below_rp_post_enable) ? 'checked':'';?>><span class="slider round"></span></label>
			    	</td>
			    </tr>
			    <tr valign="middle" class="childrens" style="display:<?php echo $hide_below_rp;?>">
			    <th scope="row">Ad Content</th>
			    <td>
			    	<textarea name="below_rp_ad" cols="70" rows="6" value="<?php echo esc_attr( get_option('below_rp_ad') ); ?>"><?php echo esc_attr( get_option('below_rp_ad') ); ?></textarea>
			    </td>
			    </tr>

			</table>
			<?php submit_button(); ?>
		</form>
    </div>
    <script type="text/javascript">
    	jQuery(document).ready(function($){
	        $('#ad_1_post_enable,#below_2_post_enable,#below_3_post_enable,#above_8_post_enable,#above_editoral_post_enable,#above_pan_post_enable,#above_interview_post_enable,#above_mobile_post_enable,#above_recent_comments_post_enable,#above_category_post_enable,#below_subtitle_post_enable,#above_social_icons_post_enable,#above_pagination_post_enable,#above_rp_post_enable,#below_rp_post_enable').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $(this).closest('tr').next('.childrens').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $(this).closest('tr').next('.childrens').hide();
	            }
	        });
	        
	    });
    </script>
    <?php
}
/**/


function telecomtalk_register_custom_options_page(){
    add_menu_page( 
        __( 'Custom Theme Settings', 'textdomain' ),
        'Custom Theme Options',
        'manage_options',
        'custom_theme_options',
        'telecomtalk_theme_options_page',
        '',
        6
    );

    add_action( 'admin_init', 'telecomtalk_custom_theme_settings' );
}
add_action( 'admin_menu', 'telecomtalk_register_custom_options_page' );

function telecomtalk_custom_theme_settings() {
	//register our settings
	register_setting( 'telecomtalk-settings-group', 'hbp_option' );
	register_setting( 'telecomtalk-settings-group', 'editors_pick_option' );
	register_setting( 'telecomtalk-settings-group', 'pan_india_option' );
	register_setting( 'telecomtalk-settings-group', 'search_box_option' );
	register_setting( 'telecomtalk-settings-group', 'header_scripts_option' );
	register_setting( 'telecomtalk-settings-group', 'frequency_band_option' );
	register_setting( 'telecomtalk-settings-group', 'dth_satellite_band_option' );
	register_setting( 'telecomtalk-settings-group', 'mdcomments_interval' );
	register_setting( 'telecomtalk-settings-group', 'footer_cpyr' );
	//Comments like dislike settings
	register_setting( 'telecomtalk-settings-group', 'display_zero' );
	register_setting( 'telecomtalk-settings-group', 'load_more_cmts' );
	register_setting( 'telecomtalk-settings-group', 'tech_news_widget' );
	register_setting( 'telecomtalk-settings-group', 'tct_also_read' );
	register_setting( 'telecomtalk-settings-group', 'show_likedislike' );
	register_setting( 'telecomtalk-settings-group', 'login_link' );
	register_setting( 'telecomtalk-settings-group', 'like_hov_txt' );
	register_setting( 'telecomtalk-settings-group', 'dislike_hov_txt' );
	register_setting( 'telecomtalk-settings-group', 'hide_like_dislike_col' );

	register_setting( 'telecomtalk-settings-group', 'subsc_count' );
	register_setting( 'telecomtalk-settings-group', 'twitter_count' );
	register_setting( 'telecomtalk-settings-group', 'telegram_count' );
	register_setting( 'telecomtalk-settings-group', 'fb_count' );
	for($i=1;$i<=3;$i++){
		register_setting( 'telecomtalk-settings-group', 'tct_ar_txt_'.$i );
		register_setting( 'telecomtalk-settings-group', 'tct_ar_link_'.$i );
	}
	for($i=1;$i<=24;$i++){
		register_setting( 'telecomtalk-settings-group', 'cat_title_'.$i );
		register_setting( 'telecomtalk-settings-group', 'cat_link_'.$i );
	}
}

function telecomtalk_theme_options_page(){
    ?>
    <style type="text/css">
    .switch { position: relative;  display: inline-block;  width: 54px;  height: 28px;}
	.switch input { opacity: 0; width: 0; height: 0;}
	.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s;}
	.slider:before {  position: absolute;  content: "";  height: 20px;  width: 20px;  left: 4px;
  bottom: 4px;  background-color: white;  -webkit-transition: .4s;  transition: .4s;}
	input:checked + .slider {background-color: #2196F3;}
	input:focus + .slider {  box-shadow: 0 0 1px #2196F3;}
	input:checked + .slider:before {  -webkit-transform: translateX(26px);  -ms-transform: translateX(26px);  transform: translateX(26px);}
	/* Rounded sliders */
	.slider.round { border-radius: 34px;}
	.slider.round:before { border-radius: 50%;}
    </style>
    <div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php settings_fields( 'telecomtalk-settings-group' ); ?>
			<?php do_settings_sections( 'telecomtalk-settings-group' ); ?>
			<?php
				$show_also_read = 'none';
		    	$show_likedislike = get_option('show_likedislike',1);
		    	$load_more_cmts = get_option('load_more_cmts',1);
		    	$tech_news_widget = get_option('tech_news_widget',1);
		    	$tct_also_read = get_option('tct_also_read',0);
		    	$display_zero = get_option('display_zero',1);
		    	$login_link = get_option('login_link','');
		    	$like_hov_txt = get_option('like_hov_txt','');
		    	$dislike_hov_txt = get_option('dislike_hov_txt','');
		    	$hide_like_dislike_col = get_option('hide_like_dislike_col',1);
		    	$fb_count = get_option('fb_count','');
		    	$twitter_count = get_option('twitter_count','');
		    	$telegram_count = get_option('telegram_count','');
		    	$subsc_count = get_option('subsc_count','');
		    	if($tct_also_read){
		    		$show_also_read = 'table-row';
		    	}
		    ?>
			<table class="form-table">
				<tr>
			    	<td colspan="2">
			    		<h2>Theme Template Settings</h2>
			    	<hr>
			    	</td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Header Scripts Box</th>
			    <td>
			    	<textarea name="header_scripts_option" cols="70" rows="6" value="<?php echo esc_attr( get_option('header_scripts_option') ); ?>"><?php echo esc_attr( get_option('header_scripts_option') ); ?></textarea>
			    </td>
			    </tr>
			    <tr valign="middle">
			    	<th scope="row">Show Tech News Widget</th>
			    	<td style="width:70%;"><label class="switch"><input type="checkbox" id="tech_news_widget" name="tech_news_widget" value="<?php echo ($tech_news_widget) ? '1':'0';?>" <?php echo ($tech_news_widget) ? 'checked':'';?>><span class="slider round"></span></label></td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Head Blog Posts</th>
			    <td><input type="text" name="hbp_option" value="<?php echo esc_attr( get_option('hbp_option') ); ?>" size="50"/>
			    	<p class="description">Add post id's separated by comma(',')</p>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Editors Pick</th>
			    <td><input type="text" name="editors_pick_option" value="<?php echo esc_attr( get_option('editors_pick_option') ); ?>" size="50"/>
			    	<p class="description">Enter the postID for editor pick section</p>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Pan India Spectrum Details</th>
			    <td><input type="text" name="pan_india_option" value="<?php echo esc_attr( get_option('pan_india_option') ); ?>" size="50"/>
			    	<p class="description">Enter the pageID for editor spectrum details</p>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Search Box</th>
			    <td>
			    	<textarea name="search_box_option" cols="70" rows="6" value="<?php echo esc_attr( get_option('search_box_option') ); ?>"><?php echo esc_attr( get_option('search_box_option') ); ?></textarea>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">Telecommunication Frequency Bands</th>
			    <td><input type="text" name="frequency_band_option" value="<?php echo esc_attr( get_option('frequency_band_option') ); ?>" size="50"/>
			    	<p class="description">Enter the pageID</p>
			    </td>
			    </tr>
			    <tr valign="middle">
			    <th scope="row">DTH Satellites in India</th>
			    <td><input type="text" name="dth_satellite_band_option" value="<?php echo esc_attr( get_option('dth_satellite_band_option') ); ?>" size="50"/>
			    	<p class="description">Enter the postID</p>
			    </td>
			    </tr>
			    <?php
			    $most_discussed = get_option('mdcomments_interval','1 week ago');
			    ?>
			    <tr valign="middle">
			    <th scope="row">Most Discussed</th>
				    <td>
				    	<select name="mdcomments_interval" id="mdc_interval">
						  	<option value="1 week ago" <?php echo ($most_discussed == '1 week ago')? "selected":"";?>>1 Week ago</option>
						  	<option value="10 days ago" <?php echo ($most_discussed == '10 days ago')? "selected":"";?>>10 days ago</option>
						  	<option value="30 days ago" <?php echo ($most_discussed == '30 days ago')? "selected":"";?>>30 Days ago</option>
						</select>
				    </td>
			    </tr>
			    <tr valign="middle">
				    <th scope="row">Footer Copyrights</th>
				    <td>
				    	<textarea name="footer_cpyr" cols="70" rows="4" value="<?php echo esc_attr( get_option('footer_cpyr') ); ?>"><?php echo esc_attr( get_option('footer_cpyr') ); ?></textarea>
				    </td>
			    </tr>
			    <tr valign="middle">
			    	<th scope="row">Follow Us Count Number</th>
			    	<td style="width:70%;"><label class="switch"><input type="checkbox" id="tct_also_read" name="tct_also_read" value="<?php echo ($tct_also_read) ? '1':'0';?>" <?php echo ($tct_also_read) ? 'checked':'';?>><span class="slider round"></span></label></td>
			    </tr>
			    <tr class="also_read_child" valign="middle" style="display: <?php echo $show_also_read;?>">
			    	<th scope="row">Facebook</th>
			    	<td>
			    		<input type="text" name="fb_count" class="fb_count" id="fb_count" value="<?php echo esc_attr( $fb_count ); ?>" size="60" placeholder="Facebook Count"/>			    		
			    	</td>
			    </tr>
			    <tr class="also_read_child" valign="middle" style="display: <?php echo $show_also_read;?>">
			    	<th scope="row">Twitter</th>
			    	<td>
			    		<input type="text" name="twitter_count" class="twitter_count" id="twitter_count" value="<?php echo esc_attr( $twitter_count ); ?>" size="60" placeholder="Twitter Count"/>
			    	</td>
			    </tr>
			    <tr class="also_read_child" valign="middle" style="display: <?php echo $show_also_read;?>">
			    	<th scope="row">Telegram</th>
			    	<td>
			    		<input type="text" name="telegram_count" class="telegram_count" id="telegram_count" value="<?php echo esc_attr( $telegram_count ); ?>" size="60" placeholder="Telegram Count"/>
			    	</td>
			    </tr>
			    <tr class="also_read_child" valign="middle" style="display: <?php echo $show_also_read;?>">
			    	<th scope="row">Subscribers</th>
			    	<td>
			    		<input type="text" name="subsc_count" class="subsc_count" id="subsc_count" value="<?php echo esc_attr( $subsc_count ); ?>" size="60" placeholder="Subscribers Count"/>
			    	</td>
			    </tr>
			    
			    <tr>
			    	<td colspan="2">
			    		<h2>Sidebar Categories</h2>
			    	<hr>
			    	</td>
			    </tr>
			    <?php 
			    for($i=1;$i<=24;$i++){
			    	?>
			    	<tr valign="middle">
				    	<th scope="row">Category <?php echo $i;?></th>
				    	<td>
				    		<input type="text" name="cat_title_<?php echo $i;?>" placeholder="Enter Category Title" class="cat_title_<?php echo $i;?>" id="cat_title_<?php echo $i;?>" value="<?php echo esc_attr( get_option('cat_title_'.$i) ); ?>" size="40"/>
				    		<span class="field_gapping"></span>
				    		<input type="text" name="cat_link_<?php echo $i;?>" class="cat_link_<?php echo $i;?>" id="cat_link_<?php echo $i;?>" placeholder="Enter Category Link" value="<?php echo esc_attr( get_option('cat_link_'.$i) ); ?>" size="60"/>
				    	</td>
				    </tr>
			    	<?php
			    }
			    ?>
			    
			</table>
			<?php submit_button(); ?>
		</form>
    </div>
    <script type="text/javascript">
    	jQuery(document).ready(function($){
    		
	        $('#display_zero,#load_more_cmts,#show_likedislike,#hide_like_dislike_col,#tech_news_widget,#tct_also_read').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                //$('.sub_heading_field').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                //$('.sub_heading_field').hide();
	            }
	        });
	        $('#tct_also_read').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.also_read_child').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.also_read_child').hide();
	            }
	        });
	        $('#enable_hightlights').click(function(){
	            if($(this).prop("checked") == true){
	                $(this).val("1");
	                $('.hightlight_fields').show();
	            }
	            else if($(this).prop("checked") == false){
	                $(this).val("0");
	                $('.hightlight_fields').hide();
	            }
	        });
	    });
    </script>
    <?php  
}

// Author Extra Fields
add_action( 'show_user_profile', 'show_extra_profile_fields', 10 );

add_action( 'edit_user_profile', 'show_extra_profile_fields', 10 );

function show_extra_profile_fields( $user ) { ?>

	<h3><?php _e('Extra Profile Information'); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="designation"><?php _e('Designation'); ?></label></th>
			<td>
				<input type="text" name="designation" id="designation" value="<?php echo esc_attr( get_user_meta( $user->ID, 'designation', true ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter the designation.'); ?></span>
			</td>
		</tr>
		<?php
		$attachment = get_user_meta( $user->ID, 'photo_id', true );
		$profile_photo = get_user_meta( $user->ID, 'photo_url', true );
		?>
		<tr>
			<th><label for="designation"><?php _e('Upload Photo'); ?></label></th>
			<td>
				<div class="upload_file_data">
			    	<input type="text" class="image_path" name="photo_url" value="<?php echo esc_attr( get_user_meta( $user->ID, 'photo_url', true ) ); ?>" size="50" readonly/>
			    	<input type="hidden" class="attachment_id" name="photo_id" value="<?php echo esc_attr( get_user_meta( $user->ID, 'photo_id', true ) ); ?>" />
			    	<input type="button" value="Upload Image" class="upload_image button-primary" class="upload_image"/>
			    	<div class="show_upload_preview">
				        <?php if($attachment){
				        ?>
				        <img src="<?php echo $profile_photo ; ?>" alt="" width="300px" height="auto">
				        <input type="button" name="remove" value="Remove Image" class="button-primary remove_image"/>
				        <?php } ?>
				    </div>
			    </div>
			</td>
		</tr>
		<tr>
			<th><label for="social-profile"><?php _e('Twitter Profile Link'); ?></label></th>
			<td>
				<input type="text" name="twitterprofilelink" id="twitterprofilelink" value="<?php echo esc_attr( get_user_meta( $user->ID, 'twitterprofilelink', true ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter the Twitter Profile Link.'); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for="social-profile"><?php _e('Linkedin Username'); ?></label></th>
			<td>
				<input type="text" name="linkedusername" id="linkedusername" value="<?php echo esc_attr( get_user_meta( $user->ID, 'linkedusername', true ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter the Linkedin Username'); ?></span>
			</td>
		</tr>
	</table>

<?php }

add_action( 'personal_options_update', 'save_extra_profile_fields' );

add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );

function save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) return false;
	
	update_user_meta( $user_id, 'designation', trim(esc_attr( $_POST['designation'] )) );
	update_user_meta( $user_id, 'photo_url', trim(esc_attr( $_POST['photo_url'] )) );
	update_user_meta( $user_id, 'photo_id', trim(esc_attr( $_POST['photo_id'] )) );
	update_user_meta( $user_id, 'twitterprofilelink', trim(esc_attr( $_POST['twitterprofilelink'] )) );
	update_user_meta( $user_id, 'linkedusername', trim(esc_attr( $_POST['linkedusername'] )) );

}


/*Custom Like Dislike Coments Code */
//add_filter('manage_edit-comments_columns', 'tct_add_like_dislike_column');
function tct_add_like_dislike_column($columns){
	$hide_like_dislike_col = get_option('hide_like_dislike_col',0);
	if ($hide_like_dislike_col == 0) {
        $columns['tct_like_column'] = __('Likes', 'comments-like-dislike');
        $columns['tct_dislike_column'] = __('Dislikes', 'comments-like-dislike');
        //$columns['tct_image_column'] = __('Image', 'comments-like-dislike');
    }
    return $columns;
}
//add_filter('manage_comments_custom_column', 'tct_display_like_dislike_values', 10, 2);
function tct_display_like_dislike_values($column, $comment_id) {
    if ('tct_like_column' == $column) {
        $like_count = get_comment_meta($comment_id, 'tct_like_count', true);
        if (empty($like_count)) {
            $like_count = 0;
        }
        echo $like_count;
    }
    if ('tct_dislike_column' == $column) {
        $dislike_count = get_comment_meta($comment_id, 'tct_dislike_count', true);
        if (empty($dislike_count)) {
            $dislike_count = 0;
        }
        echo $dislike_count;
    }
    
}
//add_action('add_meta_boxes',  'tct_render_count_info_metabox');
function tct_render_count_info_metabox(){
	add_meta_box('tct-count-info', esc_html__('Comments Like Dislike', 'comments-like-dislike'), 'tct_render_count_info_html', 'comment', 'normal');
}
//add_action('edit_comment', 'tct_save_cld_metabox' );
function tct_save_cld_metabox($comment_id){
	$nonce_name = isset($_POST['tct_metabox_nonce_field']) ? $_POST['tct_metabox_nonce_field'] : '';
    $nonce_action = 'tct_metabox_nonce';

    // Check if nonce is valid.
    if (!wp_verify_nonce($nonce_name, $nonce_action)) {
        return;
    }


    if (isset($_POST['tct_like_count'], $_POST['tct_dislike_count'])) {
        $cld_like_count = sanitize_text_field($_POST['tct_like_count']);
        $cld_dislike_count = sanitize_text_field($_POST['tct_dislike_count']);
        //$tct_comment_image = sanitize_text_field($_POST['tct_comment_image']);
        update_comment_meta($comment_id, 'tct_like_count', $cld_like_count);
        update_comment_meta($comment_id, 'tct_dislike_count', $cld_dislike_count);
        //update_comment_meta($comment_id, 'tct_comment_image', $tct_comment_image);
        return $comment_id;
    } else {
        return $comment_id;
    }
}

function tct_render_count_info_html($comment){
	$comment_id = $comment->comment_ID;
    $like_count = get_comment_meta($comment_id, 'tct_like_count', true);
    $dislike_count = get_comment_meta($comment_id, 'tct_dislike_count', true);
    //$tct_comment_image = get_comment_meta($comment_id, 'tct_comment_image', true);
    wp_nonce_field('tct_metabox_nonce', 'tct_metabox_nonce_field');
    ?>
    <table class="form-table">
	<tr valign="middle">
	    <th scope="row"><?php esc_html_e('Like Count', 'comments-like-dislike'); ?></th>
	    <td>
	        <input type="text" name="tct_like_count" value="<?php echo esc_attr($like_count); ?>" size="50"/>
	    </td>
	</tr>
	<tr valign="middle">
	    <th scope="row"><?php esc_html_e('Dislike Count', 'comments-like-dislike'); ?></th>
	    <td>
	        <input type="text" name="tct_dislike_count" value="<?php echo esc_attr($dislike_count); ?>" size="50"/>
	    </td>
	</tr>
	
	</table>
    <?php
}
//add_action('wp_head', 'tct_like_dislike_custom_styles');
function tct_like_dislike_custom_styles(){
	echo "<style>";
    if ($cld_settings['design_settings']['icon_color'] != '') {
        echo 'a.tct-like-dislike-trigger {color: #cd2653;}';
    }
    if ($cld_settings['design_settings']['count_color'] != '') {
        echo 'span.tct-count-wrap {color: #cd2653;}';
    }
    echo "</style>";
}
//add_action('tct_like_dislike_output', 'tct_generate_like_dislike_html', 10, 2);
function tct_generate_like_dislike_html($comment, $post_id){
	if (isset($comment)) {
	    $comment_id = $comment->comment_ID;
	}
	$like_count = get_comment_meta($comment_id, 'tct_like_count', true);
	$dislike_count = get_comment_meta($comment_id, 'tct_dislike_count', true);
	
	$status = get_option('show_likedislike');
	$login_link = get_option('login_link');
	$like_hov_txt = get_option('like_hov_txt');
	$dislike_hov_txt = get_option('dislike_hov_txt');
	$display_zero = get_option('display_zero');
	if($display_zero){
		$like_count = (empty($like_count)) ? 0 : $like_count;
		$dislike_count = (empty($dislike_count)) ? 0 : $dislike_count;
	}

	$like_count = apply_filters('tct_like_count', $like_count, $comment_id);
	$dislike_count = apply_filters('tct_dislike_count', $dislike_count, $comment_id);

	//Settings option to show or hide like dislike
	if ($status != 1) {
	    // if comments like dislike is disabled from backend
	    return;
	}
	$liked_ips = get_comment_meta($comment_id, 'tct_ips', true);
	$user_ip = tct_get_user_IP();
	if (empty($liked_ips)) {
	    $liked_ips = array();
	}
	if (is_user_logged_in()) {
	    $liked_users = get_comment_meta($comment_id, 'tct_users', true);
	    $liked_users = (empty($liked_users)) ? array() : $liked_users;
	    $current_user_id = get_current_user_id();
	    if (in_array($current_user_id, $liked_users)) {
	        $user_check = 1;
	        $already_liked = 1;
	    } else {
	        $user_check = 0;
	    }
	} else {
	    $user_check = 1;
	    $already_liked = 0;
	}
	if ( !empty($login_link) && $user_check == 1 && $already_liked == 0) {
	    $href = $login_link;
	} else {
	    $href = 'javascript:void(0)';
	}
	$user_ip_check = (in_array($user_ip, $liked_ips)) ? 1 : 0;
	$like_title = isset($like_hov_txt) ? esc_attr($like_hov_txt) : __('Like', CLD_TD);
	$dislike_title = isset($dislike_hov_txt) ? esc_attr($dislike_hov_txt) : __('Dislike', CLD_TD);
	?>
	<div class="tct-like-dislike-wrap tct-template-1">
		<div class="tct-like-wrap  tct-common-wrap">
		    <a href="<?php echo $href; ?>"
		       class="tct-like-trigger tct-like-dislike-trigger <?php echo ($user_ip_check == 1 || isset($_COOKIE['tct_' . $comment_id])) ? 'tct-prevent' : ''; ?>"
		       title="<?php echo $like_title; ?>"
		       data-comment-id="<?php echo $comment_id; ?>"
		       data-trigger-type="like"
		       data-restriction="user"
		       data-ip-check="<?php echo $user_ip_check; ?>"
		       data-user-check="<?php echo $user_check; ?>">
		        <?php
		        $template = 'template-1';   
		        if($template == 'template-1'){
		        ?>
		        	<span class="icon-thumbs-up"></span>
		    	<?php }else{ ?>
		    		<img src="<?php echo esc_url($cld_settings['design_settings']['like_icon']); ?>" alt="<?php echo esc_attr($like_title); ?>"/>
		    	<?php }
		        
		        //do_action('cld_like_template', $cld_settings);
		        ?>
		    </a>
		    <span class="tct-like-count-wrap tct-count-wrap"><?php echo $like_count; ?>
		    </span>
		</div>
		<div class="tct-dislike-wrap  tct-common-wrap">
		    <a href="<?php echo $href; ?>" class="tct-dislike-trigger tct-like-dislike-trigger <?php echo ($user_ip_check == 1 || isset($_COOKIE['tct_' . $comment_id])) ? 'cld-prevent' : ''; ?>" title="<?php echo $dislike_title; ?>" data-comment-id="<?php echo $comment_id; ?>" data-trigger-type="dislike" data-ip-check="<?php echo $user_ip_check; ?>" data-restriction="user" data-user-check="<?php echo $user_check; ?>">
		        <?php
		        $template = 'template-1';
		        if($template == 'template-1'){
		        ?>
		        	<span class="icon-thumbs-down"></span>
		    	<?php }else{ ?>
		    		<img src="<?php echo esc_url($cld_settings['design_settings']['dislike_icon']); ?>" alt="<?php echo esc_attr($dislike_title); ?>"/>
		    	<?php }
		        
		        //do_action('cld_dislike_template', $cld_settings);
		        ?>
		    </a>
		    <span class="tct-dislike-count-wrap tct-count-wrap"><?php echo $dislike_count; ?></span>
		</div>
	</div>
	<?php
}

//add_filter('comment_text', 'tct_comments_like_dislike', 200, 2);
function tct_comments_like_dislike( $comment_text, $comment = null ){
	if (isset($_REQUEST['comment'])) {
        return $comment_text;
    }
    if (is_admin()) {
        return $comment_text;
    }
    if (isset($comment)) {
	    $comment_id = $comment->comment_ID;
	}
	ob_start();
	$post_id = get_the_ID();
	do_action('cld_like_dislike_output', $comment, $post_id);
	$like_dislike_html = ob_get_contents();
    ob_end_clean();
    $comment_text .= $like_dislike_html;
    return $comment_text;
}

function tct_get_user_IP() {
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}
//add_action( 'wp_ajax_tct_comment_ajax_action', 'tct_like_dislike_action' );
//add_action( 'wp_ajax_nopriv_tct_comment_ajax_action',  'tct_like_dislike_action' );
function tct_like_dislike_action() {
    if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'tct-comment-nonce' ) ) {
        $comment_id = sanitize_text_field( $_POST['comment_id'] );
        /**
         * Action cld_before_ajax_process
         *
         * @param type int $comment_id
         *
         * @since 1.0.7
         */
        do_action( 'tct_before_ajax_process', $comment_id );
        $type = sanitize_text_field( $_POST['type'] );
        $user_ip = tct_get_user_IP();
        if ( $type == 'like' ) {
            $like_count = get_comment_meta( $comment_id, 'tct_like_count', true );
            if ( empty( $like_count ) ) {
                $like_count = 0;
            }
            $like_count = $like_count + 1;
            $check = update_comment_meta( $comment_id, 'tct_like_count', $like_count );

            if ( $check ) {

                $response_array = array( 'success' => true, 'latest_count' => $like_count );
            } else {
                $response_array = array( 'success' => false, 'latest_count' => $like_count );
            }
        } else {
            $dislike_count = get_comment_meta( $comment_id, 'tct_dislike_count', true );
            if ( empty( $dislike_count ) ) {
                $dislike_count = 0;
            }
            $dislike_count = $dislike_count + 1;
            $check = update_comment_meta( $comment_id, 'tct_dislike_count', $dislike_count );
            if ( $check ) {
                $response_array = array( 'success' => true, 'latest_count' => $dislike_count );
            } else {
                $response_array = array( 'success' => false, 'latest_count' => $dislike_count );
            }
        }
        /**
         * Check the liked ips and insert the user ips for future checking
         *
         */
        $liked_ips = get_comment_meta( $comment_id, 'tct_ips', true );
        $liked_ips = (empty( $liked_ips )) ? array() : $liked_ips;
        if ( !in_array( $user_ip, $liked_ips ) ) {
            $liked_ips[] = $user_ip;
        }
        /**
         * Check if user is logged in to check user login for like dislike action
         */
        if ( is_user_logged_in() ) {

            $liked_users = get_comment_meta( $comment_id, 'tct_users', true );
            $liked_users = (empty( $liked_users )) ? array() : $liked_users;
            $current_user_id = get_current_user_id();
            if ( !in_array( $current_user_id, $liked_users ) ) {
                $liked_users[] = $current_user_id;
            }
            update_comment_meta( $comment_id, 'tct_users', $liked_users );
        }

        update_comment_meta( $comment_id, 'tct_ips', $liked_ips );
        /**
         * Action cld_after_ajax_process
         *
         * @param type int $comment_id
         *
         * @since 1.0.7
         */
        do_action( 'tct_after_ajax_process', $comment_id );
        echo json_encode( $response_array );

        //$this->print_array( $response_array );
        die();
    } else {
        die( 'No script kiddies please!' );
    }
}
//add_action( 'admin_enqueue_scripts', 'tct_comments_meta_enqueue' );
function tct_comments_meta_enqueue($hook){
	wp_enqueue_script( 'tct-ajax-script', get_template_directory_uri(). '/js/tct-admin-comment-reset.js', array('jquery') );
	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	wp_localize_script( 'tct-ajax-script', 'tct_ajax_object',  array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

//add_action( 'wp_ajax_reset_comments_like_dislike', 'tct_reset_all_comments_like_dislike' );
function tct_reset_all_comments_like_dislike() {
	global $wpdb;
    $args = array(
    	'post_id' => 286611,   // Use post_id, not post_ID
        'count'   => true // Return only the count
	);
	$args = array( 'numberposts' => -1 );
	$all_posts = get_posts( $args );
    $comments = get_comments( $args );
    foreach ( $all_posts as $post ) {
	    if($comments>0){
	    	$comments_obj = get_comments( array( 'post_id' => $post->ID ) );
	    	foreach ( $comments_obj as $comment ){
			    $comment_id = $comment->comment_ID;
			    $liked_users = get_comment_meta( $comment_id, 'tct_users', true );
	            $liked_users = (empty( $liked_users )) ? array() : $liked_users;
	            
			    update_comment_meta( $comment_id, 'tct_like_count', '' );
			    update_comment_meta( $comment_id, 'tct_dislike_count', '' );
	    	}
	    }
	}
	echo "Reset Successfully";
	wp_die();
}

//add_action( 'wp_ajax_file_upload', 'file_upload_callback' );
//add_action( 'wp_ajax_nopriv_file_upload', 'file_upload_callback' );
function file_upload_callback() {
       $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    if (in_array($_FILES['cmt_attach']['type'], $arr_img_ext)) {
        $upload = wp_upload_bits($_FILES["cmt_attach"]["name"], null, file_get_contents($_FILES["cmt_attach"]["tmp_name"]));
        echo '<input type="hidden" name="cmt_img_path" class="cmt_img_path" id="cmt_img_path" value="'.$upload['url'].'">';
        echo '<img class="cmt_img" src="'.$upload['url'].'" alt="" width="200px" height="200px">';
        //$upload['url'] will gives you uploaded file path
    }
    die();
}

function aw_scripts() {
    // Register the script
    wp_register_script( 'aw-custom', get_stylesheet_directory_uri(). '/js/comment-attachment.js', array('jquery'), '1.1', true );
    // Localize the script with new data
    $script_data_array = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    );
    wp_localize_script( 'aw-custom', 'aw', $script_data_array );
    // Enqueued script with localized data.
    wp_enqueue_script( 'aw-custom' );
} 
//add_action( 'wp_enqueue_scripts', 'aw_scripts' );
// Execute the action only if the user isn't logged in
// if (!is_user_logged_in()) {
//     add_action('init', 'ajax_auth_init');
// }
function ajax_auth_init(){
	wp_register_script('validate-script', get_template_directory_uri() . '/js/jquery.validate.js', array('jquery') ); 
    wp_enqueue_script('validate-script');
    wp_register_script('ajax-auth-script', get_template_directory_uri() . '/js/ajax-auth-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-auth-script');

    wp_localize_script( 'ajax-auth-script', 'ajax_auth_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending user info, please wait...')
    ));
    //add_action( 'wp_ajax_nopriv_ajaxregister', 'ajax_register' );
}

function ajax_register(){
 
    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-register-nonce', 'security' );
		
    // Nonce is checked, get the POST data and sign user on
    $info = array();
  	$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = sanitize_user($_POST['username']) ;
    $info['user_pass'] = sanitize_text_field($_POST['password']);
	$info['user_email'] = sanitize_email( $_POST['user_email']);
	
	// Register the user
    $user_register = wp_insert_user( $info );
 	if ( is_wp_error($user_register) ){	
		$error  = $user_register->get_error_codes()	;
		
		if(in_array('empty_user_login', $error))
			echo json_encode(array('loggedin'=>false, 'message'=>__($user_register->get_error_message('empty_user_login'))));
		elseif(in_array('existing_user_login',$error))
			echo json_encode(array('loggedin'=>false, 'message'=>__('This username is already registered.')));
		elseif(in_array('existing_user_email',$error))
        echo json_encode(array('loggedin'=>false, 'message'=>__('This email address is already registered.')));
    } else {
	  auth_user_login($info['nickname'], $info['user_pass'], 'Registration');       
    }
    die();
}

function auth_user_login($user_login, $password, $login)
{
	$info = array();
    $info['user_login'] = $user_login;
    $info['user_password'] = $password;
    $info['remember'] = true;
	
	$user_signon = wp_signon( $info, '' ); // From false to '' since v4.9
    if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
		wp_set_current_user($user_signon->ID); 
        echo json_encode(array('loggedin'=>true, 'message'=>__($login.' successful, redirecting...')));
    }
	
	die();
}

remove_filter( 'the_content', 'wpautop' );

function get_breadcrumb() {
    echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
    if (is_category() || is_single()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_category(' &bull; ');
            if (is_single()) {
                echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
                the_title();
            }
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;Search Results for... ";
        echo '"<em>';
        //echo "&nbsp;:&nbsp;";
        echo the_search_query();
        echo '</em>"';
    }
}



// Number Pagination Function 
 
function tt_number_pagination() {
 
global $wp_query;
$big = 9999999; // need an unlikely integer
  echo paginate_links( array(
   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
   'format' => '?paged=%#%',
   'current' => max( 1, get_query_var('paged') ),
   'total' => $wp_query->max_num_pages) );
}

function techblog_checkContentLength(){
	global $post;
	$checklength = sizeof(explode(" ", $post->post_content));//esc_html($content);
	if($checklength < 250){
		return false;
	}else{
		return true;
	}	
}