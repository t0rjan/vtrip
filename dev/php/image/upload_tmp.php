<?php
include_once '../__global.php';
include(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');



class aj_upload extends ml_controller {
    
    private $width;
    private $height;
    
    
    function initParam() {
        
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
        $this->width = $info[0];
        $this->height = $info[1];


        if(!$this->check_permission(ML_PERMISSION_LOGIN_ONLY)) {

            $this->api_output(ML_RCODE_NOLOGIN);
        }
    }
    
    function main() {

        $filename = dechex(microtime(true)*100).'_'.Tool_ip::get_real_ip_int();
        $save_path = ML_IMG_DIR_TEMP;

        $o = new Lib_uploader();
        $o->start('file');
        $o->set_file_name($filename);
        $o->set_save_dir($save_path);
        $o->save();
        
        
        $this->api_output(ML_RCODE_SUCC , array('name' => $filename,'w'=>$this->width,'h'=>$this->height));
    }
}


new aj_upload();
