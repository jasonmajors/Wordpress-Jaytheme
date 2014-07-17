<!-- Only allowing access to this for logged in users while developing -->
<?php if (is_user_logged_in()) : ?>
<?php get_header(); ?>
    <div class="content">
        <h1 class="title"><?php the_title(); ?></h1>
        <?php 
            $mypost = array( 'post_type' => 'my_heroes' );
            $loop = new WP_Query( $mypost );
            while ( $loop->have_posts() ) : $loop->the_post();
                $tagline = get_field( "tagline" ); ?>

                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <h4><?php echo $tagline; ?></h4>

            <?php endwhile; ?>  
    </div>            
    <?php wp_reset_query(); ?>
<?php else: ?>
    <?php echo "<h2>LOGIN REQUIRED</h2>"; ?>
<?php endif; ?>
<?php wp_footer(); ?>