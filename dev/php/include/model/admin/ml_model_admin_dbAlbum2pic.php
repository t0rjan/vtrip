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

class ml_model_admin_dbAlbum2pic extends Lib_datamodel_db
{
    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_album_pic' , $db_config['meila_album_pic']);
    }


    public function get_by_picid($arr_picids)
    {
        if(!$this->init_db('', self::DB_SLAVE))
            return false;
        $str_ids = implode(',' , $arr_picids);
        $sql = 'select pic_id,album_id from '.$this->table.' where status=1 and pic_id in('.$str_ids.')';
        $rs = $this->fetch($sql);
        return $rs;
    }
    
    public function get_picids_by_aid($aid)
    {
        if(!$this->init_db('', self::DB_SLAVE))
            return false;
        $sql = 'select pic_id from '.$this->table.' where status in(1,2) and album_id ='.$aid;
        $rs = $this->fetch($sql);
        return $rs;
    }
    
}