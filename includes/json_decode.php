<?php // 0.0.1
if (!function_exists('json_decode')) {
	function json_decode($json) {
		$comment = false;
		$out = '$ret = ';

		for ($i=0; $i < strlen($json); $i++) {
			$char = $json[$i];
			if (!$comment) {
				if ($char === '{' || $char === '[') {
					$out .= ' array(';
				} else if ($char === '}' || $char === ']') {
					$out .= ')';
				} else if ($char === ':') {
					$out .= '=>';
				} else {
					$out .= $char;
				}
			} else {
				$out .= $char;
			}
			if ($char == '"' && $json[($i-1)] !== "\\") {
				$comment = !$comment;
			}
		}
		eval($out . ';');
		return $ret;
	}
}
?>