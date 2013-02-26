<?php

include('../__global.php');

class adm_conf_edit extends admin_ctrl
{
    
    private $oArraytree;
    private $sConfName;
    
    protected function _construct()
    {
        $conf_name = $this->input('conf');
        if(!in_array($conf_name , array('material' , 'product_class' , 'product_map','common_object')))
        {
            echo '配置信息错误';
            $this->_redirect('');
        }
        
        $data = k::config($conf_name);
        $this->sConfName = $conf_name;
        $this->module_data['conf'] = $conf_name;
        $this->oArraytree = new lib_arraytree($data);
        
    }
    
    private function _save_conf()
    {
        $data = $this->oArraytree->dump();
        $sCode = "<?php return ".var_export($data , true)."; ?>";
        file_put_contents(K5_CONFIG_PATH."/".$this->sConfName.".php" , $sCode);
        return true;
    }
    
    protected function run()
    {
        
        $aList = $this->oArraytree->get_list();
        $data['list'] = $aList;
        
        $this->output($data);
    }
    
    protected function api_add()
    {
        $data['name'] = $this->input('name');
        $data['eng_name'] = $this->input('eng_name');
        $parent_id = $this->input('parent_id');
        
        $rs = $this->oArraytree->add($data ,$parent_id);
        $this->_save_conf();
        
        $this->_redirect('?conf='.$this->sConfName);
    }
    
    protected function api_edit()
    {
        $data['name'] = $this->input('name');
        $data['eng_name'] = $this->input('eng_name');
        $parent_id = $this->input('parent_id');
        $id = $this->input('id');
        
        $rs = $this->oArraytree->edit($id , $data ,$parent_id);
        $this->_save_conf();
        
        $this->_redirect('?conf='.$this->sConfName);
        
    }
    
    protected function api_del()
    {
        $id = $this->input('id');
        $this->oArraytree->del($id);
        $this->_save_conf($this->oArraytree->dump());
        
        $this->_redirect('?conf='.$this->sConfName);
    }
    
    protected function page_add()
    {
        $data['top_list'] = $this->oArraytree->get_parents();
        $this->output($data);
    }
    protected function page_edit()
    {
        $id = (int)$_GET['id'];
        if(!$id)
            $this->page_add();
           
        $data['id'] = $id;
        $data['class_info'] = $this->oArraytree->get_by_id($id);
        $data['top_list'] = $this->oArraytree->get_parents();
        $this->output($data);
    }
}

new adm_conf_edit();
?>