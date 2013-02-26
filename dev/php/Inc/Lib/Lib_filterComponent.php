<?php
/**
 * 自定义组件内容过滤器
 *
 * @author yaojing<yaojing@staff.sina.com.cn>
 * @date 2009-05-19
 */
class Lib_filterComponent
{
    /**
     * XML解析器资源句柄
     *
     * @var resource
     */
    var $_parser = null;

    /**
     * 当前打开的标签名称
     *
     * @var string
     */
    var $_opened_tag = '';

    /**
     * 当前打开的标签是否被屏蔽
     *
     * @var string
     */
    var $_tag_ignored = false;

    /**
     * 错误信息
     *
     * @var array
     */
    var $_error = array();

    /**
     * 执行过滤之后的HTML内容
     *
     * @var string
     */
    var $_html = '';

    /**
     * 需要过滤屏蔽的标签
     *
     * @var array
     */
    var $_filterTags = array('STYLE', 'META', 'XML', 'TITLE', 'HEAD', 'LINK');

    /**
     * 常用的单体标签
     *
     * @var array
     */
    var $_aloneTags = array('IMG', 'HR', 'BGSOUND', 'FRAME', 'COL',
        'BASE', 'BR', 'AREA', 'LINK', 'WBR', 'META', 'PARAM');

    /**
     * 特殊标签，需要分析其src属性是否在白名单中
     *
     * @var array
     */
     var $_specialTags = array('SCRIPT', 'IFRAME');

    /**
     * 特殊标签的src属性白名单
     *
     * @var array
     */
    var $_srcList = array
        (
            'http://twitter.com/javascripts/blogger.js',
            'http://twitter.com/statuses/user_timeline/fap_b.json',
            'http://feeds.delicious.com/v2/js/fapbiao',
            'http://feeds.delicious.com/v2/js/tags/fapbiao',
            'http://v.t.sina.com.cn/widget/widget_blog.php',
            'http://service.t.sina.com.cn/widget/WeiboShow.php',
            'http://all.vic.sina.com.cn/201009iterchina/right.php', 
            'http://all.vic.sina.com.cn/201009iterchina/center.php',
            'http://all.vic.sina.com.cn/201009iterchina/left.php',
        );

    /**
     * 构造函数
     */
    function __construct()
    {
        $this->_parser = xml_parser_create('UTF-8');
        xml_parser_set_option($this->_parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_object($this->_parser, $this);
        xml_set_element_handler($this->_parser, 'open', 'close');
        xml_set_character_data_handler($this->_parser, 'data');
    }

    /**
     * 解析HTML并过滤非法标签，返回合法的HTML内容
     *
     * @access public
     * @param string $data HTML,UTF-8编码
     * @return string
     */
    function parse($data)
    {
        if ('' == trim($data)) return $this->_html;
        $this->_format($data);
        if (!xml_parse($this->_parser, $data, true))
        {
            if(function_exists('tidy_set_encoding'))
            {
                tidy_set_encoding("utf8");
            }
            
            $config = array
            (
                'output-xhtml'    => true,
                'show-body-only'=> false
            );
            $this->_html = tidy_repair_string($this->_html, $config,'utf8');    
            
            $this->_error = array
            (
                'error_code'        => xml_get_error_code($this->_parser),
                'error_string'        => xml_error_string(xml_get_error_code($this->_parser)),
                'error_line_number' => xml_get_current_line_number($this->_parser),
            );
        }
        xml_parser_free($this->_parser);
        $this->_html = preg_replace("/<\/?html[^>]*>\r?\n?/i", "", $this->_html);
        $this->_html = preg_replace("/<\/?body[^>]*>\r?\n?/i", "", $this->_html);
        $this->_html = trim($this->_html);
        $this->_html = String::esc_korea_restore($this->_html);
        return $this->_html;
    }

    /**
     * 获取过滤后的HTML内容
     *
     * @access public
     * @return string
     */
    function getHtml()
    {
        return $this->_html;
    }

    /**
     * 获取错误信息
     *
     * @access public
     * @return array
     */
    function getError()
    {
        return $this->_error;
    }

    /**
     * 添加src属性白名单
     *
     * @access public
     * @param str $url URL
     * @return void
     */
    function addUrl($url)
    {
        array_push($this->_srcList, $url);
    }

    /**
     * 获取src属性白名单列表信息
     *
     * @access public
     * @return array
     */
    function getSrcList()
    {
        return $this->_srcList;
    }

    ///////////////////// private////////////////////////////////////////////

    /**
     * 标签开始
     *
     * @access private
     * @param resource $parser
     * @param string $tag
     * @param array $attributes
     * @return void
     */
    function open(&$parser, $tag, $attributes)
    {
        $this->_opened_tag = $tag;
        $this->_tag_ignored = false;
        if (!$this->_checkTag($tag))
        {
            return;
        }
        $b_special_tag = in_array($tag, $this->_specialTags);
        $b_safe = !$b_special_tag;
        

        $tmp = '<' . $tag;
        if (is_array($attributes) && !empty($attributes))
        {
            foreach ($attributes as $attribute => $value)
            {
                if (!$this->_checkAttr($tag, $attribute, $value))
                {
                    continue;
                }
                $tmp .= " $attribute=\"$value\"";
                if (!$b_special_tag)
                {
                    continue;
                }
                if ('SRC' == strtoupper($attribute))
                {
                    if (!$this->_checkAttrSrc($value))
                    {

                        $b_safe = false;
                        break;
                    }
                    else
                    {
                        $b_safe = true;
                    }
                }
            }
        }
        if (!$b_special_tag || $b_safe)
        {
            $this->_html .= $tmp;
            $this->_html .= (in_array($tag, $this->_aloneTags)) ? ' />' : '>';
        }
        else 
        {
            $this->_tag_ignored = true;
        }
    }

    /**
     * 标签数据
     *
     * @access private
     * @param resource $parser
     * @param string $data
     * @return void
     */
    function data(&$parser, $data)
    {
        if ($this->_checkTag($this->_opened_tag))
        {
            $this->_html .= trim($data);
        }
    }

    /**
     * 标签结束
     *
     * @access private
     * @param resource $parser
     * @param string $tag
     * @return void
     */
    function close(&$parser, $tag)
    {
        if ($this->_checkTag($tag) && !in_array($tag, $this->_aloneTags) && !$this->_tag_ignored)
        {
            $this->_html .= "</$tag>";
        }
        $this->_tag_ignored = false;
    }

    /**
     * 过滤特殊字符和tidy处理
     *
     * @access private
     * @param string $data
     * @return string
     */
    function _format(&$data)
    {
        $data = preg_replace("/<span\s+style\s*=\s*\"display:\s*none\s*\">\.<\/span>/is", "", $data);
        $data = preg_replace("|\/\*(.*)\*\/|sU", "", $data);
        $data = preg_replace("/<!\[CDATA\[(.*?)\]\]>/is", "\\1", $data);
        $data = String::esc_korea_change($data);
        if(function_exists('tidy_set_encoding'))
        {
            tidy_set_encoding("utf8");
        }
        $config = array
        (
            'output-xhtml'    => true,
            'show-body-only'=> false
        );
        $data = tidy_repair_string($data, $config , 'utf8');
        $data = str_replace("&", "&amp;", $data);
    }

    /**
     * 检测是否为非过滤标签
     *
     * @access private
     * @param string $tag
     * @return boolean
     */
    function _checkTag($tag)
    {
        return in_array($tag, $this->_filterTags) ? false : true;
    }

    /**
     * 检测标签属性及属性值是否合法
     *
     * @access private
     * @param string $tag
     * @param string $attribute
     * @param string $value
     * @return boolean
     */
    function _checkAttr($tag, $attribute, &$value)
    {
        $attribute = strtoupper(trim($attribute));
        $value = trim($value);
        if (substr($attribute, 0, 2) == 'ON')
        {
            return false;
        }
        if (in_array($attribute, array('SRC', 'HREF', 'CODEBASE', 'DYNSRC', 'CONTENT', 'DATASRC', 'DATA'))
            && preg_match("/^(javascript|mocha|livescript|vbscript|about|view-source):/i", $value))
        {
            return false;
        }
        if (strpos(strtolower(trim($value)), 'javascript:') !==false
           || strpos(strtolower(trim($value)), 'vbscript:') !==false)
        {
            return false;
        }

        //WMP XSS FIX
        if(strtolower($tag) == "param" || strtolower($tag) == "embed"){
            if ( strtolower($attribute) == "captioningid" || strtolower($value) == "captioningid"){
                return false;
            }        
        }

        if ('STYLE' == $attribute)
        {
             $search  = array
            (
                iconv("GBK", "UTF-8","ｅ"),
                iconv("GBK", "UTF-8","ｘ"),
                iconv("GBK", "UTF-8","ｐ"),
                iconv("GBK", "UTF-8","ｒ"),
                iconv("GBK", "UTF-8","ｅ"),
                iconv("GBK", "UTF-8","ｓ"),
                iconv("GBK", "UTF-8","ｉ"),
                iconv("GBK", "UTF-8","ｏ"),
                iconv("GBK", "UTF-8","ｎ")
            );
            $replace = array("e","x","p","r","e","s","i","o","n");
            $value = str_replace($search, $replace, $value);

            $search = array("E","X","P","R","E","S","I","O","N");
            $replace = array("e","x","p","r","e","s","i","o","n");
            $value = str_replace($search, $replace, $value);
            $value = str_replace('expression', 'expression_x', $value);
            $value = str_replace('eval', '',$value);
        }
        return true;
    }

    /**
     * 检测javascript/iframe标签的src属性是否在白名单中
     *
     * @access private
     * @param string $url
     * @return boolean
     */
    function _checkAttrSrc($url)
    {
        if ('' == $url) return false;
        $found = false;
        foreach ($this->_srcList as $src)
        {
            if (0 === strpos($url, $src))
            {
                $found = true;
                break;
            }
        }
        return $found;
    }
}
