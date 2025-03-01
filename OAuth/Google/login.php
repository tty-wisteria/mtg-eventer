<?php
	session_start();
	require_once('common.php');
	$_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'] = $_SERVER['HTTP_REFERER'];

	$AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
	$params = array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => OAUTH_CALLBACK,
		'scope' => 'profile',
		'response_type' => 'code',
		'access_type' => 'offline'
	);

	header("Location: " . $AUTH_URL. '?' . http_build_query($params));

?>