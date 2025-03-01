<?php
require_once('./PDOManager.php');
require_once('./dater.php');
require_once('./shop/shopper.php');
require_once('./shop/shop100.php');
require_once('./shop/shop200.php');
require_once('./shop/shop300.php');
require_once('./shop/shop400.php');
require_once('./shop/shop500.php');
require_once('./shop/shop600.php');

class Eventer{
	private $num;
	private $pdo;
	private $shops;

	public $dater;

	public function __construct(){
		$this->pdo = new PDOer();
		$this->dater = new Dater();

		[$this->shops, $count] = 
			$this->pdo->executeData(
				'SELECT * FROM shop'
			);

	}

	private function getEvent($shop){
		$shopper;

		/* */ if( $shop['sid'] == 1 ){	$shopper = new Shop100($shop);
		}else if( $shop['sid'] == 2 ){	$shopper = new Shop200($shop);
		}else if( $shop['sid'] == 3 ){	$shopper = new Shop300($shop);
		}else if( $shop['sid'] == 4 ){	$shopper = new Shop400($shop);
		}else if( $shop['sid'] == 5 ){	$shopper = new Shop500($shop);
		}else if( $shop['sid'] == 6 ){	$shopper = new Shop600($shop);
		}

		$shopper->setTerm($this->dater->since, $this->dater->until);

		return $shopper->scrape();
	}

	public function insertEvents($targets = []){
		//target：対象ショップidの配列
		//taegetに指定されたショップ情報だけ更新する

		foreach($this->shops as $shop){
			$isTargetShop = false;

			if( count($targets) == 0 ){
				$isTargetShop = true;
			}else{
				foreach($targets as $t){
					if($t == $shop['id']) $isTargetShop = true;
				}
			}

			if( $isTargetShop ){
				$events = $this->getEvent($shop);

				foreach( $events as $event ){
					var_dump($event);
					$this->pdo->executeData(
						'INSERT INTO event (eid, format, date, reception, shop, fee, title, url) VALUES(?,?,?,?,?,?,?,?)',
						[
							$event['id'],
							$event['fmt'],
							$event['start'],
							Null,
							$shop['id'],
							$event['fee'],
							$event['title'],
							$event['url']
						]
					);
				}
			}
		}
	}

	public function updateEvents($targets = []){

		foreach($this->shops as $shop){
			$flag = false;

			if( count($targets) == 0 ){
				$flag = true;
			}else{
				foreach($targets as $t){
					if($t == $shop['id']) $flag = true;
				}
			}

			if($flag){
				$events = $this->getEvent($shop);

				foreach( $events as $event ){
					$this->insertDumpTable($event);
				}
			}
		}

		$this->deleteCanceledEvent();
		$this->insertEventFromDump();
		$this->deleteDumpTable();

	}

	public function updateManual($targets = []){
		foreach($this->shops as $shop){
			$flag = false;

			if( count($targets) == 0 ){
				$flag = true;
			}else{
				foreach($targets as $t){
					if($t == $shop['id']) $flag = true;
				}
			}

			if($flag){
				var_dump($shop);
				$events = $this->getEvent($shop);

				foreach( $events as $event ){
					$this->insertDumpTable($event);
				}
			}
		}

		//既存のイベントリストに存在し、更新後に存在しないイベントを削除
		echo 'Delete Deleted Event<br>';

		$this->pdo->executeData(
			'DELETE FROM event WHERE (date BETWEEN ? AND ?) AND eid NOT IN( SELECT eid FROM dumptable) AND '.'shop IN( '.$this->placeholderGenerate($targets).' )',
			array_merge(
				[$this->dater->since,$this->dater->since],
				$targets
			)
		);

		$this->insertEventFromDump();
		$this->deleteDumpTable();


	}

	private function insertDumpTable($event){	//更新で一時テーブルにイベントを挿入
		$this->pdo->executeData(
			'INSERT INTO dumptable (eid, format, date, reception, shop, fee, title, url) VALUES(?,?,?,?,?,?,?,?)',
			[
				$event['id'],
				$event['fmt'],
				$event['start'],
				Null,
				$event['sid'],
				$event['fee'],
				$event['title'],
				$event['url'],
			]
		);
	}

	private function deleteCanceledEvent(){		//既存のイベントリストに存在し、更新後に存在しないイベントを削除
		echo 'Delete Deleted Event<br>';
		$this->pdo->executeData(
			'DELETE FROM event WHERE date BETWEEN ? AND ? AND eid NOT IN( SELECT eid FROM dumptable)',
			[
				$this->dater->since,
				$this->dater->until
			]
		);
	}

	private function insertEventFromDump(){		//一時テーブルの情報をメインテーブルに更新
		echo 'Update Event<br>';
		$this->pdo->executeData(
			'INSERT INTO event SELECT * FROM dumptable ON DUPLICATE KEY UPDATE format=VALUES(format), fee = VALUES(fee), title = VALUES(title)'
		);
	}

	private function deleteDumpTable(){			//一時テーブルの情報を削除
		echo 'Delte dumptable<br>';
		$this->pdo->executeData(
			'DELETE FROM dumptable'
		);
	}

	private function placeholderGenerate($list){
		return implode( ',', array_fill(1, count($list), '?') );
	}

}

?>
