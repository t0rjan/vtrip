<?php
/**
 * @copyright meila.com
 * @author wangtao5@
 * @name
 * @param
 *         $xxx = 作用
 * @static
 *         XXX = 作用
 *
 *
 */

class ml_model_admin_dbPhoto extends Lib_datamodel_db
{
    private $fields = array('pic_id' , 'pid' , 'tags_ids' , 'like_user' , 'like_visitor' , 'info');

    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_pic' , $db_config['meila_pic']);
    }

    public function get_by_pic_id($arr_ids)
    {
        if(!$this->init_db($uid, self::DB_SLAVE))
        return false;
        $str_ids = implode(',' , $arr_ids);
        $sql = 'select '.implode(',' , $this->fields).' from '.$this->table.' where status=1 and pic_id in('.$str_ids.')';
        $rs = $this->fetch($sql);
        return $rs;
    }
}