<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$dashboard = new LearnHuntingAccountDashboard();
$is_student = $dashboard->is_student;
$is_mentor = $dashboard->is_mentor;

if ($is_student) {
	do_action('gecko_theme_parts_content', 'Student Dashboard');
} elseif ($is_mentor) {
	do_action('gecko_theme_parts_content', 'Mentor Dashboard');
} else {
	do_action('gecko_theme_parts_content', 'Customer Dashboard');
}

if (!get_user_meta(get_current_user_id(), 'initial_sso_user_ping', true)) {
	$jwt = new \Jwt_Auth_Public('jwt-auth', '99');
    $jwt->send_journeyage_sso();

	update_user_meta(get_current_user_id(), 'initial_sso_user_ping', time());
}
