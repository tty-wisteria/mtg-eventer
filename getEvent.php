<?php
	//Called From index or addEvent
	require_once('api/MySQLConnect.php');
	$eventer_pdo = db_connect();

	$index = 0;
	$cond = [
		'p' => [],
		'v' => []
	];
	$order = '';

	//$userは呼び出し元ページから参照

	if( $user->hasLoggedIn() ){
		$sql = "SELECT event.eid, format.name as format, date, DATE_FORMAT(date,'%m/%d') as md, DATE_FORMAT(date,'%H:%i') as start, area.name as area, shop.name as shop, fee, title, url, (CASE WHEN fav.eid IS NOT NULL THEN 1 ELSE 0 END) as fav FROM event LEFT JOIN shop ON event.shop = shop.id LEFT JOIN area ON shop.area = area.id LEFT JOIN format ON event.format = format.id LEFT JOIN ( SELECT eid FROM fav WHERE uid = ? AND sns = ? ) as fav ON event.eid = fav.eid";
		$cond['v'][] = $user->id();
		$cond['v'][] = $user->sns();

	}else{
		$sql = "SELECT event.eid, format.name as format, 0 as fav, date, DATE_FORMAT(date,'%m/%d') as md, DATE_FORMAT(date,'%H:%i') as start, area.name as area, shop.name as shop, fee, title, url FROM event LEFT JOIN shop ON event.shop = shop.id LEFT JOIN area ON shop.area = area.id LEFT JOIN format ON event.format = format.id";
	}

	if( $user->checkFutureEvent() ){
		$cond['p'][] = 'date > NOW()';
		$order = ' ORDER BY date ASC, event.format';
		$index = $user->pageIndex();

	}else{
		$cond['p'][] = 'date < NOW()';
		$order = ' ORDER BY date DESC, event.format';
		$index = $user->pageIndex();

	}

	foreach( $queries as $key => $val ){
		if( $key == 'freeword' ){
			if( $val != '' ){
				$cond['p'][] = "(event.title LIKE ?)";
				$cond['v'][] = '%'.$val.'%';
			}
			
		}elseif( $key == 'format' ){
			$cond['p'][] = 'event.format IN( '.implode( ',', array_fill(1, count($val), '?') ).' )';
			foreach( $val as $f ) $cond['v'][] = $f;
			
		}else if( $key == 'area' ){
			if( $val != '' ){
				$cond['p'][] = 'area.id = ?';
				$cond['v'][] = $val;
			}

		}else if( $key == 'shop' ){
			$cond['p'][] = 'shop IN( '.implode( ',', array_fill(1, count($val), '?') ).' )';
			foreach( $val as $s ) $cond['v'][] = $s;

		}else if( $key == 'date' ){
			if( $val != '' ){
				$cond['p'][] = 'DATE(date) = ?';
				$cond['v'][] = $val;
			}

		}else if( $key == 'fav' ){
			if( $val == 'on' ){
				$cond['p'][] = 'fav.eid IS NOT NULL';
			}

		}else if( $key == 'holiday' ){
			if( $val == 'on' ){
				$cond['p'][] = 'WEEKDAY(date) IN( ? , ? )';
				$cond['v'][] = 5;
				$cond['v'][] = 6;
			}

		}

	}

	$cond['v'][] = ( $index * $user->display() );
	$cond['v'][] = $user->display();

	$sql .= ' WHERE '.implode( ' AND ' , $cond['p']).$order.' LIMIT ?, ?';

	$stmt = $eventer_pdo -> prepare($sql);

	foreach( $cond['v'] as $i => $val ){
		$stmt -> bindValue($i + 1, $val);
	}

	$stmt -> execute();
	$events = $stmt-> fetchAll(PDO::FETCH_ASSOC);

	return $events;

?>
