<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php /* Template Name: user-form-output */ ?>
<?php get_header(); ?>
<?php //require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>



<?php

	$title = '道泉連区　施設予約システム(試作)';
		$explain_1 = '';
		$explain_2 = '';
		$explain_3 = '';
	$title_a = '予約が正常に完了しました';
		$explain_a1 = '以下の情報は本予約で必要になります。';
		$explain_a2 = 'スクリーンショット、または印刷ボタンにて本画面を保存ください。';
		$explain_a3 = '本予約の際は、施設窓口に予約番号をお伝えください。';
		$explain_a4 = '';
			$data_a1 = '受付日　　　：';
			$data_a2 = '本予約期日　：';
			$data_a3 = '予約番号　　：';
			$data_a4 = '予約日　　　：';
			$data_a5 = '予約時間　　：';
			$data_a6 = '予約会議室　：';
			$data_a7 = '氏名　　　　：';
			$data_a8 = 'お住まい　　：';
			$data_a9 = '';
			$data_a10 = '';
		$button_a = '印刷する';
	$title_b = '正常に予約できませんでした';
		$explain_b1 = '既に予約が入っています。';
		$explain_b2 = '恐れ入りますが、再度お試しください。';
		$explain_b3 = '';
		$explain_b4 = '';



/********************************************************************/
	

	$today = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
	$deadline = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
	$deadline->modify('+1 week');
	
	$days = [
		'Sun' => '日',
		'Mon' => '月',
		'Tue' => '火',
		'Wed' => '水',
		'Thu' => '木',
		'Fri' => '金',
		'Sat' => '土'
	];
	$info = infoget();
	
	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);
				
	
	
		
	$found = true;
	$sql_check = $pdo->prepare('select * from reservation where date = ? and room = ? and status <> "予約取消"');
	$sql_check->execute([$_POST['date'], $_POST['room']]);
	$result_check = $sql_check->fetchAll();
	for($i=0;$i<$_POST['repeat'];$i++){
		foreach($result_check as $row){
			if($row['term'] == $_POST['term']+$i){
				$found = false;
				break;
			}
		}
	}


	$ID = generateId($_POST['date'], (int)$_POST['term'], (int)$_POST['room']);
	$success = false;
	
	
	
	if($found){
		$sql_insert = $pdo->prepare('insert into holder values(null, ?, ?, ?, null)');
		if($sql_insert->execute([$ID, $_POST['rsv-name'], $_POST['adress']])){
		
			$inserted_id = $pdo->lastInsertId();
			
			for($i=0;$i<$_POST['repeat'];$i++){
			
				$sql_insert2 = $pdo->prepare('insert into reservation values(null, ?, ?, ?, ?, "仮予約", CURRENT_DATE, null)');
				if($sql_insert2->execute([$inserted_id, $_POST['room'], $_POST['date'], $_POST['term']+$i])){
					if($i == $_POST['repeat']-1){$success = true;}
				}else{
					echo "<script>alert('DB反映rsvに失敗しました');</script>";
					break;
				}
			}
		}else{
			echo "<script>alert('DB反映hldに失敗しました');</script>";
		}

	}else{
		echo "<script>alert('既に予約が入っています。今一度空き状況をご確認ください。');</script>";
	}
	
?>


		<h1><?=$title?></h1>
			<div><?=$explain_1?></div>
			<div><?=$explain_2?></div>
			<div><?=$explain_3?></div>

<?php	if($success){
?>		<h2><?=$title_a?></h2>
			<div><?=$explain_a1?></div>
			<div><?=$explain_a2?></div>
			<div><?=$explain_a3?></div>
			<div><?=$explain_a4?></div>
				<div><?=$data_a1.$today->format('m月d日(').$days[$today->format('D')].')'?></div>
				<div><?=$data_a2.$deadline->format('m月d日(').$days[$deadline->format('D')].')'?></div>
				<div><?=$data_a3.$ID?></div>
				<div><?=$data_a4.date('n月j日(', strtotime($_POST['date'])).$days[date('D', strtotime($_POST['date']))].')'?></div>
				<div><?=$data_a5.date('H:i', strtotime($info['term'][$_POST['term']-1]['start'])).'〜'.date('H:i', strtotime($info['term'][$_POST['term']+$_POST['repeat']-2]['finish']))?></div>
				<div><?=$data_a6.$info['room'][$_POST['room']-1]['name']?></div>
				<div><?=$data_a7.$_POST['rsv-name']?></div>
				<div><?=$data_a8.$_POST['adress']?></div>
				<div><?=$data_a9?></div>
				<div><?=$data_a10?></div>
				<div><button onclick="window.print(); return false;"><?=$button_a?></button></div>
				
<?php	}else{
?>		<h2><?=$title_b?></h2>
<?php			if(!$found){
?>				<div><?=$explain_b1?></div>
<?php			}
?>			<div><?=$explain_b2?></div>
			<div><?=$explain_b3?></div>
			<div><?=$explain_b4?></div>


<?php	}
?>


<?php
	topPage();
?>


<?php get_footer(); ?>
<?php //require_once 'rsv-footer.php'; ?>
