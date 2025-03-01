<?php	//東京MTG

class Shop400 extends Shopper{

	public function scrape(){

		$items = $this->getGoogleCalendar($this->url);

		foreach( $items as $item ){
			if( isset($item['description']) ){
				preg_match('/参加費.+?(\d{1},\d{3}円|\d{3}円|無料)/', $item['description'], $feeArr);
				preg_match('/形式(.+?)\//', $item['description'], $fmtArr);

				$fmt = 99;
				if( count( $fmtArr ) > 1 ) $fmt = $this->getFormat( $fmtArr[1] );
				if( $fmt == 99 ) $fmt = $this->getFormat($item['summary']);

				$this->events[] = [
					'id' => $item['id'],
					'title' => $item['summary'],
					'start' => $item['modelData']['start']['dateTime'],
					'fmt' => $fmt,
					'fee' => count( $feeArr ) > 1 ? $this->getFee( $feeArr[1] ) : 0,
					'url' => $item['htmlLink'],
					'sid' => $this->shop['id']
				];
			}
		}

		return $this->events;

	}

}



?>
