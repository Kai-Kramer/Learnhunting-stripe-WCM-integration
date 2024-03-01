<?php
/**
 * The header for our theme.
 *
 * @package template
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo("charset"); ?>">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-top-of-page="true">
<?php wp_body_open(); ?>

<?php get_template_part( "parts/header/aside-menu") ?>

<?php get_template_part( "parts/header/primary") ?>

<main>
	<section>
