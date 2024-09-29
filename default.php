<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>

<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>

<?php
	if(isset($_SESSION['drop'])){
		echo '予約を取り消しました';
		unset($_SESSION['drop']);
	}
	if(isset($_SESSION['new'])){
		echo '新規予約が完了しました';
		unset($_SESSION['new']);
	}
?>
	<h1>詳細情報</h1>
	<p>予約をクリックすると<br>予約詳細情報が表示されます</p>


<?php require_once 'rsv-footer.php'; ?>
