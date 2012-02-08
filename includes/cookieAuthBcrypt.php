<?php // 0.0.3

require_once('bcrypt.php');

// Look at cookieAuthSingleUser.php for documentation

function cookieAuthBcrypt_credentialsMatchHash($salt, $username, $password, $existingHash) {
	return bcrypt_verify("$salt|$username|$password", $existingHash);
}

function cookieAuthBcrypt_generateHash($salt, $username, $password, $rounds = 15) {
	return bcrypt_hash("$salt|$username|$password", $rounds);
}
?>