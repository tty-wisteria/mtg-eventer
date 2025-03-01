<?php
class UserSession{
	private $readStatus = true;

	public function __construct(){
		$this->loginStatusCheck();
		$this->displayCountCheck();
	}

	private function loginStatusCheck(){
		if( isset( $_SESSION['MTGEVENTER_LOGIN']['STATUS']) ){
			
		}else{
			$_SESSION['MTGEVENTER_LOGIN']['STATUS'] = false;
		}
	}

	private function displayCountCheck(){
		if( isset( $_SESSION['MTGEVENTER_LOGIN']['DISPLAY']) ){
			if( is_numeric($_SESSION['MTGEVENTER_LOGIN']['DISPLAY']) ){

			}else{
				$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'] = 20;
			}
		}else{
			$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'] = 20;
		}
	}

	public function access(){
		$_SESSION['MTGEVENTER_LOGIN']['PAGE']['FUTURE'] = 0;
		$_SESSION['MTGEVENTER_LOGIN']['PAGE']['PAST'] = 0;
	}

	public function hasLoggedIn(){
		return $_SESSION['MTGEVENTER_LOGIN']['STATUS'];
	}

	public function checkFutureEvent(){
		return $this->readStatus;
	}

	public function checkPageChange(){
		$this->readStatus = !$this->readStatus;
	}

	public function pageIndex(){
		if( $this->checkFutureEvent() ){
			return $_SESSION['MTGEVENTER_LOGIN']['PAGE']['FUTURE'];
		}else{
			return $_SESSION['MTGEVENTER_LOGIN']['PAGE']['PAST'];
		}
	}

	public function turnAPage($userCheckEventInFuture){
		if( $userCheckEventInFuture ){
			$this->readStatus = true;
			++$_SESSION['MTGEVENTER_LOGIN']['PAGE']['FUTURE'];
		}else{
			$this->readStatus = false;
			++$_SESSION['MTGEVENTER_LOGIN']['PAGE']['PAST'];
		}
	}

	public function id(){
		return $_SESSION['MTGEVENTER_LOGIN']['ID'];
	}

	public function sns(){
		return $_SESSION['MTGEVENTER_LOGIN']['SNS'];
	}

	public function display(){
		return $_SESSION['MTGEVENTER_LOGIN']['DISPLAY'];
	}

	public function thumbnail(){
		return $_SESSION['MTGEVENTER_LOGIN']['IMAGE'];
	}

	public function name(){
		return $_SESSION['MTGEVENTER_LOGIN']['USERNAME'];
	}

}
?>

