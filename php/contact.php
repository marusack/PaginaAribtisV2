<?php

include('settings.php');

// Code (no need to edit beyond here)
	if(isset($_GET['action'])) {
		if($_GET['action'] == 'contact') {
			if(isset($_POST['submitted'])) {
			
				if(trim($_POST['contactName']) === '') { 
					$hasError = 'true';
					echo 'name field is empty<br />';
				} else {
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
				} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
					$hasError = 'true';
					echo 'invalid email address<br />';
				} else {
					$email = trim($_POST['email']);
				}
			
				if(trim($_POST['comments']) === '') {
					$hasError = 'true';
					echo 'message field is empty<br />';
				} else {
					if(function_exists('stripslashes')) {
						$comments = stripslashes(trim(mysqli_real_escape_string($con,$_POST['comments'])));
					} else {
					$comments = trim( mysqli_real_escape_string($con,$_POST['comments']));
					}
				}
                                if(!isset($hasError)) {
                                        
                                        $con=mysqli_connect($mysql_host,$mysql_username,$mysql_password);
                                        if (mysqli_connect_errno()) {
                                            echo "Error al conectar con MySQL: " . mysqli_connect_error();
                                          }
                                        //abro la conexion;
                                          
                                        $name= mysqli_real_escape_string($con, $_POST['contactName']);
                                        $email= mysqli_real_escape_string($con,$_POST['email']);
                                        $subject='nombre: '.$name.' email: '.$email.'comments: '.$comments;
                                        
                                        
                                        //evitar sql injection
                                        
					if(mail('info@airbits.com.ar', $subject, $comments)){
                                                $emailSent = 'true';
                                                $date = date('Y-m-d'); // Get the current date to store with email in database
                                                $time = date('H:i:s'); // Get the current time to store with email in database
                                                //aca guardo 
                                                mysqli_query($con, "INSERT INTO $mysql_tableContac (email, body, date, time) VALUES ('$email','$comments','$date','$time')");
                                            }
                                        mysqli_close($con);
				}
			}
		}
	}
?>
