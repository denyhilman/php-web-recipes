<?php // 0.0.1
if (!function_exists('json_encode')) {
	function json_encode($value = false) {
		if (is_null($value)) return 'null';
		if ($value === false) return 'false';
		if ($value === true) return 'true';
		if (is_scalar($value)) {
			if (is_float($value)) {
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($value)));
			}

			if (is_string($value)) {
				static $jsonReplaces = array(
					array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
					array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
				);
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $value) . '"';
			} else {
				return $value;
			}
		}
		$isList = true;
		for ($i = 0, reset($value); $i < count($value); $i++, next($value)) {
			if (key($value) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();

		if ($isList) {
			foreach ($value as $v) {
				$result[] = json_encode($v);
			}
			return '[' . join(',', $result) . ']';
		} else {
			foreach ($value as $k => $v) {
				$result[] = json_encode($k).':'.json_encode($v);
			}
			return '{' . join(',', $result) . '}';
		}
	}
}
// Source: http://www.stetsenko.net/2009/09/php-json_encode-before-5-2-0/
?>