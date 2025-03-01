<?php
	session_start();

	require_once('common.php');
	require_once('facebook/autoload.php');

	//ログインページに遷移した元のURLを取得
	$_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'] = $_SERVER['HTTP_REFERER'];

	$connection = new Facebook\Facebook([
		'app_id' => CONSUMER_KEY,
		'app_secret' => CONSUMER_SECRET,
		'default_graph_version' => 'v8.0',
	]);

	$helper = $connection -> getRedirectLoginHelper();
	$access_token = $helper -> getAccessToken(OAUTH_CALLBACK);

	$permissions = ['public_profile'];
	$url = $helper -> getLoginUrl(OAUTH_CALLBACK, $permissions);

	header( 'location: '. $url );

/*
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
*/

?>
