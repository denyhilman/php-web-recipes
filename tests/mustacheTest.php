<?php
// Find and replace SRCFILE with your actual source file name
require_once(__DIR__ . '/../lib/mustache.php');

class MustacheTestCase extends PHPUnit_Framework_TestCase {

	// Surrounding with dual-braces escapes any HTML entities (i.e. {{name}})
	public function testEscapedHtml() {
		$result = mustache_renderString('<p>{{findreplace}}</p>{{findreplace}}', array(
			'findreplace' => '3 > 2'
		));
		$this->assertEquals($result, '<p>3 &gt; 2</p>3 &gt; 2');
	}

	// Surrounding with three braces renders without escaping any HTML entities (i.e. {{{name}}})
	// Also works with prefixing dual braces with an ampersand sign (i.e. &)
	public function testUnescapedHtml() {
		$result = mustache_renderString('<h1>{{{hello}}}</h1><p>{{& hello}}</p>', array(
			'hello' => '<strong>Hello!</strong>'
		));
		$this->assertEquals($result, '<h1><strong>Hello!</strong></h1><p><strong>Hello!</strong></p>');
	}

	public function testDisplayIfTrue() {
		$result = mustache_renderString('<p>{{#display}}Hello!{{/display}}</p>', array(
			'display' => true
		));
		$this->assertEquals($result, '<p>Hello!</p>');
	}

	public function testHideIfFalse() {
		$result = mustache_renderString('<p>{{#display}}Hello!{{/display}}</p>', array(
			'display' => false
		));
		$this->assertEquals($result, '<p></p>');
	}

	public function testDisplayIfFalse() {
		$result = mustache_renderString('<p>{{^display}}Goodbye!{{/display}}</p>', array(
			'display' => false
		));
		$this->assertEquals($result, '<p>Goodbye!</p>');
	}

	public function testComments() {
		$result = mustache_renderString('<p>{{! ignore me}}</p>', array());
		$this->assertEquals($result, '<p></p>');
	}

	public function testDisplayList() {
		$result = mustache_renderString('<div class="section">{{#aList}}<p>{{name}} - {{age}}</p>{{/aList}}</div>', array(
			'aList' => array(
				array('name' => 'Chris', 'age' => 12),
				array('name' => 'Joey', 'age' => 32),
				array('name' => 'Harry', 'age' => 46)
			)
		));
		$this->assertEquals($result, '<div class="section"><p>Chris - 12</p><p>Joey - 32</p><p>Harry - 46</p></div>');
	}
}
?>