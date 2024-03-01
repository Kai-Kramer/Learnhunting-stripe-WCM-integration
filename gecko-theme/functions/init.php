<?php
namespace GeckoTheme;

class Init {
	public function __construct() {
		$this->load_api_endpoints();

		$this->load_theme_parts();

		new Svg();
	}

	// Initialize API endpoints
	function load_api_endpoints() {
		// new Api\ExampleReactBlock();
	}

	// Load Gecko Theme Parts functionality
	function load_theme_parts() {
		require_once get_template_directory() . '/dist/theme-parts/theme-parts.php';
		new ThemeParts();
	}
}
