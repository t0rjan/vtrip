<?php
/**
 * @copyright meila.com
 * @author shaopu@
 * @name
 * @param
 *         $xxx = 作用
 * @static
 *         XXX = 作用
 *
 *
 */

class ml_model_admin_dbPoint extends Lib_datamodel_db
{
    private $field = array('point_id' , 'point_info' , 'pic_id' , 'mark_pid', 'posi');

    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_point' , $db_config['meila_point']);
    }

    public function get_by_pointid($arr_picids)
    {
        if(!$this->init_db($uid, self::DB_SLAVE))
        return false;
        $sql = 'select '.implode(',' , $this->field).' from '.$this->table.' where status=1 and pic_id in('.implode(',' , $arr_picids).')';
        $rs = $this->fetch($sql);
        return $rs;
    }

}