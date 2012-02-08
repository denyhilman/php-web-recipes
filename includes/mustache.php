<?php // 0.0.5

require_once('mustache.php-0.8.1/Mustache.php');

function mustache_renderString($template, $array = array()) {
	$m = new Mustache;
	return $m->render($template, $array);
}

function mustache_renderFile($path, $array = array()) {
	$template = file_get_contents($path);
	return mustache_renderString($template, $array);
}
?>