<?php
require_once(__DIR__ . '/../includes/getBrowserUrl.php');

class GetBrowserUrlTestCase extends PHPUnit_Framework_TestCase {
	public function testHttps() {
		$_SERVER['HTTPS'] = 'on';
		$_SERVER['SERVER_NAME'] = 'somedomain.com';
		$_SERVER['REQUEST_URI'] = '/folder1/folder2/document.html?param1=value1';

		$result = getBrowserUrl();
		$this->assertEquals($result, 'https://somedomain.com/folder1/folder2/document.html?param1=value1');
	}

	public function testHttp() {
		$_SERVER['SERVER_NAME'] = 'domain.com';
		$_SERVER['REQUEST_URI'] = '/folder1';

		$result = getBrowserUrl();
		$this->assertEquals($result, 'http://domain.com/folder1');
	}

	public function testIis() {
		$_SERVER['SERVER_NAME'] = 'something.com';
		$_SERVER['HTTP_X_REWRITE_URL'] = '/iisFolder';

		$result = getBrowserUrl();
		$this->assertEquals($result, 'http://something.com/iisFolder');
	}
}
?>