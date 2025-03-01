<?php
	session_start();
	require_once('common.php');

	$TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
	$INFO_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';

	try {
		$params = array(
			'code' => $_GET['code'],
			'grant_type' => 'authorization_code',
			'redirect_uri' => OAUTH_CALLBACK,
			'client_id' => CLIENT_ID,
			'client_secret' => CLIENT_SECRET,
		);

		$options = array('http' => array(
			'method' => 'POST',
			'content' => http_build_query($params),
			'header' => implode("\r\n", ['Content-Type: application/x-www-form-urlencoded']),
		));

		$res = file_get_contents($TOKEN_URL, false, stream_context_create($options));
		$token = json_decode($res, true);

		if (isset($token['error'])) {
			echo 'エラー発生';
			exit;
		}

		$access_token = $token['access_token'];
		$params = array('access_token' => $access_token);
		$res = file_get_contents($INFO_URL . '?' . http_build_query($params));
		$user = json_decode($res, true);
		
		$_SESSION['MTGEVENTER_LOGIN']['STATUS'] = true;
		$_SESSION['MTGEVENTER_LOGIN']['MUST'] = false;
		$_SESSION['MTGEVENTER_LOGIN']['SNS'] = 'Google';
		$_SESSION['MTGEVENTER_LOGIN']['ID'] = $user['id'];
		$_SESSION['MTGEVENTER_LOGIN']['USERNAME'] = $user['name'];
		$_SESSION['MTGEVENTER_LOGIN']['IMAGE'] = $user['picture'];

		$return_url = $_SESSION['MTGEVENTER_LOGIN']['RETURN_URL'];
		//セッションIDをリジェネレート
		session_regenerate_id();

		//元のページへリダイレクト
		header( 'location: '.$return_url );

	} catch (\Throwable $th) {
		//throw $th;
		echo $th;
	}


?>