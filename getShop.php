<?php
	require_once('api/MySQLConnect.php');
	$eventer_pdo = db_connect();

	$sql = 'SELECT id, alias FROM shop';
	$stmt = $eventer_pdo -> prepare($sql);
	$stmt -> execute();

	$shops = $stmt-> fetchAll(PDO::FETCH_ASSOC);
	$q = [];

	foreach( $shops as $shop){
		$q[] = "$shop[id]:'$shop[alias]'";
	}

	$s = '{'.implode(',', $q).'}';

	return $s;

?>
