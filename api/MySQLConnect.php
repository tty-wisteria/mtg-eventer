<?php

function db_connect() {

	$db_user = "root";
	$db_pass = "n2i0s0h4i";
	$db_host = "localhost";
	$db_name = "mtg";
	$db_type = "mysql";

	$dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";

	try {
		$pdo = new PDO($dsn, $db_user, $db_pass);
		$pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	} catch (PDOException $Exception) {
		$_SESSION['utaudb_login']['error'] = 'データベース接続エラー：管理者にご連絡ください。';
		header('Location: error.php');
		//print "Accesse Falsed";
	}

	return $pdo;
}

?>