<?php
	session_start();

	require_once('common.php');
	require_once('twitter/autoload.php');

	use Abraham\TwitterOAuth\TwitterOAuth;

	//ログインページに遷移した元のURLを取得
	$_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'] = $_SERVER['HTTP_REFERER'];

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

	//コールバックURL
	$request_token = $connection -> oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

	header( 'location: '. $url );

?>
