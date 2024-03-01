<?php

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
});

add_action('woocommerce_after_edit_account_form', function() {
    $script = '<script type="text/javascript">'.
              'var account_email = document.getElementById("account_email");'.
              'if(account_email) { '.
              '     account_email.readOnly = true; '.
              '     account_email.className += " disable-input";'.
              '}'.
              '</script>';
    echo $script;
});

add_action('woocommerce_save_account_details_errors', function(&$error, &$user) {
	$current_user = get_user_by( 'id', $user->ID );
	$current_email = $current_user->user_email;

	if( $current_email !== $user->user_email){
		$error->add( 'error', 'E-mail address cannot be changed.');
	}
}, 10, 2);

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form', 5);

// Validate that user has no existing memberships/subscriptions before adding to cart
add_filter('woocommerce_add_to_cart_validation', function($passed, $product_id) {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();

        if (wcs_user_has_subscription($user_id, '', 'active')) {
            wc_add_notice('Your member account already has an active subscription, please complete cancellation before signing up for a new membership.', 'error');
            $passed = false;
        } else {
            $args = [
                'status' => [
                    'active',
                    'complimentary',
                    // 'pending',
                ],
            ];
            $active_memberships = wc_memberships_get_user_memberships( $user_id, $args );

            if (!empty($active_memberships)) {
                wc_add_notice('Your member account already has an active membership, please complete cancellation before signing up for a new membership.', 'error');
                $passed = false;
            }
        }

    }

    if( WC()->cart->find_product_in_cart( WC()->cart->generate_cart_id( $product_id ) ) ) {
        global $woocommerce;

        $woocommerce->cart->empty_cart();

        wp_safe_redirect( wc_get_checkout_url() );
    }

    return $passed;
}, 10, 5);

// Custom 'Change Password' endpoint for WooCommerce My Account page
$endpoints = [
    "update-password",
    "update-availability",
];
foreach ($endpoints as $endpoint) {
    add_action('init', function() use ($endpoint) {
        add_rewrite_endpoint($endpoint, EP_ROOT | EP_PAGES);
        add_action('woocommerce_account_'.$endpoint.'_endpoint', function() use ($endpoint) {
            echo '<div id="learnhunting-account-'.$endpoint.'"></div>';
        });

        // Log Mentor Hours
        add_rewrite_endpoint('log-mentor-hours', EP_ROOT | EP_PAGES);
        add_action('woocommerce_account_log-mentor-hours_endpoint', function() {
            $current_user_data = wp_get_current_user();
            // get woocommerce customer by id
            $customer = new WC_Customer($current_user_data->ID);

            $user_name = $customer->get_billing_first_name() . ' ' . $customer->get_billing_last_name();

            $activity_options = get_field("log_mentor_hours_activity_types", "option");
            $activityOptions = [];

            foreach ($activity_options as $activity_option) {
                $activityOptions[] = $activity_option["activity"];
            }

            $props = [
                "name" => $user_name,
                "state" => $customer->get_billing_state(),
                "activityOptions" => $activityOptions,
            ];

            $encoded = json_encode($props, JSON_HEX_APOS|JSON_HEX_QUOT);

            echo '<div id="learnhunting-account-log-mentor-hours" data-props="'.htmlspecialchars($encoded, ENT_QUOTES, 'UTF-8').'"></div>';
        });
    });

    add_filter('query_vars', function($vars) use ($endpoint) {
        $vars[] = $endpoint;

        return $vars;
    }, 0);
}

add_filter('woocommerce_product_single_add_to_cart_text', function($add_to_cart_text, $product) {
    $student_product_id = 303;
    $mentor_product_id = 304;

    if ($product->get_id() === $student_product_id) {
        $add_to_cart_text = 'Sign Up Now';
    } else if ($product->get_id() === $mentor_product_id) {
        $add_to_cart_text = 'Join Now';
    }

    return $add_to_cart_text;
}, 10, 2);

add_filter( 'woocommerce_get_price_html', function($price_html, $product) {
    $student_product_id = 303;
    $mentor_product_id = 304;

    if ($product->get_id() === $student_product_id) {
        $price_html = '30 day free trial';
    }

    if ($product->get_id() === $mentor_product_id) {
        $price_html = 'Free';
    }

    return $price_html;
}, 20, 2 );

add_filter( 'woocommerce_subscriptions_product_price_string_inclusions', function ( $include, $product ) {
    $student_product_id = 303;

    if ($product->get_id() === $student_product_id) {
        $include['subscription_period'] = false;
        $include['subscription_length'] = false;
        $include['trial_length'] = false;
        $include['sign_up_fee'] = false;
    }

    return $include;
} , 21, 2 );

add_filter('woocommerce_min_password_strength', function() {
    return 2;
}, 10);
