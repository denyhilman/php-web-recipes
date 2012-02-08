<?php
require_once(__DIR__ . '/../includes/bcrypt.php');

class BcryptTestCase extends PHPUnit_Framework_TestCase {
	public function testBcrypt() {
		$input = 'test';
		$hash = bcrypt_hash($input);
		$result = bcrypt_verify($input, '$2a$15$VN8FbzpHc.eqs5GiGAeOfe0iSZAXJpPCzWNhPmsB3HPUs9QBIuaqe');
		$this->assertTrue($result);
	}
}
?>