<?php
/**
 *@fileoverview: [群博客] http头 输出 相关
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 13:31:59 GMT 2010
 *@copyright: sina
 */
    class ml_tool_httpheader
    {
        static function no_cache()
        {
            if (!headers_sent())
            {
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Data in the past 
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); //Modified 
                header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
                header('Cache-Control: post-check=0, pre-check=0', false);
                header('Pragma: no-cache'); // HTTP/1.0
            }
            return;
        }
        static function always_cache()
        {
            if (!headers_sent())
            {
                header('Expires: Mon, 26 Jul 2019 05:00:00 GMT');
                header('Last-Modified: Sat, 24 Dec 1983 08:00:00 GMT'); //Modified 
                header('Cache-Control: max-age=7776000'); // HTTP/1.1 
                header('Pragma: public'); // HTTP/1.0
            }
            return;
        }
    }