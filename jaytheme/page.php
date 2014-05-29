<?php get_header(); ?>
	<div class="content">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<h1 class="title"><?php the_title(); ?></h1>
			<p><?php the_content(); ?></p>

			<?php endwhile; ?>

		<?php endif; ?>
	</div>	