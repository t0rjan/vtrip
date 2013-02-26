<?php
/**
 *@fileoverview: [群博客] 数据库配置
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 01:48:23 GMT 2010
 *@copyright: sina
 */
include(SERVER_ROOT_PATH.'/include/config/ml_dbOtherSrv.php');

return array(
    'admin' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => $_SERVER['SINASRV_DB_HOST'].':'.$_SERVER['SINASRV_DB_PORT']
                ),
                'user' => $_SERVER['SINASRV_DB_USER'],
                'pw' => $_SERVER['SINASRV_DB_PASS'],
                'name' => $_SERVER['SINASRV_DB_NAME'],
            ),
            'slave' => array(
                'host' => array(
                    0 => $_SERVER['SINASRV_DB_HOST_R'].':'.$_SERVER['SINASRV_DB_PORT_R']
                ),
                'user' => $_SERVER['SINASRV_DB_USER_R'],
                'pw' => $_SERVER['SINASRV_DB_PASS_R'],
                'name' => $_SERVER['SINASRV_DB_NAME_R'],
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => ''
    ),
);
