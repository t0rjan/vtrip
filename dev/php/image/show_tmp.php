<?php
include_once '../__global.php';
include(SERVER_ROOT_PATH.'/include/config/ml_image_config.php');

if(preg_match('/[0-9a-z_]+/', $_GET['name']))
{
    $filepath = ML_IMG_DIR_TEMP.'/'.$_GET['name'];

    $info = getimagesize($filepath);
        
    header("Content-Type: ".$info['mime']);
    header("Content-Length: " . filesize($filepath));
    
    $fp = fopen($filepath , 'rb');
        
    fpassthru($fp);
}
?>