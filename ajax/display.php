<?php
	session_start();

	if( isset($_POST['count']) ){
		if( isset($_SESSION['MTGEVENTER_LOGIN']['DISPLAY']) ){
			if( $_POST['count'] != $_SESSION['MTGEVENTER_LOGIN']['DISPLAY'] ){
				$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'] = $_POST['count'];
				echo '{"status": "1", "message": "success", "value": '.$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'].'}';
			}else{
				echo '{"status": "0", "message": "stay", "value": '.$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'].'}';
			}
		}else{
			$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'] = 20;
			echo '{"status": "1", "message": "success", "value": '.$_SESSION['MTGEVENTER_LOGIN']['DISPLAY'].'}';
		}
	}else{
		echo '{"status": "300", "message": "data error"}';
	}

?>
