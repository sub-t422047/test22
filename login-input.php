<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-functions.php'; ?>


		<form action="<?=get_permalink(12024)?>" method="post">
			<div>ログイン名<input type="text" name="user"></div>
			<div>パスワード<input type="password" name="password"></div>
			<div><input type="submit" value="管理者ログイン"></div>
		</form>
