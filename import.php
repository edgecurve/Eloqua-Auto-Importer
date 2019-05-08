<?php

// Credentials used when compiling the Eloqua Basic Auth key
$myUser     = 'Scott.Hendrickson';
$myCompany  = 'GettyImagesInc';
$myPassword = 'PASSWORD';

/*
NOTES!

Change the XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX auth code on lines 193 and 244. Generate auth code with auth.php.

*/

// Import settings

//$brand = 'GI'; // GI, IS, TS, NN, or MB
//$sendtestemail = 'NO'; // YES OR NO

$brand = $argv[1];
$sendtestemail = $argv[2];

if ($brand=="" OR $sendtestemail=="") {
echo "\n"."Please specify brand and test email request. Example:"."\n\n"."php import.php GI YES"."\n"."php import.php IS NO"."\n"."Etc..."."\n\n";
die();
}

// End import settings


echo "\n"."Processing files. This takes around 15-30 seconds per email. Results: "."\n\n";

$testsendstatus = [];

foreach (glob("Imports/*.html") as $filename) {

//var_dump (file_get_contents($filename));

$html_content = file_get_contents($filename);
$eloqua_email_name = preg_replace("@Imports\/@", '', $filename);
$eloqua_email_name = preg_replace("@\.html@", '', $eloqua_email_name);

$res = preg_match("/<title>(.*)<\/title>/siU", $html_content, $title_matches);
        if (!$res)
            return null;

        // Clean up title: remove EOL's, excessive whitespace, brand name, and cleans character encoding.
        $title = preg_replace('/\s+/', ' ', $title_matches[1]);

        if ($brand == "IS") {
        // IS emails:
        $title = preg_replace('/\s\|\siStock/', ' ', $title_matches[1]);
        }

        if ($brand == "GI") {
        // GI emails
        $title = preg_replace('/\s\|\sGetty\sImages/', ' ', $title_matches[1]);
        }

        if ($brand=="MB") {
        // GI emails
        $title = preg_replace('/\s\|\sGetty\sImages/', ' ', $title_matches[1]);
        }

        if ($brand == "NN") {
        // GI emails
        $title = preg_replace('/\s\|\siStock/', ' ', $title_matches[1]);
        }

        if ($brand == "TS") {
        // TS emails
        $title = preg_replace('/\s\|\sThinkstock/', ' ', $title_matches[1]);
        }

        $title = trim($title);
        $title = html_entity_decode($title);
        //echo $title;

if ($brand == "IS") {
$json_array = [

	             'name' => $eloqua_email_name,
	             'subject' => $title,
	             'isTracked' => 'true',
	             'bounceBackEmail' => 'noreply@engage.istockphoto.com',
	             'replyToEmail' => 'noreply@istock.com',
	             'replyToName' => 'iStock',
	             'senderEmail' => 'info@engage.istockphoto.com',
	             'senderName' => 'iStock',
	             'emailGroupId' => 10,
	             'folderId' => 10032,
	             'htmlContent'     => [
	                     'type'    => 'RawHtmlContent',
	                     'html'    => $html_content,
	             ],

	         ];
}

if ($brand == "NN") {
$json_array = [

	             'name' => $eloqua_email_name,
	             'subject' => $title,
	             'isTracked' => 'true',
	             'bounceBackEmail' => 'noreply@engage.istockphoto.com',
	             'replyToEmail' => 'noreply@istock.com',
	             'replyToName' => 'iStock',
	             'senderEmail' => 'info@engage.istockphoto.com',
	             'senderName' => 'iStock',
	             'emailGroupId' => 16,
	             'folderId' => 10032,
	             'htmlContent'     => [
	                     'type'    => 'RawHtmlContent',
	                     'html'    => $html_content,
	             ],

	         ];
}

if ($brand == "GI") {
$json_array = [

	             'name' => $eloqua_email_name,
	             'subject' => $title,
	             'isTracked' => 'true',
	             'bounceBackEmail' => 'noreply@engage.gettyimages.com',
	             'replyToEmail' => 'noreply@gettyimages.com',
	             'replyToName' => 'Getty Images',
	             'senderEmail' => 'info@engage.gettyimages.com',
	             'senderName' => 'Getty Images',
	             'emailGroupId' => 51,
	             'folderId' => 10032,
	             'htmlContent'     => [
	                     'type'    => 'RawHtmlContent',
	                     'html'    => $html_content,
	             ],

	         ];
}

if ($brand == "TS") {
$json_array = [

	             'name' => $eloqua_email_name,
	             'subject' => $title,
	             'isTracked' => 'true',
	             'bounceBackEmail' => 'noreply@engage.thinkstockphotos.com',
	             'replyToEmail' => 'noreply@thinkstock.com',
	             'replyToName' => 'Thinkstock',
	             'senderEmail' => 'info@engage.thinkstock.com',
	             'senderName' => 'Thinkstock',
	             'emailGroupId' => 12,
	             'folderId' => 10032,
	             'htmlContent'     => [
	                     'type'    => 'RawHtmlContent',
	                     'html'    => $html_content,
	             ],

	         ];
}


if ($brand == "MB") {
$json_array = [

	             'name' => $eloqua_email_name,
	             'subject' => $title,
	             'isTracked' => 'true',
	             'bounceBackEmail' => 'noreply@engage.gettyimages.com',
	             'replyToEmail' => 'noreply@gettyimages.com',
	             'replyToName' => 'Getty Images',
	             'senderEmail' => 'info@engage.gettyimages.com',
	             'senderName' => 'Getty Images | iStock',
	             'emailGroupId' => 10,
	             'folderId' => 10032,
	             'htmlContent'     => [
	                     'type'    => 'RawHtmlContent',
	                     'html'    => $html_content,
	             ],

	         ];
}





// IMPORTNATING AN EMAIL IN ELOQUA VIA THE API

		// Contact ID = CGETI000006833982
		//echo '|      Email Changes Complete - injecting to Eloqua...     Result = ';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://secure.p04.eloqua.com/API/rest/2.0/assets/email');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
		  "Authorization: Basic ".base64_encode($myCompany.'\\'.$myUser.':'.$myPassword),
		  "Content-Type: application/json",
		 ]
		);
		$body = json_encode($json_array, JSON_HEX_QUOT | JSON_HEX_TAG);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

		$resp = curl_exec($ch);

		if(!$resp) {

			  echo ' -  Oops we did not get a response from eloqua. This normally indicates one of two things. 1. You\'re simply not connected to the internet. OR 2. Your Authorization credentials are out of date or inaccurate. OR 3. The system is hosed.  ';
			curl_close($ch);
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));

		} else {

		  	curl_close($ch); // Fix this. No curl call within call.
			//echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE)."\n\n";
			$my_email_object = json_decode($resp, true);
			echo $my_email_object['name']."\n".'https://secure.p04.eloqua.com/Main.aspx#emails&id='.$my_email_object['id']."\n\n";

			//Create array for test email
			$json_testemail_array = [
				"email" => [
					"type" => "Email",
					"id" => $my_email_object['id'],
					"name" => $my_email_object['name'],
				],
				"sendOptions" => [
					"allowSendToUnsubscribe" => "true"
				],
				"type" => "EmailLowVolumeDeployment",
				"name" => "Marketing Test Email",
				"contactIds" => [
					6833982
				]
			];

			// SENDING AN EMAIL IN ELOQUA VIA THE API IF ENABLED


			if ($sendtestemail == 'YES') {

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://secure.p04.eloqua.com/API/rest/2.0/assets/email/deployment');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, [
					  "Authorization: Basic ".base64_encode($myCompany.'\\'.$myUser.':'.$myPassword),
					  "Content-Type: application/json",
					 ]
					);
					$body = json_encode($json_testemail_array, JSON_HEX_QUOT | JSON_HEX_TAG);

					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

					$resp = curl_exec($ch);

					if(!$resp) {

						  echo ' -  test send failed in Eloqua ';

						die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));

					} else {

						array_push($testsendstatus, [$my_email_object['name'] => $my_email_object['id']] );

					}

					curl_close($ch);
			}

		}


}

if ($sendtestemail == 'YES') {
echo "Emails successfully sent: <pre>";
print_r ($testsendstatus);
echo "</pre>";
}

?>
