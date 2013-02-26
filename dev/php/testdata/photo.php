<?php
	include('../__global.php');
	include('include.php');
	$uid = $_GET['uid'];
	$trip_id = $_GET['trip_id'];
	ini_set('display_errors', 1);
	error_reporting(E_ALL^E_NOTICE);

	$oTrip = new ml_model_dbTrip();
	$oPhoto = new ml_model_dbTripPhoto();

	if($_GET['act'] == 'add_photo')
	{
		$pid = upload_pic_by_uid($uid , $_FILES['file']['tmp_name']);
		if(!$pid)
			die('upload error');

		$oPhoto->addPhotoByTripId($trip_id , $uid , $_POST['title'] , $pid , $_POST['day'] , $_POST['latitude'] , $_POST['longtitude']);
		header('Location:?uid='.$uid.'&trip_id='.$trip_id);
		die;
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<a href="user.php">用户</a> &lt;&lt;&lt; <a href="trip.php?uid=<?php echo $uid ?>">旅行</a><br/>
<br/><br/>

#创建照片<br/>
<form action="?act=add_photo&uid=<?php echo $uid; ?>&trip_id=<?php echo $trip_id ?>" method="post" enctype="multipart/form-data">
	标题：<input type="text" name="title" /><br/>
	图片:<input type="file" name="file"/><br/>
	第几天:<input type="text" name="day"/><br/>
	经纬度：<input type="text" name="latitude" /><br/><input type="text" name="longtitude" /><br/>
	<input type="submit" value="创建"/>
</form>
<?php
	$oPhoto->listPhotoByTripId($trip_id , $uid);
	$rows = $oPhoto->get_data();
	foreach($rows as $row)
	{
		echo '#'.$row['id'].' '
			.$row['content'].' '
			.'第'.$row['day'].'天 <img src="'.ml_tool_picid::pid2url($row['pic_id'] , 'sqr').'"/><br/>';

	}