<?php
class Tool_string_xss
{
    /**
     * 过滤IFRAME
     *
     * @param string $str
     * @return string
     */
    function clear_iframe($str)
    {
        $str = preg_replace("!<iframe(.+?)>!is", "", $str);
        $str = preg_replace("!<\/iframe>!is", "", $str);
        return $str;
    }
}