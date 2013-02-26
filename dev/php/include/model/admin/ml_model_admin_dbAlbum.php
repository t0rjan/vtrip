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

class ml_model_admin_dbAlbum extends Lib_datamodel_db
{
    private $field = array('`album_id`' , '`class_id`' , '`title`' , '`subtitle`' , '`desc`' , '`sort`' , '`cover_pic`' , '`info`' );

    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_album' , $db_config['meila_album']);
    }

    public function get_by_ablum_id($arr_ids)
    {
        if(!$this->init_db(0, self::DB_SLAVE))
            return false;
        $str_ids = implode(',' , $arr_ids);
        $sql = 'select '.implode(',' , $this->field).' from '.$this->table.' where status=1 and album_id in('.$str_ids.')';
        $rs = $this->fetch($sql);
        return $rs;
    }
    public function get_album_by_class_id($class_id){
        if(!$this->init_db(0, self::DB_SLAVE))
            return false;
        
        $sql = 'select album_id from '.$this->table.' where status=1 and class_id='.$class_id;
        $rs = $this->fetch($sql);
        return $rs;
    }
}