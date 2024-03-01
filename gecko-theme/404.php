<?php get_header(); ?>

	<?php get_template_part("parts/header/page", null, ["title" => "Page Not Found"]) ?>

	<div class="row">
		<p>It looks like nothing was found at this location. Maybe try one of the links below or a search?</p>

		<?php get_search_form(); ?>
	</div>

<?php get_footer(); ?>
