<?php
add_action("after_setup_theme", function()
{
	new GeckoTheme\Init();

	add_theme_support("title-tag");
	add_theme_support("post-thumbnails");
	add_theme_support("automatic-feed-links");
	add_theme_support("html5", [
		"search-form",
		"comment-form",
		"comment-list",
		"gallery",
		"caption",
	]);

	register_nav_menus([
		"primary" => "Primary",
		"footer" => "Footer",
	]);

	add_theme_support('editor-styles');
	// add_editor_style( get_template_directory_uri() . '/dist/theme.css');

	register_block_style("core/button", [
		"name" => "primary",
		"label" => "Primary",
	]);

	add_filter('gecko_theme_parts_register_locations', function($locations) {
		$locations[] = [
			'name' => 'Footer',
			'description' => 'Primary footer area content',
		];

		$locations[] = [
			'name' => 'Student Dashboard',
			'description' => 'Student dashboard area content',
		];

		$locations[] = [
			'name' => 'Mentor Dashboard',
			'description' => 'Mentor dashboard area content',
		];

		$locations[] = [
			'name' => 'Customer Dashboard',
			'description' => 'Customer dashboard area content',
		];

		return $locations;
	}, 10, 1);

	add_filter( 'block_categories', function ( $categories, $post ) {
		return array_merge(
			$categories,
			[
				[
					'slug' => 'gecko-theme',
					'title' => __( 'Gecko Theme', 'gecko-theme' ),
				],
			]
		);
	}, 10, 2);
});

add_action("wp_enqueue_scripts", function() {
	// wp_enqueue_style("theme-tailwind-utilities", get_template_directory_uri() . "/dist/tailwind-utilities.css", [], filemtime(get_template_directory() . "/dist/tailwind-utilities.css") );
	wp_enqueue_style("theme-styles", get_template_directory_uri() . "/dist/theme.css", [], filemtime(get_template_directory() . "/dist/theme.css") );

	$theme_scripts_deps = [
		"react",
		"react-dom",
		"wp-element",
		"wp-api-fetch",
		"lodash",
	];

	wp_enqueue_script(
		"theme-scripts",
		get_template_directory_uri() . "/dist/theme.bundle.js",
		$theme_scripts_deps,
		filemtime(get_template_directory() . "/dist/theme.bundle.js"),
		true
	);

	wp_localize_script('theme-scripts', 'lhApiSettings', [
		'rootapiurl' => esc_url_raw(rest_url()),
		'nonce' => wp_create_nonce('wp_rest')
	]);
});

add_action('enqueue_block_editor_assets', function () {
	wp_enqueue_style("theme-css", get_template_directory_uri()."/dist/editor.css", [], filemtime(get_template_directory() . "/dist/editor.css"));
});

add_action('admin_menu', function() {
	add_users_page(
		'Safety Check Bypass',
		'Safety Check Bypass',
		'manage_options',
		'safety-check-bypass',
		function() {
			echo '<div class="wrap">';
				echo '<h1>Safety Check Bypass</h1>';
				echo '<p>Enter the email address of the user that needs to bypass the safety check and choose their membership type:</p>';

				if (!empty($_POST["bypass_email"])) {
					$safe_token = md5($_POST["bypass_email"] . "__learn-hunting-safety-check-bypass");

					$bypass_product_id = $_POST["bypass_product_id"];

					echo '<strong style="font-size: 1.2rem;">' . wc_get_cart_url() . "?add-to-cart=" . $bypass_product_id . "&safe=" . $safe_token . '</strong>';

					echo '<p>This link will not expire and will only work with the email address and membership type specified.</p>';
				} else {
					echo '<form method="post" action="">';
						echo '<div style="margin-bottom: 1rem">';
							echo '<input type="email" name="bypass_email" required="required" value="" placeholder="email@address.com" />';
						echo '</div>';

						echo '<div style="margin-bottom: 1rem">';
							// Choose from all products
							$products = wc_get_products([
								"limit" => -1,
							]);
							echo '<select name="bypass_product_id" required="required">';
								echo '<option selected disabled value="">Choose a membership type (product)</option>';
								foreach ($products as $product) {
									echo '<option value="' . $product->get_id() . '">' . $product->get_name() . '</option>';
								}
							echo '</select>';
						echo '</div>';

						echo '<div>';
							echo '<button type="submit">Generate URL</button>';
						echo '</div>';
					echo '</form>';
				}

			echo '</div>';
		}
	);
});

add_filter( 'woocommerce_add_to_cart_redirect', function($url, $product) {
    if ($product && is_a($product, 'WC_Product') && !empty($_REQUEST["safe"])) {
		$_SESSION["lh_safety_check_bypass"] = $_REQUEST["safe"];
    }

    return $url;
}, 10, 2 );

remove_filter('woocommerce_form_field_date', 'wc_checkout_fields_date_picker_field', 10);
