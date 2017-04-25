<?php 

$to_emails = [
 "info@your-site.com"
];

$log_file_path = "/contact-form-log.json";
$blacklist_file_path = "/blacklist.json";

$sent = false;
$error_msg = "";

//check to make sure referer matches
if (isset($_SERVER['HTTP_REFERER'])) {
	$referer_domain = parse_url($_SERVER['HTTP_REFERER']);
	$referer_domain = $referer_domain['host'];

	$this_domain = $_SERVER['HTTP_HOST'];

	if ($this_domain == $referer_domain) {

		if ($_POST['trap'] == "") {

			//message appears to be valid and not spam, so we'll send it through

			$subject = "Contact Form from ".$referer_domain;

			$reply_email = "no-reply@".$referer_domain;
			$can_reply = "";
			if (isset($_POST['email'])) {
				$reply_email = strip_tags($_POST['email']);
				$can_reply = "<p>*You can reply to this email to reach the customer.</p> ";
			}

			$headers = "From: " . $reply_email . "\r\n";
			$headers .= "Reply-To: ".$reply_email . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message = "<html> 
			 <head> 
			 <style type='text/css'> 
				 body {font-family: sans-serif; font-size: 14pt; } 
				 table {border-collapse: collapse; } 
				 th {background-color: #e0e0e0; text-align: right; border-right: 1px solid #202020; } 
				 th, td {padding: 5px; } 
				 h1 {font-weight: lighter; font-size: 18pt; } 
				 span { color: red; } 
			 </style> 
			 </head> 
			 <body> 
			 <h1 style='color:#004c99;margin:0;padding:0;'>$subject</h1> 
			 <table>";

			foreach ($_POST as $field => $value) {

				if ($field != "trap") {
					//convert field key to label string
					$label = ucwords(str_replace("_", " ", $field));

					$message.= "
						<tr>
							<th>$label</th>
							<td>$value</td>
						</tr>";
				}
			}

			$message .= "
				<tr>
					<th>Date/Time Sent</th>
					<td>".date("Y-m-d H:i:s")."</td>
				</tr>
			</table>    
			 $can_reply
			 </body></html>";

			// Finally email the messages out to every email we have listed as a recipient! (defined at the top of the script)
			foreach ($to_emails as $recipient) {
				mail($recipient, $subject, $message, $headers);
			}

			$sent = true;

			echo "success";

		}
		else {
			$error_msg = "Honeypot triggered";
		}
	}
	else {
		$error_msg = "Referer doesn't match";
	}

}
else {
	$error_msg = "Referer not set";
}

if (!$sent) {
	//log the error
	$line = $error_msg.",".date("Y-m-d H:i:s")."\r\n";
	file_put_contents($log_file_path, $line, FILE_APPEND);
}