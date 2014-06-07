<?php get_header(); ?>
    <div class="content">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1 class="title"><?php the_title(); ?></h1>
            <p><?php the_content(); ?></p>
            <?php
            // ACF Plugin.
            	$field_key = "field_538e9791c2414";
            	$field = get_field_object($field_key);
            	echo $field['label'] . ':' . ' ' . $field['value'];
            	
            ?>

        <?php endwhile; ?>

        <?php endif; ?>
    </div>  