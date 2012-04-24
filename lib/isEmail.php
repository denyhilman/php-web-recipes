<?php // 0.0.2

require_once('is_email-3.01/is_email.php');

function isEmail($email, $checkDNS = false, $errorlevel = false, &$parsedata = array()) {
	return is_email($email, $checkDNS, $errorlevel, $parsedata);
}
?>