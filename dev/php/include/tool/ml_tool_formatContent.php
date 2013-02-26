<?php

class ml_tool_formatContent
{
    /**
     * 格式化内容
     * @gaojian3
     * @param unknown_type $content
     * @return unknown
     */
    static $oBizAtnick;
    static $oModelmcTinyurl;
    static public function format_content($content)
    {
        
        if(!isset(self::$oBizAtnick)){
            self::$oBizAtnick = new ml_biz_user_atNick();
        }
        
        $content = self::$oBizAtnick->start($content);
        $content=Tool_string::un_html($content);
        $content=self::autolink($content);
        $content=ml_tool_keyword::filterSensitiveWord($content);
        $content=ml_tool_expression::haveExpressionImg($content);
        
        $content=self::$oBizAtnick->over($content);
        return $content;
    }
    
    static public function autolink($s)
    {
        $s_copy = str_replace('&nbsp;' , ' ' , $s);
        preg_match_all('/((http|https|news|ftp):\/\/\w+[^\s<>\[\]]+)/i' , $s_copy , $b);
        if(is_array($b[1]) && count($b[1]))
        {
            foreach ($b[1] as $url)
            {
                if(!strpos($url, SITE_ROOT_URL."/t/")){
                    if (! isset ( self::$oModelmcTinyurl )) {
                        self::$oModelmcTinyurl = new ml_model_mcTinyurl ();
                    }
                    $tinyurl = SITE_ROOT_URL . ml_tool_urlMaker::tinyurl ( self::$oModelmcTinyurl->longurl2tiny ( $url ) );
                    $s = str_replace($url , "<a href=\"".$tinyurl."\" target=\"_blank\" title=\"".$tinyurl."\" class=\"ml_textf\">".$tinyurl."</a>" , $s);
                }else{
                    $s = str_replace($url , "<a href=\"".$url."\" target=\"_blank\" title=\"".$url."\" class=\"ml_textf\">".$url."</a>" , $s);
                }
                
            }
        }
        $s = str_replace("#爱美啦第二季·晒闺蜜照#" , "<a href='/activity' target=\"_blank\" title=\""."#爱美啦第二季·晒闺蜜照#"."\" class=\"ml_textf\">"."#爱美啦第二季·晒闺蜜照#"."</a>" , $s);//暂时就一个，多了时候读配置文件
                
        return $s;
    }
    static public function repalcelink($s) {
        if (! isset ( self::$oModelmcTinyurl )) {
            self::$oModelmcTinyurl = new ml_model_mcTinyurl ();
        }
        preg_match_all ( '/((http|https|news|ftp):\/\/\w+[^\s<>\[\]]+)/i', $s, $b );
        if (is_array ( $b [1] ) && count ( $b [1] )) {
            foreach ( $b [1] as $url ) {
                $tiny = SITE_ROOT_URL . ml_tool_urlMaker::tinyurl ( self::$oModelmcTinyurl->longurl2tiny ( $url ) );
                $s = str_replace ( $url, $tiny, $s );
            }
        }
        return $s;
    
    }
    static public function repalcegdclick($s, $gd_tinyurl, $gd_click_tinyurl) {
        $gd_tinyurl = SITE_ROOT_URL . ml_tool_urlMaker::tinyurl ( $gd_tinyurl );
        $gd_click_tinyurl = SITE_ROOT_URL . ml_tool_urlMaker::tinyurl ( $gd_click_tinyurl );
        $s = str_replace ( $gd_tinyurl, $gd_click_tinyurl, $s );
        return $s;
    }
}
?>