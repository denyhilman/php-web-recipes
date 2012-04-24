<?php
require_once(__DIR__ . '/../lib/crosstab.php');
require_once(__DIR__ . '/../lib/testException.php');

class CrosstabTest extends PHPUnit_Framework_TestCase {

	// Helpers

	public static function getRawTable() {
		return array(
			array('name' => 'Chris', 'subject' => 'english', 'score' => 30),
			array('name' => 'Chris', 'subject' => 'maths', 'score' => 80),
			array('name' => 'Rachel', 'subject' => 'english', 'score' => 80),
			array('name' => 'Randy', 'subject' => 'maths', 'score' => 70),
			array('name' => 'Chris', 'subject' => 'science', 'score' => 70),
			array('name' => 'Rachel', 'subject' => 'english', 'score' => 100),
			array('name' => 'Chris', 'subject' => 'english', 'score' => 40),
			array('name' => 'Randy', 'subject' => 'maths', 'score' => 30),
			array('name' => 'Chris', 'subject' => 'maths', 'score' => 5),
			array('name' => 'Rachel', 'subject' => 'maths', 'score' => 20),
			array('name' => 'Chris', 'subject' => 'maths', 'score' => 0)
		);
	}

	// Tests

	public function testCrosstabInvalidOperation() {
		testException($this, 'Error: Operation argument is not valid.  It must be either average, count, max, min or sum in the crosstab function.', function() {
			$table = CrosstabTest::getRawTable();
			$result = crosstab($table, 'subject', 'name', 'score', 'invalidOperation');
		});
	}

	public function testCrosstabInvalidAggregateKey() {
		testException($this, 'Missing aggregate key: Column score is missing from row 0.', function() {
			$table = array(array('name' => 'Randy', 'subject' => 'maths'));
			$result = crosstab($table, 'subject', 'name', 'score', 'sum');
		});
	}

	public function testCrosstabInvalidColumnKey() {
		testException($this, 'Missing column key: Column subject is missing from row 0.', function() {
			$table = array(array('name' => 'Randy', 'score' => 100));
			$result = crosstab($table, 'subject', 'name', 'score', 'sum');
		});
	}

	public function testCrosstabInvalidRowKey() {
		testException($this, 'Missing row key: Column name is missing from row 0.', function() {
			$table = array(array('score' => 100, 'subject' => 'maths'));
			$result = crosstab($table, 'subject', 'name', 'score', 'sum');
		});
	}

	public function testCrosstabAverage() {
		$table = CrosstabTest::getRawTable();
		$result = crosstab($table, 'subject', 'name', 'score', 'average');

		$this->assertEquals($result, array(
			'Chris' => array('english' => 35, 'maths' => 28 + (1/3), 'science' => 70),
			'Rachel' => array('english' => 90, 'maths' => 20, 'science' => NULL),
			'Randy' => array('english' => NULL, 'maths' => 50, 'science' => NULL)
		));
	}

	public function testCrosstabCount() {
		$table = CrosstabTest::getRawTable();
		$result = crosstab($table, 'subject', 'name', 'score', 'count');
		$this->assertEquals($result, array(
			'Chris' => array('english' => 2, 'maths' => 3, 'science' => 1),
			'Rachel' => array('english' => 2, 'maths' => 1, 'science' => NULL),
			'Randy' => array('english' => NULL, 'maths' => 2, 'science' => NULL)
		));
	}

	public function testCrosstabMax() {
		$table = CrosstabTest::getRawTable();
		$result = crosstab($table, 'subject', 'name', 'score', 'max');
		$this->assertEquals($result, array(
			'Chris' => array('english' => 40, 'maths' => 80, 'science' => 70),
			'Rachel' => array('english' => 100, 'maths' => 20, 'science' => NULL),
			'Randy' => array('english' => NULL, 'maths' => 70, 'science' => NULL)
		));
	}

	public function testCrosstabMin() {
		$table = CrosstabTest::getRawTable();
		$result = crosstab($table, 'subject', 'name', 'score', 'min');
		$this->assertEquals($result, array(
			'Chris' => array('english' => 30, 'maths' => 0, 'science' => 70),
			'Rachel' => array('english' => 80, 'maths' => 20, 'science' => NULL),
			'Randy' => array('english' => NULL, 'maths' => 30, 'science' => NULL)
		));
	}

	public function testCrosstabSum() {
		$table = CrosstabTest::getRawTable();
		$result = crosstab($table, 'subject', 'name', 'score', 'sum');
		$this->assertEquals($result, array(
			'Chris' => array('english' => 70, 'maths' => 85, 'science' => 70),
			'Rachel' => array('english' => 180, 'maths' => 20, 'science' => NULL),
			'Randy' => array('english' => NULL, 'maths' => 100, 'science' => NULL)
		));
	}
}
?>