<?php
add_action('edit_user_profile', function($user) {
    wp_enqueue_style("theme-css", get_template_directory_uri()."/dist/editor.css", [], filemtime(get_template_directory() . "/dist/editor.css"));

	wp_enqueue_script(
		"editor-scripts",
		get_template_directory_uri() . "/dist/editor.bundle.js",
		[
            "react",
            "react-dom",
            "wp-element",
            "wp-api-fetch",
            "lodash",
        ],
		filemtime(get_template_directory() . "/dist/editor.bundle.js"),
		true
	);

	wp_localize_script('editor-scripts', 'lhApiSettings', [
		'rootapiurl' => esc_url_raw(rest_url()),
		'nonce' => wp_create_nonce('wp_rest')
	]);

    $game_types = [];
    if ($custom_game_types = get_field("game_types", "option")) {
        $game_types = $custom_game_types;
    }

    $hunting_types = [];
    if ($custom_hunting_types = get_field("hunting_types", "option")) {
        $hunting_types = $custom_hunting_types;
    }

    $instructor_types = [];
    if ($custom_instructor_types = get_field("instructor_types", "option")) {
        $instructor_types = $custom_instructor_types;
    }

    $props = [
        "userId" => $user->ID,
        "game_types" => $game_types,
        "hunting_types" => $hunting_types,
        "instructor_types" => $instructor_types,
    ];
    $encoded = json_encode($props, JSON_HEX_APOS|JSON_HEX_QUOT);

    echo '<div id="learnhunting-admin-edit-profile" data-props="'.htmlspecialchars($encoded, ENT_QUOTES, 'UTF-8').'"></div>';
}, 1, 1);

add_action('init', function() {
    if (!empty($_REQUEST["execute_retro_ping"]) && current_user_can("administrator")) {
        $limit = 40;

        $user_query = new WP_User_Query([
            'number' => -1,
            'orderby' => 'ID',
            'order' => 'ASC',
            // include specific user ids
            // 'include' => [1],
            'role__in' => ['customer', 'subscriber'],
        ]);

        if ($users = $user_query->get_results()) {
            error_log(print_r("Found " . count($users) . " users", true));

            $jwt = new \Jwt_Auth_Public('jwt-auth', '99');

            foreach ($users as $index =>  $user) {
                if ($limit > 0) {
                    if (!get_user_meta($user->ID, 'retro_sso_user_ping', true)) {
                        error_log(print_r("- ping #$index for user $user->ID", true));

                        $jwt->user_id_journeyage_sso($user->ID);

                        update_user_meta($user->ID, 'retro_sso_user_ping', time());

                        usleep(500000);

                        $limit -= 1;
                    }
                }
            }
        }
    }
});
