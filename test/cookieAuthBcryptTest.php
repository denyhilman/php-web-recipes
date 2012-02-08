<?php
require_once(__DIR__ . '/../includes/cookieAuthBcrypt.php');

class CookieAuthBcryptTestCase extends PHPUnit_Framework_TestCase {
	public function testCookieAuthBcrypt() {
		$hash = cookieAuthBcrypt_generateHash('APPID', 'username', 'password', 15);
		$result = cookieAuthBcrypt_credentialsMatchHash('APPID', 'username', 'password', $hash);
		$this->assertTrue($result);
	}
}
?>