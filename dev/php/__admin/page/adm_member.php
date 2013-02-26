<?php

include('../__global.php');

class adm_member extends admin_ctrl
{
    /**
     * 申请列表
     *
     */
    protected function page_apply_list()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $page = (int)$this->input('p');
        $data['stat'] = $this->input('stat') == 1 ? 1 : -1;
        
        $oMemberApply = K::load_mod('member_apply');
        $oMemberApply->get_list($page , $data['stat'] , 3) or $this->busy();
        $data['apply_list'] = $oMemberApply->get_data();
        /*
        if(count($data['apply_list']))
        {
            foreach ($data['apply_list'] as $k => $row)
            {
                //访问历史
                $history = unserialize($row['visit_history']);

                if(count($history['class']) > 0)
                {
                    $oArraytree = new lib_arraytree(array());
                    
                    $oArraytree->restart(K::config('product_class'));
                    $aClass = $oArraytree->get_id2field('name');
                    
                    $oArraytree->restart(K::config('product_map'));
                    $aMap = $oArraytree->get_id2field('name');
                    
                    $oArraytree->restart(K::config('material'));
                    $aMaterial = $oArraytree->get_id2field('name');
                    
                    foreach ($history['class'] as $row)
                    {
                         list($map_id , $class_id , $material_id) = explode(',' , $row);
        
                         $a = array(
                            ($map_id ?  $aMap[$map_id] : ''),
                            ($class_id ?  $aClass[$class_id] : ''),
                            ($material_id ? $aMaterial[$class_id] : ''),
                         );
                         
                         $aHis[] = implode(' : ' , $a);
                    }
                    $data['apply_list'][$k]['history'] = $aHis;
                }
            }
        }
        */
        //$data['history'] = $aHis;
        
        $this->output($data);
    }
    /**
     * 登录用户表单
     *
     */
    protected function page_create_member()
    {
        global $K_ARRAY_USER_LEVEL;
         $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
           
        $apply_id = $this->input('id');
        if($apply_id)
        {
            $data['apply_id'] = $apply_id;
            $oMemberApply = K::load_mod('member_apply');
            $oMemberApply->get_by_id($apply_id) or $this->busy();
            $data['userinfo'] = $oMemberApply->get_data();
            
            //根据称呼生成登录名
            $data['username'] = substr($data['userinfo']['email'] , 0 , strpos($data['userinfo']['email'] , '@'));
            //$data['username'] = lib_gbk2pinyin::convert(K_str::utf2gb($data['userinfo']['name']));
        }
        
        $data['password'] = lib_random::formated_str('nnannanna');
        $data['user_level'] = $K_ARRAY_USER_LEVEL;
        $this->output($data);
    }
    /**
     * 创建用户接口
     *
     */
    protected function page_do_create_member()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        $data['username'] = $this->input('username');
        $data['password'] = $this->input('password');
        $data['level'] = (int)$this->input('level');
        $data['stat'] = (int)$this->input('stat');
        
        $data['name'] = $this->input('name');
        $data['email'] = $this->input('email');
        $data['mobile'] = $this->input('mobile');
        $data['fax'] = $this->input('fax');
        $data['company'] = $this->input('company');
        $data['intro'] = $this->input('intro');
        
        $oMember = K::load_mod('member');
        
        
        $rs = $oMember->create_user($data , $uid);
        if(!$rs)
            $this->_redirect('?page=list' , '写入失败');
        
        
        $apply_id = (int)$this->input('apply_id');
        if($apply_id)
        {
            $oMemberApply = K::load_mod('member_apply');
            $oMemberApply->edit_stat(K_COMMON_LOCK , $apply_id) or $this->busy('apply');
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '创建成功 <a href="?page=show_email&uid='.$uid.'">生成邮件</a>';
        
        die;
    }
    
    /**
     * 用户列表
     *
     */
    protected function page_list()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $page = (int)$this->input('p');
        $data['stat'] = $this->input('stat');
        
        $oMember = K::load_mod('member');
        $oMember->list_member($data['stat'] , 50 , $page) or $this->busy();
        $oMember->fetch_count($data['total']);
        $data['user_list'] = $oMember->get_data();
        
        
        
        $this->output($data);
    }
    /**
     * 编辑用户表单
     *
     */
    protected function page_show_member()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $uid = (int)$this->input('uid');
        
        $oMember = K::load_mod('member');
        $oMember->get_user($uid , true) or $this->busy();
        $data['userinfo'] = $oMember->get_data();
        
        
        //访问历史
        $history = unserialize($data['userinfo']['history']);
        if(count($history['class']) > 0)
        {
            $oArraytree = new lib_arraytree(array());
            
            $oArraytree->restart(K::config('product_class'));
            $aClass = $oArraytree->get_id2field('name');
            
            $oArraytree->restart(K::config('product_map'));
            $aMap = $oArraytree->get_id2field('name');
            
            $oArraytree->restart(K::config('material'));
            $aMaterial = $oArraytree->get_id2field('name');
            
            $aHis = array();
            foreach ($history['class'] as $row)
            {
                 list($map_id , $class_id , $material_id) = explode(',' , $row);

                 $a = array(
                    ($map_id ?  $aMap[$map_id] : ''),
                    ($class_id ?  $aClass[$class_id] : ''),
                    ($material_id ? $aMaterial[$class_id] : ''),
                 );
                 
                 $aHis['type'][] = implode(' : ' , $a);
            }
        }
        if(count($history['product']) > 0)
        {
            $oProduct = K::load_mod('product');
            $oProduct->list_by_id($history['product']);
            $aHis['product'] = $oProduct->get_key2map('id' , 'name');
        }
        $data['history'] = $aHis;
        $this->output($data);
    }
    protected function page_show_email()
    {
        $uid = $this->input('uid');
        
        $oUser = K::load_mod('member');
        $oUser->get_user($uid , true) or $this->busy();
        $data = $oUser->get_data();
        
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        
        $content = $data['name'].'，欢迎成为泰来古典家具会员<br/>';
        $content .= "&nbsp;&nbsp;&nbsp;&nbsp;我们为您开通会员帐号:<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;用户名：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data['username']."<br/>
&nbsp;&nbsp;&nbsp;&nbsp;密码：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data['_meta_password']."<br/><br/>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;您可以通过如下地址访问：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;http://bearsh0p.com/login/L3Byb2R1Y3QvbGlzdA==";
                 
                 
        $content .='<br/><br/><br/><br/><br/><br/>';                 
                 
        $content .= $data['name'].'，Welcome to join Tailai.<br/>';
        $content .= "&nbsp;&nbsp;&nbsp;&nbsp;We created a membership account for you to visit our website,after logging in you can get more product information<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;Username：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data['username']."<br/>
&nbsp;&nbsp;&nbsp;&nbsp;Password：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data['_meta_password']."<br/><br/>"
                 ."&nbsp;&nbsp;&nbsp;&nbsp;copy the url to the Address bar：<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;http://bearsh0p.com/login/L3Byb2R1Y3QvbGlzdA==";
                 

        
echo $content;                 
                 die;
    }
    
    
    /**
     * 编辑用户接口
     *
     */
    protected function api_edit_member()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $uid = $this->input('uid');
        $data['username'] = $this->input('username');
        $data['level'] = (int)$this->input('level');
        $data['stat'] = (int)$this->input('stat');
        
        $data['name'] = $this->input('name');
        $data['email'] = $this->input('email');
        $data['mobile'] = $this->input('mobile');
        $data['fax'] = $this->input('fax');
        $data['company'] = $this->input('company');
        $data['intro'] = $this->input('intro');
     
        $oMember = K::load_mod('member');
        $rs = $oMember->edit_user($data , $uid);
        if($rs)
        {
            $this->_redirect('?page=list' , '添加成功');
        }
        die('error');
    }
    /**
     * 申请处理接口
     *
     */
    protected function api_op_apply()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $aDel = array();
        $aBak = array();
        foreach ($_POST['op'] as $k => $v)
        {
            if($v)
                $aBak[] = $k;
            else 
                $aDel[] = $k;
        }
            
        $oMemberApply = K::load_mod('member_apply');
        if(count($aBak) > 0)
        {
            $rs = $oMemberApply->edit_stat(K_COMMON_YES , $aBak);
            if(!$rs)
                die('备份失败!请重试');
        }
        if(count($aDel) > 0)
        {
            $rs = $oMemberApply->del_by_ids($aDel);
            if(!$rs)
                die('删除失败!请重试');
        }
        
        $this->back();
    }
    /**
     * 修改用户状态接口
     *
     */
    protected function api_edit_member_stat()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $id = (int)$this->input('id');
        $value = (int)$this->input('v');
        
        $oMember = K::load_mod('member');
        $oMember->edit_value($id , 'stat' , $value) or $this->busy();
        
        $this->back();
    }
    
    protected function api_edit_password()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $uid = (int)$this->input('uid');
        $password = $this->input('password');
        
        $oMember = K::load_mod('member');
        $oMember->edit_password($uid , $password);
        $this->back();
    }
}

new adm_member();
?>