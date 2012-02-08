<?php // 0.0.1

// Based on http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html

function parseHttpResponseString($responseString) {
	$pattern = '/^HTTP\/(\d+.\d+) ([^ ]+) (.*?)\r\n(.*?\r\n)\r\n(.*)/s';
	preg_match_all($pattern, $responseString, $matches);

	// do validation
	if (sizeof($matches[0]) == 0) {
		trigger_error("parseHttpResponseString should specify a response string matching PCRE pattern $pattern.");
	}

	$response['httpVersion'] = $matches[1][0];

	$response['statusCode'] = $matches[2][0];
	if (!is_numeric($response['statusCode'])) {
		trigger_error("parseHttpResponseString response should have a valid status code (currently $response[statusCode]).");
	}

	$response['reasonPhrase'] = $matches[3][0];

	// parse headers
	$headersString = $matches[4][0];
	preg_match_all('/([^:]*)\:\w*(.*?)\r\n/s', $headersString, $headerMatches);
	$len = sizeof($headerMatches[0]);
	for ($i = 0; $i < $len; $i++) {
		// must remove all left whitespace for header value <http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html>
		$headers[$headerMatches[1][$i]] = ltrim($headerMatches[2][$i]);
	}
	$response['headers'] = $headers;

	$response['body'] = $matches[5][0];

	return $response;
}
?>