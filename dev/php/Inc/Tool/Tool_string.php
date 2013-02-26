<?php
/**
 *@fileoverview: 公用字符串处理类
 *@important 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Jul 20 13:13:32 GMT 2010
 *@copyright: sina
 */
class Tool_string
{
//编码转换~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
    * 转换字符串编码 从GB转到UTF-8
    *
    * @param string $str
    * @return string
    */
    function gb2utf($str)
    {
        return iconv('gbk' , 'utf-8//IGNORE' , $str);
    }
    /**
    * 转换字符串编码 从UTF-8到GBK
    * 对非GBK所能包含的字符转为HTML实体
    *
    * @param string $str
    * @return string
    */
    function utf2gb($string)
    {
        //造一个函数
        $call_back = create_function('$arr' , '
        $c = (int)$arr[1];  
        if(($c >= 19968 && $c <= 40869)       //中文字符
            || ($c >= 65280 && $c <= 65374)    //中文符号
            || ($c >= 12288 && $c <= 12585)   //中文符号
            || ($c >= 1040 && $c <= 1103)   //中文符号
            || ($c >= 65072 && $c <= 65131)   //中文符号
            || ($c >= 9472 && $c <= 9621))   //中文符号
        {
            return mb_convert_encoding($arr[0] , "GBK" ,"HTML-ENTITIES");
        }
        return $arr[0];
        ');

        $string = mb_convert_encoding($string , 'HTML-ENTITIES' , 'UTF-8');
        $string = preg_replace_callback("|&#([0-9]{1,5});|" , $call_back , $string);
        $string = self::htmlEntitiesSymbol_2_gbk($string);
        return $string;
    }
    
//过滤~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    /**
     * 过滤字符串中的HTML标记 < >
     * @param string $str 需要过滤的字符
     * @return string
     */
    function un_html($str)
    {
        $s    = array(
        "&"     => "&amp;",
        "<"    => "&lt;",
        ">"    => "&gt;",
        "\n"    => "<br>",
        "\t"    => "&nbsp;&nbsp;&nbsp;&nbsp;",
        "\r"    => "",
        " "    => "&nbsp;",
        "\""    => "&quot;",
        "'"    => "&#039;",
        );
        $str = self::esc_korea_change($str);
        $str = strtr($str, $s);
        $str = self::esc_korea_restore($str);
        return $str;
    }
    /**
     * 数据在放置在input时，使用本方法过滤，防止恶意数据导致页面错乱。
     *
     * @param string $str
     * @return string
     */
    function esc_edit_html($str)
    {
        $s    = array(
        //"&"     => "&amp;",
        "<"        => "&lt;",
        ">"        => "&gt;",
        "\""    => "&quot;",
        "'"        => "&#039;",
        );
        $str = self::esc_korea_change($str);
        $str = strtr($str, $s);
        $str = self::esc_korea_restore($str);
        return $str;
    }
    /**
     * 过滤空字符
     *
     * @param string $str
     * @return string
     */
    function esc_ascii($str)
    {
        $esc_ascii_table = array(
        chr(0),chr(1), chr(2),chr(3),chr(4),chr(5),chr(6),chr(7),chr(8),
        chr(11),chr(12),chr(14),chr(15),chr(16),chr(17),chr(18),chr(19),
        chr(20),chr(21),chr(22),chr(23),chr(24),chr(25),chr(26),chr(27),chr(28),
        chr(29),chr(30),chr(31)
        );

        $str = str_replace($esc_ascii_table, '', $str);
        return $str;
    }
    /**
     * 用于URL的字符串过滤
     * 只保留中文，英文字符和数字
     * 只适用于分类名，标签等短字符串。
     *
     * @param string $str
     * @return string
     */
    function esc_str4url($str)
    {
        $tmp = mb_convert_encoding($str , 'HTML-ENTITIES' , 'UTF-8');
        preg_match_all('/(&#?[0-9a-z]{2,7};|[0-9a-zA-Z])/' , $tmp , $aRs);
        $rs = '';
        if(count($aRs[1])>0)
        {
            foreach($aRs[1] as $char)
            {
                if($char{0} == '&')
                {
                    $chrnum = trim($char , '&#;');
                    if(is_numeric($chrnum) && $chrnum >= 19968 && $chrnum <= 40869)
                        $rs .= mb_convert_encoding($char , 'UTF-8' , 'HTML-ENTITIES');
                }
                else
                    $rs .= $char;
            }
        }
        return String::htmlEntitiesSymbol_2_gbk($rs);
    }
    
//字符串判断~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~



    
    
//字符串操作~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~`

    /**
     * 取中文字符串长度 中文算一个
     *
     * @param string $str
     * @return int
     */
    function count_all_character($str , $encoding = 'utf-8')
    {
        $str = preg_replace("/&(#\d{3,5});/", "_", $str);
        $str = preg_replace("/&([a-z]{2,7});/", "_", $str);
        return mb_strlen($str , $encoding);
    }
    /**
     * 计算字符串宽度
     * 
     *
     * @param string $str
     * @param string $encoding
     * @return int
     */
    function str_width($str , $encoding = 'utf-8')
    {
        return mb_strwidth($str , $encoding);
    }
    /**
     * 将合法的网址过滤成<a链接形式
     *
     * @param string $s
     * @return string
     */
    function autolink($s)
    {
        $s_copy = str_replace('&nbsp;' , ' ' , $s);
        preg_match_all('/((http|https|news|ftp):\/\/\w+[^\s<>\[\]]+)/i' , $s_copy , $b);
        if(is_array($b[1]) && count($b[1]))
        {
            foreach ($b[1] as $url)
            $s = str_replace($url , "<a href=\"".$url."\" target=\"_blank\" title=\"".$url."\">".$url."</a>" , $s);
        }
        return $s;
        
        $s = preg_replace("/(^|\s|[^\w])([\.\w\-]+\@[\.\w\-]+\.[\.\w\-]+)/i", "\\1<a href=\"mailto:\\2\">\\2</a>", $s);

        //不知道 (^|\s)是干嘛的 在这种情况失败 赶快点击查看>>>http://t.cn/zOkWwcu 欢迎关注
        //$s = preg_replace("/(^|\s)((http|https|news|ftp):\/\/\w+[^\s<>\[\]]+)/i", "<a href=\"\\2\" target=\"_blank\">\\2</a>", $s);
        $s = preg_replace("/((http|https|news|ftp):\/\/\w+[^\s<>\[\]]+)/i", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $s);
        return $s;

    }
    /**
     * 将过长单词进行切割
     *    
     * @param string $str
     * @return string
     */
    function html_word_cut($str , $num = 24)
    {
        $str = str_replace(array('&nbsp;'),array('&nbsp;<wbr>'),$str);

        $p = "/([a-zA-Z0-9]{".$num."})/";
        $pp = "/(<.*)&wbr&{1}(.*>)/U";
        $check_str = strip_tags($str);
    
        preg_match_all($p,$check_str,$mm);
        if (count($mm[0]) == 0) return $str;
        
        $str = preg_replace($p,"\\1&wbr&",$str);
        //$str = preg_replace($pp,"\\1\\2",$str);
        $str = preg_replace("/(<)([^>]*>)/e","'\\1'.str_replace('&wbr&','','\\2')",$str);
        $str = str_replace('\"','"',$str);
        return str_replace("&wbr&",'<wbr>',$str);
    }
    /**
     * 字符串截取 按字符宽度进行截取
     *
     * @param string $str
     * @param int $start
     * @param int $offset
     * @param string $encoding 编码方式：'gbk' 'utf-8'...
     * @return string
     */
    function substr_by_width($str , $start , $offset , $encoding = 'utf-8',$end = '')
    {
        if (!function_exists("mb_strimwidth"))
        {
            return $str ;
        }
        return mb_strimwidth($str , $start , $offset , $end , $encoding);
    }
    /**
     * 中文字符串截取 按字数截取
     * 一个中文算一个
     *
     * @param string $str
     * @param int $start
     * @param int $offset
     * @param string $encoding 编码方式：'gbk' 'utf-8'...
     * @return string
     */
    function substr_by_charater($str , $start , $offset , $encoding = 'utf-8')
    {
        if (!function_exists("mb_substr"))
        {
            return $str ;
        }

        return mb_substr($str , $start , $offset , $encoding);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $str
     * @return unknown
     */
    function esc_korea_change($str)
    {
        $str = preg_replace("/&(#?[0-9a-zA-Z]{2,7});/", "__sina_\\1_word__", $str);
        return $str;
    }
    /**
     * 把字符串中已经转换省略形态的韩文,恢复成韩文.eg:__sina_#44444_word__->&#44444
     * @param string $str
     * @return string
     */
    function esc_korea_restore($str)
    {
        $str = preg_replace("/__sina_(#?[0-9a-zA-Z]{2,7})_word__/U", "&\\1;", $str);
        return $str;
    }
    /**
     * 在utf2gb函数中，中文符号会被换成实体，本函数负责替换回ＧＢＫ．
     * 
     *
     * @param unknown_type $string
     */
    function htmlEntitiesSymbol_2_gbk($string)
    {
        $arr_replace = array("&Alpha;" => "Α",
                    "&Gamma;" => "Γ",
                    "&Epsilon;" => "Ε",
                    "&Eta;" => "Η",
                    "&Iota;" => "Ι",
                    "&Lambda;" => "Λ",
                    "&Nu;" => "Ν",
                    "&Omicron;" => "Ο",
                    "&Rho;" => "Ρ",
                    "&Tau;" => "Τ",
                    "&Phi;" => "Φ",
                    "&Psi;" => "Ψ",
                    "&alpha;" => "α",
                    "&gamma;" => "γ",
                    "&epsilon;" => "ε",
                    "&eta;" => "η",
                    "&iota;" => "ι",
                    "&lambda;" => "λ",
                    "&nu;" => "ν",
                    "&omicron;" => "ο",
                    "&rho;" => "ρ",
                    "&sigma;" => "σ",
                    "&upsilon;" => "υ",
                    "&chi;" => "χ",
                    "&omega;" => "ω",
                    "&uarr;" => "↑",
                    "&darr;" => "↓",
                    "&radic;" => "√",
                    "&infin;" => "∞",
                    "&and;" => "∧",
                    "&cap;" => "∩",
                    "&int;" => "∫",
                    "&asymp;" => "≈",
                    "&equiv;" => "≡",
                    "&ge;" => "≥",
                    "&Beta;" => "Β",
                    "&Delta;" => "Δ",
                    "&Zeta;" => "Ζ",
                    "&Theta;" => "Θ",
                    "&Kappa;" => "Κ",
                    "&Mu;" => "Μ",
                    "&Xi;" => "Ξ",
                    "&Pi;" => "Π",
                    "&Sigma;" => "Σ",
                    "&Upsilon;" => "Υ",
                    "&Chi;" => "Χ",
                    "&Omega;" => "Ω",
                    "&beta;" => "β",
                    "&delta;" => "δ",
                    "&zeta;" => "ζ",
                    "&theta;" => "θ",
                    "&kappa;" => "κ",
                    "&mu;" => "μ",
                    "&xi;" => "ξ",
                    "&pi;" => "π",
                    "&tau;" => "τ",
                    "&phi;" => "φ",
                    "&psi;" => "ψ",
                    "&isin;" => "∈",
                    "&sum;" => "∑",
                    "&prop;" => "∝",
                    "&ang;" => "∠",
                    "&or;" => "∨",
                    "&cup;" => "∪",
                    "&there4;" => "∴",
                    "&ne;" => "≠",
                    "&le;" => "≤",
                    "&larr;" => "←",
                    "&rarr;" => "→",
                    "&curren;" => "¤",
                    "&hellip;" => "…",
                    "&plusmn;" => "±",
                    "&deg;" => "°",
                    "&mdash;" => "—",
                    "&ldquo;" => "“",
                    "&rdquo;" => "”",
                    "&middot;" => "·",
                    "&lsquo;" => "‘",
                    "&rsquo;" => "’");
        $string = strtr($string , $arr_replace);
        return $string;
    }

    public function isUtf8($string)
    {
        return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string) > 0;
    }
    

    
    
}
