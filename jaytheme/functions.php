<?php
    if ( !current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }

    add_action( 'init', 'create_post_type' );
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



?>