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

    if(!empty($request_action)){
      
        if($request_action == 'send_otp'){

            $mobile_number = $_POST['mobile'];

            $fullname = $_POST['name'];

            $otp = rand(100000, 999999);

            $_SESSION['otp'] = $otp;

            $_SESSION['otp_expiry'] = time() + 300; 

            $message_content = urlencode('NEUBERG: OTP is '.$otp.' for NeuApp Login');

            $end_point = 'https://sms.sendmsg.in/smpp?username=ehrlabtr&password=xRjKiPTDk&from=NEULAB&to='.$mobile_number.'&text='.$message_content.'&urlshortening=1';
        
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $end_point);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

            $response = curl_exec($ch);

            if(curl_errno($ch)){

                $error_msg = curl_error($ch);

            }

            curl_close($ch);

            // echo $response;

            if(!empty($response)){

            $json_data = array("rslt"=>"1", 
                    "message" => 'OTP Sent Successfully.'

            ); 
            }else{
                $json_data = array("rslt"=>"2", 
                        "message" => 'Something Went Wrong.'
                ); 
            }

         }elseif ($request_action == 'verify_otp') {

            if(!empty($_SESSION['otp'])){
              
                if($_SESSION['otp'] == $_POST['otp']){

                        $_SESSION['otp_verified'] = 'yes'; 

                        $json_data = array("rslt"=>"1", 
                            "message" => 'Verified Successfully.'
                        ); 

                }else{

                    	$json_data = array("rslt"=>"2", 
                            "message" => 'Something Went Wrong.'
                        ); 

                }

            }



        }elseif ($request_action == 'submit_form') {



                if($_SESSION['otp_verified'] == 'yes'){

                        $name = $_POST['name'];

                        $mobile = $_POST['mobile'];

                        $lname = '';

                        $email = "website@neubergdiagnostics.com";

                        $phone = '';

                        $source = 'Website';

                        $secondary_source ="Website - neubergdiagnostics.com";

                        $city = 'Bengaluru';

                        $state = 'Karnataka';

                        $ownergroup = 'NADL';

                        $zip = '';

                        $digital_source_name = "Bangalore Digital";

                        $notes = "Note for the activity";
                        
                        
                        if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $_SERVER['HTTP_USER_AGENT'])) {
                            $device_name = "Website-Mobile";
                        } else {
                            $device_name = "Website-Desktop";
                        }


                        $source_page = "Landing Pages";

                        date_default_timezone_set('Asia/Kolkata');

                        $currentDateTime = date('Y-m-d H:i:s');
                    
                        $url = "https://api-in21.leadsquared.com/v2/ProspectActivity.svc/CreateCustom";

                        $headers = [
                            "x-LSQ-AccessKey: u\$r8ac1be138c21c3321c9bc9c08dfae9e1",
                            "x-LSQ-SecretKey: cc60394521a574386998a20654649546cf0dbdb3",
                            "Content-Type: application/json"
                        ];

                        $data = [
                            "LeadDetails" => [
                                [ "Attribute" => "FirstName", "Value" => $name ],
                                [ "Attribute" => "LastName", "Value" => $lname ],
                                [ "Attribute" => "EmailAddress", "Value" => $email ],
                                [ "Attribute" => "Phone", "Value" =>  $mobile],
                                [ "Attribute" => "SearchBy", "Value" => $phone ],
                                [ "Attribute" => "Source", "Value" => $source ],
                                [ "Attribute" => "mx_Secondary_Source", "Value" => $secondary_source ],
                                [ "Attribute" => "mx_City", "Value" => $city ],
                                [ "Attribute" => "mx_State", "Value" => $state],
                                [ "Attribute" => "mx_Owner_Group", "Value" => $ownergroup ],
                                [ "Attribute" => "mx_LIMS_ID", "Value" => "2" ],
                                [ "Attribute" => "mx_Zip", "Value" => $zip ],
                                [ "Attribute" => "Patient Stage", "Value" => "Open" ],
                                [ "Attribute" => "mx_Digital_Lead", "Value" => "True" ],
                                [ "Attribute" => "mx_Digital_Source_Name", "Value" => $digital_source_name ],
                                [ "Attribute" => "mx_Digital_Update_Date", "Value" => $currentDateTime ]
                            ],
                            "Activity" => [
                                "ActivityEvent" => 207,
                                "ActivityNote" => $notes,
                                "ActivityDateTime" => $currentDateTime,
                                "Fields" => [
                                    [ "SchemaName" => "Status", "Value" => "Active" ],
                                    [ "SchemaName" => "mx_Custom_72", "Value" => $device_name ],
                                    [ "SchemaName" => "mx_Custom_71", "Value" => $source_page ]
                                ]
                            ]
                        ];


                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $response = curl_exec($ch);
                        if(curl_errno($ch)) {
                            echo "cURL error: " . curl_error($ch);
                        }

                        $name = mysqli_real_escape_string($conn, $_POST['name']);

                        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

                        $sql = "INSERT INTO neuberg_form_submission (`first_name`, `last_name`, `email`, `mobile`, `phone`, `source`, `secondary_source`, `city`, `state`, `owner_group`, `zip_code`, `digital_source`, `notes`, `device_name`, `source_page`,`created_on`) VALUES ('$name', '$lname', '$email', '$mobile', '$phone', '$source', '$secondary_source', '$city', '$state', '$ownergroup', '$zip', '$digital_source_name', '$notes', '$device_name', '$source_page', '$currentDateTime')";
                        
                        if (mysqli_query($conn, $sql)) {
                            //echo "New record created successfully";
                        } else {
                            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }

                        session_destroy();
                        $json_data = array("rslt"=>"1", 
                            "message" => 'Submitted Successfully.',
                            "response" =>  $response
                        ); 
                }
        }

        echo json_encode($json_data); 
        
 }   
        

          






        
   