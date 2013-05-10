<?php

		require($mailerfull."phpmailer/class.phpmailer.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");


			$mailtostring = $email;
		
			$mailstring .= 
			"One of your friends at ".$nameid." has logged a comment at the ".$sitedescription."\n".
			"Click on the link below, to check it out!\n".
			">>>>> ".$homelocationurl."\n";
			
			$mail = new PHPMailer();
			$mail->SetLanguage("en", $rootlocation."login/phpmailer/language");

	
			$mail->IsSMTP();                                      // set mailer to use SMTP
			$mail->Host = "bluemountainairpark.com;gator1628.hostgator.com";  // specify main and backup server
			//$mail->SMTPAuth = true;     // turn on SMTP authentication
			//$mail->Username = "jswan";  // SMTP username
			//$mail->Password = "secret"; // SMTP password
			
			$mail->From = "admin@bluemountainairpark.com";
			$mail->FromName = "Mailer";
			//$mail->AddAddress("josh@example.net", "Josh Adams");
			$mail->AddAddress("eightyoctane@yahoo.com");                  // name is optional
			$mail->AddReplyTo("eightoctane@yahoo.com", "Information");
			
			$mail->WordWrap = 50;                                 // set word wrap to 50 characters
			//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
			//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
			$mail->IsHTML(true);                                  // set email format to HTML
			
			$mail->Subject = "New Comment from Friends at Blue Mountain Airpark!";
			//$mail->Body    = "This is the HTML message body <b>in bold!</b>";
			$mail->AltBody = $mailstring;
			
			if(!$mail->Send())
			{
			   $lf->logMessage("update_ajax.php: Mail message could not be sent.\n");
			   $lf->logMessage("update_ajax.php: Mailer Error: " . $mail->ErrorInfo."\n");
			} else {			
				$lf->logMessage("update_ajax.php: Mail messages have been sent.\n");		
			}
