<?php // 0.0.3
/*
To create hash:
	$hash = bcrypt_hash($input, 15);
	// 15 refers to the number of rounds (higher is better but slower)

To verify hash:
	$isGood = bcrypt_verify($input, $hash);

Source: http://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php
*/

function bcrypt_getSalt($rounds) {
	$salt = sprintf('$2a$%02d$', $rounds);
	$bytes = bcrypt_getRandomBytes(16);
	$salt .= bcrypt_encodeBytes($bytes);
	return $salt;
}

function bcrypt_getRandomBytes($count) {
	$randomState = null;
	$bytes = '';

	// OpenSSL slow on Win
	if(function_exists('openssl_random_pseudo_bytes') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) {
		$bytes = openssl_random_pseudo_bytes($count);
	}

	if($bytes === '' && is_readable('/dev/urandom') && ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
		$bytes = fread($hRand, $count);
		fclose($hRand);
	}

	if(strlen($bytes) < $count) {
		$bytes = '';

		if($randomState === null) {
			$randomState = microtime();
			if(function_exists('getmypid')) {
				$randomState .= getmypid();
			}
		}

		for($i = 0; $i < $count; $i += 16) {
			$randomState = md5(microtime() . $randomState);

			if (PHP_VERSION >= '5') {
				$bytes .= md5($randomState, true);
			} else {
				$bytes .= pack('H*', md5($randomState));
			}
		}

		$bytes = substr($bytes, 0, $count);
	}

	return $bytes;
}

function bcrypt_encodeBytes($input) {
	// The following is code from the PHP Password Hashing Framework
	$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	$output = '';
	$i = 0;
	do {
		$c1 = ord($input[$i++]);
		$output .= $itoa64[$c1 >> 2];
		$c1 = ($c1 & 0x03) << 4;
		if ($i >= 16) {
			$output .= $itoa64[$c1];
			break;
		}

		$c2 = ord($input[$i++]);
		$c1 |= $c2 >> 4;
		$output .= $itoa64[$c1];
		$c1 = ($c2 & 0x0f) << 2;

		$c2 = ord($input[$i++]);
		$c1 |= $c2 >> 6;
		$output .= $itoa64[$c1];
		$output .= $itoa64[$c2 & 0x3f];
	} while (1);

	return $output;
}

function bcrypt_hash($input, $rounds = 15) {
	$hash = crypt($input, bcrypt_getSalt($rounds));

	return strlen($hash) > 13 ? $hash : false;
}

function bcrypt_verify($input, $existingHash) {
	$hash = crypt($input, $existingHash);

	return $hash === $existingHash;
}
?>