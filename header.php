<?php

/**

 * The header for our theme

 *

 * This is the template that displays all of the <head> section and everything up until <div id="content">

 *

 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials

 *

 * @package telecom-talk

 */

?>

<!doctype html> 

<html <?php language_attributes(); ?>>

<head>
	<?php echo get_option('header_scripts_option');?>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Chrome, Firefox OS and Opera -->
	<meta name="theme-color" content="#003f76">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<!-- <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Lora:ital@1&display=swap" rel="stylesheet"> -->
	<?php do_action("tt_show_metatags"); ?>	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>


<div id="page" class="site">

	<header class="site-header header-navigation" id="header-part">
		<div class="h-m">

		</div>
		<?php 
		$myposts = get_posts( array(
	        'posts_per_page' => 5,
	        'category'       => 680,
	        'order'          => 'DESC',
	    ) );
		?>
		<div class="breaking-news">
			<span>BREAKING NEWS</span>
			<marquee scrollamount="2" class="mvt" onMouseOver="this.stop()" onMouseOut="this.start()">
				<?php if ( $myposts ) { ?>
					<ul>
						<?php 
						foreach ( $myposts as $post ) {
	            			setup_postdata( $post ); 
	            		?>
							<li><?php echo strip_tags(get_the_title());?></li>
						<?php 
						}
						wp_reset_postdata();
						?>
					</ul>
				<?php } ?>
			</marquee>
		</div>
		<div class="head-sec">
			<div class="container">
				<div class="h-s">
					<div class="lg">
						<a href="<?php echo esc_url( home_url() ); ?>">
		                <?php 
		                $custom_logo_id = esc_attr( get_theme_mod( 'custom_logo' ) );
		                if( $custom_logo_id ) {
		                	$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		                }
		                if ( has_custom_logo() ) {       	
		                    echo '<img src="'. esc_url( $logo[0] ) .'" width="140" height="32" alt="logo-2">';
		                } else {
		                    echo '<h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span>';
		                } ?>
		              </a>
					</div><!-- /.lg -->
					<div class="primary-menu">
						<?php if(has_nav_menu ('primary_nav'))
					    		wp_nav_menu( array( 
					    			'container' => '',
					    			'container_id' => '',
					    			'theme_location' => 'primary_nav',
					    			'sort_column' => 'menu_order',
					    			'menu_class' => 'menu',
					    			//'walker' => $m3walker  
					    		)
					    	);
					    ?>
					</div><!-- /. p-m -->
				</div><!-- /.h2 -->
			</div><!-- /.container -->
		</div><!-- /. head-2 -->
		<div class="mobile-sec">
			<nav id="site-navigation" class="main-nav drawer drawer--left" role="navigation">
	            <div class="mobile-menu">
	                <button type="button" class="drawer-toggle drawer-hamburger">
	                <span class="sr-only">toggle navigation</span>
	                <span class="drawer-hamburger-icon"></span>
	                </button>
	                <nav class="drawer-nav" role="navigation">
	                  <div class="drawer-menu">
	                      <h3><?php //echo esc_attr_x( 'Menu', 'TT_theme' ) ?></h3>
	                    <?php
	                    if ( has_nav_menu( 'mobile-menu' ) ) {
	                      wp_nav_menu(array(
	                      'theme_location' => 'mobile-menu',
	                      'menu_class'     => 'm-menu',
	                    ));
	                    } ?>
	                  </div>
	                </nav>
	            </div><!-- /.mobile-menu -->
	        </nav><!-- .menu-1 -->
			<div class="lg">
				<a href="<?php echo esc_url( home_url() ); ?>">
                <?php 
                $custom_logo_id = esc_attr( get_theme_mod( 'custom_logo' ) );
                if( $custom_logo_id ) {
                	$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                }
                if ( has_custom_logo() ) {       	
                    echo '<img src="'. esc_url( $logo[0] ) .'" width="140" height="32" alt="logo-2">';
                } else {
                    echo '<h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span>';
                } ?>
              </a>
			</div><!-- /.lg -->
		</div>
		
	</header><!-- #masthead -->
	<?php 
	$ad_1 = get_option('ad_1');
          	  $ad_1_post_enable = get_option('ad_1_post_enable');
        	if( $ad_1_post_enable &&  !is_page_template( array('templates/no-ads-template.php', 'templates/review-no-ads-template.php', 'templates/full-width-no-ads-template.php') )  ){ 
        		if( is_singular() && techblog_checkContentLength() ){
        		?>
	            <div class="below-header-ad text-center">
	                <?php echo $ad_1;?>
	            </div>
    	<?php } else 
    	if( is_home() && $ad_1_post_enable ){ ?>
    		<div class="below-header-ad text-center">
                <?php echo $ad_1;?>
            </div>
    	<?php } // is home

    } ?>

<div id="content" class="site-content">