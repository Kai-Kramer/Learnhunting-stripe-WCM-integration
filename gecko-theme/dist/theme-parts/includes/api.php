<?php
namespace GeckoTheme;

class ThemePartsApi {
	var $namespace = 'gecko-theme-parts/v1';

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'rest_api_init']);
	}

	public function rest_api_init()
	{
		register_rest_route($this->namespace, '/location-management-data', [
			'methods' => 'GET',
			'callback' => [$this, 'get_data'],
			'permission_callback' => "__return_true",
		]);
		register_rest_route($this->namespace, '/location-management-data', [
			'methods' => 'POST',
			'callback' => [$this, 'update_data'],
			'permission_callback' => "__return_true",
		]);
	}

	function get_data() {
		$return = [];

		// Theme Parts
		$theme_parts_posts = get_posts([
			'post_type' => 'gecko-theme-part',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title',
		]);

		$theme_parts = [];
		foreach ($theme_parts_posts as $index => $theme_part) {
			$theme_parts[] = [
				'id' => $theme_part->ID,
				'index' => $index,
				'title' => $theme_part->post_title,
			];
		}
		$return['themeParts'] = $theme_parts;

		$return['locations'] = $this->get_locations();

		return $return;
	}

	function update_data($request) {
		$return = [];
		$params = $request->get_params();
		$locations = $params['locations'];

		foreach ($locations as $key => $location) {
			update_site_option('gecko_theme_part_'.$key, $location['post_id']);
			do_action("set_theme_part_location", $location);
		}

		$return['locations'] = $this->get_locations();

		return $return;
	}

	function get_locations() {
		global $_gecko_theme_parts;
		$locations = [];

		foreach ($_gecko_theme_parts as $key => $details) {
			$location_post_id = get_site_option('gecko_theme_part_' . $key, false);

			$location = $details;
			$location['post_id'] = $location_post_id;
			if ($location_post_id > 0) {
				$location['edit_link'] = admin_url("post.php?post=" . $location_post_id . "&action=edit");
			}
			$locations[] = $location;
		}

		return $locations;
	}
}
