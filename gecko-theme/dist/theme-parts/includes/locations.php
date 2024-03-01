<?php
namespace GeckoTheme;

global $_gecko_theme_parts;
$_gecko_theme_parts = [];

class ThemePartsLocations {

	public function __construct()
	{
		add_action('load-edit.php', [$this, 'update_locations']);

		// Get all locations via filter
		add_action('after_setup_theme', function() {
			$locations = apply_filters('gecko_theme_parts_register_locations', []);
			$this->register_locations($locations);
		}, 99);

		add_action('gecko_theme_parts_content', function ($location) {
			global $_gecko_theme_parts;

			if (!$location || $location == "") return

			$locationIndex = -1;
			foreach ($_gecko_theme_parts as $key => $details) {
				if ($details['name'] == $location) {
					$locationIndex = $key;
				}
			}

			if ($locationIndex >= 0) {
				$post_id = get_site_option('gecko_theme_part_' . $locationIndex);
				if (!$post_id) return;

				$part = get_post($post_id)->post_content;
				$part = apply_filters('the_content', $part);
				echo $part;
			}
		}, 10, 1);
	}

	public function update_locations()
	{
		if (isset($_REQUEST['gecko-theme-part'])) {
			$theme_part = $_REQUEST['gecko-theme-part'];
			$location = update_site_option('gecko_theme_part_' . $theme_part['location'], $theme_part['post_id']);
		}
	}

	static function register_locations($locations = [])
	{
		global $_gecko_theme_parts;
		$_gecko_theme_parts = array_merge((array) $_gecko_theme_parts, $locations);
	}
}
