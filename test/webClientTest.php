<?php
require_once(__DIR__ . '/../includes/webClient.php');
require_once(__DIR__ . '/../includes/testException.php');

class WebClientTestCase extends PHPUnit_Framework_TestCase {
	private $pid;

	public function setUp() {
		$this->pid = shell_exec(__DIR__ . "/webClientTestServer.coffee > /dev/null 2> /dev/null & echo $!");
		usleep(500000); // 0.5 second
		// Source: http://nsaunders.wordpress.com/2007/01/12/running-a-background-process-in-php/
	}

	public function testInvalidUrl() {
		testException($this, "cURL Error: couldn't connect to host", function() {
			$wc = webClient_create();
			$response = webClient_get($wc, 'http://localhost:9876/invalidUrl');
			webClient_close($wc);
		});
	}

	public function testSimpleGet() {
		$wc = webClient_create();
		$response = webClient_get($wc, 'http://localhost:9000/simpleget');
		$this->assertEquals($response['body'], 'simpleget says hi!');
		webClient_close($wc);
	}

	public function testSimplePostYes() {
		$wc = webClient_create();
		$response = webClient_post($wc, 'http://localhost:9000/simplepost', array(
			'imLooking' => 'forTheRightInfo'
		));
		$this->assertEquals($response['body'], 'Yes');
		webClient_close($wc);
	}

	public function testSimplePostNo() {
		$wc = webClient_create();
		$response = webClient_post($wc, 'http://localhost:9000/simplepost', array(
			'imLooking' => 'forTheWrongInfo'
		));
		$this->assertEquals($response['body'], 'No');
		webClient_close($wc);
	}

	public function testGoodSession() {
		$wc = webClient_create();

		$response = webClient_get($wc, 'http://localhost:9000/step1');
		$this->assertEquals($response['body'], 'done step1');

		$response = webClient_get($wc, 'http://localhost:9000/step2');
		$this->assertEquals($response['body'], 'step1 => step2');

		$response = webClient_get($wc, 'http://localhost:9000/reset');
		$this->assertEquals($response['body'], 'reset');

		webClient_close($wc);
	}

	public function testBadSession() {
		$wc = webClient_create();

		$response = webClient_get($wc, 'http://localhost:9000/step2');
		$this->assertEquals($response['body'], 'you need to do step1 first');

		$response = webClient_get($wc, 'http://localhost:9000/reset');
		$this->assertEquals($response['body'], 'reset');

		webClient_close($wc);
	}

	public function tearDown() {
		shell_exec('kill ' . $this->pid);
	}
}
?>