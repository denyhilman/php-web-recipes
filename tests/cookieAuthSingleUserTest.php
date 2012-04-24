<?php
require_once(__DIR__ . '/../lib/cookieAuthSingleUser.php');

class CookieAuthSingleUserTestCase extends PHPUnit_Framework_TestCase {
	public function testIsLoggedIn() {
		$_COOKIE['loginCookie'] = 'SomeHashHere';
		$result = cookieAuthSingleUser_isLoggedIn('loginCookie', 'SomeHashHere');
		$this->assertTrue($result);
	}

	public function testIsNotLoggedInIncorrectCookie() {
		$_COOKIE['loginCookie'] = 'IncorrectHash';
		$result = cookieAuthSingleUser_isLoggedIn('loginCookie', 'SomeHashHere');
		$this->assertFalse($result);
	}

	public function testIsNotLoggedInNoCookie() {
		$result = cookieAuthSingleUser_isLoggedIn('loginCookie', 'SomeHashHere');
		$this->assertFalse($result);
	}

	// Note: other functions can't be tested because they rely on the presence of a web browser
}
?>