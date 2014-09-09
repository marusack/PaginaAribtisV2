<?php

include('settings.php');

// Code (no need to edit beyond here)
	
if(isset($_GET['action'])) {
		
if($_GET['action'] == 'contact') {
		
	if(isset($_POST['submitted'])) {
	
		
				
		if(trim($_POST['contactName']) === '') { 
	
				$hasError = 'true';
		
				echo 'name field is empty<br />';
				
} 
		else {
					
				$name = trim($_POST['contactName']);

				}
	
			
				
if(trim($_POST['friendlyUrl']) === '') {
	
			}
			
	else {
					
$hasError = 'true';
					
echo 'invalid spam field<br />';
				
}

			
				
if(trim($_POST['email']) === '')  {
			
		$hasError = 'true';
			
		echo 'email field empty<br />';
	
	
		} 
else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email'])))
 {
					
$hasError = 'true';
			
		echo 'invalid email address<br />';
	
			} 
else {
				
	$email = trim($_POST['email']);
		
		}
	
		
				
if(trim($_POST['comments']) === '') 
{
					
$hasError = 'true';
		
			echo 'message field is empty<br />';

				} 
else {
					
if(function_exists('stripslashes')) {
	
					
$comments = stripslashes(trim($_POST['comments']));
	
				} 
else {
					
$comments = trim($_POST['comments']);

					
}
				
}
			

				
if(!isset($hasError))
 {
			
	
				$emailTo = $yourEmail;

					$subject = 'Message from '. $name;

					$body = "Name: $name <br />Email: $email <br /><br />Message: $comments";

					
$headers  = 'MIME-Version: 1.0' . "\r\n";
		
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					
$headers .= 'Reply-To: '. $email .'' . "\r\n";// Additional headers


					$headers .= 'From: '. $siteName .'' . "\r\n";
		
if(mail('jarasergio@outlook.com', $subject, $body, $headers)) 
{
						
	$emailSent = 'true';
	

	$date = date('Y-m-d'); // Get the current date to store with email in database
	$time = date('H:i:s'); // Get the current time to store with email in database

//aca guardo el despelote
			mysql_connect($mysql_host,$mysql_username,$mysql_password);  
			mysql_select_db($mysql_database);
			$insertEmail = mysql_query("INSERT INTO contacto (email, body, date,time)	VALUES ('$email','$body','$date','$time')");

		
				 if ($insertEmail===TRUE){
				        echo 'Se inserto el chango';
				    }  else {

				        echo 'ERROR: No Se inserto el chango';
				    }

//aca iria la logica para usar un server smtp o guardar en la db!					
			//echo 'sent successfully<br />';
		
			
}
					
else {
		
				
$hasError = 'true';
	
echo 'failed to send<br />';
	
				}

				
}
		

	
}
		
}
	

}
?>
