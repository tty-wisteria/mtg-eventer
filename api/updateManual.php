<?php

	session_start();
	date_default_timezone_set('Asia/Tokyo');
	require_once('./eventer.php');

	$eventer = new Eventer();

	echo '<pre>';
	$eventer->dater->updateTerm(
		date('c', strtotime('2025-03-02')),
		date('c', strtotime('2025-03-09'))
	);


	//$eventer->updateManual([105]);
	$eventer->updateManual([101,102,103,104,105,106]);
	//$eventer->updateManual([502,503,504,505,506]);
	//$eventer->updateManual([502]);
	//$eventer->updateManual([601,602,603,604,605,606,607,608,609]);
	//$eventer->updateManual([601]);
	//$eventer->updateManual([301,302]);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>MtG Eventer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="shortcut icon" href="../img/sliver.ico" >
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="../css/common.css"/>
		<link rel="stylesheet" href="../css/sp.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="../css/pc.css" media="screen and (min-width:481px)">
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
		<script type="text/javascript" src="../js/script.js"></script>

	</head>
	<style>
		.datepicker{
			width: 256px;
		}

		.datepicker > input{
			display: inline-block;
			width: 100%;
			line-height: 24px;
			padding: 0 8px;
			margin: 0;
			border: none;
			background: transparent;
		}
	</style>

	<script>
		$('#date-start').datepicker({
			dateFormat: 'yy/mm/dd'
		});

		$('#date-end').datepicker({
			dateFormat: 'yy/mm/dd'
		});
	</script>

	<body>

		<dl>
			<dt>更新期間</dt>
			<dd>
				<span class="datepicker">
					<input type="text" id="date-start" name="date" value="" readonly>
					<span class="material-icons-round" id="date-clear">close</span>
					<span class="material-icons-round">calendar_today</span>
				</span>
				<span class="datepicker">
					<input type="text" id="date-end" name="date" value="" readonly>
					<span class="material-icons-round" id="date-clear">close</span>
					<span class="material-icons-round">calendar_today</span>
				</span>
				
			</dd>

			<dt>対象店舗</dt>
			<dd>
				<select name="" id="shop-list" multiple size="20">
					<optgroup label="晴れる屋">
						<option value="101">晴れる屋TC東京高田馬場</option>
						<option value="102">晴れる屋秋葉原</option>
						<option value="103">晴れる屋横浜</option>
						<option value="104">晴れる屋成田</option>
						<option value="105">晴れる屋大宮</option>
						<option value="106">晴れる屋吉祥寺</option>
					</optgroup>
					<optgroup label="バトコロ">
						<option value="201">バトロコ高田馬場</option>
					</optgroup>
					<optgroup label="BIG MAGIC">
						<option value="301">BIG MAGIC秋葉原</option>
						<option value="302">BIG MAGIC池袋</option>
					</optgroup>
					<optgroup label="東京MTG">
						<option value="401">東京MTG水道橋</option>
					</optgroup>
					<optgroup label="アメドリ">
						<option value="502">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ池袋</option>
						<option value="504">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ中野</option>
						<option value="503">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ新宿</option>
						<option value="501">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ秋葉原</option>
						<option value="506">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ大宮</option>
						<option value="505">ｱﾒﾆﾃｨﾄﾞﾘｰﾑ横浜</option>
					</optgroup>
					<optgroup label="Yellow Submarine">
						<option value="601">Yellow Submarine秋葉原</option>
						<option value="602">Yellow Submarine町田</option>
						<option value="603">Yellow Submarine池袋</option>
						<option value="604">Yellow Submarine立川</option>
						<option value="605">秋葉原本店★ミント秋葉原</option>
						<option value="606">Yellow Submarine横浜</option>
						<option value="607">Yellow Submarine横浜</option>
						<option value="608">Yellow Submarine川越</option>
						<option value="609">Yellow Submarine大宮</option>
					</optgroup>

				</select>
				
			</dd>
		</dl>
		
		
	</body>
</html>
