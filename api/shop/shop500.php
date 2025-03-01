<?php	//アメニティドリーム

class Shop500 extends Shopper{

	public function scrape(){
		$this->ENCODING = 'UTF-8';

		$html = $this->callSoloCURLProcess();

		$items = $html->query('//div[contains(@class,"col_tournament")]//tr[not(contains(@class, "tourntthead"))]');

		foreach($items as $tr){
			$item = $html->query('.//td', $tr);
			echo($item->item(3)->nodeValue);

			$formats = $this->getFormatList($item->item(3)->nodeValue.$item->item(4)->nodeValue);

			foreach($formats as $j => $fmtTxt){
				$start = $this->dateFixer( $item->item(0)->nodeValue );
				$fmt = $this->getFormat( $fmtTxt );

				if( $this->since <= $start AND $start <= $this->until ){		//ピック対象期間であれば
					//$j == 1 は正規表現一致の元テキストデータ
					if( $j != 1 ){
						$this->events[] = [
							'id' => $this->idMaker($this->shop['id'], $start, $fmt),
							'title' => $item->item(3)->nodeValue,
							'start' =>  $this->dateFixer( $item->item(0)->nodeValue ),
							'fmt' => $fmt,
							'fee' => $this->getFee( $item->item(6)->nodeValue ),
							'url' => $this->url,
							'sid' => $this->shop['id']
						];
					}
				}
			}
		}

		unset($html, $item);

		return $this->events;

	}

	private function getFormatList($t){
		$pattern = '/スタン|パイオニア|モダン|レガシー|シールド|統率者|ドラフト|旧モ|プレリリース/';
		preg_match_all($pattern, $t, $lists);
		$formats = array_unique($lists[0]);
		if( count($formats) < 1 ) $formats[] = 'Non Format';
		return $formats;
	}

}



?>
