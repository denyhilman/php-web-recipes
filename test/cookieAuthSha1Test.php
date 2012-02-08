<?php
require_once(__DIR__ . '/../includes/cookieAuthSha1.php');

class CookieAuthSha1TestCase extends PHPUnit_Framework_TestCase {
	public function testCookieAuthSha1() {
		$hash = cookieAuthSha1_generateHash('APPID', 'username', 'password', 15);
		$result = cookieAuthSha1_credentialsMatchHash('APPID', 'username', 'password', $hash);
		$this->assertTrue($result);
	}
}
?>