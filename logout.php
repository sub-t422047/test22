<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>


	<div>ログアウトしますか？</div>
	<form action="<?=get_permalink(12026)?>" method="post">
	<button name="logout">ログアウト</button>
	</form>
		


<?php require_once 'rsv-footer.php'; ?>
