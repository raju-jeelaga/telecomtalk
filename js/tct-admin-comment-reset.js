jQuery(document).ready(function($) {
	$('.reset_like_dislike').click(function(){
		if (confirm('Are you sure to reset comments Like Dislike count?')){
			var data = {
				'action': 'reset_comments_like_dislike',
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			$.post(tct_ajax_object.ajax_url, data, function(response) {
				$('.like_dislike_status').html(response);
			});
		}else{
			return false;
		}
	});
});