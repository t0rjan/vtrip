<?php

include('../__global.php');


class adm_tags extends admin_ctrl
{
    function run()
    {

        $page = $this->input('p','all',1);
        $type = $this->input('type','all',0);
        $tag = $this->input('tag','all',0);

            $oAdmComm = new ml_model_admin_dbCommon();
        if($tag)
        {
            $aTag = explode(',', $tag);
            $oAdmComm->tags_get_by_tag($aTag);
            $data['tags'] = $oAdmComm->get_data();
            $type = $data['tags'][0]['type'];
        }
        else
        {
            
            $oAdmComm->tags_list($page,20,$type);
            $data['tags'] = $oAdmComm->get_data();

            $oAdmComm->tags_count($type);
            $data['total'] = $oAdmComm->get_data();
            $data['page'] = $page;
        }

        if($type== ML_TAGTYPE_COLOR)
        {
            global $ML_COLOR;
            $data['sub_type'] = $ML_COLOR;

        }
        $this->output($data);
    }
    
    function page_nearHotTag()
    {
        $oAdmComm = new ml_model_admin_dbGoodsAudit();
        $oAdmComm->list_goods(ml_model_admin_dbGoodsAudit::AUDITSTATUS_PASS , 1 , 1000);
        $rows = $oAdmComm->get_data();
        $aAllTag = array();
        foreach ($rows as $key => $value) {
            $aAllTag = array_merge($aAllTag, explode(',', $value['gd_tag']));

        }
        
        $aAllTag = array_count_values($aAllTag);
        arsort($aAllTag);

        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_get_by_tag(array_keys($aAllTag));
        $data['tags'] = $oAdmComm->get_data();

        $this->output($data , 'index');
    }

    function api_batch_add()
    {
        $tags = explode("\n", $this->input('tags'));
        foreach ($tags as &$value) {
            $value = trim($value);
        }
        $type = $this->input('type');
        $oAdmComm = new ml_model_admin_dbCommon();
        
        $oAdmComm->tags_batch_add($type , $tags);
        $this->back();
    }

    function api_changeTypeById()
    {
        $id = $this->input('id');
        $type = $this->input('type');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_change_type_by_id($type , $id);

        $this->back('#id'.$id);
    }
    function api_changePtById()
    {
        $id = $this->input('id');
        $pt = $this->input('pt');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_change_pt_by_id($pt , $id);

        $this->back('#id'.$id);
    }
    function api_changeSubTypeById()
    {
        $id = $this->input('id');
        $type = $this->input('sub_type');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_change_sub_type_by_id($type , $id);

        $this->back('#id'.$id);
    }

    function api_delTag()
    {
        $id = $this->input('id');
        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_del($id);
        $this->back();
    }

    function api_rebuildRdsTaghash()
    {
        $oRds = new ml_model_guang_rdsTag();
        $oRds->flushByPrefix(ml_model_guang_rdsTag::TAG_KEY_PREFFIX);
        $oRds->flushByPrefix(ml_model_guang_rdsTag::TAG_PT_KEY_PREFFIX);

        $oAdmComm = new ml_model_admin_dbCommon();
        $oAdmComm->tags_getAll();
        $aTags = $oAdmComm->get_data();


        foreach ($aTags as $key => $value) {

            if($value['type'] == ML_TAGTYPE_COLOR)
                $typevalue='color';
            else
                $typevalue='tag';
            $tagHash = ml_tool_resid::str_hash($value['tag']);
            
            $oRds->setCtgTag($tagHash , $typevalue);
            if($value['suggest_pt']>0)
                $oRds->setTagPt($tagHash , $value['suggest_pt']);

        }
        $this->back();
    }
}

new adm_tags();
?>