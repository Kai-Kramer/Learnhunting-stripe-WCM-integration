<?php
use GeckoTheme\ThemeColors;

if (function_exists('acf_register_block_type')) {
	require_once(get_template_directory() . "/acf-theme-part/acf-theme-part.php");
	require_once(get_template_directory() . "/acf-bg-position/acf-bg-position.php");
}

// Register custom ACF blocks here
add_action('acf/init', function() {
	if( function_exists('acf_register_block_type') ) {
		// Category options: gecko-theme, common, formatting, layout, widgets, embed

		// Custom section block to handle content/grid layouts
		acf_register_block_type([
            'name'              => 'gecko-section',
            'title'             => __('Section'),
            'description'       => __('Content wrapper'),
            'render_template'   => 'blocks/section/index.php',
            'category'          => 'layout',
            'icon'              => 'align-wide',
			'keywords'          => ['section'],
			'mode'				=> 'edit',
			'supports'			=> [
				'align' => false,
				'jsx' 	=> true,
			],
		]);

		acf_register_block_type([
            'name'              => 'gecko-geo-logos',
            'title'             => __('Geo Logos'),
            'description'       => __('Display a logo based on user location'),
            'render_template'   => 'blocks/geo-logos.php',
            'category'          => 'content',
            'icon'              => 'admin-site',
			'keywords'          => ['geo', 'logo', 'geotarget', 'target'],
			'mode'				=> 'preview',
			'supports'			=> [
				'align' => false,
			],
		]);

		acf_register_block_type([
            'name'              => 'gecko-agency-logo',
            'title'             => __('Agency Logo'),
            'description'       => __('Display a logo based on state agency'),
            'render_template'   => 'blocks/agency-logo.php',
            'category'          => 'content',
            'icon'              => 'admin-site',
			'keywords'          => ['agency', 'logo'],
			'mode'				=> 'preview',
			'supports'			=> [
				'align' => true,
			],
		]);

		acf_register_block_type([
            'name'              => 'gecko-geo-testimonial',
            'title'             => __('Geo Testimonial'),
            'description'       => __('Display testimonial content based on user location'),
            'render_template'   => 'blocks/geo-testimonial.php',
            'category'          => 'content',
            'icon'              => 'admin-site',
			'keywords'          => ['geo', 'testimonial', 'content', 'geotarget'],
			'mode'				=> 'preview',
			'supports'			=> [
				'align' => false,
			],
		]);

		acf_register_block_type([
            'name'              => 'learnhunting-dashboard-grid',
            'title'             => __('Dashboard Grid'),
            'description'       => __('Display a grid of navigation items used in member dashboards'),
            'render_template'   => 'blocks/learnhunting-dashboard-grid.php',
            'category'          => 'content',
            'icon'              => 'admin-site',
			'keywords'          => ['dashboard', 'grid'],
			'supports'			=> [
				'align' => false,
			],
		]);
    }
});

// Register a custom "Theme Settings" page for global ACF settings
add_action('init', function() {
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page([
			'page_title' 	=> 'LearnHunting.org - Theme Settings',
			'menu_title'	=> 'Theme Settings',
			'menu_slug' 	=> 'gecko-theme-settings',
			'capability'	=> 'edit_posts',
			'position'		=> '61.4',
			'icon_url'		=> 'dashicons-admin-settings',
			'redirect'		=> false,
		]);
	}
});

// This function adds custom colors to the ACF color picker
add_action('acf/input/admin_footer', function() {
	?>
	<script type="text/javascript">
	(function($) {
		acf.add_filter('color_picker_args', function(args, $field) {
			args.palettes = <?= json_encode(ThemeColors::all()) ?>;
			return args;
		});
	})(jQuery);
	</script>
	<?php
});
