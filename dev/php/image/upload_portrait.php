<?php
include_once '../__global.php';
include(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');



class aj_upload extends ml_controller {
    
    private $x;
    private $y;
    private $w;
    private $tmp_name;
    
    
    function initParam() {
        $this->x = abs((int)$this->input('x'));
        $this->y = abs((int)$this->input('y'));
        $this->w = abs((int)$this->input('w'));
        $this->tmp_name = $this->input('tmp_name');
    }
    function checkParam() {
        if(!preg_match('/[0-9a-z_]+/', $this->tmp_name))
            $this->api_output(ML_RCODE_PARAM);
        
    }
    
    function main() {

        $tmp_path = ML_IMG_DIR_TEMP.'/'.$this->tmp_name;
        $save_path = ml_tool_picid::uid2portraitPath($this->__visitor['uid']);
        $dir = dirname($save_path);
        if(!is_dir($dir))
            mkdir($dir , 0777 , true);
        
        
        $oCrop = new Lib_imageCrop();
        $oCrop->set_src_path($tmp_path);
        $oCrop->set_dest_path($tmp_path.'_crop');
        $oCrop->crop($this->x,$this->y,$this->w,$this->w);
        if($this->w==200)
            copy($tmp_path.'_crop' , $save_path);
        else
        {
            $oResize = new Lib_imageResizer();
            $oResize->set_mode(Lib_imageResizer::MODE_REGWIDTH);
            $oResize->set_single_size(200);
            $oResize->set_source_image($tmp_path.'_crop');
            $oResize->set_dest_image($tmp_path.'_resize');
            $oResize->resize();
            copy($tmp_path.'_resize' , $save_path);
        }


        $this->_clear_portrait();
        $this->api_output(ML_RCODE_SUCC);
    }

    function _clear_portrait()
    {
        $conf = array_keys(ml_factory::load_standard_conf('portraitSize'));
        unset($conf[0]);
        foreach ($conf as $key => $value) {
            $path = ml_tool_picid::uid2portraitPath($this->__visitor['uid'] , $value);
            unlink($path);
        }
    }
}


new aj_upload();
