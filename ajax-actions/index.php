<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();
$servername = "localhost";
$username = "phpmyadmin";
$password = 'Neuberg@1243';
$database = 'neuberg_campaigns_db';
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
$request_action = $_REQUEST['page_action'];
if (!empty($request_action)) {
    if ($request_action == 'send_otp') {
        $mobile_number = $_POST['mobile'];
        $fullname = $_POST['name'];
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + 300;
        $message_content = urlencode('NEUBERG: OTP is ' . $otp . ' for NeuApp Login');
        $end_point = 'https://sms.sendmsg.in/smpp?username=ehrlabtr&password=xRjKiPTDk&from=NEULAB&to=' . $mobile_number . '&text=' . $message_content . '&urlshortening=1';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $end_point);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (!empty($response)) {
            $json_data = array("rslt" => "1", "message" => 'OTP Sent Successfully.');
        } else {
            $json_data = array("rslt" => "2", "message" => 'Something Went Wrong.');
        }
    } elseif ($request_action == 'verify_otp') {
        if (!empty($_SESSION['otp'])) {
            if ($_SESSION['otp'] == $_POST['otp']) {
                $_SESSION['otp_verified'] = 'yes';
                $json_data = array("rslt" => "1", "message" => 'Verified Successfully.');
            } else {
                $json_data = array("rslt" => "2", "message" => 'Invalid OTP.');
            }
        }
    } elseif ($request_action == 'submit_form') {
        if ($_SESSION['otp_verified'] == 'yes') {
            $name = $_POST['name'];
            $mobile = $_POST['mobile'];
            $lname = '';
            $email = "website@neubergdiagnostics.com";
            $phone = '';
            $source = 'Google';
            $secondary_source = "Website - neubergdiagnostics.com";
            $city = 'Chennai';
            $state = 'Tamil Nadu';
            $ownergroup = 'iSolve';
            $zip = '';
            $digital_source_name = "Chennai_SEM_Web";
            $notes = "Note for the activity";
            // Detect device
            if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $_SERVER['HTTP_USER_AGENT'])) {
                $device_name = "Website-Mobile";
            } else {
                $device_name = "Website-Desktop";
            }
            $source_page = "STDPanel Chennai";
            date_default_timezone_set('Asia/Kolkata');
            $currentDateTime = date('Y-m-d H:i:s');
            // -------- Capture gclid & UTM values --------
            $gclid      = isset($_POST['gclid']) ? $_POST['gclid'] : '';
            $utm_source = isset($_POST['utm_source']) ? $_POST['utm_source'] : '';
            $utm_campaign = isset($_POST['utm_campaign']) ? $_POST['utm_campaign'] : '';
            $utm_term   = isset($_POST['utm_term']) ? $_POST['utm_term'] : '';
            $utm_medium = isset($_POST['utm_medium']) ? $_POST['utm_medium'] : '';
            $webform    = "STDPanel Chennai"; // you can change dynamically if multiple forms exist
            // -------- NEW API FORMAT --------
            $accessKey = "u\$r8ac1be138c21c3321c9bc9c08dfae9e1";   // Replace with actual key
            $secretKey = "cc60394521a574386998a20654649546cf0dbdb3"; // Replace with actual key
            $url = "https://api-in21.leadsquared.com/v2/ProspectActivity.svc/CreateCustom?accessKey={$accessKey}&secretKey={$secretKey}";
			// $note = $name . " | " . $email . " | " . $mobile . " | " . $source . " | " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . " | " . $source_page;
            $data = [
                "LeadDetails" => [
                    ["Attribute" => "Source", "Value" => $source],
                    ["Attribute" => "EmailAddress", "Value" => $email],
                    ["Attribute" => "FirstName", "Value" => $name],
                    ["Attribute" => "Phone", "Value" => $mobile],
                    ["Attribute" => "mx_City", "Value" => $city],
                    ["Attribute" => "mx_Owner_Group", "Value" => $ownergroup],
                    ["Attribute" => "mx_Digital_Source_Name", "Value" => $digital_source_name],
                    ["Attribute" => "Stage", "Value" => "New"],
                    ["Attribute" => "SearchBy", "Value" => "Phone"]
                ],
                "Activity" => [
                    "ActivityEvent" => 207,
                    // "ActivityEvent_Note" => "test",
				"ActivityNote" => $name . " | " . $email . " | " . $mobile . " | " . $source . " | " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . " | " . $source_page,
    "ActivityEvent_Note" => $name . " | " . $email . " | " . $mobile . " | " . $source . " | " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . " | " . $source_page,
                    "Fields" => [
                        ["SchemaName" => "mx_Custom_79", "Value" => $gclid],         // Google Click ID
                        ["SchemaName" => "mx_Custom_71", "Value" => 'Landing Pages'],  // Source Page
                        ["SchemaName" => "mx_Custom_81", "Value" => $utm_source],   // UTM Source
                        ["SchemaName" => "mx_Custom_82", "Value" => $utm_campaign], // UTM Campaign
                        ["SchemaName" => "mx_Custom_83", "Value" => $utm_term],     // UTM Term
                        ["SchemaName" => "mx_Custom_84", "Value" => $utm_medium],   // UTM Medium
                        ["SchemaName" => "mx_Custom_85", "Value" => $webform]       // Webform Name
                    ]
                ]
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                echo "cURL error: " . curl_error($ch);
            }
            curl_close($ch);
            // Save submission in local DB
            $name = mysqli_real_escape_string($conn, $name);
            $mobile = mysqli_real_escape_string($conn, $mobile);
            $sql = "INSERT INTO neuberg_form_submission
                    (`first_name`, `last_name`, `email`, `mobile`, `phone`, `source`, `secondary_source`, `city`, `state`, `owner_group`, `zip_code`, `digital_source`, `notes`, `device_name`, `source_page`,`created_on`)
                    VALUES ('$name', '$lname', '$email', '$mobile', '$phone', '$source', '$secondary_source', '$city', '$state', '$ownergroup', '$zip', '$digital_source_name', '$notes', '$device_name', '$source_page', '$currentDateTime')";
            mysqli_query($conn, $sql);
            session_destroy();
            $json_data = array("rslt" => "1", "message" => 'Submitted Successfully.', "response" => $response);
        }
    }
    echo json_encode($json_data);
}
?>
