<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php /* Template Name: rsv-admin-screen */ ?>
<?php get_header(); ?>
<?php //require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>


<?php

	if (isset($_SESSION['login'])){
			$_SESSION['user'] = $_POST['user'];
			$_SESSION['password'] = $_POST['password'];
			require 'login-output.php';
			unset($_SESSION['login']);
	}
	
	if(isset($_SESSION['administrator'])){
		echo '<p>'.$_SESSION['administrator']['name'].'さん　ログイン中</p>';
?>

		<h1>道泉施設予約　管理画面</h1>
		<div class="tab-group">
			<ul class="tab-button">
				<li class="tab tab-01 is-active">予約情報</li>
				<li class="tab tab-02">検索</li>
				<li class="tab tab-03">新規予約</li>
				<li class="tab tab-04">休日設定<br>(未)</li>
				<li class="tab tab-05">ログアウト</li>
			</ul>
			<div class="tab-contents">
				<div class="contenta tab-01 is-display"><?php require 'admin-rsv-cld.php'; ?></div>
				<div class="contenta tab-02">
					
					
					<iframe src="/wp-content/themes/cocoon-child-master/admin-temp-rsv.php" name="temp" width="100%" height="1000px">
					</iframe>
					
					
					<?php //require 'admin-temp-rsv.php'; ?>
				</div>
				<div class="contenta tab-03">
					<?php //require 'admin-new-rsv.php'; ?>
					<iframe src="/wp-content/themes/cocoon-child-master/admin-new-rsv.php" name="temp" width="100%" height="1000px">
					</iframe>
				</div>
				<div class="contenta tab-04">利用できない日の設定等　要要件確認により現在作成中</div>
				<div class="contenta tab-05"><?php require 'logout.php'; ?></div>
			</div>
		</div>
		
		<style>
			.tab-group {
				margin-top: 20px;
				width: 100%;
				list-style: none;
				text-align: center;
			}
			.tab-button {
				padding-left: 0;
				margin: 0;
				display: flex;
				justify-content: space-between;
				list-style: none;
				cursor: pointer;
			}
			.tab-button .is-active {
				background: #4bab80;
				border: 2px solid #4bab80;
				color: #FFF;
			}
			
			.tab {
				width: calc(100% / 5);
				border: 2px solid #ccebdc;
				padding: 8px;
				border-bottom: none;
			}
			
			.tab-contents {
				border-top: none;
				background: #ccebdc;
			}
			
			.contenta {
				display: none;
				padding: 8px;
				text-align: left;
			}
			
			.contenta.is-display {
				display: block;
				border: 2px solid #4bab80;
				height: 100%;
			}
		
		</style>
		
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				const targets = document.getElementsByClassName('tab');
				for (let i = 0; i < targets.length; i++) {
					targets[i].addEventListener('click', changeTab, false);
				}

				function changeTab() {
					document.getElementsByClassName('is-active')[0].classList.remove('is-active');
					this.classList.add('is-active');
					document.getElementsByClassName('is-display')[0].classList.remove('is-display');
					const arrayTabs = Array.prototype.slice.call(targets);
					const index = arrayTabs.indexOf(this);
					document.getElementsByClassName('contenta')[index].classList.add('is-display');
				};
			}, false);
			
		</script>

<?php
	}else{
		$_SESSION['login'] = true;
		require 'login-input.php';
			
	}
	

?>


<?php get_footer(); ?>
<?php //require_once 'rsv-footer.php'; ?>
