<?php
/** 
 * @fileoverview     
 * @important        
 * @author:            shaopu@staff.sina.com 
 * @date            Tue Apr 26 07:46:51 GMT 2011
 * @package         INC 系统基础包，不能随意修改和下线
 */


//php基础设置
date_default_timezone_set('Asia/Shanghai');
//set_magic_quotes_runtime(0);



//项目根目录
define('SERVER_ROOT_PATH',str_replace('\\','/',substr(dirname(__FILE__),0,-4)));
define('IN_SYSTEM',true);
//系统调试开关
define('SYSDEF_DEBUG' , true);


if (SYSDEF_DEBUG){
    ini_set('display_errors' , 1);
    error_reporting(E_ALL ^ E_NOTICE);    
    if(extension_loaded('xhprof'))
        xhprof_enable();
}
require_once(SERVER_ROOT_PATH.'/Inc/Config/ServerTypeDefine.php');
require_once(SERVER_ROOT_PATH.'/Inc/Config/SysDefine.php');
require_once(SERVER_ROOT_PATH.'/Inc/Config/SinaDefine.php');




$aIncProjectSign = array(
    'ml' => 'include',              //当前根目录下
    'pg' => 'page'
);


/**
 * 自动加载
 * *通用规则
 * 类名=目录+下划线
 * 文件名=类名
 * *INC
 * 目录必须是首字母大写
 * *具体项目
 * 在$aIncProjectSign中定义项目缩写及库文件存在目录
 *
 * @param unknown_type $classname
 */
function __inc_autoload($classname)
{
    global $aIncProjectSign;
    
    
    $class_explode = explode('_' , $classname);
    $root_dir = '';
    //首字母大写的是INC级别的
    if(preg_match('/^[A-Z]/' , $class_explode[0]))
    {
        array_pop($class_explode);
        foreach ($class_explode as &$key)
        {
            $key = ucfirst($key);
        }
        $file_path = SERVER_ROOT_PATH.DIRECTORY_SEPARATOR.'Inc'.DIRECTORY_SEPARATOR;
        $file_path .= implode(DIRECTORY_SEPARATOR , $class_explode).DIRECTORY_SEPARATOR.ucfirst($classname).'.php';
    }
    //其他的为项目级别的 第一个是ml就在include下
    else if(isset($aIncProjectSign[$class_explode[0]]))
    {
        $project_sign = array_shift($class_explode);
        array_pop($class_explode);
        $file_path = SERVER_ROOT_PATH . DIRECTORY_SEPARATOR . $aIncProjectSign[$project_sign] . DIRECTORY_SEPARATOR;
        $file_path .= implode(DIRECTORY_SEPARATOR , $class_explode) . DIRECTORY_SEPARATOR . $classname.'.php';
    }
    else 
        die('system error autoload 43 class: ' . $classname);
    

    if(is_file($file_path))
        include($file_path);
    else 
    {
        return false;
        //die('system error autoload 37');
    }
}
spl_autoload_register('__inc_autoload');


?>
