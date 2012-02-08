<?php // 0.0.1
if (get_magic_quotes_gpc()) {
	foreach($_COOKIE as $key => $value) {
		$_COOKIE[$key] = stripslashes($value);
	}

	foreach($_GET as $key => $value) {
		$_GET[$key] = stripslashes($value);
	}

	foreach($_POST as $key => $value) {
		$_POST[$key] = stripslashes($value);
	}
}
?>