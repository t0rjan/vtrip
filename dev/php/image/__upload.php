<?php
include_once '../__global.php';
include_once(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');



class aj_upload extends ml_controller {
    
    private $w,$h;
    private $uid;
    
    function initParam() {
        $this->uid = $this->input('uid');
    }
    function checkParam() {
        
        if($_FILES['file']['size'] > ML_IMG_MAXSIZE)        
        {
            $this->api_output(ML_RCODE_PARAM , '' , 'max_size');
        }

        $info = getimagesize($_FILES['file']['tmp_name']);
        if ($info === false || !in_array($info[2], array(1,2,3))) {
            $this->api_output(ML_RCODE_PARAM , '' , 'invalid file type,only PNG,JPEG,GIF');
        }

        $this->w = $info[0];
        $this->h = $info[1];

    }
    
    function main() {

        $pid = ml_tool_picid::uid2pid($this->uid ,$this->w, $this->h);

        $save_path = ml_tool_picid::pid2filepath($pid , true);

        $path = pathinfo($save_path);

        $o = new Lib_uploader();
        $o->start('file');
        $o->set_file_name($path['filename']);
        $o->set_save_dir($path['dirname']);
        $o->save();
   
        $data= array(
                'pid'=>$pid,
                'thumb'=>ml_tool_picid::pid2url($pid,'thm')
            );
        
        $this->api_output(ML_RCODE_SUCC,$data);
    }
}


new aj_upload();
