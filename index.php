<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	$now = strtotime(date('Y-m-d H:i:s'));

	require_once('api/userSession.php');

	$user = new UserSession();
	$user->access();

	//addEventでクエリを組み立てる必要があるため
	$queries = $_GET;

	$future_events = require('./getEvent.php');
	$future_events_count = count($future_events);

	$user->checkPageChange();
	$past_events = require('./getEvent.php');

	$shop_list = require('./getShop.php');

	function loginCheck(){
		global $user;
		if( $user->hasLoggedIn() ){
			return '';
		}
		return 'disabled';
	}

	function displayCheck($val){
		global $user;
		if( $user->display() == $val ){
			return 'checked';
		}
		return '';
	}

	function freewordCheck(){
		if( isset($_GET['freeword']) ){
			return $_GET['freeword'];
		}

		return '';
	}

	function formatCheck($val){
		if( isset($_GET['format']) ){
			if( in_array( $val, $_GET['format']) ){
				return 'checked';
			}
		}

		return '';
	}

	function areaCheck($val){
		if( isset($_GET['area']) ){
			if( $_GET['area'] == $val ){
				return 'selected';
			};
		}

		return '';
	}

	function shopCheck($val){
		if( isset($_GET['shop']) ){
			if( in_array( $val, $_GET['shop']) ){
				return 'checked';
			}
		}

		return '';

	}

	function dateCheck(){
		if( isset($_GET['date']) ){
			return $_GET['date'];
		}

		return '';

	}

	function holidayCheck(){
		if( isset($_GET['holiday']) ){
			if( $_GET['holiday'] == 'on' ){
				return 'checked';
			};
		}

		return '';
	}

	function favCheck(){
		if( isset($_GET['fav']) ){
			if( $_GET['fav'] == 'on' ){
				return 'checked';
			};
		}

		return '';
	}

	function freewordInputted(){
		if( isset($_GET['freeword']) ){
			if( $_GET['freeword'] != '' ){
				return 'inputted';
			}
		}
		return '';
	}

	function areaSelected(){
		if( isset($_GET['area']) ){
			if( $_GET['area'] != '' ){
				return 'selected';
			}
		}
		return '';
	}

	function dateInputted(){
		if( isset($_GET['date']) ){
			if( $_GET['date'] != '' ){
				return 'inputted';
			}
		}
		return '';
	}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>MtG Eventer</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<link rel="shortcut icon" href="img/sliver.ico" >
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="css/common.css"/>
		<link rel="stylesheet" href="css/sp.css" media="screen and (max-width:480px)">
		<link rel="stylesheet" href="css/pc.css" media="screen and (min-width:481px)">
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
		<?="<script type=\"text/javascript\">var shop = {$shop_list}</script>"?>
		<script type="text/javascript" src="js/script.js"></script>
	</head>

	<body>
		<div id="root">
	
	<header>
		<a href=""><span id="header-name">MTG Eventer</span></a>
		<p id="about"><a href="about.php">サイトについて</a></p>
<?php if( $user->hasLoggedIn() ){ ?>
		<div id="account">
			<p id="icon">
				<img src="<?=htmlspecialchars($user->thumbnail())?>" alt="">
			</p>
		</div>
<?php }else{ ?>
		<div id="login">
			<p id="icon">
				<span class="material-icons-round">person</span>
			</p>
		</div>
<?php } ?>
	</header>

	<section id="filter-section">
		<form action="" method="GET">
			<h2 id="filter-caption">イベント検索</h2>
			<div id="filter" class="">
					<dl id="filter-list">
						<dt>フリーワード</dt>
						<dd>
							<input type="text" id="freeword" name="freeword" value="<?=htmlspecialchars(freewordCheck())?>" class="<?=freewordInputted()?>">
						</dd>
						<dt>構築</dt>
						<dd>
							<p class="format-checkbox">
								<label><input type="checkbox" name="format[]" value="1" <?=formatCheck(1)?>><span>St</span></label>
								<label><input type="checkbox" name="format[]" value="2" <?=formatCheck(2)?>><span>Pi</span></label>
								<label><input type="checkbox" name="format[]" value="3" <?=formatCheck(3)?>><span>Md</span></label>
								<label><input type="checkbox" name="format[]" value="4" <?=formatCheck(4)?>><span>Le</span></label>
								<label><input type="checkbox" name="format[]" value="5" <?=formatCheck(5)?>><span>Vi</span></label>
								<label><input type="checkbox" name="format[]" value="6" <?=formatCheck(6)?>><span>Pa</span></label>
								<label><input type="checkbox" name="format[]" value="13" <?=formatCheck(13)?>><span>Co</span></label>
							</p>
						</dd>
						<dt>リミテッド</dt>
						<dd>
							<p class="format-checkbox">
								<label><input type="checkbox" name="format[]" value="11" <?=formatCheck(11)?>><span>Dr</span></label>
								<label><input type="checkbox" name="format[]" value="12" <?=formatCheck(12)?>><span>Se</span></label>
							</p>
						</dd>
						<dt>エリア</dt>
						<dd>
							<p id="area-wrapper">
								<select name="area" id="area-list" class="<?=areaSelected()?>">
									<option value="">地域を選択</option>
									<optgroup label="東京">
										<option value="101"<?=areaCheck(101)?>>秋葉原</option>
										<option value="102"<?=areaCheck(102)?>>高田馬場</option>
										<option value="103"<?=areaCheck(103)?>>池袋</option>
										<option value="105"<?=areaCheck(105)?>>水道橋</option>
										<option value="106"<?=areaCheck(106)?>>立川</option>
										<option value="110"<?=areaCheck(110)?>>吉祥寺</option>
										<option value="109"<?=areaCheck(109)?>>成田</option>
									</optgroup>
									<optgroup label="神奈川">
										<option value="201"<?=areaCheck(201)?>>横浜</option>
									</optgroup>
									<optgroup label="埼玉">
										<option value="301"<?=areaCheck(301)?>>大宮</option>
									</optgroup>
								</select>
							</p>
						</dd>
						<dt>店舗</dt>
						<dd><span id="select_shop">選択</span></dd>
						<dt>日付</dt>
						<dd>
							<span id="datepicker" class="<?=dateInputted()?>"><input type="text" id="targetdate" name="date" value="<?=dateCheck()?>" readonly><span class="material-icons-round" id="date-clear">close</span><span class="material-icons-round">calendar_today</span></span>
						</dd>
						<dt>土日祝</dt>
						<dd>
							<label class="search-toggle">
								<input type="checkbox" class="search-toggle-flag" name="holiday" <?=holidayCheck()?>>
								<span class="toggle-on">ON</span>
								<span class="toggle-off">OFF</span>
								<span class="toggle-button"></span>
							</label>
						</dd>
						<dt>お気に入り</dt>
						<dd>
							<label class="search-toggle">
								<input type="checkbox" class="search-toggle-flag" name="fav" <?=loginCheck()?> <?=favCheck()?>>
								<span class="toggle-on">ON</span>
								<span class="toggle-off">OFF</span>
								<span class="toggle-button"></span>
							</label>
						</dd>
					</dl>
					<p id="submit">
						<input type="submit" value="検索">
					</p>
			</div>

			<div id="popup_shop" class="">
				<div id="popup_shop_frame">
					<div class="popup-header">
						店舗
						<span id="popup_shop_close" class="popup-close"></span>
					</div>

					<div id="popup_shop_list">
						<ul>
							<li>
								<p><label><input type="checkbox" class="shop-p"><span>晴れる屋</span></label></p>
								<ul>
									<li><label><input type="checkbox" name="shop[]" value="101"<?=shopCheck(101)?>><span>TC東京</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="102"<?=shopCheck(102)?>><span>秋葉原</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="103"<?=shopCheck(103)?>><span>横浜</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="104"<?=shopCheck(104)?>><span>成田</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="105"<?=shopCheck(105)?>><span>大宮</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="106"<?=shopCheck(106)?>><span>吉祥寺</span></label></li>
								</ul>
							</li>

							<li>
								<p><label><input type="checkbox" class="shop-p"><span>BIG MAGIC</span></label></p>
								<ul>
									<li><label><input type="checkbox" name="shop[]" value="301"<?=shopCheck(301)?>><span>秋葉原</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="302"<?=shopCheck(302)?>><span>池袋</span></label></li>
								</ul>
							</li>

							<li>
								<p><label><input type="checkbox" class="shop-p"><span>バトロコ</span></label></p>
								<ul>
									<li><label><input type="checkbox" name="shop[]" value="201"<?=shopCheck(201)?>><span>高田馬場</span></label></li>
								</ul>
							</li>

							<li>
								<p><label><input type="checkbox" name="shop[]" value="401"<?=shopCheck(401)?>><span>東京MTG</span></label></p>
							</li>

							<li>
								<p><label><input type="checkbox" class="shop-p"><span>Yellow Submarine</span></label></p>
								<ul>
									<li><label><input type="checkbox" name="shop[]" value="601"<?=shopCheck(601)?>><span>ハイパーアリーナ</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="605"<?=shopCheck(605)?>><span>秋葉原本店★ミント</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="603"<?=shopCheck(603)?>><span>池袋ゲームショップ</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="604"<?=shopCheck(604)?>><span>立川店</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="602"<?=shopCheck(602)?>><span>町田店</span></label></li>
									
								</ul>
							</li>

							<li>
								<p><label><input type="checkbox" class="shop-p"><span>アメニティドリーム</span></label></p>
								<ul>
									<li><label><input type="checkbox" name="shop[]" value="501"<?=shopCheck(501)?>><span>秋葉原</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="503"<?=shopCheck(503)?>><span>新宿</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="502"<?=shopCheck(502)?>><span>池袋</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="506"<?=shopCheck(506)?>><span>大宮</span></label></li>
									<li><label><input type="checkbox" name="shop[]" value="505"<?=shopCheck(505)?>><span>横浜</span></label></li>
								</ul>
							</li>
						</ul>
					</div>

					<div id="popup_shop_button">
						<span id="popup_shop_submit">全選択</span><span id="popup_shop_cancel">全解除</span>
					</div>
				</div>
			</div>

		</form>

	</section>

	<section id="event-section">

		<input type="radio" name="radio-event-category" value="1" id="event-future"<?=$future_events_count > 0?' checked':'';?>>
		<input type="radio" name="radio-event-category" value="0" id="event-past"<?=$future_events_count > 0?'':' checked';?>>

		<ul id="event-time">
			<li id="tab-event-future"><label for="event-future">未来のイベント</label></li>
			<li id="tab-event-past"><label for="event-past">終了したイベント</label></li>
		</ul>

		<p class="ev-header">
			<span class="ev-fav">-</span>
			<span class="ev-format">-</span>
			<span class="ev-day">日付</span>
			<span class="ev-start">開始</span>
			<span class="ev-arena">地域</span>
			<span class="ev-shop">店舗</span>
			<span class="ev-pay">参加費</span>
		</p>

		<dl id="event-list-future" class="tournament-list">
<?php
	foreach( $future_events as $event ){
		$fee = ( $event['fee'] == 9999 )? '別途' : $event['fee'].'円' ;
?>
			<dt>
				<label>
					<input type="checkbox" class="fav-tag" value="<?=htmlspecialchars($event['eid'])?>"<?=$event['fav']?' checked':''?>>
					<span class="material-icons-round fav">star</span>
				</label>
			</dt>
			<dd>
				<a href="<?=htmlspecialchars($event['url'])?>">
					<p class="ev-info">
						<span class="ev-format"><span class="format <?=htmlspecialchars($event['format'])?>"><?=htmlspecialchars($event['format'])?></span></span>
						<span class="ev-day"><?=htmlspecialchars($event['md'])?></span>
						<span class="ev-start"><?=htmlspecialchars($event['start'])?></span>
						<span class="ev-arena"><?=htmlspecialchars($event['area'])?></span>
						<span class="ev-shop"><?=htmlspecialchars($event['shop'])?></span>
						<span class="ev-pay"><?=htmlspecialchars($fee)?></span>
					</p>
					<p class="ev-title"><?=htmlspecialchars($event['title'])?></p>
				</a>
			</dd>
<?php } ?>
		</dl>

		<dl id="event-list-past" class="tournament-list">
<?php
	foreach( $past_events as $event ){
		$fee = ( $event['fee'] == 9999 )? '別途' : $event['fee'].'円' ;
?>
			<dt>
				<label>
					<input type="checkbox" class="fav-tag" value="<?=htmlspecialchars($event['eid'])?>"<?=$event['fav']?' checked':''?>>
					<span class="material-icons-round fav">star</span>
				</label>
			</dt>
			<dd>
				<a href="<?=htmlspecialchars($event['url'])?>">
					<p class="ev-info">
						<span class="ev-format"><span class="format <?=htmlspecialchars($event['format'])?>"><?=htmlspecialchars($event['format'])?></span></span>
						<span class="ev-day"><?=htmlspecialchars($event['md'])?></span>
						<span class="ev-start"><?=htmlspecialchars($event['start'])?></span>
						<span class="ev-arena"><?=htmlspecialchars($event['area'])?></span>
						<span class="ev-shop"><?=htmlspecialchars($event['shop'])?></span>
						<span class="ev-pay"><?=htmlspecialchars($fee)?></span>
					</p>
					<p class="ev-title"><?=htmlspecialchars($event['title'])?></p>
				</a>
			</dd>
<?php } ?>
		</dl>

		<p id="load">
			<span class="material-icons-round" id="waiting">south</span>
			<span class="material-icons-round" id="loading">autorenew</span>
		</p>

	</section>


	<section id="modal-section">

		<div id="login-modal" class="">
			<div id="login-alert">
				<div class="popup-header">
					ログイン
					<div id="login-close" class="popup-close"></div>
				</div>
				<p>
					サービスの利用には、ログインが必要です。<br>
					いずれかの方法でログインしてください。
				</p>
				<ul id="login-list">
					<li class="tw"><a href="OAuth/Twitter/login.php"><img src="img/logo_twitter.png" alt="">Twitterでログイン</a></li>
					<li class="gl"><a href="OAuth/Google/login.php"><img src="img/logo_google.png" alt="">Googleでログイン</a></li>
					<li class="fb"><a href="OAuth/Facebook/login.php"><img src="img/logo_facebook.png" alt="">Facebookでログイン</a></li>
					<li class="ln"><a href="OAuth/LINE/login.php"><img src="img/logo_line.png" alt="">LINEでログイン</a></li>
				</ul>
			</div>
		</div>

		<div id="setting-modal" class="">
			<div id="setting">
				<div id="profile">
					<div id="profile-header">
						ログイン情報
						<div id="profile-close"></div>
					</div>
					<div id="thumbnail">
						<img src="<?=$user->thumbnail()?>" alt="">
					</div>
					<div id="userinfo">
						<p><?=htmlspecialchars($user->name())?></p>
						<p><?=htmlspecialchars($user->sns())?>でログイン中</p>
					</div>

				</div>
				<dl id="setting-list">
					<dt>表示数</dt>
					<dd>
						<p id="display-count">
							<label><input type="radio" name="display" value="20" <?=displayCheck(20)?>><span>20</span></label>
							<label><input type="radio" name="display" value="50"<?=displayCheck(50)?>><span>50</span></label>
							<label><input type="radio" name="display" value="100"<?=displayCheck(100)?>><span>100</span></label>
						</p>
					</dd>
				</dl>
				<p id="setting-commit"><span>設定を反映</span></p>

				<p id="logout"><a href="OAuth/logout.php">ログアウト</a></p>
			</div>
		</div>


	</section>

		</div>
	</body>
</html>