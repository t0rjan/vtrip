<?php
/**
 * 创建模型类
 * 
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 * 
 */
class ml_model_dbTripLikeme extends Lib_datamodel_db 
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
        parent::__construct('trip_photo' , $db_config['trip_photo']);
    }
    
    function addLikeme($act_uid , $dest_uid , $trip_id , $photo_id = 0)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
            'act_uid' => $act_uid,
            'uid' => $dest_uid,
            'trip_id' => $trip_id,
            'photo_id' => $photo_id,
            'ctime' => time(),
        );
        return $this->insert($data);
    }

    function listLikemeByTrip($uid , $trip_id , $photo_id = 0 , $page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $start = ($page -1) * $pagesize;
        $photo_where = $photo_id > 0 ? ' AND photo_id = '.$photo_id : '';
        $sql = 'select * from '.$this->table.' where uid = '.$uid.$photo_where.' order by id desc limit '.$start.' , '.$pagesize;
        return $this->fetch($sql);
    }
    function listLikemeCountByTrip($uid , $trip_id , $photo_id = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $photo_where = $photo_id > 0 ? ' AND photo_id = '.$photo_id : '';
        $sql = 'uid = '.$uid.$photo_where;
        return $this->fetch_count($sql);
    }
}
?>