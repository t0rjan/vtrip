<?php
    class ml_tool_jsoutput
    {
        static function output($data , $format = 'JSON' , $varname = '' , $jsonp = '')
        {
            $format = strtoupper($format);
            
            if (!in_array($format, array('JSON', 'JSONP', 'XML', 'PHP')))
            {
                $format = 'JSON';
            }
            if ('JSON' == $format || 'JSONP' == $format)
            {
                if ($_GET['domain'] == 1)
                {
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
                    echo '<script type="text/javascript">document.domain="sina.com.cn";</script>' . "\n";
                }
                
                if ('' != $varname)
                {
                    $varname = str_replace(array("<",">"),"",$varname);
                    echo $varname . '=' . json_encode($data);
                }
                elseif(preg_match('/^[0-9a-zA-Z-_]{1,}$/' , $jsonp))
                {
                    echo $jsonp . '(' . json_encode($data) . ')';
                }
                else
                {
                    echo json_encode($data);
                }
            }
            elseif ('XML' == $format)
            {
                echo self::xml_encode($data);
            }
            elseif('PHP' == $format) 
            {
                echo serialize($data);
            }
               
            exit;
        }
        
        static private function _xml_encode($data, $encoding = 'utf-8', $root = "root")
        {
            $xml = "<?xml version=\"1.0\" encoding=\"" . $encoding . "\"?>\n";
            $xml .= "<{$root}>\n";
            $xml .= self::_data_to_xml($data);
            $xml .= "</{$root}>";
            return $xml;
        }
        
        static private function _data_to_xml($data)
        {
            if (is_object($data))
            {
                $data = get_object_vars($data);
            }
            $xml = '';
            foreach($data as $key => $val)
            {
                is_numeric($key) && $key = "item id=\"$key\"";
                $xml .= "<$key>";
                if (is_array($val) || is_object($val))
                {
                    $xml .= "\n" . self::_data_to_xml($val);
                }
                else 
                {
                    $xml .= in_array($key, array('category', 'title', 'memo', 'tag')) ? "<![CDATA[" . $val . "]]>" : $val;
                }
                list($key,) = explode(' ', $key);
                $xml .= "</$key>\n";
            }
            return $xml;
        }
    }
?>