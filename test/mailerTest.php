<?php
require_once(__DIR__ . '/../includes/mailer.php');

define('TRAPMAIL_PATH', '/tmp/trapmail.log');
define('SENDMAIL_PATH', 'cat /dev/stdin >> ' . TRAPMAIL_PATH);

class mailerTestCase extends PHPUnit_Framework_TestCase {
	private function validateSendMailPathCorrect() {

		if (ini_get('sendmail_path') !== SENDMAIL_PATH) {
			trigger_error('In php.ini, sendmail_path should be set to "' . SENDMAIL_PATH . '".');
		}
	}

	private function deleteTrapmailIfItExists() {
		if (file_exists(TRAPMAIL_PATH)) {
			unlink(TRAPMAIL_PATH);
		}
	}

	public function testNewlineIsInjected() {
		$result = mailer_isInjected("email1@example.com\r\nTo: email2@example.com");
		$this->assertTrue($result);
	}

	public function testIsNotInjected() {
		$result = mailer_isInjected("email@example.com");
		$this->assertFalse($result);
	}

	public function testSend() {
		$this->validateSendMailPathCorrect();
		$this->deleteTrapmailIfItExists();
		mailer_send('chris.khoo@gmail.com', 'Test Subject', "<html><body><p><strong>Blah Blah Blah!</strong></p><p>Testing 1 2 3!</p></body></html>", 'Chris Khoo', 'christopher.khoo@deta.qld.gov.au', 'text/html');

		// headers can change according to PHP platform & version, so I haven't bothered to validate them here
		$contents = file_get_contents('/tmp/trapmail.log');
		$findstring = "PGh0bWw+PGJvZHk+PHA+PHN0cm9uZz5CbGFoIEJsYWggQmxhaCE8L3N0cm9uZz48L3A+PHA+VGVz\r\ndGluZyAxIDIgMyE8L3A+PC9ib2R5PjwvaHRtbD4=";
		$this->assertTrue(strpos($contents, $findstring) !== FALSE);
	}
	
}
?>