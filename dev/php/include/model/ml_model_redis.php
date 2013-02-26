<?php
class ml_model_redis extends Lib_datamodel_abstract
{
    protected  $oRedis;
    private $rds_conf;
    static $conns;

    function __construct(){
        //初始化redis动态链接库
        if (!extension_loaded('redis')) {
            if (!dl('redis.so')) {
                return false;
            }
        }

    }

    function setConf($type) {
        $cnf = ml_factory::load_standard_conf('redis');
        $this->rds_conf = $cnf[$type][0];
    }

    protected function  init_rds($type) {
        
        $this->setConf($type);
        $key = md5($this->rds_conf['host'] . $this->rds_conf['port']);
        if(isset(self::$conns[$key]))
        {
            $this->oRedis = self::$conns[$key];
            return true;
        }
        else
        {
            $this->oRedis = new Redis();
            
            try{
                Tool_logger::runningLog(__CLASS__ , 'connect',$this->rds_conf['host'] .':'. $this->rds_conf['port']);
                $this->oRedis->connect($this->rds_conf['host'] , $this->rds_conf['port'] ,1);
            }
            catch (Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__, 'connect:'.$this->rds_conf['host'] .':'. $this->rds_conf['port'],Tool_logger::LOG_LEVEL_ALERM);
                return false;
            }
            self::$conns[$key] = $this->oRedis;
        }
        return true;
    }

    /**
     * 根据 keys 获取value
     * @param unknown_type $keys
     */
    public function mget($keys) {
        Tool_logger::runningLog(__CLASS__ , 'mget',implode(',', $keys));
        try{
            return $this->oRedis->getMultiple($keys);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis mget  keys | '.$keys ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis mget ', 'keys | '.$keys);
            return false;
        }
    }


    public function get($key) {
        Tool_logger::runningLog(__CLASS__ , 'get',$key);
        try{
            return $this->oRedis->get($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis get  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis get ', 'key | '.$key);
            return false;
        }

    }

    public function set($key,$value) {
        Tool_logger::runningLog(__CLASS__ , 'set',$key);
        try{
            return $this->oRedis->set($key,$value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis set  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis set ', 'key | '.$key.' value | '.$value);
            return false;
        }
    }

    public function setex($key,$value,$time) {
Tool_logger::runningLog(__CLASS__ , 'setex',$key);
        try{
            return $this->oRedis->set($key,$value,$time);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis setex  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis set ', 'key | '.$key.' value | '.$value);
            return false;
        }
    }

    public function keys($pattern) {

        try{
            return $this->oRedis->keys($pattern);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis keys  pattern | '.$pattern ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis keys ', 'pattern | '.$pattern);
            return false;
        }
    }


    public function delete($key) {
Tool_logger::runningLog(__CLASS__ , 'delete',$key);
        try{
            return $this->oRedis->delete($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis delete  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //    Tool_logger::debugLog('redis delete ', 'key | '.$key);
            return false;
        }
    }


    public function incr($key) {
Tool_logger::runningLog(__CLASS__ , 'incr',$key);
        try{
            return $this->oRedis->incr($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis incr  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis incr ', 'key | '.$key);
            return false;
        }
    }

    public function decr($key) {
Tool_logger::runningLog(__CLASS__ , 'decr',$key);
        try{
            return $this->oRedis->decr($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis decr  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis decr ', 'key | '.$key);
            return false;
        }
    }
    public function exists($key) {

        try{
            return $this->oRedis->exists($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis exists  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis exists ', 'key | '.$key);
            return false;
        }
    }

    public function expireAt($key, $cache_time){

        try{
            return $this->oRedis->expireAt($key , $cache_time);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis expireAt  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis expireAt ', 'key | '.$key);
            return false;
        }
    }

    public function zSize($key){

        try{
            return $this->oRedis->zSize($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis zSize  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zSize ', 'key | '.$key);
            return false;
        }
    }
    public function sort($key, $sort_map){

        try{
            return $this->oRedis->sort($key, $sort_map);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis sort  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis sort ', 'key | '.$key);
            return false;
        }
    }
    public function type($key){

        try{
            return $this->oRedis->type($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis type  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis type ', 'key | '.$key);
            return false;
        }
    }
    public function lLen($key){

        try{
            return $this->oRedis->lLen($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lLen  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lLen ', 'key | '.$key);
            return false;
        }
    }

    public function lRange($key, $start, $end){

        try{
            return $this->oRedis->lRange($key, $start, $end);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lRange  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lRange ', 'key | '.$key);
            return false;
        }
    }

    public function zIncrBy ($key, $increment , $member  ){

        try{
            return $this->oRedis->zincrby ($key, $increment, $member);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis zIncrBy  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zIncrBy ', 'key | '.$key);
            return false;
        }
    }

    public function zRevRange($key, $start, $end){

        try{
            return $this->oRedis->zRevRange($key, $start, $end);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis zRevRange  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zRevRange ', 'key | '.$key);
            return false;
        }
    }

    public function zRevRank($key, $val){

        try{
            return $this->oRedis->zRevRank($key, $val);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis zRevRank  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zRevRank ', 'key | '.$key);
            return false;
        }
    }

    public function zInter($key, $array){

        try{
            return $this->oRedis->zInter($key, $array);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis zInter  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zInter ', 'key | '.$key);
            return false;
        }
    }

    public function hSet($hash_name, $key, $value){
        try{
            return $this->oRedis->hSet($hash_name, $key, $value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis hSet  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis hSet ', 'hash_name | '. $hash_name. ' key | '.$key. ' value | '.$value);
            return false;
        }
    }
    public function hGet($hash_name, $key){
        try{
            return $this->oRedis->hget($hash_name, $key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis hGet  hash_name | '. $hash_name. ' key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis hGet ', 'hash_name | '. $hash_name. ' key | '.$key);
            return false;
        }
    }

    public function hIncrby($hash_name, $key, $value){
        try{
            return $this->oRedis->hincrby($hash_name, $key, $value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis hincrby  hash_name | '. $hash_name. ' key | '.$key. ' value | '.$value ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis hincrby ', 'hash_name | '. $hash_name. ' key | '.$key. ' value | '.$value);
            return false;
        }
    }

    public function hMGet($hash_name, $array){
        try{
            return $this->oRedis->hmGet($hash_name, $array);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis hMGet  hash_name | '. $hash_name ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis hMGet ', 'hash_name | '. $hash_name);
            return false;
        }
    }

    public function zAdd($key, $score, $member){
        try{
            return $this->oRedis->zAdd($key, $score, $member);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis zAdd  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zAdd ', 'key | '. $key);
            return false;
        }
    }

    public function zRange($key, $start, $end, $withscores = 0){
        try{
            return $this->oRedis->zRange($key, $start, $end, $withscores);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis zRange  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
        //    Tool_logger::debugLog('redis zRange ', 'key | '. $key);
            return false;
        }
    }

    public function zRemRangeByRank($key, $start, $end){
        try{
            return $this->oRedis->zRemRangeByRank($key, $start, $end);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis zRemRangeByRank  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zRemRangeByRank ', 'key | '. $key);
            return false;
        }
    }

    public function zScore($key, $value){
        try{
            return $this->oRedis->zScore($key, $value);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis zScore  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis zScore ', 'key | '. $key);
            return false;
        }
    }

    public function sAdd($key, $value){
        try{
            return $this->oRedis->sAdd($key, $value);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis sAdd  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
        //    Tool_logger::debugLog('redis sAdd ', 'key | '. $key);
            return false;
        }
    }

    public function sIsMember($key, $value){
        try{
            return $this->oRedis->sIsMember($key, $value);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis sIsMember  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis sIsMember ', 'key | '. $key);
            return false;
        }
    }


    public function sInter(){
        try{
            $args = func_get_args ();

            return call_user_func_array ( array ($this->oRedis, 'sInter', ), $args );

        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis sInter  sets | '.$args ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis sInter ', 'sets | '. $args);
            return false;
        }
    }

    public function sInterStore(){
        try{
            $args = func_get_args ();

            return call_user_func_array ( array ($this->oRedis, 'sInterStore', ), $args );

        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis sInterStore  sInterStore | '.$args ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis sInterStore ', 'sInterStore | '. $args);
            return false;
        }
    }

    public function sRem($key, $value){
        try{
            return $this->oRedis->sRem($key, $value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis sRem  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis sRem ', 'key | '. $key);
            return false;
        }
    }

    public function lPush($key, $value){
        try{
            return $this->oRedis->lPush($key, $value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lPush  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lPush ', 'key | '. $key);
            return false;
        }
    }

    public function lRem($key, $value, $count=0){
        try{
            return $this->oRedis->lRem($key, $value, $count);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lRem  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lRem ', 'key | '. $key);
            return false;
        }
    }

    public function lTrim($key, $start=0, $end=0){
        try{
            return $this->oRedis->lTrim($key, $start, $end);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lTrim  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lTrim ', 'key | '. $key);
            return false;
        }
    }

    public function lInsert($key, $pivot, $value, $pos = 1){
        if ($pos > 0 ) {
            $config = 'AFTER';
        } else {
            $config = 'BEFORE';
        }
        try{
            return $this->oRedis->lInsert($key, $config, $pivot, $value);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lInsert  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lInsert ', 'key | '. $key);
            return false;
        }
    }

    public function lSize($key){
        try{
            return $this->oRedis->lSize($key);
        }catch(Exception $oRedis){
            Tool_logger::monitorLog(__CLASS__,'redis lSize  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lSize ', 'key | '. $key);
            return false;
        }
    }

    public function rPush($key, $value){
        try{
            return $this->oRedis->rPush($key, $value);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis rPush  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis rPush ', 'key | '. $key);
            return false;
        }
    }

    public function lGet($key, $value){
        try{
            return $this->oRedis->lGet($key, $value);
        }catch(Exception $oRedis){
                Tool_logger::monitorLog(__CLASS__,'redis lGet  key | '.$key ,Tool_logger::LOG_LEVEL_NOTICE);
            //Tool_logger::debugLog('redis lGet ', 'key | '. $key);
            return false;
        }
    }
}