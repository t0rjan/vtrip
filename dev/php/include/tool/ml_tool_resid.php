<?php
/**
 * 生成资源ID用的。
 * @author leo
 *
 */
class ml_tool_resid
{
    static public function make_resid($uid , $type , $id)
    {
        return str_pad(dechex($uid),8,0,STR_PAD_LEFT)
              .str_pad($type,2,0,STR_PAD_LEFT)
              .str_pad(base_convert($id,10,36),7,0,STR_PAD_LEFT);
    }
    
    static public function resid2uid($resid)
    {
        return hexdec(substr($resid , 0 , 8));
    }
    
    static public function resid2type($resid)
    {
        return (int)substr($resid , 8 , 2);
    }
    
    static public function resid2id($resid)
    {
        return base_convert(substr($resid , 10) , 36 , 10);
    }
    
    static public function is_resid($resid)
    {
        return preg_match("/^[0-9a-f]{8}[0-9]{2}[0-9a-z]{7}$/" , $resid);
    }
    
    
    /**
     * 获取 nick 的 key
     * @param unknown_type $rs
     * @return string
     */
    static function getNickKey($nick) {
        return 'n2u_'.self::str_hash($nick);
    }
    static function str_hash($str)
    {
        return base_convert(sprintf("%u", crc32($str)) , 10 , 36)
               .substr(base_convert(md5($str) , 16 , 36) , 0 , 4)
               .base_convert(ord($str{0}) . ord($str{3}).ord($str{(strlen($str) - 1)}) , 10 , 36);    
    }

}

//$a =  ml_tool_resid::make_resid(13123,01,23454234);
//echo ml_tool_resid::resis2id($a);
