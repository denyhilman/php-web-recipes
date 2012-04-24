<?php // 0.0.3

/*
The reason for getBrowserUrl is that $_SERVER['REQUEST_URI'] only works on Apache whereas $_SERVER['HTTP_X_REWRITE_URL'] works on some IIS servers.
*/

function getBrowserUrl() {
	$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

	if (isset($_SERVER['REQUEST_URI'])) {
		$pathAndQueryString = $_SERVER['REQUEST_URI'];
	} elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
		$pathAndQueryString = $_SERVER['HTTP_X_REWRITE_URL'];
	}

	return "{$protocol}://{$_SERVER['SERVER_NAME']}{$pathAndQueryString}";
}
?>