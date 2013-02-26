<?php    
/**
*AsyncSender 异步提交系统发送客户端、lib
*发送给异步提交系统
*/

class Lib_asyncSender{
    public $pack_method;
    public $mqIsConn = false;
    public $memcache;
    public $async_server_list;

    public function __construct($async_server_list,$pack_method='json'){
        //传输的数据格式，可以是 json 或者 msgpack
        $this->pack_method = $pack_method;
        $this->async_server_list = $async_server_list;
        $this->addServer();
    }
    
    //添加server
    public function addServer(){
    $this->memcache = new Memcache();
        foreach($this->async_server_list as $server){
            $flag = $this->memcache->addServer($server['host'],$server['port']);
            if($flag){
                $this->mqIsConn = 1;
            }
        }
    }

    //判断是否已经连结
    public function isConn(){
        return $this->mqIsConn;
    }
    
    //添加进去
    public function add($msgtype,$msg_data,$user_ip='',$sys_ip='',$script='',$from=''){
    if(!$this->isConn()){
        //echo "not connect";
        return false;
    }
    $value['user_ip'] = $user_ip;
    $value['sys_ip'] = $sys_ip;
    $value['sys_time'] = time(); 
    $value['script'] = $script;
    $value['from'] = $from;
    $value['data'] = $msg_data;    
    if($this->pack_method == 'json'){
        $pack_data = json_encode($value);
    }else if($this->pack_method == 'msgpack'){
        $pack_data = msg_pack($value);
    }else{
        return false;
    }    
        $rs = $this->memcache->add($msgtype, $pack_data, 0,0);
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
}
    
    
?>
