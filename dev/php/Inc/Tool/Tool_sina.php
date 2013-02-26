<?php
/**
 *@fileoverview: 
 *@important 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Wed Apr 27 06:43:23 GMT 2011
 *@copyright: sina
 */

class Tool_sina
{
    /**
     * 分表
     *
     * @param int $u    uid
     * @param int $n    分多少个表
     * @return int
     */
    public static function calc_hash_tbl($u, $n = 128)
    {
        $h  = sprintf("%u", crc32($u));
        $h1 = intval($h / $n);
        $h2 = $h1 % $n;
        return self::n2tbNo($h2);
//        $h3 = base_convert($h2, 10, 16);
//        $h4 = sprintf("%02s", $h3);
    
//        return $h4;
    }
    /**
     * 分库
     *
     * @param int $u    uid
     * @param int $s    分多少个库
     * @return int 
     */
    public static function calc_hash_db($u, $s = 4)
    {
        $h  = sprintf("%u", crc32($u));
        $index = intval(fmod($h, $s));
        return $index;
    }

    public static function n2tbNo($int)
    {
        $h3 = base_convert($int, 10, 16);
        return sprintf("%02s", $h3);
    }
}



?>