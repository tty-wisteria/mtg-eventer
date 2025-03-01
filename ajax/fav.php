<?php
	session_start();
	require_once('../api/MySQLConnect.php');

	$entry = ( $_POST['status'] == 'true' )? true : false ;

	if(
		isset($_SESSION['MTGEVENTER_LOGIN']['STATUS']) &&
		$_SESSION['MTGEVENTER_LOGIN']['STATUS'] == true
	){

		$eventer_pdo = db_connect();

		if( $entry ){
			$sql = 'INSERT INTO fav values(?, ?, ?)';
			$stmt = $eventer_pdo -> prepare($sql);
			$stmt -> bindValue(1, $_SESSION['MTGEVENTER_LOGIN']['ID']);
			$stmt -> bindValue(2, $_SESSION['MTGEVENTER_LOGIN']['SNS']);
			$stmt -> bindValue(3, $_POST['eid']);

		}else{
			$sql = 'DELETE FROM fav WHERE uid = ? AND sns = ? AND eid = ?';
			$stmt = $eventer_pdo -> prepare($sql);
			$stmt -> bindValue(1, $_SESSION['MTGEVENTER_LOGIN']['ID']);
			$stmt -> bindValue(2, $_SESSION['MTGEVENTER_LOGIN']['SNS']);
			$stmt -> bindValue(3, $_POST['eid']);

		}

		try {
			$stmt -> execute();
			if( $entry ){
				echo '{"status": "1", "message": "success"}';
			}else{
				echo '{"status": "2", "message": "delete success"}';
			}

		} catch (PDOException $Exception) {
			echo '{"status": "100", "message": "sql error"}';

		}

	}else{
		echo '{"status": "200", "message": "login error"}';
	}


?>
