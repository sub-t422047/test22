<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php /* Template Name: rsv-user-screen */ ?>
<?php get_header(); ?>
<?php //require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>


<?php
	
	$title = '道泉連区　施設予約システム(試作)';
		$explain = '';
	$title_a = '施設概要';
		$explain_a1 = '';
		$explain_a2 = '';
		$explain_a3 = '';
		$explain_a4 = '';
	$title_b = '予約状況';
		$explain_b1 = '下表より"仮予約"が可能です。本予約は施設窓口にてお手続きをお願いいたします。';
		$explain_b2 = '仮予約の期限は本日より1週間です。期限を過ぎますと、自動的に予約が取消となりますので、ご了承ください。';
		$explain_b3 = '';
		$explain_b4 = '';

	$table = array(
			0 => '部屋',
			1 => '利用料金',
			2 => '利用料金(市外在住者)',
			3 => '備考'
	);

/********************************************************************/

	$date = cldDateSet();
	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);
	$sql_room=$pdo->query('select * from room');
	$result_room = $sql_room->fetchAll();

?>

	<h1><?=$title?></h1>
		<div><?=$explain?></div>

	<h2><?=$title_a?></h2>
		<div><?=$explain_a1?></div>
		<div><?=$explain_a2?></div>
		<div><?=$explain_a3?></div>
		<div><?=$explain_a4?></div>

	<table class="roominfo">
		<tr>
			<th><?=$table[0]?></th>
			<th><?=$table[1]?></th>
			<th><?=$table[2]?></th>
			<th><?=$table[3]?></th>
		</tr>
	
<?php
		foreach($result_room as $row){
			echo '<tr>';
			echo '<td>'.$row['name'].'</td>';
			echo '<td>'.$row['fee1'].'円</td>';
			echo '<td>'.$row['fee1']*2 .'円</td>';
			echo '<td>'.$row['note'].'</td>';
			echo '</tr>';
		}
?>
	
	</table>

	<h2><?=$title_b?></h2>
		<div><?=$explain_b1?></div>
		<div><?=$explain_b2?></div>
		<div><?=$explain_b3?></div>
		<div><?=$explain_b4?></div>



<?php
	//カレンダー(db情報, 開始日, 日付表示数, 表示モード, 自己ファイルパス)
	showCalendar($db, $date, 7, 'user',basename(__FILE__));
	//showCalendar($db, $date, 7, 'admin',basename(__FILE__));
?>


<?php get_footer(); ?>
<?php //require_once 'rsv-footer.php'; ?>
