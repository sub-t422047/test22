<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>


	<iframe src="default.php" name="info-search" width="100%" height="300" style="resize: vertical;">
	</iframe>

	<form action="admin-temp-rsv.php" method="post" target="temp">
		<select name="column">
			<option value="r.status" selected>状態</option>
			<option value="h.name">氏名</option>
			<option value="h.code">予約番号</option>
		</select>
		検索内容<input type="text" name="search" value="仮予約" required>
		<button type="submit" name="submit">検索する</button>
	</form>

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
		
	if(isset($_POST['search'])){
		$SQL = 'select min(r.id) as id, r.holder, r.date, t.start, r.status, r.update, h.name, h.code,  count(*) as count 
			from reservation as r 
			join holder as h on r.holder = h.id 
			join term as t on r.term = t.id
			where '.$_POST['column'].' LIKE "%'.$_POST['search'].'%"
			and date >= "2024-05-16"
			group by r.holder
			order by r.date, r.room, r.term';
			
		/*$sql_rsvlist = $pdo->prepare('	select min(r.id) as id, r.holder, r.date, t.start, r.status, r.update, h.name, h.code,  count(*) as count 
						from reservation as r 
						join holder as h on r.holder = h.id 
						join term as t on r.term = t.id
						where ? = ? 
						and date >= "2024-05-16"
						group by r.holder
						order by r.date, r.room, r.term');*/
		$sql_rsvlist = $pdo->query($SQL);
						//SQLの日付部分　→本来はCURRENT_DATE
		//$sql_rsvlist->execute([$_POST['column'], $_POST['search']]);
	
	
	}else{
		/*$sql_rsvlist = $pdo->query('	select r.id, r.holder, r.date, t.start, r.status, r.update, h.name, h.code, sub.count_id 
						from reservation as r 
						join holder as h 
						on r.holder = h.id 
						join term as t
						on r.term = t.id
						join ( 
							select  min(id) as min_id, count(*) as count_id
							from reservation
							where status = "仮予約"
							and date >= "2024-05-16"
							group by holder
						) sub on r.id = sub.min_id
						order by r.date, r.room, r.term');*/
						//SQLの日付部分　→本来はCURRENT_DATE
		$sql_rsvlist = $pdo->query('	select min(r.id) as id, r.holder, r.date, t.start, r.status, r.update, h.name, h.code,  count(*) as count 
						from reservation as r 
						join holder as h on r.holder = h.id 
						join term as t on r.term = t.id
						where r.status = "仮予約" 
						and date >= "2024-05-16"
						group by r.holder
						order by r.date, r.room, r.term');
	}
						
						
	$result_rsvlist = $sql_rsvlist->fetchAll();
	if(count($result_rsvlist) > 0){
	
?>		<div class="cld">
			<table>
					<tr>
						<td>予約番号</td>
						<td>氏名</td>
						<td>予約日</td>
						<td>状態</td>
						<td>状態更新</td>
						<td>本予約期日</td>
						<td>詳細情報</td>
					</tr>
			
<?php				foreach($result_rsvlist as $row){
					$date = new Datetime($row['update'], new DateTimeZone('Asia/Tokyo'));
					$date->modify('+1 week');
?>					<tr>
						<td><?=$row['code']?></td>
						<td><?=$row['name']?></td>
						<td><?=date('n月j日(', strtotime($row['date'])).$days[date('D', strtotime($row['date']))].')'?></td>
						<td><?=$row['status']?></td>
						<td><?=$row['update']?></td>
						<td>
<?php							if($row['status'] == "仮予約"){
								echo date('n月j日(', strtotime($date->format('Y-m-d'))).$days[date('D', strtotime($date->format('Y-m-d')))].')';
							}else{
								echo '-';
							}
?>						</td>
						<td>
							<a 
							href="detailed-info.php" 
							target="info-search" 
							onclick="updateSession2(<?=$row['id']?>)"
							>
							詳細情報
							</a>
						</td>
					</tr>
<?php			}
?>			</table>
		</div>
		<script>
			function updateSession2(info) {
				
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "rsv-functions.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
				xhr.send('detailed-info-frag=' + info);
				xhr.onload = function(){
					if(xhr.status!=200){
						alert('error');
					}
				}
				xhr.onerror = function(){
					alert("Request failed");
				}
				xhr.onreadystatechange = function(){
					if(xhr.readyState === 4 && xhr.status === 200){
						//location.href = location.href;
					}
				}
			}
		</script>
<?php	}else{

?>		<p>結果が見つかりませんでした。</p>

<?php	}



?>


<?php require_once 'rsv-footer.php'; ?>
