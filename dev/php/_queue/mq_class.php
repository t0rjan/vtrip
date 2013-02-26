<?php
/**
*author:xiaozhen
*/

ini_set('memory_limit','128M');
set_time_limit(0);

//进程安全性保证
function mq_term_handler($signo)
{
    MqClass::setTermHandler(true);
}

declare(ticks = 1);
if(function_exists('pcntl_signal')) {
    pcntl_signal(SIGTERM, "mq_term_handler");
}

/*
$data_example = array(
    "__systime" => time(),  //消息创建时间
    "__errornum" => 1,  //消息出错次数
);
*/


/**
*feed队列基类
*/

class MqClass{
    //调试模式 调试信息会被打印出来
    const DEBUG = true;
    //最大失败次数
    const MAX_ERROR_NUM = 5;
    //最大取空次数 超出时退出
    const MAX_EMPTY_NUM = 30;
    //LOG存放目录
    const MAX_DELAY_TIME = 600;

    const LOGTYPE_ERROR = 'ERROR';
    const LOGTYPE_DEBUG = 'DEBUG';

    

    //当前进程的代码名称
    public $script_name;
    //当前进程的参数
    public $script_argv;
    //消息队列接口 可用mq redis
    public $queue;
    //要执行的数据
    protected  $src_data;
    protected  $uid;

    private $data_format = 'serialize';

    public static $signal_killed = false;
    public $done_num = 0;
    public $start_time = 0; 
    public $end_time = 0; 
    private $log_dir = '';
    
    //构造函数
    public function __construct($queue , $log_dir){
        $this->queue = $queue;
        $this->log_dir = $log_dir;
        if(!$this->queue->isConn()){
            //统一监控MQ、Redis资源连接情况
            $this->writeLog(__CLASS__."_DEBUG", 'can_not_to_connect_'.$this->mqHost['host']); 
            echo 'Mq can not connect'. getmypid();
        }
        $this->_construct();
    }

    protected function _construct()
    {}
    
    //析构函数
    public function __destruct(){
        $this->queue->close();
    }
    
    //初始化函数
    public function setArgv($argv){
        $this->script_name = $_SERVER['SCRIPT_FILENAME'];
        $this->script_argv = $argv;
    }
    protected function setDataFormat($format)
    {
        $this->data_format = $format;

    }
    //执行cmd命令
    public function runCmd($cmd){
        $fp = popen($cmd , 'r');
        while (!feof($fp))
        {
            $rs .= fgets($fp);
        }
        fclose($fp);
        return $rs;
    }
    //心跳注册
    public static function setTermHandler($bool){
        self::$signal_killed = $bool;
    }

    public function redo_job()
    {
        $this->src_data['__errornum']+=1;
        $this->src_data['__systime'] = time();
        $this->queue->set($this->src_data);
    }

    //获取mq消息
    public function getMessage(){
        $value = $this->queue->get();
        if(false != $value){
            $this->writeLog(get_class($this)."_SOURCE", $this->queue->getKey().'||'. $value);
            return $value;
        }
        return false;
    }
    
    //写mq队列
    public function setMessage($value){
        return $this->queue->set($value);
    }
    
    //
    public function setRunNum(){
        $this->done_num++;
        return $this->done_num;
    }
    
    private function resetRunNum()
    {
        $this->done_num = 0;
    }

    //检测脚本运行情况
    public function checkStart()
    {
        $cmd = "ps auwwx | grep '" . $this->script_name . " " . $this->script_argv. "' | grep -v grep | grep -v vi | grep -v '/bin/sh' | wc -l";
        $num = $this->runCmd($cmd);
        if($num  > 1)
        {
            echo "已经运行";
            $this->writeLog(__CLASS__."_DEBUG", 'class ' . get_class($this) . ' script already run');
            exit;
        }
        return true ;
    }

                
    //流程函数
    public function execute(){
        //检查是否已经启动
        $this->checkStart();
        //执行循环

        $this->resetRunNum();
        while (true)
        {
            $this->_heartBeat();
            $message_data = $this->getMessage();
            if(!is_array($message_data))
                $message_data = unserialize($message_data);


            //取不到数据超过次数时退出
            if(!$message_data || !is_array($message_data)){
                //取空50次推出
                if($this->setRunNum() > self::MAX_EMPTY_NUM){
                    $this->writeLog(get_class($this)."_DEBUG", 'empty to exit'); 
                    break;
                }
                sleep(1);
                echo $this->done_num."\n";
                continue;
            }else{
                $this->src_data = $message_data;
                //数据校验通过后，执行应用逻辑
                if(true == $this->_checkMessage()){
                    $this->run_job();
                }
                else
                {
                //exit or continue;
                }
            }
        }

        
    }


    //create dir
    public function pathValidate($path){
        $path_array = split("\/+", $path);
        $tmp_path = "";
        for ($i = 0; $i < count($path_array); $i++)
        {
            if ("" == $path_array[$i]) continue;
            $tmp_path .= (0 == $i ? "" : "/").$path_array[$i];
            if (!is_dir($tmp_path))
            {
                if (!mkdir($tmp_path, 0777)){
                    return false;
                }
            }
        }
        return true;
    }


    public function writeLog($dir, $msg)
    {
        if(self::DEBUG){
            echo "File:".$dir."\n";
            echo "msg:".$msg."\n";
        }
        $this->_write_err_log($dir, time().'||'.$msg);
        return true;
    }

    private function _write_err_log($dir, $str_content){
        $str_file_dir = $this->log_dir.$dir."/";
        if (!is_dir($str_file_dir))
        {
            $this->pathValidate($str_file_dir);
        }
        $str_file_name = $str_file_dir . "/" . date("Y-m-d") . ".log";
        $fp = fopen($str_file_name , 'a');
        fwrite($fp , $str_content . "\n");
        fclose($fp);
    }

        //心跳检查
    private function _heartBeat(){
        if(self::$signal_killed){
            $str = 'someone killed me'. getmypid();
            $this->writeLog(__CLASS__."_DEBUG", $str);
            exit();
        }
    }
    private function _checkMessage(){
        //队列超时记日志
        if(time() - $this->src_data['__systime'] > self::MAX_DELAY_TIME){
            //此处去统一监控队列拥堵情况
            $this->writeLog(__CLASS__."_ERROR", $this->queue->getKey().'||TIMEOUT');
        }
        
        //超过最大失败次数，放弃处理
        if($this->src_data['__errornum'] > self::MAX_ERROR_NUM){
            $this->writeLog(get_class($this)."_ERROR",$this->queue->getKey().'||DEALERR||'.serialize($this->src_data));
            return false;
        }
        return true;
    }

}


/**
*抽象类
*/
abstract class BaseOperateQueue{
    const LOGTYPE_ERROR = 'error';
    const LOGTYPE_DEBUG = 'debug';
    const LOGTYPE_DATA = 'data';
    private $conf_array;
    private $queue_key;
    private $isConn;
    abstract protected function getVersion();
    abstract protected function isConn();
    abstract protected function connect();
    abstract protected function close();
    abstract protected function get();
    abstract protected function getKey();
    abstract protected function _log($type , $msg);
    abstract protected function _hashQueueConfByKey($queue_key , $count);
}

/**
*mq操作类
*/

class McQueue extends BaseOperateQueue{
    private $memcache;

    public function __construct($queue_key, $conf_array=''){
        $this->queue_key = $queue_key;
        $this->conf_array = $conf_array;

        $rs = $this->connect($this->_hashQueueConfByKey($this->queue_key , count($this->conf_array)));

        if($rs)
            $this->isConn = true;
    }
    
    //获取获取队列基本信息
    public function getVersion(){
        return 'memcacheq';
    }

    public function connect($hashkey = 0){

        $mq_conf = $this->conf_array[$hashkey];

        if(empty($mq_conf))
            return false;
        
        echo 'connect '.$mq_conf['host'].$mq_conf['port']."\n";

        $this->memcache = new Memcache;
        if(!$this->memcache->connect($mq_conf['host'], $mq_conf['port'])){
            
            return false;
        }
        return true;
    }

    public function isConn(){
        return $this->isConn;
    }
    
    public function getKey(){
        return $this->queue_key;
    }

    public function set($value){
        $this->memcache->setCompressThreshold(512, 0.2); 
        $rs = $this->memcache->set($this->queue_key, $value, 0, 86400*7);
        return $rs;
    }

    public function get(){
        $rs = $this->memcache->get($this->queue_key);
        return $rs;
    }

    public function close(){
        $this->memcache->close();
    }

    public function __destruct(){
        $this->close();
    }
    
    public function getStat($cmd='stats'){
        $stat = array();
        $cmd == '' && $cmd = 'stats';
        if($cmd == 'stats'){
            $stat = $this->memcache->getStats();
        }else{
            $fp = fsockopen($this->mqHost['host'], $this->mqHost['port'], $errno, $errstr, 3);
            if ($fp) {
                $out = $cmd . "\r\n\r\n";
                $buf = '';
                fwrite($fp, $out);
                while (!feof($fp)) {
                    $c=fgets($fp, 2048);
                    $buf .= $c;
                    if(substr($c,-5,3) == "END") break;
                }
                fclose($fp);
                $buf = trim($buf);
                $tmp_array = explode("\r\n",$buf);
                unset($tmp_array[count($array)-1]); 
                foreach($tmp_array as $each_stat){
                    list(,$key,$value) = explode(" ",$each_stat);
                    if($key){ 
                        $stat[$key] = $value;
                    }
                }
            }
        }
        return $stat;
    }
    
    //获取队列长度
    public function getSize(){
        $stat_array = $this->getStat();
        $info = trim($stat_array[$this->queue_key]);
        if(empty($info)){
            return false;
        }
        list($total_num, $deal_num) = explode('/', $info);
        $length = $total_num - $deal_num;
        return $length<=0? 0: $length;
    }

    protected function _hashQueueConfByKey($queue_key , $count)
    {
        return 0;
    }
    protected function _log($type , $msg)
    {
        echo $type."    ".$msg."\n";
    }
}



/**
*Redis操作类
*/

class RsQueue extends BaseOperateQueue{
    
    public $redis;
    public function __construct($queue_key, $conf_array=''){
        if (!extension_loaded('redis')) {
            if (!dl('redis.so')) {
                exit('redis.so not loaded');
            }
        }
        //队列名称
        $this->queue_key = $queue_key;
        $this->conf_array = $conf_array;

        
        //连接
        if(!$this->connect($this->_hashQueueConfByKey($this->queue_key , count($this->conf_array)))){
            $this->isConn = false;
        }else{
            $this->isConn = true;
        }
    }
    
    //获取获取队列基本信息
    public function getVersion(){
        return 'redis';
    }
    
    //连接Redis队列服务器
    public function connect($hashkey = 0){
        $conf = $this->conf_array[$hashkey];
        $this->redis = new Redis;
        if(!$this->redis->connect($conf['host'], $conf['port'], 1)){
            

            return true;
        }
        return true;
    }
    
    //检查连接是否正常
    public function isConn(){
        return $this->isConn;
    }
    
    //返回当前的队列的名词
    public function getKey(){
        return $this->queue_key;
    }
    
    //插入队列
    public function set($value){
        $rs = $this->redis->lpush($this->queue_key, $value); 
        return $rs;
    }
    
    //从队列取出值
    public function get(){
        $rs = $this->redis->rpop($this->queue_key);
        return $rs;
    }
    
    //关闭连接
    public function close(){
        $this->redis->close();
    }
    
    //关闭连接
    public function __destruct(){
        $this->close();
    }
    
    //获取队列长度
    public function getSize(){
        return $this->redis->lSize($this->queue_key);
    }
    
    //获取服务器信息
    public function getStat(){
    }

    public function _hashQueueConfByKey($queue_key , $count)
    {
        return 0;
    }
    protected function _log($type , $msg)
    {
        echo $type."    ".$msg."\n";
    }
}
?>
