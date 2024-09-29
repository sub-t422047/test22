<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php
/********************************************************************
/********************************************************************
	関数用ファイル
*********************************************************************
********************************************************************/
?>



<?php
/********************************************************************
詳細情報表示のためのセッション更新()　!! セッション管理　not関数　!!
********************************************************************/
if (isset($_POST['detailed-info-frag'])) {
	//session_start();
	$_SESSION['detailed_frag'] = $_POST['detailed-info-frag'];
}



/********************************************************************
カレンダー日付更新()　!! セッション管理　not関数　!!
********************************************************************/
if (isset($_POST['cld-session-frag'])) {
	//session_start();
	$_SESSION['cld_frag'] = $_POST['cld-session-frag'];
}



/********************************************************************
予約システムトップページ表示
********************************************************************/
function topPage(){

?>	<div style="float: right;">
		<a href="<?=get_permalink(12012)?>">トップ画面に戻る</a><!--★-->
	</div>
<?php	

}


/********************************************************************
ID生成
********************************************************************/
function generateId($date, int $term, int $room){

	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);
	$count = 0;
	$sql_count = $pdo->prepare('select count(*) from reservation where date = ? and term = ? and room = ?');
	$sql_count->execute([$date, $term, $room]);
	$count = (int)$sql_count->fetchColumn();

	list($year, $month, $day) = explode('-', $date);
	$year = substr($year, -2);
	$month = str_pad($month, 2, '0', STR_PAD_LEFT);
	$day = str_pad($day, 2, '0', STR_PAD_LEFT);
	$term = str_pad($term, 2, '0', STR_PAD_LEFT);
	$room = str_pad($room, 2, '0', STR_PAD_LEFT);
	$count = str_pad($term, 2, '0', STR_PAD_LEFT);
	
	$baseId = intval($term.$room.$month.$count.$day);
	
	$uniqueId = base_convert($baseId, 10, 36);
	
	return strtoupper(str_pad($uniqueId, 6, '0', STR_PAD_LEFT));

}


/********************************************************************
XSS対策
********************************************************************/
function xss(string $str, string $charset = 'UTF-8'): string{

	return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset, false);

}



/********************************************************************
カレンダー開始日取得()
********************************************************************/
function cldDateSet(){

	if(isset($_SESSION['cld_frag'])){
		//echo 'frag:'.$_SESSION['cld_frag'].'<br>';
		//echo 'check2:'.$_SESSION['date']->format('Y-m-d<br>').'<br>';
		switch($_SESSION['cld_frag']){
			case 'back':
				$today = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
				//$today->setDate(2024, 5, 16);//実際は不要
				$date = $_SESSION['date'];
				$back = $_SESSION['rowMax']*2;
				$date->modify('-'.$back.'day');
				if($date < $today){
					$date = clone $today;
				}
				break;
			case 'next':
				$limday = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
				//$limday->setDate(2024, 5, 16);//実際は不要
				$limday->modify('+2 months');
				$limday->modify('last day of this month');
				//echo 'limday:'.$limday->format('Y-m-d').'<br>';
				$date = $_SESSION['date'];
				//echo 'check2:'.$_SESSION['date']->format('Y-m-d').'<br>';
				if($date > $limday){
					$back = $_SESSION['rowMax'];
					$date->modify('-'.$back.'day');
				}
				break;
		}
	}else{
		$date = new Datetime(null, new DateTimeZone('Asia/Tokyo'));
		//$date->setDate(2024, 5, 16);//実際は不要
	}


	/*
		if(isset($_SESSION['date'])){
			echo 'date:'.$_SESSION['date']->format('Y/m/d').'<br>';
		}else{
			echo 'session:dateなし<br>';
		}*/
	
	
	return $date;
}


/********************************************************************
DB情報取得()
********************************************************************/
function dbInit(){

	$db = array(
		'ip'	=> 'localhost',
		'db' 	=> 'test613',
		'char'	=> 'utf8',
		'usr' 	=> 'test613',
		'passwd'=> 'seto2024'
	);
	
	return $db;
}


/********************************************************************
施設名枠時間取得()
********************************************************************/
function infoget(){

	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);
	
	$sql_term=$pdo->query('select * from term');
	$result_term = $sql_term->fetchAll();
	//echo '予約開始時間：'.date('H:i', strtotime($result_term[$_GET['term']-1]['start'])).'〜<br>';

	$sql_room=$pdo->query('select name from room order by id');
	$result_room = $sql_room->fetchAll();
	//echo '予約会議室　：'.$sql_term->fetch()['name'].'<br>';
	
	
	$info = array(
		'room' 	=> $result_room,
		'term' 	=> $result_term
	);
	return $info;	
}


/********************************************************************
カレンダー表示(db情報, 開始日, 日付表示数, 表示モード,実行ファイルパス)
********************************************************************/
function showCalendar($db, $date, $rowMax, $mode, $self){


	switch($mode){
		case 'user':
			$rink = get_permalink(12016);//user_form_input.php
			break;
		case 'admin':
			$rink = '/wp-content/themes/cocoon-child-master/detailed-info.php';
			break;
		case 'admin-r':
			//$rink = 'wp-content/themes/cocoon-child-master/admin_form_input.php';
			//追記
			$rink = '/wp-content/themes/cocoon-child-master//admin_form_input.php';
			break;
	}	
	
	
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);
		
	$days = [
		'Sun' => '日',
		'Mon' => '月',
		'Tue' => '火',
		'Wed' => '水',
		'Thu' => '木',
		'Fri' => '金',
		'Sat' => '土'
	];
	
	
	
	//●SQL:term
		$sql_term=$pdo->query('select * from term');
		$result_term = $sql_term->fetchAll();

	//●SQL:room
		$sql_room=$pdo->query('select * from room');
		$result_room = $sql_room->fetchAll();

	//●SQL:reservation(user)
		if($mode != 'admin'){
			$sql_rsv=$pdo->prepare('select * 
						from reservation 
						where date >= ? 
						order by date, room, term');
			$sql_rsv->execute([$date->format('Y-m-d')]);
			$result_rsv = $sql_rsv->fetchAll();
		}
	
	//●SQL:reservation(admin)
		if($mode == 'admin'){
			$sql_rsv=$pdo->prepare('select r.id, r.holder, r.room, r.date, r.term, r.status, r.update, r.note, h.name, h.adress 
						from reservation as r 
						join holder as h 
						on r.holder = h.id 
						where r.date >= ? 
						order by r.date, 
						r.room, r.term');
			$sql_rsv->execute([$date->format('Y-m-d')]);
			$result_rsv = $sql_rsv->fetchAll();
		}
?>



	<div class="cld">
		<table>
			<tr>
				<th class="sticky1 sticky2">日付<br><input type="button" name="back" value="▲" onclick="cld_back()"></th>
				<th class="sticky1 sticky3">部屋</th>
<?php				for($i=0; $i<count($result_term); $i++){
?>					<th class="sticky1" start-time="<?=date('H:i', strtotime($result_term[$i]['start']))?>"><?=$i+1?>枠</th>
<?php				}
?>

			</tr>
			<tr>


<?php			//外ループ(日付,表の縦方向1)?>
<?php			for($i=0; $i<$rowMax; $i++){?>
	
				<td class="sticky2" rowspan="<?=count($result_room)?>"><?=$date->format('m/d(').$days[$date->format('D')]?>)</td>

<?php				//中ループ(部屋,表の縦方向2)
?><?php				for($j=0; $j<count($result_room); $j++){
					
					$room_id = $j+1;
?>					
					<td class="sticky3">
					
<?php					foreach($result_room as $row){
						if($row['id'] == $room_id){
							echo $row['name'];
							break;
						}
					}
?>					</td>
		
		
<?php					//内ループ(枠,表の横方向)
?><?php					for($k=0; $k<count($result_term); $k++){
				
						$term_id = $k+1;
						$found = false;  
						$text = '○';
										
						foreach($result_rsv as $row){
							if(($row['date'] == $date->format('Y-m-d')) && ($row['room'] == $room_id) && ($row['term'] == $term_id)){
								if(($row['status'] == '本予約') || ($row['status'] == '仮予約')){
									
									$text = ($mode!='admin') ? '✕' : $row['name'].'様';
									$found = true;
									
									break;
								}
							}
						}
				
?>						<td>
<?php						if($mode == 'user'){ 
							if($found){
								echo $text; 
							}else{
?>								<!--<a 
								href="<?=$rink?>
									?date=<?=urlencode($date->format('Y-m-d'))?>
									&term=<?=urlencode($term_id)?>
									&room=<?=urlencode($room_id)?>" 
								target="_blank"
								rel="noopenner noreferrer"
								>
									<?=$text?>
								</a>-->
								<form action="<?=$rink?>" method="post">
									<input type="hidden" name="date" value="<?=$date->format('Y-m-d')?>">
									<input type="hidden" name="term" value="<?=$term_id?>">
									<input type="hidden" name="room" value="<?=$room_id?>">
									<button type="submit" class="link-button"><?=$text?></button>
								</form>
<?php							}
						}else if($mode == 'admin'){
							//echo $found ? '<a href="'.$rink.'"data-info="'.$row['id'].'" target="_blank" rel="noopenner noreferrer" onclick="updateSession2(this)">'.$text.'</a>' :$text;
							if($found){ 
?>								<a 
								href="<?=$rink?>" 
								target="info" 
								onclick="updateSession2(<?=$row['id']?>)"
								>
									<?=$text?>
								</a>
<?php							}else{
								echo $text;
							}
						}else{
							if($found){
								echo $text; 
							}else{
?>								
								<form action="<?=$rink?>" method="post" target="info-r">
									<input type="hidden" name="date" value="<?=$date->format('Y-m-d')?>">
									<input type="hidden" name="term" value="<?=$term_id?>">
									<input type="hidden" name="room" value="<?=$room_id?>">
									<button type="submit" class="link-button"><?=$text?></button>
								</form>
<?php							}
						
						}
?>						</td>
<?php					}
			
					if($i!=$rowMax-1){
?>						</tr><tr>
<?php					}else{
?>						</tr>
<?php					}
				
				}
		
				$date->modify('+1 day');
		
	}
?>
			<tr>
				<td class="sticky2"><input type="button" name="next" value="▼" onclick="cld_next()"></td>
				<td class="sticky3"></td>
				<td colspan=<?=count($result_term)?>></td>
			</tr>
		</table>
	</div>
	
	
<?php
	//セッションのセット
	unset($_SESSION['cld_frag']);
	//echo $date->format('Y-m-d');
	unset($_SESSION['date']);
	$_SESSION['date'] = $date;
	//echo $_SESSION['date']->format('Y-m-d');
	$_SESSION['rowMax'] = $rowMax;

?>
	<style>
		div:empty{
			display: none;
		}


		/********************************************************************
		施設情報
		********************************************************************/
		.roominfo,
		.roominfo th,
		.roominfo td{
			border-collapse: collapse;
			border: 1px solid black;
		}
		.roominfo th{
			background: #f2f2f2;
		}

		.roominfo td{
			text-align: left;
		}

		/********************************************************************
		カレンダー
		********************************************************************/
		.cld{
			/*overflow-x: auto;*/
			/*overflow-y: hidden;*/
			width: 100%;
			padding-top: 10px;
			margin-bottom: 30px;
		}
		.cld table{
			margin: 0;
			border-spacing: 0;
			border-collapse: collapse;
			width: 100%;
		}
		.cld th{
			min-width: 45px;
			height: 65px !important;
			font-size: 15px !important;
			white-space: nowrap;
			text-align: center;
			border: 1px solid black;
			background: #f2f2f2;
			position: sticky;
			top: -1px;
			z-index: 4;
		}
		.cld th:nth-child(n+3)::before{
			content: attr(start-time);
			position: absolute;
			top: 42px;
			left: 0px;
			/*transform: translateX(-50%);*/
			background-color: white;
			padding: 0px 1px;
			border: 1px solid black;
			font-size: 12px;
			white-space: nowrap;
			z-index: 6;
		}
		.cld th:nth-child(1),.cld th:nth-child(2){
			z-index: 5;
		}
		.cld td{
			border: 1px solid black;
			text-align: center;
			/*white-space: nowrap;*/
			background: #FFF;
			padding: 5px;
		}
		th.sticky2,
		td.sticky2{
			white-space: nowrap;
			position: sticky;
			min-width: 75px;
			left: 0px;
			z-index: 1;
		}
		.sticky2::before{
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			border: 1px solid #000000;
			background: #c4c3c2;
			z-index: -1;
		}
		th.sticky3,
		td.sticky3{
			white-space: nowrap;
			position: sticky;
			left: 75px;
			z-index: 1;
		}
		.sticky3::before{
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			border: 1px solid #000000;
			background: #c4c3c2;
			z-index: -1;
		}
		.link-button{
			background: none;
			color: blue;
			border: none;
			padding: 0;
			font: inherit;
			cursor: pointer;
			text-decoration: underline;
		}
	</style>

	<!--リンク(◀▶)押下時のイベント処理-->
	<script type="text/javascript">
		function cld_back() {
			updateSession('back');
		}

		function cld_next() {
			updateSession('next');
		}
		
		function updateSession(value) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "/wp-content/themes/cocoon-child-master/rsv-functions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			xhr.send('cld-session-frag=' + value);
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
					location.href = location.href;
				}
			}
		}
		
		/*function updateSession2(info) {
			//var info = linkElement.getAttribute('data-info');
			
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
		}*/
		
		function updateSession2(info) {
			
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "/wp-content/themes/cocoon-child-master/rsv-functions.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
			xhr.send('detailed-info-frag=' + info);
			xhr.onload = function(){
				if(xhr.status!=200){
					alert('errorxxx');
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
	
<?php
}
?>
