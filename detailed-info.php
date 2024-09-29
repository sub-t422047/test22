<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>

<?php

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


	if ($_POST['command'] == "update") {
	
		$update = $_POST['update'];
		$note = $_POST['note']."\n";
		$dif = false;
		//echo $_POST['pre_name'];
		//echo $_POST['pre_adress'];
		//echo $_POST['pre_status'];
		
		if($_POST['pre_name'] != $_POST['name']){
			$name_change = $_POST['pre_name'].' →　'.$_POST['name']."\n";
			$dif = true;
		}
		if($_POST['pre_adress'] != $_POST['adress']){
			$adress_change = $_POST['pre_adress'].' →　'.$_POST['adress']."\n";
			$dif = true;
		}
		
		if($_POST['pre_status'] != $_POST['status']){
			$status_change = $_POST['pre_status'].' →　'.$_POST['status']."\n";
			$date = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
			$update = $date->format('Y-m-d');
			$dif = true;
		}
		
		if($dif){
			$note = "---------\n".'更新：'.$update."\n".$name_change.$adress_change.$status_change.$note;
		}
		
	
		$sql_update=$pdo->prepare('	update reservation as r
						join holder as h
						on r.holder = h.id
						set 
						r.status = ?, 
						r.note = ?,
						r.update = ?, 
						h.name = ?, 
						h.adress = ?
						where h.id = ?');
		if($sql_update->execute([$_POST['status'], $note, $update, $_POST['name'], $_POST['adress'], $_POST['holder_id']])){
			if($_POST['status'] == '予約取消'){
				unset($_SESSION['detailed_frag']);
				$_SESSION['drop'] = 'true';
?>				<script>
					if(window.top !== window.self){
						window.top.location.reload();
					}
				</script>		
<?php			}else{
				echo '情報の更新が完了しました。';
			}
		}else{
			echo '更新失敗';
		}
	}


	if (isset($_SESSION['detailed_frag'])){

			
		$repeat = 0;
		$code = '';
		
		$sql_hld=$pdo->prepare('select h.id 
					from reservation as r 
					join holder as h 
					on r.holder = h.id 
					where r.id = ? 
					order by r.date, r.room, r.term');
		$sql_hld->execute([$_SESSION['detailed_frag']]);
		$result_hld = $sql_hld->fetchAll();	
		
		
		$sql_rsv=$pdo->prepare('select r.id, r.holder, r.room, r.date, r.term, r.status, r.update, r.note, h.name, h.adress, h.code 
					from reservation as r 
					join holder as h 
					on r.holder = h.id 
					where h.id = ?
					and r.status <> "予約取消"
					order by r.date, r.room, r.term');
		$sql_rsv->execute([$result_hld[0]['id']]);
		$result_rsv = $sql_rsv->fetchAll();
		
		
		
		$repeat = count($result_rsv);
		$info = infoget();
		
?>

		<h2>予約者情報</h2>
		<div class="cld">
			<table>

<?php			
	
	
?>				<tr>
					<td style="background: #cccccc;">予約番号</td>
					<td style="background: #cccccc;">予約</td>
					<td style="background: #cccccc;">部屋</td>
					<td style="background: #cccccc;">状態</td>
					<td style="background: #cccccc;">状態更新日</td>
					<td style="background: #cccccc;">氏名</td>
					<td style="background: #cccccc;">住まい</td>
				</tr>
				<tr>
					<td><?=$result_rsv[0]['code']?></td>
					<td><?=date('n月j日(', strtotime($result_rsv[0]['date'])).$days[date('D', strtotime($result_rsv[0]['date']))].')'.'　'.date('H:i', strtotime($info['term'][$result_rsv[0]['term']-1]['start'])).'〜'.date('H:i', strtotime($info['term'][$result_rsv[0]['term']+$repeat-2]['finish']))?></td>
					<td><?=$info['room'][$result_rsv[0]['room']-1]['name']?></td>
					<td><?=$result_rsv[0]['status']?></td>
					<td><?=$result_rsv[0]['update']?></td>
					<td><?=$result_rsv[0]['name']?></td>
					<td><?=$result_rsv[0]['adress']?></td>
				</tr>
				<tr>
					<td colspan=7 style="text-align: left;">
						<pre>【備考】</pre>
						<pre><?=$result_rsv[0]['note']?></pre>
					</td>
				</tr>
			
<?php			
?>			</table>
		</div>
		
		<h2 style="font-weight: bold;">情報変更</h2>
		<form action="detailed-info.php" method="post">
			<div>
				○氏名
				<input type="text" name="name" value="<?=$result_rsv[0]['name']?>" required>
			</div>
			<div>
				○お住まい
<?php				if($result_rsv[0]['adress'] == "市内"){
?>					<input type="radio" name="adress" value="市内" checked>市内
					<input type="radio" name="adress" value="市外">市外
<?php				}else{
?>					<input type="radio" name="adress" value="市内">市内
					<input type="radio" name="adress" value="市外" checked>市外
<?php				}
?>			</div>
			<div>
				○予約状態
				<select id="status" name="status">
<?php					if($result_rsv[0]['status'] == "仮予約"){
?>						<option value="仮予約">仮予約</option>
<?php					}
?>						<option value="本予約">本予約</option>
						<option value="予約取消">予約取消</option>
				</select>
			</div>
			<div style="display: flex; align-items: flex-start;">
				○備考
				<textarea name="note" rows="4" cols="50"><?=$result_rsv[0]['note']?></textarea>
			</div>
				<input type="hidden" name="pre_name" value="<?=$result_rsv[0]['name']?>">
				<input type="hidden" name="pre_adress" value="<?=$result_rsv[0]['adress']?>">
				<input type="hidden" name="pre_status" value="<?=$result_rsv[0]['status']?>">
				<input type="hidden" name="update" value="<?=$result_rsv[0]['update']?>">
				<input type="hidden" name="holder_id" value="<?=$result_hld[0]['id']?>">
				<input type="hidden" name="command" value="update">	
			<input type="submit" value="更新する" onclick="checkSelection(event)">
		</form>
		
		<script>
			function checkSelection(event){
				const selectElement = document.getElementById('status');
				const selectedOption = selectElement.value;
				
				if(selectedOption === '予約取消'){
					const confirmation = window.confirm("【予約取消】が選択されています。\n本当に取り消してよろしいですか？\n\n※操作は取り消せません");
					if(!confirmation){
						event.preventDefault();
					}
				}else{
					const confirmation = window.confirm("情報を更新します。よろしいですか");
					if(!confirmation){
						event.preventDefault();
					}
				}
			
			}
		</script>
		
<?php	}else{
?>		<h1>lll詳細情報</h1>
		<p>予約をクリックすると<br>予約詳細情報が表示されます</p>
<?php	}
?>	



<?php require_once 'rsv-footer.php'; ?>
