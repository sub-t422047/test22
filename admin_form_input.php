<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>



<?php
	if(isset($_POST['comand']) && $_POST['comand'] == 'insert'){
	


		$title = '窓口新規予約';
			$explain_1 = '';
			$explain_2 = '';
			$explain_3 = '';
		$title_a = '予約が正常に完了しました';
			$explain_a1 = '予約をお忘れにならないよう、お手元にお控えください。';
			$explain_a2 = '';
			$explain_a3 = '';
			$explain_a4 = '';
				$data_a1 = '受付日　　　：';
				$data_a2 = '　　　　　　：';
				$data_a3 = '予約番号　　：';
				$data_a4 = '予約日　　　：';
				$data_a5 = '予約時間　　：';
				$data_a6 = '予約会議室　：';
				$data_a7 = '氏名　　　　：';
				$data_a8 = 'お住まい　　：';
				$data_a9 = '';
				$data_a10 = '';
			$button_a = '';
		$title_b = '正常に予約できませんでした';
			$explain_b1 = '既に予約が入っています。';
			$explain_b2 = '恐れ入りますが、再度お試しください。';
			$explain_b3 = '';
			$explain_b4 = '';



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


		$ID = generateId($_POST['date'], $_POST['term'], $_POST['room']);
		$success = false;
		
		if($found){
			$sql_insert = $pdo->prepare('insert into holder values(null, ?, ?, ?, null)');
			if($sql_insert->execute([$ID, $_POST['name'], $_POST['adress']])){
			
				$inserted_id = $pdo->lastInsertId();
				
				for($i=0;$i<$_POST['repeat'];$i++){
				
					$sql_insert2 = $pdo->prepare('insert into reservation values(null, ?, ?, ?, ?, "本予約", CURRENT_DATE, null)');
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

<?php		if($success){
			//header("Location: admin-screen.php");
			$_SESSION['new'] = 'true';
?>				<script>
					if(window.top !== window.self){
						window.top.location.reload();
					}
				</script>
		

				
<?php		}else{
?>			<h2><?=$title_b?></h2>
<?php				if(!$found){
?>					<div><?=$explain_b1?></div>
<?php				}
?>				<div><?=$explain_b2?></div>
				<div><?=$explain_b3?></div>
				<div><?=$explain_b4?></div>


<?php		}



	
	
	
	}else if(isset($_POST['date'])){
		$title = '窓口新規予約';
			$explain_1 = 'ここでの操作は"本予約"となります。';
			$explain_2 = '';
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


		<form action = "admin_form_input.php" method = "post">
			<table>
				<tr>
					<td><?=$input_1?></td>
					<td><input type="text" name="name" placeholder="予約太郎" required></td>
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
			<input type="hidden" name="comand" value="insert">
			<input type="submit" value="<?=$submit?>" onclick="return confirm('予約を実行します。よろしいですか');">
		</form>


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
<?php	}else{

?>	<h1>窓口新規予約</h1>
	<p>空き枠クリックすると<br>予約フォームが表示されます</p>

<?php	}
?>





<?php require_once 'rsv-footer.php'; ?>
