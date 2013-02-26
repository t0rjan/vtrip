<?php
/**
 *@fileoverview: [群博客] 数据模型基类
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 05:16:19 GMT 2010
 *@copyright: sina
 *@
 */

class Lib_datamodel_db extends Lib_datamodel_abstract
{
    private static $_connections;                   //连接池
    const LOG_DIR = 'ml_model';                     //log目录名
    const DEBUG = true;                             //调试模式 记录所有SQL
    const DB_MASTER = 'master';                        
    const DB_SLAVE = 'slave';    
    
    private $_datamodel_name;                       //数据模型名称
    private $_db_config;                            //当前模型的数据库配置
    private $_conn;                                 //当前DB连接
    private $_fetch_result_num = false;
    
    protected $table;                               //当前的数据表名
    
    /**
     * 构造函数
     * @todo 按项目进行DB配置分文件
     *
     */
    public function __construct($db_name , $db_stdconf)
    {
        //忽略容错

        $this->_datamodel_name = $db_name;
        $this->_db_config = $db_stdconf;
    }
    
    
//内部方法~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * 连接MYSQL
     *
     * @param string $host      
     * @param string $port
     * @param string $user
     * @param string $pw
     * @param string $db_name
     * @return bool
     */
    private function _connect($host , $port , $user , $pw , $db_name)
    {

        //连接池的KEY
        $hash_key = md5($host.$port.$user);
        
        //已存在的连接直接返回
        if(!isset(self::$_connections[$hash_key]) || !mysql_ping(self::$_connections[$hash_key]))
        {
            if(is_resource(self::$_connections[$hash_key]))
            {
                mysql_close(self::$_connections[$hash_key]);
            }
            $start = $this->_microtime();
            
            //连接数据库
            Tool_logger::runningLog(__CLASS__ , 'connect',$host.':'.$port);
            for ($i = 0;$i < 3;$i++)
            {
                $conn = mysql_connect($host.':'.$port , $user , $pw);

                if($conn !== false)
                    break;
            }
            if(!$conn)
            {
                Tool_logger::monitorLog(__CLASS__ , 'connect_err '.$host.' '.$user.' '.mysql_error() , Tool_logger::LOG_LEVEL_ALERM );
                return false;
            }
            
            
            
            //连接次数
            if($i>0)
                Tool_logger::monitorLog(__CLASS__ , 'connect_fail '.$host.' '.$i.' times' , Tool_logger::LOG_LEVEL_NOTICE );
            
            $t = $this->_microtime() - $start;
            //记录时间
            if($t > 1)
                Tool_logger::monitorLog(__CLASS__ , 'conn '.$t.' '.$host.':'.$port , Tool_logger::LOG_LEVEL_NOTICE );
                
            self::$_connections[$hash_key] = $conn;
        }
        
        $this->_conn = self::$_connections[$hash_key];
        
        mysql_set_charset('utf8' , $this->_conn);
        


        //选择数据库
        $rs = mysql_select_db($db_name , self::$_connections[$hash_key]);
        if(!$rs)
        {
            $rs = mysql_select_db($db_name , self::$_connections[$hash_key]);
            if(!$rs)
            {
                Tool_logger::monitorLog(__CLASS__ , 'select_db_err '.$host.' '.$user.' '.$db_name.' '.mysql_error() , Tool_logger::LOG_LEVEL_ALERM );
                return false;
            }
        }
        
        return true;
    }
    private function _microtime()
    {
        return array_sum(explode(' ' , microtime()));
    }
    
//子类中使用的方法~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * 数据库操作准备 每次数据库操作之前调用 内部封装数据库的分库和分表逻辑
     *
     * @param mix $hash_key         //哈希依据  博客默认为UID
     * @param unknown_type $type    //数据库类型 master=主库 slave=从库 other根据db配置选择（比如文章列表专用）
     * @return bool                 //连接是否成功
     */
    protected function init_db($hash_key = '' , $type = self::DB_MASTER)
    {
        if(!isset($this->_db_config['connect'][$type]))
        {
            Tool_logger::monitorLog(__CLASS__ , 'db_connect_type_err '.$this->_datamodel_name.' '.$type , Tool_logger::LOG_LEVEL_ALERM );
            return false;
        }
        
        //分库
        $db_key = 0;
        $n = count($this->_db_config['connect']['host'][$type]);
        if($n > 1)
            $db_key = Tool_sina::calc_hash_db($hash_key , $n);
            

        $host    = $this->_db_config['connect'][$type]['host'][$db_key];
        list($host , $port) = explode(':' , $host);
        $user    = $this->_db_config['connect'][$type]['user'];
        $pw      = $this->_db_config['connect'][$type]['pw'];
        $db_name = $this->_db_config['connect'][$type]['name'];
     
        //连接DB
        $rs = $this->_connect($host,'',$user,$pw,$db_name);

        if(!$rs)
            return false;
            
        //分表
        if($this->_db_config['tb_n'] > 1)
            $this->table = $this->_db_config['tb_prefix'].Tool_sina::calc_hash_tbl($hash_key , $this->_db_config['tb_n']);
        else 
            $this->table = $this->_db_config['tb_prefix'];
        return true;
    }
    protected function switch_fetch_num()
    {
        $this->_fetch_result_num = true;
    }
    
    /**
     * 执行SQL
     *
     * @param string $sql
     * @param bool $return_data //是否需要返回数据
     * @return bool             //操作是否成功
     */
    protected function query($sql , $return_data = false)
    {
        $result_type = $this->_fetch_result_num ? MYSQL_NUM : MYSQL_ASSOC;
        $this->_fetch_result_num = false;
        if (SYSDEF_DEBUG){
            
            $this->lastSQL = $sql;
            $this->allSQL[] = $sql;
        }
        //检查资源
        if(!is_resource($this->_conn))
        {
            Tool_logger::monitorLog(__CLASS__ , 'query_no_connect '.$sql , Tool_logger::LOG_LEVEL_ALERM );
            return false;
        }
        
        $this->_data = array();
        
        
            
        $start = $this->_microtime();
        $rs = mysql_query($sql , $this->_conn);
        $t = $this->_microtime() - $start;
        Tool_logger::runningLog(__CLASS__ , 'query' , $sql.' '.$t);

        if(!$rs)
        {
            Tool_logger::monitorLog(__CLASS__ , 'query_err '.$sql.' '.mysql_error($this->_conn) , Tool_logger::LOG_LEVEL_ALERM );
            return false;
        }
        if( $t > 1)
            Tool_logger::monitorLog(__CLASS__ , 'slow '.$sql.' '.$t , Tool_logger::LOG_LEVEL_NOTICE );
        
        //返回数据？
        
        if($return_data)
        {
            while (($row = mysql_fetch_array($rs , $result_type)) !== false)
            {
                $this->_data[] = $row;
            }
        }
        
        $this->hook_after_fetch();
        return true;
    }
    /**
     * 取记录
     *
     * @param string $sql
     * @return bool
     */
    protected function fetch($sql)
    {
        return $this->query($sql , true);
    }
    /**
     * 取单行记录
     *
     * @param string $sql
     * @return bool
     */
    protected function fetch_row($sql)
    {
        $rs = $this->query($sql , true);
        if(!$rs)
            return false;
            
        $this->_data = $this->_data[0];
        return true;
    }
    /**
     * 取行数
     *
     * @param string $where 条件
     * @return bool
     */
    protected function fetch_count($where = '')
    {
        $where ? $where = ' WHERE '.$where : '';
        $sql = 'SELECT count(*) n FROM '.$this->table.$where;
        $this->switch_fetch_num();
        $rs = $this->query($sql , true);
        if(!$rs)
            return false;
            
        $this->_data = $this->_data[0][0];
        return true;
    }
    
    /**
     * 插入新记录
     *
     * @param array $array
     * @return bool
     */
    protected function insert($array)
    {
        
        $array = $this->hook_before_write($array);
        
        $sql_set = $this->format_set_sql($array);
        
        if(!$sql_set)
            return false;
            
        $sql = 'INSERT INTO `'.$this->table.'` '.$sql_set;
        
        return $this->query($sql);
    }
    /**
     * 覆盖操作
     *
     * @param array $array
     * @return bool
     */
    protected function replace($array)
    {
        $array = $this->hook_before_write($array);
        $sql_set = $this->format_set_sql($array);
        if(!$sql_set)
            return false;
            
        $sql = 'REPLACE INTO `'.$this->table.'` '.$sql_set;
        return $this->query($sql);
    }
    /**
     * 更新操作
     *
     * @param array $array
     * @param string $where
     * @param int $limit
     * @return bool
     */
    protected function update($array , $where , $limit = 0)
    {
        $array = $this->hook_before_write($array);
        $sql_set = $this->format_set_sql($array);
        if(!$sql_set)
            return false;
            
        $sql = 'UPDATE `'.$this->table.'` '.$sql_set.' WHERE '.$where.($limit > 0 ? ' LIMIT '.$limit : '');
        
        return $this->query($sql);
    }
    
    /**
     * 安全过滤
     *
     * @param string $s
     * @return string
     */
    protected function escape($s)
    {
        return mysql_escape_string($s);
    }
    /**
     * 格式化 SET `aa`='b',`cc`='d'
     *
     * @param array $array  //array([field] => {value},)
     * @return string
     */
    protected function format_set_sql($array)
    {
        if(!$array)
        {
            Tool_logger::monitorLog(__CLASS__ , __FUNCTION__.' data_null' , Tool_logger::LOG_LEVEL_ALERM );
            return '';
        }    
        
        $rs = '';
        foreach ($array as $k => $v)
        {
            $rs .= '`'.$k.'` = "'.$this->escape($v).'",';
        }
        $rs = ' SET '.rtrim($rs , ',');
        return $rs;
    }

//公用方法~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    
    /**
     * 取最后INSERT 的数据ID
     *
     * @return int
     */
    public function insert_id()
    {
        return mysql_insert_id($this->_conn);
    }
    /**
     * 取写操作影响的行数
     *
     * @return int
     */
    public function affected_rows()
    {
        return mysql_affected_rows($this->_conn);
    }
    
    /**
     * 在调试模式下获得最后一次执行的SQL语句
     * @return string
     */
    public function getLastSQL(){
        if (!SYSDEF_DEBUG){
            return 'please define SYSDEF_DEBUG = true';
        }
        return $this->lastSQL;
    }
    /**
     * 在调试模式下获得所有执行过的SQL语句数组
     * @return array
     */
    public function getAllSQL(){
           if (!SYSDEF_DEBUG){
            return 'please define SYSDEF_DEBUG = true';
        }
        return $this->allSQL;
    }
    
//钩子方法
    protected function hook_after_fetch(){}
    
    protected function hook_before_write($array)
    {
        return $array;
    }
}
?>