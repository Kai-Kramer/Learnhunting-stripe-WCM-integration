<?php
namespace GeckoTheme;

class ThemePartsAdmin {
	public function __construct() {
		add_action('admin_enqueue_scripts', function () {
			$this->register_scripts();
		});

		add_action('admin_menu', function () {
			$location_menu = add_submenu_page(
				'edit.php?post_type=gecko-theme-part',
				'Location Management',
				'Locations',
				'manage_options',
				'gecko-theme-part-locations',
				[$this, 'render_locations_page']
			);

			add_action('admin_print_scripts-'. $location_menu, function () {
				wp_enqueue_script('location-management');
			});

			add_submenu_page(
				'edit.php?post_type=gecko-theme-part',
				'Theme Parts Help',
				'Help',
				'manage_options',
				'gecko-theme-parts-help',
				[$this, 'render_help_page']
			);
		});
	}

	public function render_locations_page()
	{
		$locations_admin = get_template_directory() . '/dist/theme-parts/admin/partials/gecko-theme-parts-locations-admin.php';
        if (file_exists($locations_admin)) {
			include($locations_admin);
		}
	}

	public function render_help_page()
	{
		$help_page = get_template_directory() . '/dist/theme-parts/admin/partials/gecko-theme-parts-help.php';
		if (file_exists($help_page)) {
			include($help_page);
		}
	}

	public function register_scripts()
	{
		$scripts = [
			'location-management' => ['react', 'react-dom', 'wp-editor', 'wp-element'],
		];

		foreach ($scripts as $script => $deps) {
			$this->register_script($script, $deps);
		}
	}

	private function register_script($slug, $deps = [])
	{
        $script_path = get_template_directory() . '/dist/theme-parts/admin/build/scripts/' . $slug . '.js';
		$script_url = get_template_directory_uri() . '/dist/theme-parts/admin/build/scripts/' . $slug . '.js';

		if (file_exists($script_path)) {
			wp_register_script($slug, $script_url, $deps, filemtime($script_path));
		}
	}
}
