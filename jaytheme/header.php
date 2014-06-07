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
                if ( is_user_logged_in() ) {
                    $current_user = wp_get_current_user();
                    $name = $current_user->user_firstname;
                    $msg = "<li>Welcome, $name! </li>";
                    echo $msg;
                    $logged_in = true;
                }
                
                $home = "<li><a href='/wp'>J</a></li>";
                echo $home;
                $pages = get_pages();
                $logout = wp_logout_url();
                
                
                foreach ($pages as $page) {
                    $link = '<li><a href="' . get_page_link($page->ID) . '">';
                    $link .= $page->post_title;
                    $link .= '</a></li>';
                    $access = get_field( "access", $page->ID );
                    if (isset($access) && $access == 'private') {
                        if ( $logged_in ) {
                            echo $link;
                            continue;
                            
                        } else {
                            continue;
                        }
                    }

                    echo $link; 
                }

                if ( $logged_in ) {
                    echo "<li><a href='$logout'>Log Out</a></li>";
                }
                else {
                    echo "<li><a href='/wp/login'>Log in</a></li>";
                }
            ?>  
            </ul>
            
        </div>  