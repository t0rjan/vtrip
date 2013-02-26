<?php
/*********************************************************************************
 Copyright (c) 2008, 新浪网-互动社区-博客
 All rights reserved.
 @程序名称：keyword.class.php
 @程序功能：将所有关键字相关的方法集中于此
 如文章审核关键字
 @作　　者：[REDANT]辛少普
 @版　　本：1.00[V5]
 @修改历史：

 *********************************************************************************/
class Tool_keyword{

    //内部方法
    /**
     * 取关键字数组
     *
     * @param string $type
     * @param string $encoding
     * @return array or false
     */
    public static function _get_keyword_array($type, $encoding = 'gbk', $pos = 'front'){
        if ($pos == 'back'){
            $path = KEYWORD_FILE_PATH_BACK;
        }else{
            $path = KEYWORD_FILE_PATH;
        }
        $suffix = $encoding == 'gbk' ? 'gbk' : 'utf8';
        $file_path = $path . '/keyword_' . $type . '.' . $suffix;
        if (!file_exists($file_path))
        return false;
        $arr = file($file_path);
        if (!is_array($arr) || count($arr) < 1)
        return false;
        foreach ($arr as $k => $v){
            $arr[$k] = trim($v);
        }
        return $arr;
    }

    /**
     * 文章封杀关键字
     *
     * @param string $string
     * @param string $encoding
     * @return bool true通过 false禁止
     */
    public static function check_article_kill_keyword($string, $encoding = 'gbk'){
        $key = 'kill';
        $arr_kw = self::_get_keyword_array($key, $encoding);
        $string = strtolower($string);
        $j = 0;
        if (!is_array($arr_kw))
        return true;
        foreach ($arr_kw as $kw){
            //同时包含两个关键字算不合法
            $kw = strtolower(trim($kw));
            if (strpos($string, $kw) !== false){
//                $j++;
//                if ($j >= 2){
                    return false;
                //}
                continue;
            }
        }
        return true;
    }

    public static  function filterSensitiveWord($string){
        $key = 'kill';
        $arr_kw = self::_get_keyword_array($key, 'utf8');
        $string = strtolower($string);
        $j = 0;
        if (!is_array($arr_kw))
        return true;
        
//        $senWord = ml_factory::load_standard_conf('sensitiveWord');
        $replacements = array_pad(array(),count($arr_kw),"**");;
        return str_replace($arr_kw,$replacements,$string);
    }



}