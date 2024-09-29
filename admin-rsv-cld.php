<?php if(session_status() == PHP_SESSION_NONE){session_start();}?>
<?php require_once 'rsv-header.php'; ?>
<?php require_once 'rsv-functions.php'; ?>

<?php
$date = cldDateSet();
$db = dbInit();
?>

<iframe src="/wp-content/themes/cocoon-child-master/default.php" name="info" width="100%" height="300" style="resize: vertical;">
</iframe>

<?php
//カレンダー表示(db情報, 開始日, 日付表示数, 表示モード,実行ファイルパス)
showCalendar($db, $date, 7, 'admin',basename(__FILE__));

?>



<?php require_once 'rsv-footer.php'; ?>
