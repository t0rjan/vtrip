<?php
/**
 *@fileoverview: [群博客] 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Thu Dec 09 02:54:36 GMT 2010
 *@copyright: sina
 */
class Tool_http
{
    private static $_isCurl = true;
    const DEBUG = true;

    public static function get($url , $timeout = 15)
    {
        if(self::$_isCurl)
            return self::_curlGet($url , $timeout);
        else
            return self::_socketGet($url , $timeout);
    }
    
    public static function post($url , $aPost , $timeout = 15)
    {
        if(self::$_isCurl)
            return self::_curlPost($url , $aPost , $timeout);
        else 
            return self::_socketPost($url , $aPost , $timeout);
    }
    
    private function _curlGet($url , $timeout)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL            , $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
    
        $start = self::_microtime();
        $rs =curl_exec($curl);
        $conn_time = round(self::_microtime() - $start , 3);
        curl_close($curl);
        

        $a = parse_url($url);
        if($rs === false)
        {
            Tool_logger::monitorLog(__CLASS__ , 'curl_get_err '.$a['host'].$a['path'].' '.curl_error($curl) , Tool_logger::LOG_LEVEL_ALERM );
        }
        if($conn_time > 1)
            Tool_logger::monitorLog(__CLASS__ , 'curl_get_time '.$a['host'].$a['path'].' '.$conn_time , Tool_logger::LOG_LEVEL_NOTICE );
        else 
            Tool_logger::debugLog(__CLASS__ , 'curl_get_time '.$a['host'].$a['path'].' '.$conn_time );
        return $rs;
    }
    
    private function _curlPost($url , $aPost , $timeout)
    {
        $curl = curl_init();
        
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,     CURLOPT_URL            , $url);
        curl_setopt($curl,     CURLOPT_POST        , 1);
        curl_setopt($curl,    CURLOPT_POSTFIELDS    , $aPost);
        curl_setopt($curl,  CURLOPT_TIMEOUT, $timeout);
    
        $start = self::_microtime();
        $rs =curl_exec($curl);
        $conn_time = round(self::_microtime() - $start , 3);
        curl_close($curl);
        
        $a = parse_url($url);
        if($rs === false)
        {
            Tool_logger::monitorLog(__CLASS__ , 'curl_post_err '.$a['host'].$a['path'] , Tool_logger::LOG_LEVEL_ALERM );
        }
        if($conn_time > 1)
            Tool_logger::monitorLog(__CLASS__ , 'curl_post_time '.$a['host'].$a['path'].' '.$conn_time , Tool_logger::LOG_LEVEL_NOTICE );
        else 
            Tool_logger::debugLog(__CLASS__ , 'curl_post_time '.$a['host'].$a['path'].' '.$conn_time );
        return $rs;
    }
    
    private function _socketGet($url , $timeout)
    {
        $aUrl = parse_url($url);
        $aUrl['port'] = isset($aUrl['port']) ? $aUrl['port'] : 80;
        
        
        $content = 'GET http://'.$url;
        $errno = $errstr = '';
        $start = self::_microtime();
        for ($i=1;$i<=5;$i++)
        {
            $fp = @fsockopen ($aUrl['host'], $aUrl['port'], $errno, $errstr, $timeout);
            if($fp)
            {
                break;
            }
        }
        $conn_time = round(self::_microtime() - $start , 3);
        if (!$fp)
        {
            $end  = array_sum(explode(' ', microtime()));
            Tool_logger::monitorLog(__CLASS__ , 'socket_get_conn_err '.$aUrl['host'] , Tool_logger::LOG_LEVEL_ALERM );
            return false;
        }
        else
        {
            if($conn_time > 1)
                Tool_logger::monitorLog(__CLASS__ , 'socket_get_conn_time '.$aUrl['host'].' '.$conn_time , Tool_logger::LOG_LEVEL_NOTICE );

            fputs($fp, "GET $url HTTP/1.0\r\nHost:" . $aUrl['host'] . "\r\n\r\n");
            $line = '';
            $start = self::_microtime();
            while (!feof($fp))
            {
                $line .= fgets ($fp, 1024);
            }
            
            $get_time = round(self::_microtime() - $start , 3);
            if($get_time > 1)
                Tool_logger::monitorLog(__CLASS__ , 'socket_get_wait_time '.$url.' '.$get_time , Tool_logger::LOG_LEVEL_NOTICE );
            
            fclose ($fp);
        }
        
        Tool_logger::debugLog(__CLASS__ , 'GET_sock '.$url);
        $line = preg_replace("/(.+?)\\r\\n\\r\\n(.+?)/is", "\\2", $line, 1);
        return $line;
    }
    
    private function _socketPost($url , $aPost , $timeout = 15)
    {
        $aUrl = parse_url($url);
        $aUrl['port'] = isset($aUrl['port']) ? $aUrl['port'] : 80;
        $errno =  $errstr = '';
        
        $data = is_array($aPost) ? self::_http_build_query($aPost) : $aPost;
        
        $start = self::_microtime();
        $fp = @fsockopen($aUrl['host'], $aUrl['port'], $errno, $errstr, $timeout);
        $conn_time = round(self::_microtime() - $start , 3);
        
        if (!$fp)
        {
            Tool_logger::monitorLog(__CLASS__ , 'socket_post_conn_err '.$aUrl['host'] , Tool_logger::LOG_LEVEL_ALERM );
            return false;
        }
        else
        {
            if($conn_time > 1)
                Tool_logger::monitorLog(__CLASS__ , 'socket_post_conn_time '.$aUrl['host'].' '.$conn_time , Tool_logger::LOG_LEVEL_NOTICE );            
                
            fwrite($fp, "POST " . $url . " HTTP/1.0\r\n");
            fwrite($fp, "Content-Length: " . strlen($data) . "\r\n");
            fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
            fwrite($fp, "Host: " . $aUrl['host'] . "\r\n\r\n");
            fwrite($fp, $data);
            $line = '';
            $start = self::_microtime();
            while (!feof($fp))
            {
                $line .= fgets($fp, 1024);
            }
            $post_time = round(self::_microtime() - $start , 3);
            fclose($fp);
            
            if($post_time > 1)
                Tool_logger::monitorLog(__CLASS__ , 'socket_post_wait_time '.$url.' '.$post_time , Tool_logger::LOG_LEVEL_NOTICE );
        }
        
        Tool_logger::debugLog(__CLASS__ , 'GET_sock '.$url);
        $line = preg_replace("/(.+?)\\r\\n\\r\\n(.+?)/is", "\\2", $line, 1);
        return $line;
    }

    private function _microtime()
    {
        return array_sum(explode(' ' , microtime()));
    }
    
    private function _http_build_query($aData)
    {
        if(function_exists(http_build_query))
        {
            return http_build_query($aData);
        }
        else 
        {
            if(!is_array($aData) || count($aData) < 1)
                return '';
                
            foreach ($aData as $k => $v)
            {
                $rs .= '&'.$k . "=" . urlencode($v);
            }
            return trim($rs , '&');
        }
    }
}
unserialize()
?>