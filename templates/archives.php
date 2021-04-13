<?php

/*

Template Name: Archives Template

Template Post Type: post, page

*/

get_header(); ?>



<div class="archives-post content-area">

    <div class="pg">

        <div class="container">

        	<div id="content" class="archives-page" role="main">

                <div class="archivespage">

                    <h3 class="widget-title"><span>By Post: (Last 100 articles) :</span></h3>

                    <ul>

                        <?php

                            $args = array( 
                                'numberposts' => '100',
                                'post_status' => 'publish',
                             );

                            $recent_posts = wp_get_recent_posts( $args );

                            foreach( $recent_posts as $recent ){

                                echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li> ';

                            }

                            wp_reset_query();

                        ?>

                    </ul>

        	   </div><!-- /.archivespage-recent-posts -->

                <div class="most-reply-commentors rivews-sidebar">

                    <!-- <h4>Most Commentators</h4> -->

                    <?php if ( is_active_sidebar( 'archives-sidebar' )  ) : ?>

                        <aside class="activity-sidebar widget-area" role="complementary">

                            <?php dynamic_sidebar( 'archives-sidebar' ); ?>

                        </aside><!-- .sidebar .widget-area -->

                    <?php endif; ?>

                </div>

            </div><!-- #content -->

        </div><!-- /.container -->

    </div>

</div><!-- /.archives-post -->

<?php get_footer(); ?>

