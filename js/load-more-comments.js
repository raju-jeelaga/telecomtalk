jQuery(function($){
 
	// load more button click event
	$('.load-more-comments').click( function(){
		var button = $(this);
 		$('.tct-comment-form').show();
		$.ajax({
			url : ajaxurl, // AJAX handler, declared before
			data : {
				'action': 'commentsloadmore', // wp_ajax_cloadmore
				'post_id': parent_post_id, // the current post
				'cpage' : cpage,
			},
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); // preloader here
			},
			success : function( data ){
				if( data ) {
					$('.tct-comment-form').show();
					$('.comment-list').append( data );
					$(".tct-like-dislike-trigger").click(function () {
						$('.modal').show();
					});
					// $('.emoji-code').click(function(){
					// 	var id = $(this).attr('id').replace('e','');
					// 	$('.emoji-code').removeClass('active');
					// 	$(this).addClass('active');
					// 	insertEmoji(emojis[id]);
					// 	//$('#comment').append($(this).data('emoji'));
					// });
					button.text('More comments');

					 // if the last page, remove the button
					if ( cpage == 1 ){
						button.remove();
						$('.comments-area').append( '<span class="no-more-comments">No More Comments</span> ' );
					}
					cpage--;
				} else {
					button.remove();
				}
			}
		});
		
		return false;
	});
 	function insertEmoji(emoji) {
		var commentBox = document.getElementById('comment');
		commentBox.value =  commentBox.value + emoji;
	}
	$('.emoji-code').click(function(){
		var id = $(this).attr('id').replace('e','');
		$('.emoji-code').removeClass('active');
		$(this).addClass('active');
		insertEmoji(emojis[id]);
		//$('#comment').append($(this).data('emoji'));
	});
});