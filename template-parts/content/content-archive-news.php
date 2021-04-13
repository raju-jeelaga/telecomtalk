<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package webnews
 */

 ?>
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