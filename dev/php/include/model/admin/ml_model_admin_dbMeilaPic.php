<?php
/**
 * 创建模型类
 *
 * 类名
 * ml_model_db...后面接自己的名字，驼峰写法
 *
 *
 */
class ml_model_admin_dbMeilaPic extends Lib_datamodel_db
{

    /**
     * 创建构造函数
     *
     */
    function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_pic' , $db_config['meila_pic']);
    }
    /**
     * 是否有pid的图片
     *
     * @param unknown_type $pid
     * @return unknown
     */

    function getPicByPid($pid)
    {
        if(!$this->init_db())
        return false;
        $sql = 'select `like_user`,`like_visitor`,`pid` from '.$this->table.' where  `pic_id` = \''.$pid.'\'';
        return $this->fetch_row($sql);
    }

    function getPicByPids($pids)
    {
        if(!$this->init_db())
        return false;
        $where = implode(",", $pids);
        $sql = "select `like_user`,`like_visitor`,`pic_id` from `$this->table` where  `pic_id` in ($where)";
        $rs=$this->fetch($sql);
        $data=$rs? $this->_data: null;
        $data = Tool_array::format_2d_array($data , 'pic_id' , Tool_array::FORMAT_FIELD2ROW);
        return $data;
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
    }



    public function update($array,$where) {
        if(!$this->init_db())
        return false;
        return  parent::update($array,$where);
    }

    public function incr_like_cnt($set,$pic_id) {
        if(!$this->init_db())
            return false;
        
        $array = array('like_user', 'like_visitor');
        if(!in_array($set, $array))
            $set = $array[0];
        
        $sql = 'UPDATE `'.$this->table."` set `$set`=`$set`+1 WHERE pic_id=".$pic_id." limit 1";
        return $this->query($sql);
    }
}
?>