<?php
	include('../__global.php');
	ini_set('display_errors', 1);
	error_reporting(E_ALL^E_NOTICE);
	$oUser = new ml_model_dbUser();

	if($_GET['act'] == 'add_user')
	{
		
		
		$oUser->addUser($_POST['nick']);
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
#创建用户<br/>
<form action="?act=add_user" method="post">
	<input type="text" name="nick" />
	<input type="submit" value="创建"/>
</form>
<br/><br/><br/>
<?php
	$oUser->listUser();
	$rows = $oUser->get_data();
	foreach ($rows as $key => $value) {
?>
	#<?php echo $value['id']; ?> 昵称:<?php echo $value['nick']; ?> <a href="trip.php?uid=<?php echo $value['id'] ?>">旅行</a><br/>
<?php
	}
?>