<?php
include_once '../__global.php';
include(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');

class aj_upload extends ml_controller {
    
    private $url;
    private $uid;
    
    function initParam() {
        $this->url = $this->input('url');
        $this->uid = $this->input('uid');
    }
    function checkParam() {
        

        
    }
    
    function main() {

        
        

        $fp = fopen($this->url , 'br');
        while (!feof($fp)) {
            $bin .= fgets($fp);
        }
        
        fclose($fp);


        $tmp_path = SYSDEF_DATA_ROOT_PATH.'fetchpictmp/'.md5($this->url);
        $dir = dirname($tmp_path);
        if(!is_dir($dir))
            mkdir($dir);

        $ffp = fopen($tmp_path , 'w');
        fwrite($ffp, $bin);
        fclose($ffp);
        chmod($tmp_path, '0777');

        $a = getimagesize($tmp_path);
        $w = $a[0];
        $h = $a[1];


        $pid = ml_tool_picid::uid2pid($this->uid , $w,$h);

        $save_path = ml_tool_picid::pid2filepath($pid , true);
        $dir = dirname($save_path);
        if(!is_dir($dir))
        {
            if(!mkdir($dir , 0777 , true))
            {
                $this->api_output(ML_RCODE_BUSY , $data);
            }
        }

        $path = pathinfo($save_path);

        $rs = rename($tmp_path, $save_path);

        $data = array(
            'pid' => $pid,
        );
        $this->api_output(ML_RCODE_SUCC , $data);
    }
}


new aj_upload();
