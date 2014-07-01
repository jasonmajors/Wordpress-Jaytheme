<?php get_header(); ?>
<div id='duck'>
    <div class='content'>
        <h1 class='title'><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></h1>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="post">
                <h2 id="post-center"><?php the_title(); ?></h2>
                <p id="post-center"><?php the_time('F jS, Y'); ?></p>
                <p><?php the_content(); ?></p>
            </div>  
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
    </div>   
</div>       
<?php wp_footer(); ?>
