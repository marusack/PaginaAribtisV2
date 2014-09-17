<?php

include('settings.php');

/* === BEGIN PHP SCRIPT === */

if(isset($_GET['action'])) {
	if($_GET['action'] == 'signup'){
		
		if($saveToMySQL == 'true') {
			$con =mysqli_connect($mysql_host,$mysql_username,$mysql_password);  
			mysqli_select_db($mysql_database);
		}
		
		// Sanitize Email Address (for security)
		
		if($saveToMySQL == 'true') { 
                    $email = mysqli_real_escape_string($con,$_POST['signup-email']);
                    }
		$email = trim(strip_tags($email));
                    
                        // Check / store emails
			if(empty($email)){
				$status = "error";
				$returnmessage = $emailempty_errormsg;
			}
			else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ // Validate Email Address (If browser does not support HTML5 auto email validation)
				$status = "error";
				$returnmessage = $invalidemail_errormsg;
			}
			else {
				$date = date('Y-m-d'); // Get the current date to store with email in database
				$time = date('H:i:s'); // Get the current time to store with email in database
						
				if($saveToMySQL == 'true') {
					$emailexist = mysqli_query($con,"SELECT * FROM $mysql_table WHERE signup_email_address='$email'"); // Check if entered email already exists in the database 
					if(mysql_num_rows($emailexist) < 1) {
						$date = date('Y-m-d'); // Get the current date to store with email in database
						$time = date('H:i:s'); // Get the current time to store with email in database
						
						$insertEmail = mysqli_query($con,"INSERT INTO $mysql_table (signup_email_address, signup_date, signup_time) VALUES ('$email','$date','$time')"); // Insert email address into MySQL table, or remove this for a custom way to save addresses
						if($insertEmail){
							$status = "success";
							$returnmessage = $subscriptionsuccess_msg;
						}
						else {
							$status = "error";
							$returnmessage = $insertfailure_errormsg;
						}
					}
					else { // if already signed up
						$status = "error";
						$returnmessage = $emailexists_errormsg;
					}
				}
				
				if($sendToEmail == 'true') {
					$date = date('Y-m-d'); // Get the current date to store with email in database
					$time = date('H:i:s'); // Get the current time to store with email in database
					
					$to = $myEmail;
					$subject = "A new user has subscribed!";
					$message = "A new user has subscribed. <br />Email address: ". $email ."<br />Time: ". $time ."<br />Date: ". $date;
					$from = $emailFrom;
					$headers = "From:" . $from ."\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					mail($to,$subject,$message,$headers);

							$status = "success";
							$returnmessage = $subscriptionsuccess_msg;
				}
				
				if($mailChimpList == 'true') {
					require_once 'mailchimp/MCAPI.class.php';
					
					$api = new MCAPI($apikey);
					
					// By default this sends a confirmation email - you will not see new members
					// until the link contained in it is clicked!
					$retval = $api->listSubscribe( $listId, $email );
					
					if ($api->errorCode){
						$status = "error";
						$returnmessage = $emailexists_errormsg;
					}
					else {
						$status = "success";
						$returnmessage = $subscriptionsuccess_msg;
					}
				}
			}
			
	// JSON response
	$data = array(
		'status' => $status,
		'returnmessage' => $returnmessage
	);
	
	echo json_encode($data);
	exit;
	}
}
?>