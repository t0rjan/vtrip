<?php
/**
 * 创建模型类
 * 
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 * 
 */
class ml_model_dbTripPhoto extends Lib_datamodel_db 
{
    const GEO_SCALE = 1000000;
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
    
    function hook_before_write($array)
    {
        $array['latitude']*=self::GEO_SCALE;
        $array['longtitude']*=self::GEO_SCALE;
        return $array;
    }
    function hook_after_fetch()
    {
        if(is_array($this->_data))
        {
            if(isset($this->_data['latitude']))
            {
                $this->_data['latitude'] /= self::GEO_SCALE;
                $this->_data['longtitude'] /= self::GEO_SCALE;
            }
            else
            {
                foreach ($this->_data as &$row) {
                    $row['latitude'] /= self::GEO_SCALE;
                    $row['longtitude'] /= self::GEO_SCALE;
                }
            }
        }
    }

    function addPhotoByTripId($trip_id , $uid , $content , $pic_id , $day , $latitude , $longtitude)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;

        $data = array(
            'trip_id' => $trip_id,
            'uid' => $uid,
            'content' => $content,
            'pic_id' => $pic_id,
            'day' => $day,
            'latitude' => $latitude,
            'longtitude' => $longtitude,
            'ctime' => time(),
        );
        return $this->insert($data);
    }

    function listPhotoByTripId($trip_id , $uid , $page = 1 , $pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $start = ($page - 1) * $pagesize;
        $sql = 'select * from '.$this->table.' where trip_id = "'.$trip_id.'" order by ctime desc limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }
    function getCountByTripId($trip_id , $uid)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $where = 'trip_id = "'.$trip_id.'"';
        return $this->fetch_count($where);
    }
}
?>