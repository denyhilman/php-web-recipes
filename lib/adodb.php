<?php // 0.0.3

require_once('adodb-5.1.4/adodb.inc.php');

/*
To get a unique ID (checks table to ensure that it hasn't been used):
	adodb_getUniqueId($db, 'tableName', 'guidField');
	// 'guidField' must be a CHAR(23) UNIQUE field

To get a host-specific unique ID, use a prefix (useful for generating unique IDs across multiple hosts):
	adodb_getUniqueId($db, 'tableName', 'guidField', 'hostPrefix');
*/

function adodb_connect($server, $username, $password, $database) {
	$db = NewADOConnection('mysql');
	$db->connect($server, $username, $password, $database);
	return $db;
}

function adodb_getUniqueId($db, $table, $idField, $prefix = '') {
	$row = null;
	while ($row !== array()) {
		$uid = uniqid($prefix, true);
		$row = $db->GetRow("SELECT $idField FROM $table WHERE $idField = ?", $uid);
	}
	return $uid;
}
?>