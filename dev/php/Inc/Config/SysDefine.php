<?php
/**
 *@fileoverview: 系统配置 只有万年不变的东西才写在这里
 *@important 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Wed Apr 27 02:43:17 GMT 2011
 *@copyright: sina
 */


    
    //LOG目录
    define('SYSDEF_LOG_ROOT_PATH' , '/www/web_logs/trip/');
    //数据目录 主要存放统计数据之类的
    define('SYSDEF_DATA_ROOT_PATH' , '/www/web_data/trip/');
    //
    define('SYSDEF_PRIVDATA_ROOT_PATH' , '/www/web_data/trip/');
    //
    define('SYSDEF_CACHE_ROOT_PATH' , '/www/web_data/trip/');
    
    define('SYSDEF_TTF_ROOT_PATH' , '/www/web_data/trip/');



//调试LOG
define('SYSDEF_LOG_DEBUG_PATH' , SYSDEF_LOG_ROOT_PATH.'debug');
//监控LOG
define('SYSDEF_LOG_MONITOR_PATH' , SYSDEF_LOG_ROOT_PATH.'MONITOR');
//数据统计LOG
define('SYSDEF_LOG_DATA_PATH' , SYSDEF_LOG_ROOT_PATH.'data');
//行为统计LOG
define('SYSDEF_LOG_ACTION_PATH' , SYSDEF_LOG_ROOT_PATH.'action');
//LOG字段分隔符
define('SYSDEF_LOG_SEPARATE' , "\t");
?>