<?php
/**
 *@fileoverview: 
 *@important 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Wed Apr 27 05:59:16 GMT 2011
 *@copyright: sina
 */

class Tool_ip
{
    /**
     * 取本服务器IP地址
     *
     * @return string
     */
    static function getLocalLastIp($is_inner_net = false , $cache = true)
    {
        $ip_cache = SYSDEF_CACHE_ROOT_PATH.'PHP5_CACHED_IP_CONFIG';
        if($cache && file_exists($ip_cache)){
            $arr= parse_ini_file($ip_cache);
            if(isset($arr['IPADDR'])){
                $ips[]=$arr['IPADDR'];
            }
        }
    
        if(!is_array($ips))
        {
            $handle = popen("/sbin/ifconfig|grep 'inet addr'", 'r');
            while ($s = fgets($handle,1024))
            {
                if (preg_match("/inet addr:([0-9.]+)/", $s, $match))
                {
                    $ips[] = $match[1];
                }
            }
        }
        foreach ($ips as $ip)
        {
            $sub_ip = substr($ip, 0, 5);
            
            $aInner = array('127.0' , '10.55' , '10.49' , '10.69' , '10.73');
            if ( /*取外网IP*/ !$is_inner_net && (!in_array($sub_ip , $aInner))
                /*取内网IP*/ || $is_inner_net && (in_array($sub_ip , $aInner)))
            {
                if(!file_exists($ip_cache)){
                    @file_put_contents($ip_cache, 'IPADDR="'.$ip.'"');
                }
                return $ip;
            }
        }
    }
    /**
     * 取访问用户的IP地址
     * @return ip or ''
     */
    static function get_real_ip()
    {
    
        if( getenv('HTTP_X_FORWARDED_FOR') != '' )
        {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
    
            $entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
            reset($entries);
            while (list(, $entry) = each($entries))
            {
                $entry = trim($entry);
                if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
                {
                    $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
    
                    if ($client_ip != $found_ip)
                    {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        }
        else
        {
            $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
        }
    
        return   $client_ip ;

    }
    /**
     * Enter description here...
     *
     * @return unknown
     */
    static function get_real_ip_int()
    {
        return sprintf("%u",ip2long(self::get_real_ip()));
    }
    
    static public function domain2ip($domain)
    {
        $cmd = "dig ".$domain." | grep 'IN A' | awk '{print $5}'";
        $rs = Tool_os::run_cmd($cmd);
        $rs = explode("\n" , $rs);
        foreach ($rs as $k => $ip)
        {
            if(empty($ip))
                unset($rs[$k]);
        }
        return $rs;
    }
}
?>