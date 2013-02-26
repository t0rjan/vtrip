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

class ml_model_admin_dbAlbumTag extends Lib_datamodel_db
{
    private $field = array('tags_id' , 'title' , 'tagsgroup_id' , 'info');

    public function __construct()
    {
        $db_config = ml_factory::load_standard_conf('dbLb');
        parent::__construct('meila_tags' , $db_config['meila_tags']);
    }

    public function get_by_tgid($arr_tgids)
    {
        if(!$this->init_db($uid, self::DB_SLAVE))
        return false;

        foreach ($arr_tgids as $tg_id)
        {
            $rs = ml_tool_lbTag::tgidDecode($tg_id);
            $arr_ids[] = $rs['tag_id'];
        }
        $str_ids = implode(',' , $arr_ids);
        $sql = 'select '.implode(',' , $this->field).' from '.$this->table.' where status=1 and tags_id in('.$str_ids.')';
        $rs = $this->fetch($sql);
        return $rs;
    }

}