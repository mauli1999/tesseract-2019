<?php
	date_default_timezone_set('Asia/Kolkata');
	$dateTime = date('m/d/Y h:i:s a', time());
	if(isset($_POST['emailid']) && isset($_POST['tkn']) && $_POST['tkn'] == "CheckEmail") {
		if(checkEmail($_POST['emailid']) != true) {
			echo "0";
		}
		else {
			echo "1";
		}
	}

	if(isset($_POST['sName']) && isset($_POST['fName']) && isset($_POST['lName']) && isset($_POST['phoneNo']) && isset($_POST['emailid']) && isset($_POST['clgName']) && isset($_POST['eventName']) && isset($_POST['base64']) && isset($_POST['ipAddr']) && isset($_POST['loctn']) && $_POST['tkn'] == "RegisterNow") {
		if(checkEmail($_POST['emailid']) != true) {
			echo "0";
			exit();
		}
		else {
			echo "1";
		}
		/*
		$eventsList = "";
		$i = 0;
		while($i < count($_POST['eventName'])) {
			$eventsList .= $_POST['eventName'][$i];
			if($i == count($_POST['eventName']) - 2) {
				$eventsList .= " and ";
			}
			else if($i != count($_POST['eventName']) - 1) {
				$eventsList .= ", ";
			}
			$i++;
		}*/
		
		$imgName = saveImage($_POST['base64']);
		$organizerEmail = "foramparikh0103@gmail.com"; //Organizer's Email address
		echo sendEmailToParticipant($_POST['sName'], $_POST['fName'], $_POST['lName'], $_POST['phoneNo'], $_POST['emailid'], $_POST['clgName'], $_POST['eventName'], $dateTime);
		echo sendEmailToOrganizer($_POST['sName'], $_POST['fName'], $_POST['lName'], $_POST['phoneNo'], $_POST['emailid'], $organizerEmail, $_POST['clgName'], $_POST['eventName'], $dateTime, $imgName, $_POST['ipAddr'], $_POST['loctn']);
	}
	else {
		echo "Invalid data received.";
	}

	function checkEmail($emailid) {
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
		if (preg_match($regex, $emailid)) {
			return true;
		}
		else {
			return false;
		}
	}

	function set_mail_data($subject, $msg, $receiverEmail, $receiverName, $img_name = null) {
		require_once('phpmailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->isHTML(true);
		$mail->Username = "foramparikh0103@gmail.com"; //Sender email address
		$mail->Password = "foramtesseract2019"; //Sender password
		$mail->From = "foramparikh0103@gmail.com"; //Sender email address
		$mail->FromName = "Foram Parikh"; //Sender Name
		$mail->Subject = "Registration in Tesseract"; //Subject
		$mail->Body = $msg; //Body of mail
		$mail->addAddress($receiverEmail, $receiverName); //Participant's email address and name
		if($img_name != null) {
			if(substr($img_name, -2) == "ng") {
				$type = "image/png";
			}
			elseif(substr($img_name, -2) == "pg") {
				$type = "image/jpeg";
			}
			$mail->addAttachment("payments/".$img_name, 'payment_img', 'base64', $type);
		}
		if(!$mail->Send()) {
			return false;
		}
		else {
			return true;
		}
	}

	function sendEmailToParticipant($sName, $fName, $lName, $phoneNo, $emailid, $clgName, $eventNames, $dateTime) {
		$subject = "Registration in Tesseract"; //Subject
		$msg = "<div style='background: #c63fa4; background-image: -moz-linear-gradient(120deg, #df42b1 0%, #505add 100%); background-image: -webkit-linear-gradient(120deg, #df42b1 0%, #505add 100%); background-image: -ms-linear-gradient(120deg, #df42b1 0%, #505add 100%); width: 100%; text-align: center;'><div style='font: 32px Calibri Light; color: white; padding: 1%;'>PDPU | Tesseract</div></div><br><div style='font: 18px Calibri Light; color: #545454; text-align: left; margin-top: 1%;'>Dear $fName,</div><div style='font-size: 14px;'><br>Thank you for registering in Tesseract for <b>$eventNames</b>.</div><br><div style='font-size: 12px;'>Have a great day.</div>";
		return set_mail_data($subject, $msg, $emailid, $fName.' '.$sName);
	}
	
	function sendEmailToOrganizer($sName, $fName, $lName, $phoneNo, $emailid, $organizerEmail, $clgName, $eventNames, $dateTime, $imgName, $ipAddr, $loctn) {
		$subject = "Registration in Tesseract"; //Subject
		$msg = "<div style='background: #c63fa4; background-image: -moz-linear-gradient(120deg, #df42b1 0%, #505add 100%); background-image: -webkit-linear-gradient(120deg, #df42b1 0%, #505add 100%); background-image: -ms-linear-gradient(120deg, #df42b1 0%, #505add 100%); width: 100%; text-align: center;'><div style='font: 32px Calibri Light; color: white; padding: 1%;'>PDPU | Tesseract</div></div><br><div style='font: 18px Calibri Light; color: #545454; text-align: left; margin-top: 1%;'>Time: $dateTime<br>Name: <b>$sName $fName $lName</b><br>Phone No.: <b>$phoneNo</b><br>Email address: <b>$emailid</b><br>College: <b>$clgName</b><br>Event: <b>$eventNames</b></div><br>IP Address: <b>$ipAddr</b><br>Location: <b>$loctn</b><br>";
		return set_mail_data($subject, $msg, $organizerEmail, $fName.' '.$sName, $imgName);
	}
	
	function saveImage($data) {
		if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
			$data = substr($data, strpos($data, ',') + 1);
			$type = strtolower($type[1]);

			if (!in_array($type, [ 'jpg', 'jpeg', 'png' ])) {
				echo 'invalid image type.';
			}

			$data = base64_decode($data);

			if ($data === false) {
				echo 'base64_decode failed.';
			}
		}
		else {
			echo 'did not match data URI with image data.';
		}
		file_put_contents("payments/img.{$type}", $data);
		return "img.{$type}";
	}
?>