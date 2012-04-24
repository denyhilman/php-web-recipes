<?php // 0.0.6

require_once('mustache.php-1.1.0/Mustache.php');

function mustache_renderString($template, $array = array()) {
	$m = new Mustache;
	return $m->render($template, $array);
}

function mustache_renderFile($path, $array = array()) {
	$template = file_get_contents($path);
	return mustache_renderString($template, $array);
}
?>