<?php

class Dater{
	public $since;
	public $until;
	public $firstDayOfNextMonth;
	public $lastDayOfNextMonth;
	public $defaultSince;
	public $defaultUntil;

	public function __construct(){
		$this->firstDayOfNextMonth = date('c', strtotime( date('Y-m-d 00:00:00', strtotime('first day of next month'))));
		$this->lastDayOfNextMonth = date('c', strtotime( date('Y-m-d 23:59:59', strtotime( 'last day of next month'))));

		$this->defaultSince = date('c', strtotime( date('Y-m-d 00:00:00', strtotime('+1 day'))));
		$this->defaultUntil = date('c', strtotime( date('Y-m-d 23:59:59', strtotime('+7 day'))));

		$this->initializeTerm();
	}

	public function isBigginingOfAMonth(){
	}

	public function isOverAMonth(){
		//今日を基準日に、1週間後が月を跨ぐ場合
		if( date('Ym', strtotime($this->$since)) != date('Ym', strtotime($this->$until)) ){
			return true;
		}
		return false;
	}

	public function isThe3rdThu(){
		$w = date('W') - date('W', strtotime('first day of this month')) + 1;
		if( $w == 3 ) return true;
		return false;
	}

	public function updateTerm($since = null, $until = null){
		if( $since == null && $until == null ){
			$this->initializeTerm();
		}else{
			$this->since = $since;
			$this->until = $until;
		}
	}

	private function setNextMonthTerm(){
		$this->since = $this->firstDayOfNextMonth;
		$this->until = $this->lastDayOfNextMonth;
	}

	private function initializeTerm(){
		$this->since = $this->defaultSince;
		$this->until = $this->defaultUntil;
	}

}

?>
