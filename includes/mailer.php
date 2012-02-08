<?php // 0.0.1

/*
Learn how to secure your emails from injection attacks with these articles:
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

function mailer_send($to, $subject, $body, $fromName, $fromAddress, $messageContentType = 'text/plain') {
	$timestamp = time();
	$phpVersion = phpversion();

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
		"MIME-Version: 1.0",
		"Content-Type: $messageContentType; charset=utf-8",
		"Content-Transfer-Encoding: base64"
	);
	$headerString = implode("\r\n", $headers);
	
	$content = chunk_split(base64_encode($body));

	// on some servers, the PHP interpreter uses the sendmail_from INI setting regardless of the headers set, so we'll set it here
	ini_set('sendmail_from', $fromAddress);

	$isSent = mail($to, $subject, $content, $headerString);

	ini_restore('sendmail_from');

	return $isSent;
}
?>