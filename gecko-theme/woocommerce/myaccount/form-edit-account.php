<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

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
    "game_types" => $game_types,
    "hunting_types" => $hunting_types,
    "instructor_types" => $instructor_types,
];
$encoded = json_encode($props, JSON_HEX_APOS|JSON_HEX_QUOT);
?>

<div id="learnhunting-account-edit-profile" data-props="<?= htmlspecialchars($encoded, ENT_QUOTES, 'UTF-8') ?>"></div>
