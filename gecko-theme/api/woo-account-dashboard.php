<?php
namespace GeckoTheme\Api;

use LearnHuntingAccountDashboard;

class WooAccountDashboard extends \GeckoTheme\Api
{
    private $dashboard;
    private $hours_table;
    private $wpdb;
    private $jwt;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->hours_table = $this->wpdb->prefix.'lh_mentor_hours';

        $this->jwt = new \Jwt_Auth_Public('jwt-auth', '99');

		add_action( 'rest_api_init', [$this, 'rest_api_init']);
	}

    function rest_api_init()
    {
        $this->dashboard = new LearnHuntingAccountDashboard();

        register_rest_route($this->namespace, '/edit-profile', [
			'methods' => 'GET',
			'callback' => [$this, 'get_edit_profile'],
			'permission_callback' => function () {
				return is_user_logged_in() && ($this->dashboard->is_student || $this->dashboard->is_mentor);
			}
        ]);

        register_rest_route($this->namespace, '/edit-profile/(?P<user_id>\d+)', [
			'methods' => 'GET',
			'callback' => [$this, 'get_edit_profile'],
			'permission_callback' => function () {
				return current_user_can('administrator');
			}
        ]);

        register_rest_route($this->namespace, '/edit-profile', [
			'methods' => 'POST',
			'callback' => [$this, 'update_edit_profile'],
			'permission_callback' => function () {
				return is_user_logged_in() && ($this->dashboard->is_student || $this->dashboard->is_mentor);
			}
        ]);

        register_rest_route($this->namespace, '/edit-profile/(?P<user_id>\d+)', [
			'methods' => 'POST',
			'callback' => [$this, 'update_edit_profile'],
			'permission_callback' => function () {
				return current_user_can('administrator');
			}
        ]);

        register_rest_route($this->namespace, '/upload-avatar', [
			'methods' => 'POST',
			'callback' => [$this, 'upload_avatar'],
			'permission_callback' => function () {
				return is_user_logged_in() && ($this->dashboard->is_student || $this->dashboard->is_mentor);
			}
        ]);

        register_rest_route($this->namespace, '/upload-avatar/(?P<user_id>\d+)', [
			'methods' => 'POST',
			'callback' => [$this, 'upload_avatar'],
			'permission_callback' => function () {
				return current_user_can('administrator');
			}
        ]);

        register_rest_route($this->namespace, '/clear-avatar', [
			'methods' => 'POST',
			'callback' => [$this, 'clear_avatar'],
			'permission_callback' => function () {
				return is_user_logged_in() && ($this->dashboard->is_student || $this->dashboard->is_mentor);
			}
        ]);

        register_rest_route($this->namespace, '/clear-avatar/(?P<user_id>\d+)', [
			'methods' => 'POST',
			'callback' => [$this, 'clear_avatar'],
			'permission_callback' => function () {
				return current_user_can('administrator');
			}
        ]);

        register_rest_route($this->namespace, '/update-password', [
			'methods' => 'POST',
			'callback' => [$this, 'update_password'],
			'permission_callback' => function () {
				return is_user_logged_in();
			}
        ]);

        register_rest_route($this->namespace, '/availability', [
			'methods' => 'GET',
			'callback' => [$this, 'get_availability'],
			'permission_callback' => function () {
				return is_user_logged_in();
			}
        ]);

        register_rest_route($this->namespace, '/availability', [
			'methods' => 'POST',
			'callback' => [$this, 'update_availability'],
			'permission_callback' => function () {
				return is_user_logged_in();
			}
        ]);

        register_rest_route($this->namespace, '/log-hours', [
			'methods' => 'POST',
			'callback' => [$this, 'add_hours'],
			'permission_callback' => function () {
				return is_user_logged_in();
			}
        ]);
    }

	function get_edit_profile($request)
	{
		$return = [];
        $member_type = "";
        $profile_details = [];
        $avatar_details = [];

        $user_id = get_current_user_id();

        if (!empty($request->get_param("user_id"))) {
            $user_id = $request->get_param("user_id");
        }
        if (get_user_meta($user_id, "is_student", true)) {
            $member_type = "student";
        }
        if (get_user_meta($user_id, "is_mentor", true)) {
            $member_type = "mentor";
        }

        $return["memberType"] = $member_type;

        if (!empty($user_id) && $customer = new \WC_Customer($user_id)) {
            $first_name = $customer->get_billing_first_name();
            $last_name = $customer->get_billing_last_name();

            // Standard fields
            $profile_details["firstName"] = $first_name;
            $profile_details["lastName"] = $last_name;

            $profile_details["email"] = $customer->get_billing_email();
            $profile_details["phone"] = $customer->get_billing_phone();
            $profile_details["address"] = $customer->get_billing_address_1();
            $profile_details["address2"] = $customer->get_billing_address_2();
            $profile_details["city"] = $customer->get_billing_city();
            $profile_details["state"] = $customer->get_billing_state();
            $profile_details["zip"] = $customer->get_billing_postcode();
            $profile_details["country"] = $customer->get_billing_country();

            if ($member_type === "mentor") {
                $profile_details["birthYear"] = get_user_meta($user_id, "birth_year", true);
                $profile_details["firstHuntAge"] = get_user_meta($user_id, "first_hunt_age", true);
                $profile_details["huntingType"] = get_user_meta($user_id, "hunting_type", true);
                $profile_details["gameType"] = get_user_meta($user_id, "game_type", true);
                $profile_details["instructorType"] = get_user_meta($user_id, "instructor_type", true);
            }

            if ($member_type === "student") {
                $profile_details["birthYear"] = get_user_meta($user_id, "birth_year", true);
            }

            // Avatar details
            $avatar_details["monogram"] = strtoupper(substr($first_name, 0, 1) . substr($last_name, 0, 1));

            if ($avatar_filename = get_user_meta($user_id, "avatar_image_filename", true)) {
                $avatar_details["url"] = wp_get_upload_dir()["baseurl"] . "/user-avatars/" . $avatar_filename;
            }
        }

        $return["profileDetails"] = $profile_details;
        $return["avatarDetails"] = $avatar_details;

		return $return;
	}

    private function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool
    {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }

    private function isValidPhoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool
    {
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);

        return $this->isDigits($telephone, $minDigits, $maxDigits);
    }

	function update_edit_profile($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];
        $member_type = "";

        // Set profile details
        $user_id = get_current_user_id();

        if (!empty($request->get_param("user_id"))) {
            $user_id = $request->get_param("user_id");
        }

        if (get_user_meta($user_id, "is_student", true)) {
            $member_type = "student";
        }
        if (get_user_meta($user_id, "is_mentor", true)) {
            $member_type = "mentor";
        }

        if (!empty($user_id) && $customer = new \WC_Customer($user_id)) {
            // Validate all fields

            if (empty($data["firstName"])) {
                $errors["firstName"] = "First Name is required";
            }

            if (empty($data["lastName"])) {
                $errors["lastName"] = "Last Name is required";
            }

            if (!empty($data["phone"]) && !$this->isValidPhoneNumber($data["phone"])) {
                $errors["phone"] = "Phone number is invalid, must be 10-digit number";
            }

            if (empty($data["birthYear"])) {
                $errors["birthYear"] = "Date of birth is invalid";
            }

            if (!empty($data["firstHuntAge"]) && (intval($data["firstHuntAge"]) < 0 || intval($data["firstHuntAge"]) > 100 || !is_numeric($data["firstHuntAge"]))) {
                $errors["firstHuntAge"] = "Age of first hunt is invalid";
            }

            // Validation complete, save values
            if (empty($errors)) {
                $customer->set_billing_first_name(sanitize_text_field($data["firstName"]));
                $customer->set_billing_last_name(sanitize_text_field($data["lastName"]));
                $customer->set_billing_phone(sanitize_text_field($data["phone"]));
                $customer->set_billing_address(empty($data["address"]) ? "" : sanitize_text_field($data["address"]));
                $customer->set_billing_address_2(empty($data["address2"]) ? "" : sanitize_text_field($data["address2"]));
                $customer->set_billing_city(empty($data["city"]) ? "" : sanitize_text_field($data["city"]));
                $customer->set_billing_state(empty($data["state"]) ? "" : sanitize_text_field($data["state"]));
                $customer->set_billing_postcode(empty($data["zip"]) ? "" : sanitize_text_field($data["zip"]));
                $customer->save();

                update_user_meta($user_id, "birth_year", sanitize_text_field($data["birthYear"] ?? ""));

                if ($member_type === "mentor") {
                    update_user_meta($user_id, "first_hunt_age", sanitize_text_field($data["firstHuntAge"] ?? ""));
                    update_user_meta($user_id, "hunting_type", sanitize_text_field($data["huntingType"] ?? ""));
                    update_user_meta($user_id, "game_type", sanitize_text_field($data["gameType"] ?? ""));
                    update_user_meta($user_id, "instructor_type", sanitize_text_field($data["instructorType"] ?? ""));
                }
            }

            $this->jwt->send_journeyage_sso();
        } else {
            $errors["user_id"] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

		return $return;
	}

	function upload_avatar($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];

        $user_id = get_current_user_id();

        if (!empty($request->get_param("user_id"))) {
            $user_id = $request->get_param("user_id");
        }

        if (isset($_FILES['file'])) {
			if ($_FILES['file']['size'] > 0 && $_FILES['file']['size'] < 1000000) {
				$file = $_FILES['file'];
				$file_info = pathinfo($file['name']);

				$file_ext = $file_info['extension'];
				$file_name = md5(time()) . "." . $file_ext;

				$upload_dir = wp_upload_dir();
				$target_dir = $upload_dir['basedir'] . '/user-avatars/';
				$target = $target_dir . $file_name;

				if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}

				if (move_uploaded_file($file['tmp_name'], $target)) {
					// Look for the old file in the uploads folder, collect the trash!
					$old_file = get_user_meta($user_id, 'avatar_image_filename', true);

					if ($old_file) {
						$old_file_path = $upload_dir['basedir'] . '/user-avatars/' . $old_file;

						if (file_exists($old_file_path)) {
							// Delete the og file if the new one is in place.
							unlink($old_file_path);
						}
					}

					update_user_meta($user_id, 'avatar_image_filename', $file_name);

					$return['success'] = 'Uploaded successfully!';
					$return['logo_url'] = '/wp-content/uploads/user-avatars/' . $file_name;

                    $this->jwt->send_journeyage_sso();
				} else {
					$return['error'] = 'Error uploading file!';
				}
			}
		} else {
			$return['error'] = 'Invalid file, not saving.';
		}

		return $return;
	}

	function clear_avatar($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];

        $user_id = get_current_user_id();

        if (!empty($request->get_param("user_id"))) {
            $user_id = $request->get_param("user_id");
        }

        if (!empty($user_id)) {
            $old_file = get_user_meta($user_id, 'avatar_image_filename', true);

            if ($old_file) {
                $upload_dir = wp_upload_dir();

                $old_file_path = $upload_dir['basedir'] . '/user-avatars/' . $old_file;

                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }

            delete_user_meta($user_id, 'avatar_image_filename');
        } else {
            $errors[] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

        $this->jwt->send_journeyage_sso();

		return $return;
	}

	function update_password($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];

        // Set profile details
        $user_id = get_current_user_id();

        if (!empty($user_id) && $user = get_user_by("id", $user_id)) {
            $oldPassword = $data["oldPasswd"];
            $password1 = $data["passwd1"];
            $password2 = $data["passwd2"];

            if (wp_check_password($oldPassword, $user->data->user_pass, $user_id)) {
                if ($password1 === $password2) {
                    wp_set_password($password1, $user_id);
                    $return["success"] = wp_signon([
                        "user_login" => $user->data->user_login,
                        "user_password" => $password1
                    ], false);
                } else {
                    $errors[] = "New passwords do not match";
                }
            } else {
                $errors[] = "Current password is not correct";
            }
        } else {
            $errors[] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

		return $return;
	}

	function get_availability($request)
	{
        $return = [];
        $errors = [];

        $user_id = get_current_user_id();

        if (!empty($user_id) && $user = get_user_by("id", $user_id)) {
            $return["is_available"] = get_user_meta($user_id, "is_available", true);
        } else {
            $errors[] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

		return $return;
	}

	function update_availability($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];

        // Set profile details
        $user_id = get_current_user_id();

        if (!empty($user_id) && $user = get_user_by("id", $user_id)) {
            update_user_meta($user_id, "is_available", $data["is_available"] ? true : false);

            $return["is_available"] = get_user_meta($user_id, "is_available", true);
        } else {
            $errors[] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

        $this->jwt->send_journeyage_sso();

		return $return;
	}

	function add_hours($request)
	{
        $data = $request->get_params();

		$return = [];
        $errors = [];

        // Set profile details
        $user_id = get_current_user_id();

        if (!empty($user_id) && $user = get_user_by("id", $user_id)) {
            $new_entry = [
                "user_id" => $user_id,
                "name" => sanitize_text_field($data["mentorName"]) ?? "",
                "date" => sanitize_text_field($data["activityDate"]) ?? "",
                "start_time" => sanitize_text_field($data["startTime"]) ?? "",
                "end_time" => sanitize_text_field($data["endTime"]) ?? "",
                "total_hours" => floatval($data["hours"]) ?? 0,
                "miles_driven" => floatval($data["milesDriven"]) ?? 0,
                "activity_name" => sanitize_text_field($data["activityName"]) ?? "",
                "activity_state" => sanitize_text_field($data["activityState"]) ?? "",
                "date_created" => time(),
            ];

            $this->wpdb->insert($this->hours_table, $new_entry);
        } else {
            $errors[] = "User ID is required / invalid customer";
        }

        if (!empty($errors)) {
            $return["errors"] = $errors;
        }

		return $return;
	}
}
