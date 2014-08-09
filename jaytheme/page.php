<?php get_header(); ?>
    <div class="content">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1 class="title slide-in-from-right"><?php the_title(); ?></h1>
            <p class="slide-in-from-bottom"><?php the_content(); ?></p>

            <?php endwhile; ?>

        <?php endif; ?>
    </div>  
<?php wp_footer(); ?>