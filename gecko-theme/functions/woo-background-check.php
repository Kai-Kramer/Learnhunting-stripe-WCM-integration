<?php
class LearnHuntingBgCheck
{
    private $api_key;
    private $account_id;
    private $account_pass;
    private $category;
    private $max_records;
    private $check_url;

    public function __construct()
    {
        $this->api_key = "b12711a71d09463aa378d5909477ec36";
        $this->account_id = "prod_ihea";
        $this->account_pass = "bis4E8ucrIp3iStu";
        $this->category = "ARCRMWPASXWA";
        $this->max_records = "200";
        $this->check_url = "https://prod-api.themisds.com/annotatednatcrim/search_by_name";
    }

    public function run($data = [])
    {
        if (!get_field("enable_background_check", "options")) {
            return [
                'success' => true,
                'message' => 'Background check disabled.',
            ];
        }

        if (empty($data) || !is_array($data) || empty($data['first_name']) || empty($data['last_name'])) {
            return [
                'success' => false,
                'message' => 'No data provided',
            ];
        }

        if (!empty($_SESSION["failed_bg_check"]) && $_SESSION["failed_bg_check"] == true) {
            // Only block the user if they haven't initiated the safety check bypass
            if (empty($_SESSION["lh_safety_check_bypass"])) {
                return [
                    'success' => false,
                    'message' => 'Failed Safety Screening ERR002',
                ];
            } else {
                error_log(print_r("Already failed BG check, using Safety Check Bypass.", true));
            }
        }

        $dob = "";
        if (!empty($data['date_of_birth'])) {
            $dob = date("Ymd", strtotime($data['date_of_birth']));
        }

        $payload = [
            "Session" => [
                "Account" => $this->account_id,
                "Password" => $this->account_pass,
                "ReferenceID" => "",
                "DisclaimersAgree" => "",
                "EnableAnnotations" => "True"
            ],
            "SearchParameters" => [
                "IncludePartialMiddlename" => "",
                "Aka" => "true",
                "PriorityStates" => "",
                "PriorityFlag" => "0",
                "AllSOR" => "",
                "AllPatriot" => "",
                "RVP" => "",
                "Source" => "",
                "Category" => $this->category,
                "MaxRecords" => $this->max_records,
                "LastName" => $data["last_name"],
                "FirstName" => $data["first_name"],
                "MiddleName" => "",
                // "ZipCode" => $data["postal_code"] ?? "",
                "ZipCode" => "",
                "State" => $data["state"] ?? "",
                "DOB" => $dob,
                "IncludePartialDOB" => "6",
            ],
        ];

        // curl post request using api authorization
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->check_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'ocp-Apim-Subscription-Key: ' . $this->api_key,
        ]);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'success' => false,
                'message' => curl_error($ch),
            ];
        }
        curl_close($ch);

        $result = json_decode($result, true);

        if (empty($result) || empty($result['Search_results'])) {
            return [
                'success' => false,
                'message' => 'No results found',
            ];
        }

        $allowed_categories = [];
        if ($allowed_category_rows = get_field("allowed_categories", "option")) {
            foreach ($allowed_category_rows as $category_row) {
                $category_string = "";
                $category_string .= $category_row["category"];
                $category_string .= "|||";
                $category_string .= $category_row["subcategory"];
                $category_string .= "|||";
                $category_string .= $category_row["subsubcategory"];

                $allowed_categories[] = $category_string;
            }
        }

        $all_records_passed = true;

        if (!empty($result['Search_results']) && !empty($result['Search_results']['Returned'])) {
            // Loop through all records to check category values
            foreach ($result['Search_results']['Record'] as $record) {
                $annotations = $record['Charge_info']['charge'][0]['AnnotatedChargeInformation'];
                $category = $annotations['ChargeCategory'];
                $subcategory = $annotations['ChargeSubcategory'];
                $subsubcategory = $annotations['ChargeSubsubcategory'];
                $category_string = $category . "|||" . $subcategory . "|||" . $subsubcategory;

                if (!in_array($category_string, $allowed_categories)) {
                    error_log(print_r("NOT ALLOWED: $category_string", true));
                    $all_records_passed = false;
                }
            }

            if (!$all_records_passed) {
                $this->notify_admin_on_failure($data, $result);

                $_SESSION["failed_bg_check"] = true;

                return [
                    'success' => false,
                    'message' => 'Failed Safety Screening ERR001',
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Passed Safety Screening',
        ];
    }

    private function notify_admin_on_failure($data, $result)
    {
        // error_log(print_r("Send admin email notification on failed background check", true));
        // error_log(print_r($data, true));
        // error_log(print_r($result, true));

		$headers = [
            "Content-Type: text/html; charset=UTF-8",
            "From: LearnHunting.org <noreply@learnhunting.org>",
        ];

		$subject = "Failed User Background Check";

		$message = "<h2>Background check failure details</h2>";

        foreach ($data as $field => $value) {
            $message .= "<p><strong>{$field}</strong>: $value</p>";
        }

        $message .= "<hr />";

        $message .= "<h3>Found " . $result['Search_results']['Returned'] . " records:</h3>";

        if (!empty($result['Search_results']) && !empty($result['Search_results']['Record'])) {
            if (is_array($result['Search_results']['Record'])) {
                foreach ($result['Search_results']['Record'] as $record) {
                    // error_log(print_r($record, true));

                    foreach ($record as $field => $value) {
                        if (!empty($value) && !is_array($value)) {
                            $message .= "<p><strong>{$field}</strong>: $value</p>";
                        } elseif (!empty($value) && is_array($value)) {
                            $message .= '<pre style="font-size:10px;">' . print_r($value, true) . '</pre>';
                        }
                    }

                    $message .= "<hr />";
                }
            }
        }

        // Send the message to all admins
        $send_to = explode(",", get_field("background_check_notification_email_to", "options")) ?? [];

        foreach ($send_to as $email_address) {
            $email = sanitize_email($email_address);

            if (is_email($email)) {
                wp_mail($email, $subject, $message, $headers);
            }
        }
    }
}
