<?php // 0.0.2
/*
Sources:
* http://www.php.net/manual/en/function.mail.php#105661
* http://www.damonkohler.com/2008/12/email-injection.html
* http://stackoverflow.com/questions/6527815/can-i-avoid-crlf-injection-attacks-by-replacing-just-the-cr
*/

function mailer_isInjected($string) {
	return preg_match('/(\r)|(\n)/i', $string) ? true : false;
}

function mailer_errorOutIfInjected($string, $typeName) {
	if (mailer_isInjected($string)) {
		trigger_error("$typeName address contains CRLF characters.");
		exit();
	}
}

function mailer_send($to, $subject, $body, $fromName, $fromAddress, $messageContentType = 'text/plain', $attachments = array()) {
	$hasAttachments = (sizeof($attachments) > 0);
	$phpVersion = phpversion();
	$timestamp = time();

	mailer_errorOutIfInjected($to, 'To');
	mailer_errorOutIfInjected($subject, 'Subject');
	mailer_errorOutIfInjected($fromName, 'From Name');
	mailer_errorOutIfInjected($fromAddress, 'From Address');

	// create headers (note: message-id & x-mailer helps to pass spam filters)
	$headers = array(
		"From: $fromName <$fromAddress>",
		"Reply-To: $fromName <$fromAddress>",
		"Message-ID: <{$timestamp}.{$fromAddress}>",
		"X-Mailer: PHP/{$phpVersion}",
		"MIME-Version: 1.0"
	);

	if ($hasAttachments) {
		$semiRand = md5($timestamp);
		$mimeBoundary = "==Multipart_Boundary_x{$semiRand}x";
		$headers[] = "Content-Type: multipart/mixed; boundary=\"$mimeBoundary\"";
	} else {
		$headers[] = "Content-Type: $messageContentType; charset=utf-8";
		$headers[] = "Content-Transfer-Encoding: base64";
	}

	$headerString = implode("\r\n", $headers);

	$content = array();
	if ($hasAttachments) {
		$content[] = "--$mimeBoundary";
		$content[] = "Content-Type: $messageContentType; charset=utf-8";
		$content[] = "Content-Transfer-Encoding: base64\n"; // requires newline to separate headers & content
		$content[] = chunk_split(base64_encode($body)) . "\n"; // requires newline to separate each boundary

		foreach ($attachments as $fileName => $fileContents) {
			$content[] = "--$mimeBoundary";
			$fileSize = strlen($fileContents);
			$fileContentsEncoded = chunk_split(base64_encode($fileContents));

			$content[] = "Content-Type: application/octet-stream; name=\"$fileName\"";
			$content[] = "Content-Description: $fileName";
			$content[] = "Content-Disposition: attachment; filename=\"$fileName\"";
			$content[] = "Content-Transfer-Encoding: base64\n"; // requires newline to separate headers & content
			$content[] = $fileContentsEncoded . "\n"; // requires newline to separate each boundary
		}
    	$content[] = "--{$mimeBoundary}--";
	} else {
		$content[] = chunk_split(base64_encode($body));
	}

	$contentString = implode("\n", $content);

	// on some servers, the PHP interpreter uses the sendmail_from INI setting regardless of the headers set, so we'll set it here
	ini_set('sendmail_from', $fromAddress);

	$isSent = mail($to, $subject, $contentString, $headerString);

	ini_restore('sendmail_from');

	return $isSent;
}
?>