<?php
	session_start();
	require_once('../api/userSession.php');

	$user = new UserSession();

	$queries = getQuery();

	$target = 0 + $_POST['page'];
	$user->turnAPage($target);

	$events = require_once('../getEvent.php');

	echo json_encode($events, JSON_UNESCAPED_UNICODE);

	function getQuery(){
		$posted = parse_url($_POST['url']);
		$q = [];

		if( isset($posted['query']) ){
			parse_str($posted['query'], $q);
		}

		return $q;
	}

?>
