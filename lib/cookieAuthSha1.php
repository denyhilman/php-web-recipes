<?php // 0.0.2

// Look at cookieAuthSingleUser.php for documentation

function cookieAuthSha1_credentialsMatchHash($salt, $username, $password, $existingHash) {
	return cookieAuthSha1_generateHash($salt, $username, $password) === $existingHash;
}

function cookieAuthSha1_generateHash($salt, $username, $password) {
	return sha1("$salt|$username|$password");
}
?>