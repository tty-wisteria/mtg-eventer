<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	require_once('./eventer.php');

	$eventer = new Eventer();

	echo '<pre>';

	//毎月第3木曜日に、翌月の情報を取得
	if( $eventer->dater->isThe3rdThu() ){
		$eventer->dater->setNextMonthTerm();
		$eventer->insertEvents([]);
	}

	//毎週更新
	$eventer->dater->updateTerm();
	$eventer->updateEvents([]);

	echo '</pre>';

?>
