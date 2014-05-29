<?php get_header() ?>
        <div class='content'>
            <h1 class='title'><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></h1>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div id="the-post">
                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                    <p><?php the_content(); ?></p>
                    <p>Posted: <?php the_time('F jS, Y'); ?> by <?php the_author_posts_link(); ?></p>
                </div>
                <div id="the-comment">
                    <?php comments_number() ?>
                </div>  
            <?php endwhile; else: ?>
                <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
            <?php endif; ?>
        </div>      
        </body>
    </html> 