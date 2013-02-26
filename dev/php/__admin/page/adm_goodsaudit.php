<?php

include('../__global.php');


class adm_goodsaudit extends admin_ctrl
{
    function run()
    {
        $page = $this->input('p','all',1);
        $type = $this->input('type','all',0);
        $tag = $this->input('tag','all',0);

        $oAdmComm = new ml_model_admin_dbGoodsAudit();
        $oAdmComm->list_goods();
        $data['goods'] = $oAdmComm->get_data();
        $this->output($data);
    }
    
    function api_batch_audit()
    {
        $aRid = $_POST['rid'];
        $oAdmComm = new ml_model_admin_dbGoodsAudit();
        foreach ($aRid as $rid) {
            if($_POST['pt'][$rid] > 0)
            {
                $aData = array(
                    'sex' => $_POST['sex'][$rid],
                    'color' => $_POST['color'][$rid],
                    'gd_tag' => $_POST['tag'][$rid],
                    'score' => $_POST['pt'][$rid],
                    'gd_catelog' => $_POST['ctg'][$rid],
                    'audit_status' => 1,
                );
            }
            $oAdmComm->update_by_rid($rid , $aData);
        }
        $this->back();
    }

    
}

new adm_goodsaudit();
?>