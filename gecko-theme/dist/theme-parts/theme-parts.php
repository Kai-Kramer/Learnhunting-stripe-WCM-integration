<?php
namespace GeckoTheme;

class ThemeParts {
	public function __construct() {
        $base_path = get_template_directory() . '/dist/theme-parts';

        require_once $base_path . '/admin.php';
        new ThemePartsAdmin();

        require_once $base_path . '/includes/post-type.php';
        new ThemePartsPostType();

        require_once $base_path . '/includes/locations.php';
        new ThemePartsLocations();

        require_once $base_path . '/includes/api.php';
        new ThemePartsApi();
	}
}
