<?php
    if ( !current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }

    
    function create_post_type() 
    {
        register_post_type( 'my_heroes', 
                            array(
                                'labels' => array(
                                        'name' => __( 'Heroes'),
                                        'singular_name' => __( ' Hero ')
                                        ),
                                'public' => true,
                                'has_archive' => true,
                                'rewrite' => array( 'slug' => 'hero'),

                            )
                        );
    }
    add_action( 'init', 'create_post_type' );

    function my_scripts_method()
    {
        wp_enqueue_script(
                    'mouseover-description',
                    get_stylesheet_directory_uri() . '/js/test.js',
                    array( 'jquery' )
                );
    }
    add_action( 'wp_enqueue_scripts', 'my_scripts_method')


?>