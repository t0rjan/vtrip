<?php
define('SERVER_ROOT_PATH' , dirname(dirname(__FILE__)));
require_once(SERVER_ROOT_PATH.'/Inc/Common.inc.php');
include_once(SERVER_ROOT_PATH.'/include/config/ml_config.php');     //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/ml_api_code.php');     //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/ml_queue_name.php');     //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/config/ml_catelog.php');     //群博客基本参数
include_once(SERVER_ROOT_PATH.'/include/ml_factory.php');            //群博客基本参数
include_once(SERVER_ROOT_PATH.'/_queue/mq_class.php');            //群博客基本参数

?>