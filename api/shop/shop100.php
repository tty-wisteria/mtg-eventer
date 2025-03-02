<?php	//晴れる屋

class Shop100 extends Shopper{

	public function scrape(){

		$scrapePages = [];
		$scrapePages[] = $this->url;
		$lowerPages = [];

		$targetURLs = [];
		$lowerURLs;

		//翌月に跨る場合
		if( date('Ym', strtotime($this->since)) != date('Ym', strtotime($this->until)) ){
			$scrapePages[] = $this->url.'&date='.date('Ym', strtotime($this->until));
		}

		var_dump($scrapePages);


		$upperPages = $this->callMultiCURLProcess($scrapePages, function($pageURL, $html){
			$pageEvents = [];

			preg_match(
				'/([0-9]{4})年([0-9]{1,2})月/',
				$html->query('//ul[@class="tab"]/li[2]/a[2]')->item(0)->nodeValue,
				$YM
			);

			$date = ['y'=> $YM[1],'m'=> $YM[2]];
			$dailyEvents = $html->query('//li[contains(@class,"eventCalendar__calendarList__data")]/div');

			foreach ($dailyEvents as $d => $daily) {						//日単位のチェック

				$chkDate = date('c', mktime('0', '0', '0', $date['m'], $d + 1, $date['y']));		//日付を組み立て

				if( $this->since <= $chkDate AND $chkDate <= $this->until ){		//ピック対象期間であれば

					$events = $html->query('span[contains(@class,"title")]//a/@href', $daily);	//URLを取得

					foreach( $events as $eventNode ){												//対象URLのチェック
						$pageEvents[] = $eventNode->nodeValue;
					}
				}
			}

			unset($dailyEvents, $events);

			return $pageEvents;
		});


		foreach( $upperPages as $u ){					//対象URLのチェック
			if(strpos( $u, 'detail' ) !== false){		//detailを含むURLの場合
				$targetURLs[] = $u;						//イベントページなので保持
			}else{										//detailを含まないURLの場合
				$lowerPages[] = $u;
			}
		}

		if( count($lowerPages) > 0 ){
			$lowerURLs = $this->callMultiCURLProcess($lowerPages, function($pageURL, $html){
				$lowerPageEvents = [];

				$lowerLinks = $html->query('//div[contains(@class,"event-offer-list__item-name")]//a/@href');
				foreach( $lowerLinks as $url ){
					$lowerPageEvents[] = $url->nodeValue;
				}
				unset($lowerLinks);

				return $lowerPageEvents;
			});

			$targetURLs = array_merge($targetURLs, $lowerURLs);
		}

		echo('<b>targetURLs</b><br>');
		var_dump($targetURLs);

		$events = $this->callMultiCURLProcess($targetURLs, function($pageURL, $html){
			
			$data  = [];

			try {

				$pageItem = $html->query('//div[@class="event_detail_table_text"]');
				$title = $pageItem->item(1)->nodeValue;

				$pageItemHeader = $html->query('//div[@class="event_detail_table_title"]')->item(3)->nodeValue;
				//$blank = $pageItem->length - 8;				//特殊なイベントの場合、受付日時の行がない場合がある

				$format = str_replace(["\r\n", "\n", "\r"], '', $pageItem->item(4)->nodeValue);
				$formats = explode(' ', $format);
				$formats = array_filter($formats);

				foreach($formats as $f) {
					$fmtID = $this->getFormat($f);

					$data[] = [
						'id' => explode('/', $pageURL)[5].$fmtID,
						'title' => $pageItem->item(1)->nodeValue,
						'start' => (DateTime::createFromFormat('Y年m月d日 H時i分', $pageItem->item(2)->nodeValue))->format('Y-m-d H:i'),
						'fmt' => $fmtID,
						'fee' => $this->getFee($pageItem->item(6)->nodeValue),
						'url' => $pageURL,
						'sid' => $this->shop['id']
					];
				}

				unset($pageItem, $pageItemHeader);

			} catch (\Throwable $th) {
				echo 'error';
				//throw $th;
			}

			echo '</pre>';

			return $data;

		});

		return $events;
	}

}

?>
