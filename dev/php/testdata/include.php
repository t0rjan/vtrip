<?php

function upload_pic_by_uid($uid , $tmpname)
{
	$info = getimagesize($tmpname);
	$pid = ml_tool_picid::uid2pid($uid ,$info[0], $info[1]);

    $save_path = ml_tool_picid::pid2filepath($pid , true);

    $path = pathinfo($save_path);

    $o = new Lib_uploader();
    $o->start('file');
    $o->set_file_name($path['filename']);
    $o->set_save_dir($path['dirname']);
    $o->save();

    return $pid;
}

