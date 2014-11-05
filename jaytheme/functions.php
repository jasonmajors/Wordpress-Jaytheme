<?php
    if ( !current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }
    
    function create_post_types() 
    {
        register_post_type( 'my_heroes', 
                            array(
                                'labels' => array(
                                    'name' => __( 'Heroes' ),
                                    'singular_name' => __( 'Hero' )
                                    ),
                                'public' => true,
                                'has_archive' => true,
                                'rewrite' => array( 'slug' => 'hero'),

                            )
                        );
    }


    function my_scripts_method()
    {
        wp_enqueue_script(
                    'mouseover-description',
                    get_stylesheet_directory_uri() . '/js/test.js',
                    array( 'jquery' ),
                    $in_footer=true
                );
    }

    function my_logo_login() { 
        ?>
        <!-- quick dirty css, im lazy -->
        <style type="text/css">
            .login #login h1 a {
                background-image: url( "<?php echo get_stylesheet_directory_uri(); ?>/images/login-bg.png" );
                background-size: auto;
                height: 150px;
                width: auto;
            }

        </style>  <!-- end quick and dirty -->  
    <?php }

    function modify_dashboard()
    {   
        if ( !current_user_can( 'manage_options' ) ) {
            remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
            remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
            remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
        }
    }
    // Add the hooks for the relevant events.
    add_action( 'login_enqueue_scripts', 'my_logo_login');
    add_action( 'init', 'create_post_types' );
    add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
    add_action( 'wp_dashboard_setup', 'modify_dashboard' );

?>