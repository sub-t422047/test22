<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php /* Template Name: rsv-admin-logout */ ?>
<?php get_header(); ?>
<?php //require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>


<?php	if(isset($_SESSION['administrator'])){
		unset($_SESSION['administrator']);
		echo 'ログアウトしました';
	}
?>


<?php get_footer(); ?>
<?php //require_once 'rsv-footer.php'; ?>
