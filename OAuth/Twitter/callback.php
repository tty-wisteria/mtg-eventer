<?php
	session_start();

	require_once('common.php');
	require_once('twitter/autoload.php');

	use Abraham\TwitterOAuth\TwitterOAuth;

	if (isset( $_REQUEST['oauth_token'] ) AND $_SESSION['oauth_token'] == $_REQUEST['oauth_token']) {
		$connection = new TwitterOAuth(
			CONSUMER_KEY,
			CONSUMER_SECRET,
			$_SESSION['oauth_token'],
			$_SESSION['oauth_token_secret']
		);

		$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

		$user_connection = new TwitterOAuth(
			CONSUMER_KEY,
			CONSUMER_SECRET,
			$access_token['oauth_token'],
			$access_token['oauth_token_secret']
		);

		$user = $user_connection -> get('account/verify_credentials');

		if( isset( $user -> errors[0] -> code ) ){
			$_SESSION['MTGEVENTER_LOGIN']['ERROR'] = 'TwitterAPI制限：時間を置いてから接続してください。';
			header( 'location: error.php');
			exit;
		}

		$_SESSION['MTGEVENTER_LOGIN']['STATUS'] = true;
		$_SESSION['MTGEVENTER_LOGIN']['SNS'] = 'Twitter';
		$_SESSION['MTGEVENTER_LOGIN']['ID'] = $user -> id;
		$_SESSION['MTGEVENTER_LOGIN']['USERNAME'] = $user -> screen_name;
		$_SESSION['MTGEVENTER_LOGIN']['IMAGE'] = str_replace('_normal', '', $user -> profile_image_url ) ;

		$return_url = $_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'];
		//セッションIDをリジェネレート
		session_regenerate_id();

		//元のページへリダイレクト
		header( 'location: '.$return_url );

	}else{
	    die( 'Error!' );
	}

?>