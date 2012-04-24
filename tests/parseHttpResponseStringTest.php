<?php
require_once(__DIR__ . '/../lib/parseHttpResponseString.php');
require_once(__DIR__ . '/../lib/testException.php');

class ParseHttpResponseStringTestCase extends PHPUnit_Framework_TestCase {
	public function testRubbish() {
		testException($this, 'parseHttpResponseString should specify a response string matching PCRE pattern /^HTTP\/(\d+.\d+) ([^ ]+) (.*?)\r\n(.*?\r\n)\r\n(.*)/s.', function() {
			parseHttpResponseString('rubbish');
		});
	}

	public function testNonNumericStatusCode() {
		testException($this, 'parseHttpResponseString response should have a valid status code (currently nonNumericStatusCode).', function() {
			parseHttpResponseString("HTTP/1.1 nonNumericStatusCode OK\r\nStuff: Stuff:Content\r\n\r\nBody starts here");
		});
	}

	public function testCorrectResponseString() {
		$response = parseHttpResponseString("HTTP/1.1 200 OK\r\nStuff: Stuff:Content\r\n\r\nBody starts here");
		$this->assertEquals($response['httpVersion'], '1.1');
		$this->assertEquals($response['statusCode'], 200);
		$this->assertEquals($response['reasonPhrase'], 'OK');
		$this->assertEquals($response['headers'], array(
			'Stuff' => 'Stuff:Content'
		));
		$this->assertEquals($response['body'], 'Body starts here');
	}
}
?>