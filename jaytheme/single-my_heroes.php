<?php if (is_user_logged_in()) : ?>
<?php get_header(); ?>
    <div class="content">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1 class="title"><?php the_title(); ?></h1>
            <?php 
            // Get the field values from advanced custom fields.
                $desc = get_field( "description" );
                $power = get_field( "tagline" ); 
            ?>
            <h3>Description: <?php echo $desc; ?></h3>
            <h3>Superpower: <?php echo $power; ?></h3>
            <?php
                $image = get_field( "image" );
                if (!empty( $image ) ): ?>
                    <img src="<?php echo $image['url']; ?>" />
                <?php endif; ?>  
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>  
<?php else: ?>
    <?php echo "<h2>Login Required</h2>"; ?>
<?php endif; ?>

<?php wp_footer(); ?>