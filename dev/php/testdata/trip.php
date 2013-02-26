<?php
	include('../__global.php');
	$uid = $_GET['uid'];
	ini_set('display_errors', 1);
	error_reporting(E_ALL^E_NOTICE);
	$oTrip = new ml_model_dbTrip();
	$oExt = new ml_model_dbTripExt();

	if($_GET['act'] == 'add_trip')
	{
		$oTrip->addTripByUid($uid , $_POST['start_date'] , $_POST['days'] , $_POST['title']);
		$id = $oTrip->insert_id();
		$oExt->addExtByTripId($id , $_POST['memo']);
		header('Location:?uid='.$uid);
		die;
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<a href="user.php">用户</a><br/>
<br/><br/>
#创建旅游<br/>
<form action="?act=add_trip&uid=<?php echo $uid; ?>" method="post">
	标题：<input type="text" name="title" /><br/>
	开始时间：<input type="text" name="start_date" value="2009-10-01" /><br/>
	天数：<input type="text" name="days" value="3" /><br/>
	描述：<textarea name="memo"></textarea><br/>
	<input type="submit" value="创建"/>
</form>
<?php
	$oTrip->getTripListByUid($uid);
	$rows = $oTrip->get_data();

	foreach ($rows as $key => $value) {
?>
	#<?php echo $value['id']; ?>
	<?php echo $value['title']; ?>
	<?php echo $value['start_date']; ?>
	<?php echo $value['days']; ?>天
	<a href="photo.php?uid=<?php echo $uid; ?>&trip_id=<?php echo $value['id']; ?>">发图</a>
	<br/>

<?php
	}
?>