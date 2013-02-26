<?php
/**
 * 创建模型类
 * 
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 * 
 */
class ml_model_dbLmPic extends Lib_datamodel_db 
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
        parent::__construct('meila' , $db_config['meila']);
    }
    
    function getPicCntByUid($uid)
    {
        /**
         * 初始化数据库连接
         * 这个函数需要判断结果，因为可能出现连接失败的情况
         * 内部封装了库表的哈希，连接等情况
         * 表名用$this->table
         * 
         * 参数：
         * 1，哈希依据 一般我们用UID进行哈希，如果是单表，可以传空字串
         * 2，主或是从 默认为主库 从库使用 self::DB_SLAVE标识
         */
        if(!$this->init_db($uid , self::DB_SLAVE))
            return false;
        
        /**
         * 取行数专用
         * 参数：
         * 1，条件,比如：uid=1 或 nick='new_shop'
         */
        return $this->fetch_count('uid=1');
    }
    
    function getPicByPicid($uid , $pic_id)
    {
        
        if(!$this->init_db($uid))
            return false;
        
        /**
         * 取数据逻辑自行整理SQL
         * 表名使用 $this->table 如果是多表，并且已经通过init_db($uid)，此时表名已经指向哈希的结果 如attention_2f;
         * 请注意使用$this->escape 对各输入参数的安全过滤防止 SQL注入 
         */
        
        $sql = 'select * from '.$this->table.' where pic = `'.$this->escape($pic_id).'`';
        
        
        return $this->fetch_row($sql);    //取单行记录
        return $this->fetch($sql);    //取多行记录
    }
    
    
    function addPic($uid , $data)
    {
        /**
         * 默认使用主库
         */
        if(!$this->init_db($uid))
            return false;
            
        /**
         * 写入数据
         * 一般情况下，直接通过本方法即可
         * 内部会进行数据转义 防止SQL注入
         */
        $this->insert($data);
        /**
         * 插入ID
         */
        $insert_id = $this->insert_id();
        
        /**
         * 相类似的覆盖操作
         */
        $this->replace($data);
        
        /**
         * 相类似的更新操作
         * 参数：
         * 1，数据
         * 2，条件
         * 3，LIMIT
         */
        $this->update($data , 'uid='.$uid , 1);
        $affected_rows = $this->affected_rows();    //受影响的行数
        
    }
}
?>