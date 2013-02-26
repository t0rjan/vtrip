<?php
class ml_tool_ua
{
    static public function is_sinaMobileRead()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'] , 'SINA_ROBO') !== false ? true : false;
    }
    
    static public function is_MobileTerminal()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'] , ' Mobile') !== false ? true : false;
    }
    
    static public function is_fake_visitor()
    {
        //根据UA检测
        $ua_keyword = array(
            'Sogou web spider',    //sogou 不按ROBOT.TXT控制抓取，这种B养的。
            'Googlebot',
            'YoudaoBot',
            'Baiduspider',
            'YodaoBot',
            'YRSpider',
            'Sosospider',
            'Huaweisymantecspider',
            'ahrefs.com',
            'Alibaba.Security.Heimdall',
        );
        
        foreach ($ua_keyword as $kw)
        {
            if(strpos($_SERVER['HTTP_USER_AGENT'] , $kw) !== false)
                return true;
        }
        
        //根据IP检测
        $ip_list = array(
        //以下是公司安全部门扫描
            '180.149.153.22',
            '180.149.135.231',
            '220.181.136.81',
            '180.149.135.91',
            '220.181.136.93',
            '180.149.153.21',
            '10.210.128.51',
            '10.210.12.252',
            '172.16.140.21',
            '180.149.134.10',
            '111.13.8.110',
            '110.75.186.214',
        //安全部门完
        );
        $ip_visit = Tool_ip::get_real_ip();
        if(in_array($ip_visit , $ip_list))
        {
            return true;
        }

        return false;
    }
    
    public static function add_usid($value = 0) {
        if(!empty($_COOKIE['usid'])){
            if('u' == substr($_COOKIE['usid'],0,1)) return $_COOKIE['usid'];
        }
        
        if(empty($_COOKIE['mlUsign']))
        {
            $microtime = explode(" ",microtime());
            $datetime = $microtime[1].substr($microtime[0],2,6);
            $usid = "us".base_convert($datetime,10,32);
            setcookie('mlUsign', $usid, time()+86400*365, '/' , ML_COOKIE_DOMAIN);
            $_COOKIE['mlUsign'] = $usid;
        }
        
        if($value == 0)
        {
            $microtime = explode(" ",microtime());
            $datetime = $microtime[1].substr($microtime[0],2,6);
            $usid = "u".base_convert($datetime,10,32);
        }
        else 
        {
            $usid = $value;
        }
        setcookie('usid', $usid, null, '/' , ML_COOKIE_DOMAIN);
        return $usid;
    }
    public static function get_usign()
    {
        return $_COOKIE['mlUsign'];
    }
}