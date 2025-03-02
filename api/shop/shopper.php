<?php
require '../vendor/autoload.php';
ini_set("max_execution_time",480);

class Shopper{
	protected $shop;
	protected $url;
	protected $since;
	protected $until;
	protected $events = [];
	protected $ENCODING = 'UTF-8';

	protected $optParams = [
		'orderBy' => 'startTime',
		'singleEvents' => true,
		'timeMin' => '',
		'timeMax' => ''
	];

	public function __construct($shop){
		$this->shop = $shop;
		$this->url = $this->shop['cid'];
	}

	public function setTerm($s, $u){
		$this->since = $s;
		$this->until = $u;

		$this->optParams['timeMin'] = $s;
		$this->optParams['timeMax'] = $u;

	}

	public function scrape(){
		//継承先で記載
		return $events;
	}

	protected function callSoloCURLProcess(){
		echo 'do curl<br>';
		echo $this->url.'<br>';
		$ch = curl_init();
		$TIMEOUT = 480;

		curl_setopt_array($ch, array(
			CURLOPT_URL				=> $this->url,
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_TIMEOUT			=> $TIMEOUT,
			CURLOPT_CONNECTTIMEOUT	=> $TIMEOUT
		));

		$html = curl_exec($ch);

		$dom = new DOMDocument;
		@$dom->loadHTML( mb_convert_encoding($html, 'HTML-ENTITIES', $this->ENCODING) );
		$page = new DOMXPath($dom);

		unset($dom);
		curl_close($ch);

		return $page;
	}

	protected function callMultiCURLProcess($urls, $method){
		$result = [];
		$TIMEOUT = 480;
		$mh = curl_multi_init();
		$ch_array = [];

		foreach ($urls as $url) {
			$ch = curl_init();
			$ch_array[] = $ch;
			curl_setopt_array($ch, array(
				CURLOPT_URL				=> $url,
				CURLOPT_RETURNTRANSFER	=> true,
				CURLOPT_TIMEOUT			=> $TIMEOUT,
				CURLOPT_CONNECTTIMEOUT	=> $TIMEOUT
			));
			curl_multi_add_handle($mh, $ch);
		}

		do {
			curl_multi_exec($mh, $running);
			curl_multi_select($mh);
		} while ($running > 0);

		//HTML取得
		foreach ($ch_array as $ch) {
			var_dump( curl_getinfo($ch));
			
			$content = mb_convert_encoding(
				curl_multi_getcontent($ch),
				'HTML-ENTITIES',
				$this->ENCODING
			);

			$dom = new DOMDocument;
			@$dom->loadHTML($content);
			$page = new DOMXPath($dom);
			unset($dom);

			$res = $method( curl_getinfo($ch)['url'], $page);

			foreach($res as $val){
				$result[] = $val;
			}

			curl_multi_remove_handle($mh, $ch);
			curl_close($ch);
		}

		curl_multi_close($mh);	//マルチハンドルの後始末

		return $result;
	}

	protected function getGoogleCalendar($url){
		global $optParams;
		$client = new Google_Client();
		$client->setApplicationName('Google Calendar API PHP Quickstart');
		$client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
		$client->setAuthConfig('mtg-eventer-a90c6f0ec980.json');

		$service = new Google_Service_Calendar($client);
		$results = $service->events->listEvents($this->url, $this->optParams);
		$items = $results->getItems();

		unset($client,$service,$results);

		return $items;
	}

	protected function dateFixer($str){
		$m = date('m');
		$y = date('Y');
		preg_match('/\d{1,2}\/(\d{1,2})/', $str, $mArr);

		if( $m > $mArr[0] ) $y++;
		//mm/dd(曜) hh:mmから(曜)を削除
		$dateStr = preg_replace(
			'/(\d{1,2}\/\d{1,2})\(.+?\) (\d{1,2}\:\d{1,2})/',
			$y.'/'.'\1 \2',
			$str
		);

		return date('c', strtotime($dateStr));
	}

	protected function idMaker($id, $start, $fmt){
		//同日同時刻同フォーマットのイベントがない前提のid
		preg_match('/(\d{4})\-(\d{1,2})\-(\d{1,2})T(\d{1,2})\:(\d{1,2})/', $start, $time);

		return
			$id.
			$time[1].
			str_pad($time[2], 2, 0, STR_PAD_LEFT).
			str_pad($time[3], 2, 0, STR_PAD_LEFT).
			str_pad($time[4], 2, 0, STR_PAD_LEFT).
			str_pad($time[5], 2, 0, STR_PAD_LEFT).
			str_pad($fmt, 2, 0, STR_PAD_LEFT);
	}

	protected function getFormat($txt){
		$fmt = '';

		if(
			( strpos( $txt, 'Planeswalker Championship' ) !== false ) OR
			( strpos( $txt, 'Standard' ) !== false ) OR
			( strpos( $txt, 'スタン' ) !== false ) OR
			( strpos( $txt, 'ｽﾀﾝ' ) !== false )
		){
			$fmt = 1;
		}else if(
			( strpos( $txt, 'Pioneer' ) !== false ) OR
			( strpos( $txt, 'パイオニア' ) !== false ) OR
			( strpos( $txt, 'ﾊﾟｲｵﾆｱ' ) !== false )
		){
			$fmt = 2;
		}else if(
			( strpos( $txt, '旧枠モダン' ) !== false ) OR
			( strpos( $txt, '旧モ' ) !== false ) OR
			( strpos( $txt, 'Modern' ) !== false ) OR
			( strpos( $txt, 'モダン' ) !== false ) OR
			( strpos( $txt, 'ﾓﾀﾞﾝ' ) !== false )
		){
			$fmt = 3;
		}else if(
			( strpos( $txt, 'Legacy' ) !== false ) OR
			( strpos( $txt, 'レガシー' ) !== false ) OR
			( strpos( $txt, 'ﾚｶﾞｼｰ' ) !== false )
		){
			$fmt = 4;
		}else if(
			( strpos( $txt, 'Vintage' ) !== false ) OR
			( strpos( $txt, 'ヴィンテ' ) !== false ) OR
			( strpos( $txt, 'ｳﾞｨﾝﾃ' ) !== false )
		){
			$fmt = 5;
		}else if(
			( strpos( $txt, 'Pauper' ) !== false ) OR
			( strpos( $txt, 'パウパー' ) !== false ) OR
			( strpos( $txt, 'ﾊﾟｳﾊﾟｰ' ) !== false )
		){
			$fmt = 6;
		}else if(
			( strpos( $txt, 'Draft' ) !== false ) OR
			( strpos( $txt, 'ドラフト' ) !== false ) OR
			( strpos( $txt, 'ﾄﾞﾗﾌﾄ' ) !== false )
		){
			$fmt = 11;
		}else if(
			( strpos( $txt, 'プレリリース' ) !== false ) OR
			( strpos( $txt, 'ﾌﾟﾚﾘﾘｰｽ' ) !== false ) OR
			( strpos( $txt, 'プレリ' ) !== false ) OR
			( strpos( $txt, 'ﾌﾟﾚﾘ' ) !== false ) OR
			( strpos( $txt, 'シールド' ) !== false ) OR
			( strpos( $txt, 'ｼｰﾙﾄﾞ' ) !== false )
		){
			$fmt = 12;
		}else if(
			( strpos( $txt, 'コマンダー' ) !== false ) OR
			( strpos( $txt, 'ｺﾏﾝﾀﾞｰ' ) !== false ) OR
			( strpos( $txt, 'Commander' ) !== false ) OR
			( strpos( $txt, 'ヒュージ・リーダーズ' ) !== false ) OR
			( strpos( $txt, '統率者' ) !== false )
		){
			$fmt = 13;
		}else{
			$fmt = 99;
		}

		return $fmt;
	}

	protected function getFee($txt){
		$fee = 0;

		if(
			( strpos( $txt, '無料' ) !== false ) OR
			( strpos( $txt, 'Free' ) !== false )
		){
			$fee = 0;
		}else{
			$txt = trim($txt);
			$txt = preg_replace('/^[\\¥]/u', '', $txt);
			$txt = str_replace('円', '', $txt);
			$txt = str_replace( ',', '', $txt);

			if( is_numeric($txt) ){
				$fee = $txt;

			}else{
				$fee = 9999;
			}

		}

		return $fee;
	}
}

?>
