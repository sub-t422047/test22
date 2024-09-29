<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php /* Template Name: user-form-input */ ?>
<?php get_header(); ?>
<?php //require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>



<?php

	$title = '道泉連区　施設予約システム(試作)';
		$explain_1 = 'ここでの操作は仮予約となります。';
		$explain_2 = '仮予約の期限は本日より1週間です。期日までに、施設窓口にて本予約をお願いいたします。';
		$explain_3 = '';
	$title_a = '';
		$explain_a1 = '予約日　　　：';
		$explain_a2 = '予約開始時間：';
		$explain_a3 = '予約会議室　：';
		$explain_a4 = '';
	$title_b = '';
		$explain_b1 = '';
		$explain_b2 = '';
		$explain_b3 = '';
		$explain_b4 = '';
	$form = '';
		$input_1 = '氏名';
		$input_2 = 'お住まい';
			$input_2_option1 = '瀬戸市内';
			$input_2_option2 = '瀬戸市外';
		$input_3 = '連続使用時間';
		$input_4 = '';
		$submit = '予約する';


/********************************************************************/

	$days = [
		'Sun' => '日',
		'Mon' => '月',
		'Tue' => '火',
		'Wed' => '水',
		'Thu' => '木',
		'Fri' => '金',
		'Sat' => '土'
	];

	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);

	//●SQL:term		
		$sql_term=$pdo->query('select * from term');
		$result_term = $sql_term->fetchAll();	
	//●SQL:room	
		$sql_room=$pdo->prepare('select name from room where id=?');
		$sql_room->execute([$_POST['room']]);
	//●SQL:reservation
		$sql_rsv=$pdo->prepare('select * 
					from reservation 
					where date = ? 
					and room = ? 
					and status <> "予約取消" 
					order by term');
		$sql_rsv->execute([$_POST['date'], $_POST['room']]);
		$result_rsv = $sql_rsv->fetchAll();


	$limit = 1;
	for($i=0;$i<=(count($result_term)-$_POST['term']+1);$i++){
		$limit = $i;
		foreach($result_rsv as $row){
			if($row['term'] == ($_POST['term']+$i)){
				break 2;
			}
		}
	}
?>


	<h1><?=$title?></h1>
		<div><?=$explain_1?></div>
		<div><?=$explain_2?></div>
		<div><?=$explain_3?></div>

	<h2><?=$title_a?></h2>
		<div><?=$explain_a1.date('n月j日(', strtotime($_POST['date'])).$days[date('D', strtotime($_POST['date']))].')'?></div>
		<div><?=$explain_a2?><span id = "baseTime"><?=date('H:i', strtotime($result_term[$_POST['term']-1]['start']))?></span><span id = "displayTime"></span></div>
		<div><?=$explain_a3.$sql_room->fetch()['name']?></div>
		<div><?=$explain_a4?></div>
		
	<h2><?=$title_b?></h2>
		<div><?=$explain_b1?></div>
		<div><?=$explain_b2?></div>
		<div><?=$explain_b3?></div>
		<div><?=$explain_b4?></div>


	<form action = "<?php echo esc_url(get_permalink(12018));?>" method = "post">
		<table>
			<tr>
				<td><?=$input_1?></td>
				<td><input type="text" name="rsv-name" placeholder="予約太郎" required></td>
			</tr>
			<tr>
				<td><?=$input_2?></td>
				<td>	<input type="radio" name="adress" value="市内" checked><?=$input_2_option1?>
					<input type="radio" name="adress" value="市外"><?=$input_2_option2?>
				</td>
			</tr>
			<tr>
				<td><?=$input_3?></td>
				<td><input type="number" id="repeat" name="repeat" min="1" max="<?=$limit?>"required></td>
			</tr>
		</table>

		<input type="hidden" name="date" value="<?=$_POST['date']?>">
		<input type="hidden" name="term" value="<?=$_POST['term']?>">
		<input type="hidden" name="room" value="<?=$_POST['room']?>">
		<input type="submit" value="<?=$submit?>" onclick="return confirm('予約を実行します。よろしいですか');">

	</form>


<?php
	topPage();
?>

	<script>
		document.getElementById('repeat').addEventListener('input', function(){
		
			var hoursInput = document.getElementById('repeat').value;
			var hoursToAdd = parseInt(hoursInput, 10);
			
			var baseTimeString = document.getElementById('baseTime').textContent;
			var displayTimeElement = document.getElementById('displayTime');
			
			var baseHours = parseInt(baseTimeString.split(':')[0], 10);
			var newHours = ('0' + (baseHours + hoursToAdd)).slice(-2);
			
			displayTimeElement.textContent = '〜' + newHours + ':00';
		});
	</script>



<?php get_footer(); ?>
<?php //require_once 'rsv-footer.php'; ?>
