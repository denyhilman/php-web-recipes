<?php
require_once(__DIR__ . '/../lib/testException.php');

class TestExceptionTestCase extends PHPUnit_Framework_TestCase {
	public function testNonExceptionalCodeBlock() {
		// the below code can be confusing to understand because an exception should be thrown in the testException code if the code block is error-free.
		try {
			testException($this, '', function() {
				echo ''; // noop
			});
			$thrownException = FALSE;
		} catch(Exception $e) {
			$this->assertEquals('testException failed: Exception should have been raised in testNonExceptionalCodeBlock.', $e->getMessage());
			$thrownException = TRUE;
		}
		if (!$thrownException) {
			throw new Exception("Non-exceptional code block should have errored out in testException.");
		}
	}

	public function testExceptionalCodeBlockWithCorrectMessage() {
		try {
			testException($this, 'Random Error!', function() {
				throw new Exception('Random Error!');
			});
		} catch(Exception $e) {
			throw new Exception("Error messages should've matched.");
		}
	}

	public function testExceptionalCodeBlockWithIncorrectMessage() {
		try {
			testException($this, 'Non-matching Error!', function() {
				throw new Exception('Random Error!');
			});
			$exceptionThrown = FALSE;
		} catch(Exception $e) {
			$exceptionThrown = TRUE;
		}
		if (!$exceptionThrown) {
			throw new Exception("Error messages shouldn't have matched.");
		}
	}
}
?>