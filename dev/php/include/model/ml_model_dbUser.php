<?php
/**
 * 创建模型类
 * 
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 * 
 */
class ml_model_dbUser extends Lib_datamodel_db 
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
        $db_config = ml_factory::load_standard_conf('db');        //目前只有一个配置文件，所以
        /**
         * 构造函数
         * 参数：
         * 1，当前模型名称
         * 2，相关数据库配置
         */
        parent::__construct('user' , $db_config['user']);
    }

    function addUser($nick)
    {
        if(!$this->init_db($uid , self::DB_MASTER))
            return false;
        $data = array(
                'nick' => $nick,
                'reg_time' => time(),
            );
        return $this->insert($data);
    }

    function listUser($page = 1,$pagesize = 10)
    {
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;

        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }
}
?>