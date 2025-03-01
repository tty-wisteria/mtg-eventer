<?php
	session_start();
	require_once('common.php');
	$_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'] = $_SERVER['HTTP_REFERER'];

	$AUTH_URL = 'https://access.line.me/oauth2/v2.1/authorize';
	$params = array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => OAUTH_CALLBACK,
		'scope' => 'profile openid',
		'response_type' => 'code',
		'state' => OAUTH_STATE
	);

	header("Location: " . $AUTH_URL. '?' . http_build_query($params));

?>