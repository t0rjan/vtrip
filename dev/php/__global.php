<?php
require_once(dirname(__FILE__).'/Inc/Common.inc.php');
require_once(SERVER_ROOT_PATH.'/Inc/Lib/Lib_memSession.php');       //session类
include_once(SERVER_ROOT_PATH.'/include/config/ml_config.php');     //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/ml_api_code.php');    //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/dataRule/ml_datarule_base.php');
// include_once(SERVER_ROOT_PATH.'/include/config/ml_catelog.php');    //品类配置文件
include_once(SERVER_ROOT_PATH.'/include/ml_controller.php');        //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/ml_api_controller.php');        //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/ml_factory.php');            //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/ml_mail_config.php');     //美啦邮件基本定义
include_once(SERVER_ROOT_PATH.'/include/config/ml_stdConf_imageSize.php');     //美啦邮件基本定义


define('ML_CNF_DOMAIN_SNS' , 'trip.gulibaby.com');
define('ML_CNF_DOMAIN_LOOKBOOK' , 'lookbook.meila.com');        
define('ML_CNF_DOMAIN_IMAGE' , 'trip.gulibaby.com');        


define('ML_PAGE_404' , '/page/error_404.php');            //404 页面
define('ML_PAGE_GUANG' , '/guang');                     //逛的主页
define('ML_PAGE_SYSTEM_BUSY' , '/busy.php');             //系统繁忙
define('ML_PAGE_LOGIN' , '/page/login.php');             //登录页
define('ML_PAGE_ACTIVE' , '/page/user/active.php');  //激活页


define('ML_COOKIE_DOMAIN', '.trip.com');            //美啦cookie域



?>