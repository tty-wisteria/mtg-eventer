<?php
	session_start();
	require_once('common.php');

	$TOKEN_URL = 'https://api.line.me/oauth2/v2.1/token';
	$INFO_URL = 'https://api.line.me/v2/profile';

	try {
		$params = array(
			'code' => $_GET['code'],
			'grant_type' => 'authorization_code',
			'client_id' => CLIENT_ID,
			'client_secret' => CLIENT_SECRET,
			'redirect_uri' => OAUTH_CALLBACK
		);

		$options = array(
			'http' => array(
				'method' => 'POST',
				'content' => http_build_query($params),
				'header' => ['Content-Type: application/x-www-form-urlencoded'],
			)
		);

		$res = file_get_contents($TOKEN_URL, false, stream_context_create($options));
		$token = json_decode($res, true);

		if (isset($token['error'])) {
			echo 'エラー発生';
			exit;
		}

		$access_token = $token['access_token'];
		$id_token = $token['id_token'];

		$options = array(
			'http' => array(
				'method' => 'GET',
				'header' => ['Authorization: Bearer ' . $access_token ],
			)
		);

		$res = file_get_contents($INFO_URL, false, stream_context_create($options));
		$user = json_decode($res, true);
		
		$_SESSION['MTGEVENTER_LOGIN']['STATUS'] = true;
		$_SESSION['MTGEVENTER_LOGIN']['MUST'] = false;
		$_SESSION['MTGEVENTER_LOGIN']['SNS'] = 'LINE';
		$_SESSION['MTGEVENTER_LOGIN']['ID'] = $user['userId'];
		$_SESSION['MTGEVENTER_LOGIN']['USERNAME'] = $user['displayName'];
		$_SESSION['MTGEVENTER_LOGIN']['IMAGE'] = $user['pictureUrl'];

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