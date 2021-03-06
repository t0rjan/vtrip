<?php
/**
 *@fileoverview: [群博客] 数据库配置
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 01:48:23 GMT 2010
 *@copyright: sina
 */


return array(
    'trip' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'vt_trip'
    ),
    'trip_ext' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'vt_trip_ext'
    ),
    'trip_photo' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'vt_trip_photo'
    ),
    'trip_comment' => array(
        'connect' => array(
            'master' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            ),
            'slave' => array(
                'host' => array(
                    0 => 'localhost:3306'
                ),
                'user' => 'trip',
                'pw' => 'cucued',
                'name' => 'trip',
            )
        ),
        'tb_n' => 1,
        'tb_prefix' => 'vt_trip_comment'
    ),
);
