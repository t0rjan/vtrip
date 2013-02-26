<?php

include('../__global.php');


class adm_op extends admin_ctrl
{
    function run()
    {

        $this->output($data);
    }
    
    function page_htmlblock_list()
    {
        $page = $this->input('p' , 'get' , 1);
        $type = 0;
        $pagesize = 20;

        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->htmlblock_list($type , $page , $pagesize);
        $data['list'] = $oAdmComm->get_data();

        $this->output($data);
    }

    function api_htmlblock_add()
    {
        
        $name = $this->input('name');
        $content = $this->input('content');

        $page = $this->input('page');
        $comment = $this->input('comment');

        $oAdmComm = new ml_model_admin_dbCommon();
        
        $oAdmComm->htmlblock_add($name , $content , $page , $comment);
        $this->back();
    }

    function api_htmlblock_update()
    {
        $id = $this->input('id');
        $name = $this->input('name');
        $content = $this->input('content');

        $page = $this->input('page');
        $comment = $this->input('comment');

        $oAdmComm = new ml_model_admin_dbCommon();
        
        $oAdmComm->htmlblock_update($id,$name , $content , $page , $comment);
        $this->back();
    }

    function api_htmlblock_publish()
    {
        $id = $this->input('id');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->htmlblock_getbyid($id);
        $row = $oAdmComm->get_data();

        $filepath = ML_HTMLBLOCK_DIR.'/'.$row['name'].'.php';
        file_put_contents($filepath, $row['content']);
        $this->back();
    }
}

new adm_op();
?>