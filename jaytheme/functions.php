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

        register_post_type( 'subscriber',
                            array(
                                'labels' =>array(
                                    'name' => __( 'Subscriber' ),
                                    'singular_name' => __( 'Subscriber' )
                                    ),
                                'public' => true
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

    // Add the hooks for the relevant events.
    add_action( 'init', 'create_post_types' );
    add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

?>