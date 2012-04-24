<?php // 0.0.2

require_once('parseHttpResponseString.php');

function webClient_close($ch) {
	curl_close($ch);
}

function webClient_create() {
	$cookieFile = tempnam(sys_get_temp_dir(), '');

	$ch = curl_init();

	// set defaults
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // follow redirects
	curl_setopt($ch, CURLOPT_HEADER, TRUE); // flag to return header in response
	curl_setopt($ch, CURLOPT_PROXY, NULL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // flag to return response
	return $ch;
}

function webClient_execUrlAndProcessResponse($ch, $url) {
	curl_setopt($ch, CURLOPT_URL, $url);
	$responseString = curl_exec($ch);

	$curlError = curl_error($ch);
	if ($curlError !== '') {
		trigger_error("cURL Error: $curlError");
	}

	$response = parseHttpResponseString($responseString, $url);

	return $response;
}

function webClient_get($ch, $url) {
	curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
	return webClient_execUrlAndProcessResponse($ch, $url);
}

function webClient_post($ch, $url, $postArray) {
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postArray);
	return webClient_execUrlAndProcessResponse($ch, $url);
}
?>