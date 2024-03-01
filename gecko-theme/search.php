<?php get_header(); ?>

	<?php get_template_part("parts/header/page", null, ["title" => "Search"]) ?>

	<div>
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				// get_template_part( 'template-parts/content', 'search' );
			endwhile;

			the_posts_navigation();
		else :
			// get_template_part( 'template-parts/content', 'none' );
		endif;
		?>
	</div>

<?php get_footer(); ?>
