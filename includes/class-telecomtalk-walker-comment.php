<?php
/**
 * Custom comment walker for this theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

if ( ! class_exists( 'Telecomtalk_Walker_Comment' ) ) {
	/**
	 * CUSTOM COMMENT WALKER
	 * A custom walker for comments, based on the walker in Twenty Nineteen.
	 */
	class Telecomtalk_Walker_Comment extends Walker_Comment {

		
		protected function html5_comment( $comment, $depth, $args ) {

			$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

			?>
			<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
					<footer class="comment-meta">
						<div class="comment-author vcard">
							<?php
							$comment_author_url = get_comment_author_url( $comment );
							$comment_author     = get_comment_author( $comment );
							$avatar             = get_avatar( $comment, $args['avatar_size'] );
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
							    <a href="#modal-close" title="Close" class="modal-close">X</a>
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
								'depth'     => $depth,
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
								'depth'     => $depth,
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

				</article><!-- .comment-body -->

			<?php
		}
	}
}
