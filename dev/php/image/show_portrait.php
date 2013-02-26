<?php

include_once '../__global.php';

include(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');

#ini_set('display_errors', 1);
#error_reporting(E_ALL);


$pid = $_GET['uid'];
$size = $_GET['size'] ? $_GET['size'] : 'big';

$sizeConf = ml_factory::load_standard_conf('portraitSize');
if(!isset($sizeConf[$size]))
    die('fdfdf');

/**
    @todo 输出容错图片
*/

$filepath = ml_tool_picid::uid2portraitPath($pid , $size);

if(!is_file($filepath))
{


    $bigpath = ml_tool_picid::uid2portraitPath($pid);
    if(!is_file($bigpath))
        $filepath = SERVER_ROOT_PATH.'/static/images/def_pic/portrait_200.jpg';
    else
    {
        $conf = $sizeConf[$size];
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
        if($conf['type'] == ML_IMG_TYPE_REGULARWIDTH)
        {
            $oResizer->set_mode(Lib_imageResizer::MODE_REGWIDTH);
            $oResizer->set_single_size($conf['width']);
            $oResizer->resize();
        }
    }
}

        $info = getimagesize($filepath);
        
        header("Content-Type: ".$info['mime']);
        header("Content-Length: " . filesize($filepath));
        $fp = fopen($filepath , 'rb');
            
        fpassthru($fp);