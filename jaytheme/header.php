<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    	<div id='navbar'>
    		<ul>
    		<?php
    		// Build inline list of pages on the navbar.
				$home = "<li><a href='/wp'>J</a></li>";
				$pages = get_pages();
				echo $home;
				foreach ($pages as $page) {
					$link = '<li><a href="' . get_page_link($page->ID) . '">';
					$link .= $page->post_title;
					$link .= '</a></li>';
					echo $link;
				}
			?>	
			</ul>
			
		</div>	