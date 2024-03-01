<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

$is_mentor = false;
$is_student = false;

$order_id = $order->get_id();

if (get_post_meta($order_id, 'is_mentor', true)) {
	$is_mentor = true;
} elseif (get_post_meta($order_id, 'is_student', true)) {
	$is_student = true;
}

$content = "";

if ($is_mentor && $mentor_content = get_field("welcome_email_instructor_content", "options")) {
	$content = $mentor_content;
} elseif ($is_student && $student_content = get_field("welcome_email_student_content", "options")) {
	$content = $student_content;
}

echo $content;

if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
