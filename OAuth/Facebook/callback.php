<?php
	session_start();

	require_once('common.php');
	require_once('facebook/autoload.php');

	date_default_timezone_set('Asia/Tokyo');

	$connection = new Facebook\Facebook([
		'app_id' => CONSUMER_KEY,
		'app_secret' => CONSUMER_SECRET,
		'default_graph_version' => 'v8.0',
	]);

	if (isset( $_REQUEST['state'] ) AND $_SESSION['FBRLH_state'] == $_REQUEST['state']) {

	}else{
	    die( 'Error!' );
	}

		$helper = $connection -> getRedirectLoginHelper();
		$accessToken = $helper -> getAccessToken(OAUTH_CALLBACK);
		$connection -> setDefaultAccessToken($accessToken);

		$UserProfile = $connection -> get('/me?fields=id,name');
		$profile = $UserProfile -> getGraphUser();

		$UserPicture = $connection->get('/me/picture?redirect=false&height=200');
        $picture = $UserPicture -> getGraphUser();

		$_SESSION['MTGEVENTER_LOGIN']['STATUS'] = true;
		$_SESSION['MTGEVENTER_LOGIN']['MUST'] = false;
		$_SESSION['MTGEVENTER_LOGIN']['SNS'] = 'Facebook';
		$_SESSION['MTGEVENTER_LOGIN']['ID'] = $profile['id'];
		$_SESSION['MTGEVENTER_LOGIN']['USERNAME'] = $profile['name'];
		$_SESSION['MTGEVENTER_LOGIN']['IMAGE'] = $picture['url'];

		$return_url = $_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'];
		//セッションIDをリジェネレート
		session_regenerate_id();

		//元のページへリダイレクト
		header( 'location: '.$return_url );
?>