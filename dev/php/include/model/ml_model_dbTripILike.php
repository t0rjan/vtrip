<?php
/**
 * 创建模型类
 * 
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 * 
 */
class ml_model_dbTripILike extends Lib_datamodel_db 
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
    
    function isIliked($act_uid , $dest_uid , $trip_id , $photo_id = 0)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $photo_where = $photo_id > 0 ? ' and photo_id = '.$photo_id : '';
        $sql = 'uid = '.$act_uid.' and trip_uid = '.$dest_uid.' and trip_id = '.$trip_id.$photo_where;
        return $this->fetch_count($sql);

    }

    function addIlike($act_uid , $dest_uid , $trip_id , $photo_id = 0)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
            'trip_uid' => $dest_uid,
            'uid' => $act_uid,
            'trip_id' => $trip_id,
            'photo_id' => $photo_id,
            'ctime' => time(),
        );
        return $this->insert($data);
    }

    function listIlike($uid , $page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $start = ($page -1) * $pagesize;
        
        $sql = 'select * from '.$this->table.' where uid = '.$uid.' order by id desc limit '.$start.' , '.$pagesize;
        return $this->fetch($sql);
    }
    function listIlikeCountByTrip($uid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        $photo_where = $photo_id > 0 ? ' AND photo_id = '.$photo_id : '';
        $sql = 'uid = '.$uid.$photo_where;
        return $this->fetch_count($sql);
    }
}
?>