<?php
/**
 *@fileoverview: 日志投放
 *@important 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Wed Apr 27 06:09:00 GMT 2011
 *@copyright: sina
 */


class Tool_logger
{
    const LOG_LEVEL_ALERM = 'alarm';
    const LOG_LEVEL_ERROR = 'error';
    const LOG_LEVEL_NOTICE = 'notice';
    
    const LOG_SEP = ' | ';
    static $runningLog;
    
    /**
     * 调试LOG
     *
     * @param string $type
     * @param string $msg
     * @param bool $force
     * @return bool
     */
    public static function debugLog ($type, $msg , $force = false) {
        if(SYSDEF_DEBUG || $force)
        {
            self::_firephplog($msg , $type);
            
            $dir = SYSDEF_LOG_DEBUG_PATH . "/" . $type;

            self::_checkDir($dir);
            
            return self::_writeLogToFile($dir, time() . SYSDEF_LOG_SEPARATE . $msg);
        }
    }
    /**
     * 监控LOG
     * 根据LOG进行系统运行状态监控
     *
     * @param string $type
     * @param string $msg
     * @param string $level Tool_logger::LOG_LEVEL_ALERM
     */
    public static function monitorLog ($type , $msg , $level)
    {
        self::_firephplog($msg.' '.$level , $type);
        
        $dir = SYSDEF_LOG_MONITOR_PATH . "/" . $type;
        self::_checkDir($dir);
        $msg = '['.$level.'] '.$type.' '.$_SERVER['SCRIPT_NAME'].' '.Tool_ip::getLocalLastIp().' '.date('y-m-d H:i:s'). " " . $msg;
        //$msg = '['.$level.'] '.$type.' '.time() . " " . $msg;
        self::scrib_log('meila-debug_monitor' , $msg);
        self::_writeLogToFile($dir, $msg);
    }
    /**
     * 数据投放
     * 需要长期跟进的 需要设置数据回收机制
     *
     * @param string $type
     * @param string $msg
     * @param bool $force
     * @param bool $savebydate
     * @param bool $extinfo
     * @return unknown
     */
    public static function dataLog ($type , $msg , $force = true , $savebydate = true , $extinfo = false)
    {
        if(SYSDEF_DEBUG || $force)
        {
            $dir = SYSDEF_LOG_DATA_PATH . "/" . $type;
            self::_checkDir($dir);
                
            return self::_writeLogToFile($dir, $msg , $savebydate , $extinfo);
        }
        return ;
    }
    
    public static function baseActionLog($usersign , $action_code , $data)
    {
        self::_checkDir(SYSDEF_LOG_DATA_PATH);
        $dir = SYSDEF_LOG_ACTION_PATH . "/" . $type . "/" . 'act_'.$action_code;
        self::_checkDir($dir);
        
        $sep = "\t";
        $msg = date('Y-m-d H:i:s') 
            .$sep. Tool_ip::get_real_ip() 
            .$sep. $usersign
            .$sep. $action_code
            .$sep. $_SERVER['SCRIPT_NAME']
            .$sep. Tool_ip::getLocalLastIp()
            .$sep. self::_formatActLog($data);
        return self::_writeLogToFile($dir, $msg , true , false);
    }
    
    public static function runningLog($class , $act , $msg)
    {
        self::$runningLog[] = array(
            'class' => $class,
            'act' => $act,
            'msg' => $msg,
        );
        return true;
    }
    
    public static function saveRunningLog()
    {
        if(count(self::$runningLog) > 0)
        {
            $log =$_SERVER['REQUEST_URI']."\n";
            foreach (self::$runningLog as $value) {
                    $log.=$value['class'].' '.$value['act'].' '.$value['msg']."\n;";
            }
            $log.="\n";

            $dir = SYSDEF_LOG_DEBUG_PATH . "/RUNNING";
            return self::_writeLogToFile($dir, $log , true , false);
        }
        else
            return true;
    }

    /**
     * 心跳LOG
     *
     * @param string $name
     * @return bool
     */
    public static function heartbeatLog($name)
    {
        $file = SYSDEF_LOG_DATA_PATH . "/heartbeat";
        self::_checkDir($dir);
        $file .= '/'.$name.'.log';
            
        return touch($name);
    }
    /**
     * 检查并创建目录
     *
     * @param string $path
     */
    private static function _checkDir($path)
    {
        if (! is_dir($path)) {
            mkdir($path , 0755 , true);
            //chown(SYSDEF_LOG_DEBUG_PATH, "www");
            //chgrp(SYSDEF_LOG_DEBUG_PATH, "www");
        }
        return ;
    }
    /**
     * 写debug日志共用方法体
     * example
     *     Logger::_writeLogToFile($type,$msg)
     * @param string $type    日志类型
     * @param string $msg 日志信息
     * @author 杨祥宇 <xiangyu1@staff.sina.com.cn>
     */
    private static function _writeLogToFile ($path, $msg, $savebydate = true , $extinfo = true) {
        $log_name = '';
        $now = time();
        $now_date = date("Y-m-d", $now);
        $now_time = date("Y-m-d H:i:s", $now);
        if ($savebydate) {
            $fname = $path . '/' . $now_date . "_" . Tool_ip::getLocalLastIp() . '.v7.log';
        } else  {
            $fname = $path . '/file.log';
        }
        
        if (! is_dir($f = dirname($fname))) {
            @mkdir($f);
            //@chown($f, "www");
            //@chgrp($f, "www");
        }
        if ($extinfo)  {
            $msg = $msg . SYSDEF_LOG_SEPARATE;
            $msg .= $now . SYSDEF_LOG_SEPARATE . $now_time;
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
            $msg .= SYSDEF_LOG_SEPARATE . ($ip == '' ? $_SERVER['REMOTE_ADDR'] : $ip);
            $msg .= SYSDEF_LOG_SEPARATE . $_SERVER['SCRIPT_NAME'];
        }
        $msg .= "\n";
        
        if (! is_file($fname))
        {
            fopen($fname, 'a');
            @chown($fname, "www");
            @chgrp($fname, "www");
        }
        $fp = fopen($fname, 'a');
        if ($fp)
        {
            $r = fwrite($fp, $msg);
            fclose($fp);
            return $r;
        }
        return false;
    }
    
    private static function _formatActLog($array)
    {
        return implode(',' , $array);
    }
    
    public static function oneLog($type , $name , $content)
    {
        self::_checkDir(SYSDEF_LOG_DEBUG_PATH);
        $dir = SYSDEF_LOG_DEBUG_PATH . "/" . $type;
        self::_checkDir($dir);
        
        $path = $dir.'/'.$name;
        file_put_contents($path , $content);
        return true;
    }
    
    private static function _firephplog($s , $name)
    {
        if(SYSDEF_DEBUG && isset($_SERVER['SERVER_NAME']))
        {
            require_once(SERVER_ROOT_PATH.'/Inc/Lib/Firephp/Lib_firephp.php');
            $firephp = FirePHP::getInstance(true);
            $firephp->log($s, 'GB_'.$name);
        }
        return ;
    }
    
    public  static function scrib_log($category , $msg)
    {
        return;
        include_once 'SinaService/SinaLeopardService/SinaLeopardService.php';
        $scribe = new SinaLeopardService('d372eg2r63j94wd42r6sbf2wqex6f841cr');
        return $scribe->sendMessage($category, rtrim($msg) . "\n");
    }
}
//Tool_logger::baseActionLog(1,2,array(999,111));