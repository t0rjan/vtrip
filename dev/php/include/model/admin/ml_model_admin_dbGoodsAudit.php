<?php
class ml_model_admin_dbGoodsAudit extends Lib_datamodel_db
{
    const TABLE_NAME = 'mla_goodsaudit_';

    const AUDITSTATUS_TMP = 0;
    const AUDITSTATUS_PASS = 1;
    const AUDITSTATUS_INREDIS = 2;

    public function __construct() {
        
        $db_config = ml_factory::load_standard_conf ( 'dbAdmin' );
        
        parent::__construct('admin' , $db_config['admin']);
    }

    protected function hook_after_fetch()
    {
        $data = $this->get_data();

        if(isset($data['gd_info']))
        {
            $data['gd_info'] = unserialize($data['gd_info']);
            $this->set_data($data);
        }else
        {
            foreach($data as $key=>$val)
            {
                if(isset($val['gd_info']))
                {
                    $val['gd_info'] =unserialize($val['gd_info']);
                }
                $arr[$key]=$val;
            }
            $this->set_data($arr);
        }

        return true;
    }
    protected function hook_before_write($array)
    {
        if(isset($array['gd_info']))
        {
            $array['gd_info'] = serialize($array['gd_info']);
        }
        return $array;
    }
    
    public function insert_content($data,$catelog,$aTag){
        if(!$this->init_db())
        return false;
        $array['rid'] = $data['rid'];
        $array['uid'] = $data['uid'];
        $array['content'] = $data['content'];
        $array['pic_id'] = $data['pic_id'];
        $array['gd_title'] = $data['gd_title'];
        $array['gd_price'] = $data['gd_price'];
        $array['gd_catelog'] = $catelog;
        $array['gd_info'] = $data['gd_info'];
        $array['gd_tag'] = implode(',', $aTag);
        $array['status'] = 1;
        $array['color'] = $data['color'];
        $array['sex'] = $data['sex'];
        $array['audit_status'] = $data['audit_status'];
        $array['ctime'] = $data['ctime'];
        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        return $this->insert($array);

    }
    /**
     * 修改A表淘宝客标识
     * @gaojian3
     * @param unknown_type $rid
     * @return unknown
     */
    public function update_gdclick($rid){
        if(!$this->init_db())
        return false;
        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        $where='`rid` = "'.$this->escape($rid).'"';
        $update['gd_click'] = 1;
        return $this->update($update,$where);
    }
    
    /**
     * 删除商品
     * @gaojian3
     * @param unknown_type $rid
     * @return unknown
     */
    public function delete_goods($rid){
        if(!$this->init_db())
            return false;
            
        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        $sql = 'delete from `'.$this->table.'` where `rid` =  "'.$this->escape($rid).'"';
        return $this->query($sql,true);
    }
    
    public function list_goods($audit_status = self::AUDITSTATUS_TMP , $page = 1 , $pagesize=20)
    {
        if(!$this->init_db())
            return false;

        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        $start = ($page-1)*$pagesize;
        $sql = 'select * from '.$this->table.' where audit_status='.$audit_status.' order by ctime limit '.$start.','.$pagesize;
        return $this->fetch($sql);
    }

    public function count_goods($audit_status = self::AUDITSTATUS_TMP)
    {
        if(!$this->init_db())
            return false;
        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        $where = 'audit_status = '.$audit_status;
        return $this->fetch_count($where);
    }

    public function update_by_rid($rid , $data)
    {
        if(!$this->init_db())
            return false;
        $where = 'rid ="'.$rid.'"';
        $date= date("Y");
        $this->table = self::TABLE_NAME.$date;
        return $this->update($data , $where);
    }
}
?>