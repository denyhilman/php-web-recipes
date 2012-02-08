<?php
require_once(__DIR__ . '/../includes/isEmail.php');

class isEmailTestCase extends PHPUnit_Framework_TestCase {
	public function testCorrectEmail() {
		$result = isEmail('chris.khoo@gmail.com');

		$this->assertTrue($result);
	}

	public function testIncorrectEmail() {
		$result = isEmail('someRubbishHere');

		$this->assertFalse($result);
	}
}
?>