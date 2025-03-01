<?php	//バトロコ

class Shop200 extends Shopper{

	public function scrape(){

		$items = $this->getGoogleCalendar($this->url);

		foreach( $items as $item ){
			if( strpos( $item['summary'], 'MTG' ) !== false ){
				preg_match('/【開始時刻】([0-9]{1,2}:[0-9]{1,5})/', $item['description'], $starttime);
				preg_match('/【参加費】([0-9]{1,5}|無料)/', $item['description'], $feeArr);
				preg_match('/【FMT】(.+$)/', $item['description'], $fmtArr);

				$fmtTxt = count( $fmtArr ) > 1 ? $fmtArr[1] : $item['summary'];

				foreach( explode('･', $fmtTxt) as $f ){
					$fmt = $this->getFormat($f);
					$this->events[] = [
						'id' => $item['id'].str_pad($fmt, 2, '0', STR_PAD_LEFT),
						'title' => $item['summary'],
						'start' => $item['modelData']['start']['date'],
						'fmt' => $this->getFormat($f),
						'fee' => count( $feeArr ) > 1 ? $feeArr[1] : 0,
						'url' => $item['htmlLink'],
						'sid' => $this->shop['id']
					];
				}
			}
		}

		return $this->events;
	}

}

?>
