<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-functions.php'; ?>


<?php
	unset($_SESSION['administrator']);
	
	$db = dbInit();
	$pdo = new PDO('mysql:
			host='.$db['ip'].';
			dbname='.$db['db'].';
			charset='.$db['char'].';',
			$db['usr'],
			$db['passwd']
			);

	$sql=$pdo->prepare('select * from user where login=? and password=?');
	$sql->execute([$_SESSION['user'], $_SESSION['password']]);
	unset($_SESSION['user']);
	unset($_SESSION['password']);

	foreach($sql as $row){
		$_SESSION['administrator'] = [
			'name'=>$row['name'],
			'login'=>$row['login'],
			'password'=>$row['password']
		];
	}

	if(empty($_SESSION['administrator'])){
		echo 'ログイン名またはパスワードが違います。';
	}

?>

