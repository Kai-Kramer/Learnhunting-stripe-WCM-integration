<?php
class LearnHuntingAccountDashboard
{
    public $student_product_id;
    public $mentor_product_id;

    public $is_student;
    public $is_mentor;

    private $IS_DEV;
    private $DEV_SERVERS = ['learn-hunting.gecko'];

    public function __construct()
    {
        $this->IS_DEV = (in_array($_SERVER['SERVER_NAME'], $this->DEV_SERVERS));

        if ($this->IS_DEV) {
            add_filter('https_ssl_verify', '__return_false');
        }

        $this->student_product_id = 303;
        $this->mentor_product_id = 304;

        $this->is_student = false;
        $this->is_mentor = false;

        $user = wp_get_current_user();
        $active_plans = wc_memberships_get_user_active_memberships($user->ID);

        foreach ($active_plans as $plan) {
            $product_id = $plan->get_product()->get_id();

            if ($product_id == $this->student_product_id) {
                $this->is_student = true;
            }

            if ($product_id == $this->mentor_product_id) {
                $this->is_mentor = true;
            }
        }
    }

    public function get_journeyage_token()
    {
        $public = new Jwt_Auth_Public('jwt-auth', '99');
        $response = $public->local_token();

        if (!is_wp_error($response) && !empty($response["token"])) {
            return $response["token"];
        } elseif (is_wp_error($response)) {
            error_log(print_r($response->get_error_message(), true));
        }

        return "";
    }
}
