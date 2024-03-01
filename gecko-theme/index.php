<?php get_header(); ?>

<?php get_template_part("parts/header/page", null, ["title" => "Posts"]) ?>

<?php if ( have_posts() ): ?>

	<div class="gecko-theme-posts">
		<?php while ( have_posts() ): the_post(); ?>

			<?php get_template_part("parts/loop/post"); ?>

		<?php endwhile ?>
	</div>

	<div class="gecko-theme-pagination">
		<?php the_posts_navigation(); ?>
	</div>

<?php else: ?>

	NO POSTS

<?php endif ?>

<?php get_footer();
