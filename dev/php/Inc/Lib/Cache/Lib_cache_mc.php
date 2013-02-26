<?php

class Lib_cache_mc implements Lib_cache_base  
{
    
    var $con;
    var $mc;
    var $mc_hash;
    var $expire_time = 0;   //过期时间
    var $flag = 0; //是否压缩
    static $conns;
    
    public function init_cache($u, $s)
    {
        $this->mc_hash = Tool_sina::calc_hash_db($u , $s);
        return true;
    }
    public function connect($server) 
    {
        if(empty($this->mc_hash)){
            $mc_server = $server;
        }else{
            $mc_server = $server[$this->mc_hash];
        }
        $key = md5($mc_server['host'].$mc_server['port']);
        if(isset(self::$conns[$key])){
            $this->mc = self::$conns[$key];
            return true;
        }
        
        $this->mc = new Memcache;
        for ($i = 0; $i< 3;$i++)
        {
            $this->con = $this->mc->connect($mc_server['host'], $mc_server['port']);
            if($this->con)
                break;
        }
        //连接失败
        if(!$this->con)
        {
            Tool_logger::monitorLog(__CLASS__ , 'mc_connect_err:'.$mc_server['host'].':'.$mc_server['port']);
            return false;
        }
        
        //多次重连
        if($i > 1)
        {
            Tool_logger::monitorLog(__CLASS__ , 'mc_connect_fail:'.$i.' times' , Tool_logger::LOG_LEVEL_NOTICE );
        }
        self::$conns[$key] = $this->mc;
        return true;
    }
    
    public function get($key)
    {
        Tool_logger::debugLog(__CLASS__ , 'get '.$key);
        return $this->mc->get($key);
    }
    
    public function expire($time){
        $this->expire_time = $time;
    }
    public function compressed(){
        $this->flag = MEMCACHE_COMPRESSED;
        $this->mc->setCompressThreshold(2000, 0.2); 
    }
    public function set($key , $value)
    {
        return $this->mc->set($key , $value, $this->flag, $this->expire_time);
    }

    public function delete($key)
    {
        return $this->mc->delete($key);
    }
    
    public function close()
    {
        return $this->mc->close();
    }
}

?>