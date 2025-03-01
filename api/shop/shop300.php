<?php	//BIG MAGIC

class Shop300 extends Shopper{

	public function scrape(){

		$items = $this->getGoogleCalendar($this->url);

		$pattern = '/MTG|Planeswalker|WPN|ゲームデー/';

		foreach( $items as $item ){

			//パターン内のいずれかのタイトル名に一致しなければ、おそらくMTGのイベント
			if( preg_match($pattern, $item['summary']) != 0 ){

				$fee = $this->getFeeBM($item['description']);		//参加費の取得
				$fmt = $this->getFormat($item['summary']);			//フォーマットの取得

				if( $fmt == 99 ){
					preg_match('/^(.+?)(?:\<br\>|\n)/', $item['description'], $lines);
					foreach ($lines as $line) {
						$fmt = $this->getFormat($line);
						if( $fmt != 99 ) break;
					}
				}

				$this->events[] = [
					'id' => $item['id'],
					'title' => $item['summary'],
					'start' => $item['modelData']['start']['dateTime'],
					'fmt' => $fmt,
					'fee' => $fee,
					'url' => $item['htmlLink'],
					'sid' => $this->shop['id']
				];

			}
		}

		return $this->events;

	}

	private function getFeeBM($str){
		if( preg_match('/参加費　([0-9]{1,5})円/', $str, $feeArr) ){
			return $feeArr[1];

		}else if( preg_match('/参加費：([0-9]{1,5})円/', $str, $feeArr )){
			return $feeArr[1];

		}else{
			return 0;
		}

		return 0;
	}



}



?>
