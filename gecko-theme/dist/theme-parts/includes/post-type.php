<?php
namespace GeckoTheme;

class ThemePartsPostType {

	public function __construct()
	{
		add_action('init', [$this, 'init_cpt']);
	}

	public function init_cpt()
	{
		$labels = [
			'name' => __('Theme Part', 'gecko-theme'),
			'singular_name' => __('Theme Part', 'gecko-theme'),
			'add_new' => __('Add New', 'gecko-theme'),
			'add_new_item' => __('Add New Theme Part', 'gecko-theme'),
			'edit_item' => __('Edit Theme Parts', 'gecko-theme'),
			'new_item' => __('New Theme Part', 'gecko-theme'),
			'view_item' => __('View Theme Parts', 'gecko-theme'),
			'search_items' => __('Search Theme Parts', 'gecko-theme'),
			'not_found' => __('No Theme Parts found', 'gecko-theme'),
			'not_found_in_trash' => __('No Theme Parts found in Trash', 'gecko-theme'),
			'menu_name' => __('Theme Parts', 'gecko-theme'),
		];

		$args = [
			'labels' => $labels,
			'hierarchical' => false,
			'description' => __('Theme Parts', 'gecko-theme'),
			'supports' => array('title', 'slug', 'editor'), // What does the Asset Support
			'public' => false, // Should the cpt have urls
			'show_ui' => true,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'menu_position' => 25,
			'menu_icon' => 'dashicons-welcome-widgets-menus', // https://developer.wordpress.org/resource/dashicons/
			'show_in_nav_menus' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => false, // Slug for the Archive Page
			'query_var' => false,
			'can_export' => false,
			'rewrite' => false,
			'capability_type' => 'post' // If you want hierarchical use page
		];

		register_post_type('gecko-theme-part', $args);

		// add_filter('manage_edit-gecko-theme-part_columns', [$this, 'columns']);
		// add_action('manage_gecko-theme-part_posts_custom_column', [$this, 'location_column'], 10, 2);
	}

	public function columns($columns)
	{
		$columns = [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'theme-location' => 'Location',
		];

		return $columns;
	}

	public function location_column($column, $post_id)
	{
		if ($column == 'theme-location') {

			global $_gecko_theme_parts;
			$location_string = '';
			foreach ($_gecko_theme_parts as $key => $location) {
				$location_post_id = get_site_option('gecko_theme_part_' . $key, false);
				if ($location_post_id == $post_id) {
					$location_string .= $location['name'].', ';
				}
			}
			$location_string = rtrim($location_string, ', ');
			if ($location_string != "") {
				echo '<a href="'.admin_url('edit.php?post_type=gecko-theme-part&page=gecko-theme-part-locations').'">'.$location_string.'</a>';
			}
		}
	}
}
