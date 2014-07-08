<?php get_header(); ?>
</body>
<body id="duck">
    <div class='content'>
        <h1 id='main' class='title'><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></h1>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="post">
                <h2 id="post-center"><?php the_title(); ?></h2>
                <p id="post-center"><?php the_time('F jS, Y'); ?></p>
                <!-- Can get rid of this if can't make it work -->
                <?php $intro = get_field( "post_intro" ); ?>
                <?php if ( $intro ): ?>
                    <p><?php echo $intro; ?></p>
                    <p><a href="<?php the_permalink(); ?>">View Full Post</a></p>
                <?php else: ?>
                <!-- End the purge -->
                    <p><?php the_content(); ?>
                <?php endif; ?>        
            </div>  
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
    </div>         
<?php wp_footer(); ?>
