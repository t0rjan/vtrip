<?php

include_once '../__global.php';



#ini_set('display_errors', 1);
#error_reporting(E_ALL);


$pid = $_GET['pid'];
$size = $_GET['size'] ? $_GET['size'] : 'big';


$ML_IMG_SIZE_CONFIG = ml_factory::load_standard_conf('imageSize');
if(!isset($ML_IMG_SIZE_CONFIG[$size]))
    die('fdfdf');

/**
    @todo 输出容错图片
*/

$filepath = ml_tool_picid::pid2filepath($pid , ($size == 'big')?true:false , $size);
//if(!is_file($filepath))
{


    $bigpath = ml_tool_picid::pid2filepath($pid , true);
    $conf = $ML_IMG_SIZE_CONFIG[$size];
    $oResizer = new Lib_imageResizer();
    $oResizer->set_source_image($bigpath);

    $path = pathinfo($filepath);
    if(!is_dir($path['dirname']))
        mkdir($path['dirname'],0777 , true);
    $oResizer->set_dest_image($filepath);



    if($conf['type'] == ML_IMG_TYPE_REGULARWIDTH)
    {
        $oResizer->set_mode(Lib_imageResizer::MODE_REGWIDTH);
        $oResizer->set_single_size($conf['width']);
        $oResizer->resize();
    }
    else if($conf['type'] == ML_IMG_TYPE_CROP)
    {
        $oResizer->set_mode(Lib_imageResizer::MODE_CROP);
        $oResizer->set_size($conf['width'] , $conf['height']);
        $oResizer->resize();
    }
}

        $info = getimagesize($filepath);
        
        header("Content-Type: ".$info['mime']);
        header("Content-Length: " . filesize($filepath));
        ml_tool_httpheader::always_cache();
        
        $fp = fopen($filepath , 'rb');
            
        fpassthru($fp);