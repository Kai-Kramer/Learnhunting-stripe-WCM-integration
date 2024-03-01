<?php
// Custom function to recursively load all php files in a directory
if ( !function_exists("gecko_theme_glob_loader") ){
	function gecko_theme_glob_loader($dir) {
		$scan = glob("$dir/*");

		foreach ($scan as $path) {
			if (preg_match('/\.php$/', $path)) {
				require_once $path;
			}
			elseif (is_dir($path)) {
				gecko_theme_glob_loader($path);
			}
		}
	}
}

// Start with the function directory for all core functions
gecko_theme_glob_loader(__DIR__ . "/functions/");

// Load the API endpoints, these require the API to be loaded before they can be used
gecko_theme_glob_loader(__DIR__ . "/api/");

// new GeckoTheme\Api();
new GeckoTheme\Api\WooAccountDashboard();
