<?php get_header(); ?>
<!-- Need to close the body tag and reopen with duck ID to apply the bg -->
<!-- Sketchy? I don't know... -->
</body>
<body id="index-bg">
    <div class='content'>
        <h1 class='title' id='main'><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></h1>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="post">
                <h2 id="post-center"><?php the_title(); ?></h2>
                <p id="post-center"><?php the_time('F jS, Y'); ?></p>
                <!-- Can get rid of this if can't make it work -->
                <!-- End the purge -->
                <p><?php the_content(); ?>        
            </div>  
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
    </div>   
</div>       
<?php wp_footer(); ?>