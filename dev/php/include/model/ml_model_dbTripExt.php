<?php
/**
 * 
 */
class ml_model_dbTripExt extends Lib_datamodel_db 
{
    
/**
 * 创建构造函数
 *
 */
    function __construct()
    {
        /**
         * 加载数据库配置
         */
        $db_config = ml_factory::load_standard_conf('dbContent');        //目前只有一个配置文件，所以
        /**
         * 构造函数
         * 参数：
         * 1，当前模型名称
         * 2，相关数据库配置
         */
        parent::__construct('trip_ext' , $db_config['trip_ext']);
    }
    
    function getTripExtByTripId($trip_id , $uid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $sql = 'select * from '.$this->table.' where trip_id = '.$trip_id;
        return $this->fetch_row;
    }

    function addExtByTripId($trip_id , $memo)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
            'trip_id' => $trip_id,
            'memo' => $memo
        );
        return $this->insert($data);
    }
}
?>