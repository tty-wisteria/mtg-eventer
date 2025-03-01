<?php	//Yellow Submarine

class Shop600 extends Shopper{

	public function scrape(){
		$this->ENCODING = 'SJIS';

		$html = $this->callSoloCURLProcess($this->url);

		$items = $html->query('//div[@class="sp_roll"]//tr');

		foreach($items as $tr){

			$item = $html->query('.//td', $tr);

			if(  strpos($item->item(1)->nodeValue ,'マジック') !== false ){

				$start = $this->dateFixer( $item->item(0)->nodeValue.' '.$item->item(3)->nodeValue );
				$fmt = $this->getFormat( $item->item(2)->nodeValue );

				if( $this->since <= $start AND $start <= $this->until ){		//ピック対象期間であれば
					$this->events[] = [
						'id' => $this->idMaker($this->shop['id'], $start, $fmt),
						'title' => $item->item(2)->nodeValue,
						'start' => $start,
						'fmt' => $fmt,
						'fee' => $this->getFee( $item->item(5)->nodeValue ),
						'url' => $this->shop['cid'],
						'sid' => $this->shop['id']
					];
				}
			}
		}

		unset($html, $item);

		return $this->events;

	}

}

?>
