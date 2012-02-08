<?php // 0.0.1

function testException($testCase, $expectedMessage, $codeBlock) {
	try {
		$codeBlock();
		$exceptionThrown = FALSE;
	} catch (Exception $e) {
		$testCase->assertEquals($expectedMessage, $e->getMessage());
		$exceptionThrown = TRUE;
	}
	if (!$exceptionThrown) {
		$trace = debug_backtrace();
		$function = $trace[1]['function'];
		throw new Exception("testException failed: Exception should have been raised in $function.");
	}
}
?>