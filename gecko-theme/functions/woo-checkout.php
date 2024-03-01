<?php

// Update core checkout fields/field layout
add_filter('woocommerce_checkout_fields' , function($fields) {
    // $fields['billing']['billing_email']['priority'] = 21;
    // $fields['billing']['billing_phone']['priority'] = 22;

    unset($fields['billing']['billing_company']);
    unset($fields['order']['order_comments']);

    if ($custom_state_desc = get_field("custom_state_description", "options")) {
        $fields['billing']['billing_state']['description'] = $custom_state_desc;
    }

    return $fields;
}, 20, 1);

add_filter('woocommerce_default_address_fields', function($fields) {
    $fields['address_1']['required'] = false;
    $fields['city']['required'] = false;

    return $fields;
});

// Add custom fields after order notes section
add_action('woocommerce_after_order_notes', function($checkout) {
    $customCheckout = new LearnHuntingCheckout();

    $safeValue = (!empty($_SESSION["lh_safety_check_bypass"]) ? $_SESSION["lh_safety_check_bypass"] : "");
    woocommerce_form_field( 'safe', [
        'type' => 'hidden',
        'class' => [],
        'label' => '',
    ], sanitize_text_field($safeValue));

    woocommerce_form_field( 'birth_year', [
        'type' => 'date',
        'class' => ['form-row-first'],
        'label' => 'Date of Birth',
        'description' => 'This program is only for adults over 18 years of age',
        'required' => true,
    ], $checkout->get_value('birth_year'));

    if ($customCheckout->is_mentor) {
        woocommerce_form_field( 'first_hunt_age', [
            'type' => 'text',
            'class' => ['form-row-last'],
            'label' => 'First hunt age',
        ], $checkout->get_value('first_hunt_age'));

        echo '<div class="form-row form-row-first">';
            $customCheckout->huntingTypeInputs();

            echo '<br />';

            $customCheckout->instructorTypeInputs();

            echo '<br />';

            $customCheckout->availabilityInputs();
        echo '</div>';

        echo '<div class="form-row form-row-last">';
            $customCheckout->gameTypeInputs();
        echo '</div>';

        woocommerce_form_field( 'is_mentor', [
            'type' => 'hidden',
        ], 1);
    }

    if ($customCheckout->is_student) {
        woocommerce_form_field( 'is_student', [
            'type' => 'hidden',
        ], 1);
    }

    echo '<p class="form-row form-row-wide">You\'ll have an opportunity to update your profile information after you complete the registration process.</p>';
});

function add_stripe_customer_id_to_user_meta($user_id) {
    $stripe_testmode = get_option('woocommerce_stripe_settings')['testmode'];
    $stripe_live_secret_key = get_option('woocommerce_stripe_settings')['secret_key'];
    $stripe_test_secret_key = get_option('woocommerce_stripe_settings')['test_secret_key'];
    $stripe_secret_key = ($stripe_testmode === true) ? $stripe_live_secret_key : $stripe_test_secret_key;

    \Stripe\Stripe::setApiKey($stripe_secret_key); // Replace with your actual Stripe Secret Key

    $user_data = get_userdata($user_id);

    $customer = \Stripe\Customer::create([
        'email' => $user_data->user_email,
        // Other customer data as needed
    ]);

    // Obtain the customer ID
    $stripe_customer_id = $customer->id;
    error_log('wp__stripe_customer_id: ' . $stripe_customer_id);
    // Store the customer ID in your WordPress user's metadata
    update_user_meta($user_id, 'wp__stripe_customer_id', $stripe_customer_id);
}

// add_action('user_register', 'add_stripe_customer_id_to_user_meta');

// Validation
add_action('woocommerce_checkout_process', function() {
    $student_product_id = 303;
    $mentor_product_id = 304;

    $is_student = false;
    $is_mentor = false;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        if ($student_product_id === $cart_item['product_id']) {
            $is_student = true;
        } else if ($mentor_product_id === $cart_item['product_id']) {
            $is_mentor = true;
        }
    }

    if ($is_mentor) {
        if (!empty($_POST['first_hunt_age']) && (intval($_POST['first_hunt_age']) <= 0 || intval($_POST['first_hunt_age']) > 200)) {
            wc_add_notice("Please enter a valid first hunt age", "error");
        }

        if (empty($_POST['hunting_type'])) {
            wc_add_notice("You much select at least one <strong>Hunting Type</strong>", "error");
        }

        if (empty($_POST['game_type'])) {
            wc_add_notice("You much select at least one <strong>Game Type</strong>", "error");
        }

        if (empty($_POST['instructor_type'])) {
            wc_add_notice("You much select an <strong>Instructor Type</strong>", "error");
        }

        if (empty($_POST['is_available'])) {
            wc_add_notice("You much select an availability option", "error");
        }
    }

    if (empty($_POST['birth_year'])) {
        wc_add_notice("Please enter a valid date of birth", "error");
    } else {
        $current_year = date('Y');
        $birth_year = date('Y', strtotime($_POST['birth_year']));

        if ($current_year - $birth_year <= 17) {
            wc_add_notice("You must be at least 18 years of age to register for this program", "error");
        } else {
            $bg_check = new LearnHuntingBgCheck();

            $bg_check_data = [
                "user_type" => ($is_mentor ? 'mentor' : 'student'),
                "first_name" => sanitize_text_field($_POST['billing_first_name']),
                "last_name" => sanitize_text_field($_POST['billing_last_name']),
                "state" => sanitize_text_field($_POST['billing_state']),
                "postal_code" => sanitize_text_field($_POST['billing_postcode']),
                "email_address" => sanitize_email($_POST['billing_email']),
                "date_of_birth" => sanitize_text_field($_POST['birth_year']),

                "address_line_1" => sanitize_text_field($_POST['billing_address_1']),
                "address_line_2" => sanitize_text_field($_POST['billing_address_2']),
                "city" => sanitize_text_field($_POST['billing_city']),
                "country" => sanitize_text_field($_POST['billing_country']),
                "phone" => sanitize_text_field($_POST['billing_phone']),
            ];

            if ($is_mentor) {
                $bg_check_data["first_hunt_age"] = sanitize_text_field($_POST['first_hunt_age']);
                $bg_check_data["hunting_type"] = sanitize_text_field(implode(",", $_POST['hunting_type']));
                $bg_check_data["game_type"] = sanitize_text_field(implode(",", $_POST['game_type']));
                $bg_check_data["instructor_type"] = sanitize_text_field(implode(",", $_POST['instructor_type']));
            }

            $passed_bg_check = [
                "success" => true,
                "message" => "",
            ];

            if (empty($_POST["safe"])) {
                $passed_bg_check = $bg_check->run($bg_check_data);
            } else {
                $check_token = md5($_POST["billing_email"] . "__learn-hunting-safety-check-bypass");
                $safe_token = $_POST["safe"];

                error_log(print_r("found SAFE variable", true));
                error_log(print_r("check: " . $check_token, true));
                error_log(print_r("safe: " . $safe_token, true));

                if ($check_token !== $safe_token) {
                    error_log(print_r("safe codes DO NOT MATCH", true));

                    $passed_bg_check = [
                        "success" => false,
                        "message" => "Invalid safe token",
                    ];
                } else {
                    error_log(print_r("safe codes MATCH! Continue...", true));

                    $passed_bg_check = [
                        "success" => true,
                        "message" => "Safety bypass successful",
                    ];
                }
            }

            if ($passed_bg_check['success'] === true) {
                // wc_add_notice( "Passed background check.", "success" );
            } else {
                $failure_message = "We are unable to create your account at this time. An administrator has been notified and will follow up with you shortly.";

                if ($custom_message = get_field("background_check_failure_message", "options")) {
                    $failure_message = $custom_message;
                }

                wc_add_notice($failure_message, "error" );

                // error_log(print_r($passed_bg_check, true));
            }
        }
    }
});

// Store custom fields as order meta
add_action('woocommerce_checkout_update_order_meta', function($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_customer_id();
    $customer = new WC_Customer($user_id);

    if (!empty($_POST['nickname']) && $nickname = sanitize_text_field($_POST['nickname'])) {
        update_user_meta($user_id, 'legal_name', $customer->get_billing_first_name());
        update_user_meta($user_id, 'nickname', $nickname);
        $customer->set_first_name($nickname);
        $customer->set_billing_first_name($nickname);
        $customer->save();
    }

    if (!empty($_POST['birth_year']) && $birth_year = sanitize_text_field($_POST['birth_year'])) {
        update_post_meta($order_id, 'birth_year', $birth_year);
        update_user_meta($user_id, 'birth_year', $birth_year);
    }

    if (!empty($_POST['first_hunt_age']) && $first_hunt_age = sanitize_text_field($_POST['first_hunt_age'])) {
        update_post_meta($order_id, 'first_hunt_age', $first_hunt_age);
        update_user_meta($user_id, 'first_hunt_age', $first_hunt_age);
    }

    if (!empty($_POST['instructor_type']) && $instructor_type = sanitize_text_field(implode(",", $_POST['instructor_type']))) {
        update_post_meta($order_id, 'instructor_type', $instructor_type);
        update_user_meta($user_id, 'instructor_type', $instructor_type);
    }

    if (!empty($_POST['game_type']) && $game_type = sanitize_text_field(implode(",", $_POST['game_type']))) {
        update_post_meta($order_id, 'game_type', $game_type);
        update_user_meta($user_id, 'game_type', $game_type);
    }

    if (!empty($_POST['hunting_type']) && $hunting_type = sanitize_text_field(implode(",", $_POST['hunting_type']))) {
        update_post_meta($order_id, 'hunting_type', $hunting_type);
        update_user_meta($user_id, 'hunting_type', $hunting_type);
    }

    if (!empty($_POST['is_available']) && $is_available = sanitize_text_field($_POST['is_available'])) {
        update_post_meta($order_id, 'is_available', ($is_available === "yes" ? true : false));
        update_user_meta($user_id, 'is_available', ($is_available === "yes" ? true : false));
    }

    if (!empty($_POST['is_mentor'])) {
        update_post_meta($order_id, 'is_mentor', true);
        update_user_meta($user_id, 'is_mentor', true);
        update_user_meta($user_id, 'is_student', false);
    }

    if (!empty($_POST['is_student'])) {
        update_post_meta($order_id, 'is_student', true);
        update_user_meta($user_id, 'is_mentor', false);
        update_user_meta($user_id, 'is_student', true);
    }

    if (!empty($_POST['gender'])) {
        update_user_meta($user_id, 'gender', sanitize_text_field($_POST['gender']));
    }
});

// Display details on admin order page
add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
    $customCheckout = new LearnHuntingCheckout(false);

    if (get_post_meta($order->id, 'is_mentor', true)) {
        echo '<h3>Instructor Membership Details</h3>';
    } else if (get_post_meta($order->id, 'is_student', true)) {
        echo '<h3>Student Membership Details</h3>';
    }

    if ($birth_year = get_post_meta($order->id, 'birth_year', true)) {
        echo '<p><strong>Date of Birth:</strong> ' . $birth_year . '</p>';
    }

    if ($first_hunt_age = get_post_meta($order->id, 'first_hunt_age', true)) {
        echo '<p><strong>First hunt age:</strong> ' . $first_hunt_age . '</p>';
    }

    if ($game_type = get_post_meta($order->id, 'game_type', true)) {
        $display_game_types = [];
        $game_types = explode(",", $game_type);
        foreach ($game_types as $value) {
            $display_game_types[] = $customCheckout->game_types[$value];
        }

        echo '<p><strong>Game types: </strong> ';
            foreach ($display_game_types as $value) {
                echo '<span style="display:inline-block; padding:3px 6px; border-radius:4px; background:#eee; margin:0 4px 4px 0;">' . $value . '</span>';
            }
        echo '</p>';
    }

    if ($hunting_type = get_post_meta($order->id, 'hunting_type', true)) {
        $display_hunting_types = [];
        $hunting_types = explode(",", $hunting_type);
        foreach ($hunting_types as $value) {
            $display_hunting_types[] = $customCheckout->hunting_types[$value];
        }

        echo '<p><strong>Hunting types: </strong>';
            foreach ($display_hunting_types as $value) {
                echo '<span style="display:inline-block; padding:3px 6px; border-radius:4px; background:#eee; margin:0 4px 4px 0;">' . $value . '</span>';
            }
        echo '</p>';
    }

    if ($instructor_type = get_post_meta($order->id, 'instructor_type', true)) {
        $display_instructor_types = [];
        $instructor_types = explode(",", $instructor_type);
        foreach ($instructor_types as $value) {
            $display_instructor_types[] = $customCheckout->hunting_types[$value];
        }

        echo '<p><strong>Instructor types: </strong>';
            foreach ($display_instructor_types as $value) {
                echo '<span style="display:inline-block; padding:3px 6px; border-radius:4px; background:#eee; margin:0 4px 4px 0;">' . $value . '</span>';
            }
        echo '</p>';
    }
}, 10, 1 );

// Automatically add student-intro coupon to cart
// add_action('woocommerce_before_calculate_totals', function() {
//     $student_product_id = 303;

//     // Loop through all cart items and check if student product is in cart
//     $student_product_in_cart = false;
//     foreach (WC()->cart->get_cart() as $cart_item) {
//         if ($cart_item['product_id'] === $student_product_id) {
//             $student_product_in_cart = true;
//         }
//     }

//     if ($student_product_in_cart) {
//         // Add coupon to cart
//         WC()->cart->add_discount('student-intro');
//     }
// });
add_filter('woocommerce_subscription_payment_complete', 'custom_subscription_payment_complete', 10, 2);

function custom_subscription_payment_complete($subscription) {
    // Get the order
    $subscription->update_status('on-hold', __('Order total is zero.', ''));

    return $subscription;
}

remove_action('woocommerce_thankyou', __CLASS__ . '::subscription_thank_you');
add_action('woocommerce_thankyou', function($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    // if ($user_id) {
    //     add_stripe_customer_id_to_user_meta($user_id);
    // }

    $student_product_id = 303;
    $mentor_product_id = 304;

    global $woocommerce;
    // Get the cart contents
    $cart_contents = $woocommerce->cart->get_cart();
    $line_items = [];

    foreach ($order->get_items() as $order_item) {
        $product_id = $order_item->get_product_id();
        $quantity = $order_item['quantity'];

        if ($product_id === $student_product_id || $product_id === $mentor_product_id) {
            // Send the SSO details to journeyage, then redirect.
            // $jwt = new \Jwt_Auth_Public('jwt-auth', '99');
            // $jwt->send_journeyage_sso();
            $_product = new WC_Product( $product_id );
            // getting the defined product attributes
            $product_attr = $_product->get_attributes();
            $option_values    = array(); // Initializing

            foreach ( $product_attr as $name => $values ) {
                $label_name       = wc_attribute_label($values->get_name()); // Get product attribute label name
            
                $data             = $values->get_data(); // Unprotect attribute data in an array
            
                $is_for_variation = $data['is_variation']; // Is it used for variation
                $is_visible       = $data['is_visible']; // Is it visible on product page
                $is_taxonomy      = $data['is_taxonomy']; // Is it a custom attribute or a taxonomy attribute
            
            
            
                // For taxonomy product attribute values
                if( $is_taxonomy ) {
                    $terms = $values->get_terms(); // Get attribute WP_Terms
            
                    // Loop through attribute WP_Term
                    foreach ( $terms as $term ) {
                        $term_name     = $term->name; // get the term name
                        $option_values[] = $term_name;
                    }
                }
                // For "custom" product attributes values
                else {
                    // Loop through attribute option values
                    foreach ( $values->get_options() as $term_name ) {
                        $option_values[] = $term_name;
                    }
                }
            }
            
            // Add the line item to the array
            $line_items[] = [
                'price' => $option_values[0],
                'quantity' => $quantity
            ];
        }
    }
    $stripe_testmode = get_option('woocommerce_stripe_settings')['testmode'];
    if ($stripe_testmode) {
        $stripe_secret_key = get_option('woocommerce_stripe_settings')['test_secret_key'];
    } else {
        $stripe_secret_key = get_option('woocommerce_stripe_settings')['secret_key'];
    }
    require_once(__DIR__ . '/stripe-php-master/init.php');
    \Stripe\Stripe::setApiKey($stripe_secret_key); // Replace with your actual Stripe Secret Key

    $stripe_checkout_params = [
        'payment_method_types' => ['card'],
        'payment_method_collection' => 'if_required',
        'line_items' => $line_items, // Make sure $line_items is populated
        'mode' => 'subscription',
        'success_url' => home_url('/my-account'), // Replace with your success URL
        'cancel_url' => home_url('/sign-up'),   // Replace with your cancel URL
        'customer_email' => get_userdata($user_id)->user_email
    ];

    // add trial period to student memberships
    if ($order_id === 303) $stripe_checkout_params['subscription_data'] = ['trial_period_days' => 30];

    // Create a Checkout Session
    $session = \Stripe\Checkout\Session::create($stripe_checkout_params);

    wc_setcookie( 'wc_stripe_payment_request_redirect_url', $session->url );
    
    wp_redirect($session->url);

    exit;
});


// add_action("woocommerce_checkout_order_processed", function($order_id) {
//     $jwt = new \Jwt_Auth_Public('jwt-auth', '99');
//     $jwt->send_journeyage_sso();
// }, 99, 1);

add_filter('add_to_cart_redirect', function() {
    global $woocommerce;
    $lw_redirect_checkout = $woocommerce->cart->get_checkout_url();
    return $lw_redirect_checkout;
});

add_filter('wc_add_to_cart_message_html', function($message, $products) {
    return false;
}, 10, 2);

add_filter('woocommerce_order_button_text', function() {
    return "Complete Registration";
});

add_action('wc_gateway_stripe_process_webhook_payment', 'handle_stripe_webhook');

function handle_stripe_webhook() {
  $request_body = file_get_contents('php://input');
  $event_data = json_decode($request_body, true);

  if (!$event_data) {
    return;
  }

  $event_type = $event_data['type'];
  $event_id = $event_data['id'];

  switch ($event_type) {
    case 'charge.succeeded':
      process_successful_payment($event_data);
      break;
    case 'charge.refunded':
      process_refunded_payment($event_data);
      break;
    case 'charge.failed':
      process_failed_payment($event_data);
      break;
    default:
      // Handle other relevant events
  }
}
