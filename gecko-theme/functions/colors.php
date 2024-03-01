<?php
namespace GeckoTheme;

class ThemeColors {
	/**
	 * Return an array of colors registered in the theme.json file
	 * Optionally, set $return_with_slug to true to return each color as an array with color, slug and name.
	 *
	 * @param boolean $return_with_slug
	 * @return mixed
	 */
	public static function all($return_with_slug = false) {
		// Get the theme.json file contents as decoded json.
		$theme = json_decode( file_get_contents( get_stylesheet_directory() . "/theme.json" ), true);

		// Merge the pre-set palette and the custom colors
		$colors = array_merge(
			$theme["settings"]["color"]["palette"],
			$theme["settings"]["color"]["custom"] ? $theme["settings"]["color"]["custom"] : []
		);

		// Return the merged colors as-is if the slug is requested
		if ($return_with_slug){
			return $colors;
		}

		// If no slug is requested, just return the color column
		return array_column($colors, "color");
	}

	/**
	 * Return a specific color from the theme.json file by slug
	 *
	 * @param [type] $slug The slug (from the theme.json file) of the color to be returned
	 * @return null | string Returns null or the string color
	 */
	public static function get($slug){
		// If no slug is passed in, return null
		if (!$slug){
			return null;
		}

		// Get all colors and filter them with the provided slug.
		$color = array_filter( self::all(true), function ($color) use ($slug) {
			if ($color["slug"] === $slug){
				return $color;
			}
		});

		// Return just the color code or null if the filter didn't leave anything.
		return array_key_exists(0, $color) ? $color[0]["color"] : null;
	}
}