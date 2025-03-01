<?php
require_once('./common.php');

class PDOer{
	private $pdo;

	public function __construct(){
		$dsn = DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8';

		try {
			$this->pdo = new PDO($dsn, DB_USER, DB_PASS);
			$this->pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		} catch (PDOException $Exception) {
			$_SESSION['utaudb_login']['error'] = 'データベース接続エラー：管理者にご連絡ください。';
			header('Location: error.php');
			//print "Accesse Falsed";
		}

	}

	public function executeData($sql, $placeholders = []){
		$stmt = $this->pdo -> prepare($sql);
		$cnt = count($placeholders);

		if( $cnt > 0 ){
			for ($i = 1; $i <= $cnt; $i++) {
				$stmt->bindValue($i, $placeholders[$i-1]);
			}
		}

		$stmt -> execute();

		$count = $stmt->rowCount();
		$data = $stmt->fetchAll();

		return [$data, $count];
	}

}

?>
