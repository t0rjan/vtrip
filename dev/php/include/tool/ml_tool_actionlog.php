<?php
include_once(SERVER_ROOT_PATH.'/include/config/ml_actionCode.php');

class ml_tool_actionlog
{
    static public function register($uid , $email , $nick , $gender,$from=0)
    {
        $ext = array(
            'regsource' => 0,
            'email' => $email,
            'nickname' => $nick,
            'gender' => $gender,
        );
        
        self::_add_userpath_log(ML_SINAACTCODE_USER_REG 
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' , $from, $ext);
        $data = array(

        );
        return self::_add_log($uid , ML_ACTCODE_USER_REG , $data);
    }
    static public function reg_3rd($uid , $server_id , $email , $nick , $gender,$from=0)
    {
        $ext = array(
            'regsource' => 0,
            'regentry' => $server_id,
            'email' => $email,
            'nickname' => $nick,
            'gender' => $gender,
        );
        self::_add_userpath_log(ML_SINAACTCODE_USER_REG 
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' , $from , $ext);
                        
        $data = array(
            'server_id' => $server_id,
        );
        return self::_add_log($uid , ML_ACTCODE_USER_REG , $data);
    }
    
    static public function login($uid , $type , $server_id=0,$from=0)
    {
        $ext = array(
            'user_type' => $server_id,
        );
        self::_add_userpath_log(ML_SINAACTCODE_USER_LOGIN 
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' ,$from , $ext);
    }
    static public function logout($uid,$from=0)
    {
        $ext = array(
            
        );
        self::_add_userpath_log(ML_SINAACTCODE_USER_LOGOUT
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' , $from , $ext);
    }
    
    
    static public function publish_gd($uid ,$rid, $gd_url , $txt, $have_pic=false ,$price = 0, $at_num = 0,$from=0)
    {
        $a = parse_url($gd_url);
        $ext = array(
            'filter' => ($have_pic? 4+2 : 4),
            'longurl' => urlencode($gd_url),
            'product_source' => $a['host'],
            'at_num' => $at_num,
            'price' => $price
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_PUBLISH
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,$rid , $from , $ext);
        
        
        $data = array(
            'type' => 'gd',
            'gd_domain' => $a['host'],
            'txt_len' => strlen($txt)
        );
        if($have_pic){
            $data['have_pic'] = 'p1';
        }else{
            $data['have_pic'] = 'p0';
        }
        return self::_add_log($uid , ML_ACTCODE_CONTENT_PUBLISH , $data);
    }
    static public function publish_txt($uid ,$rid, $txt , $at_num = 0,$from=0)
    {
        $ext = array(
            'filter' => 1,
            'at_num' => $at_num
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_PUBLISH
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,$rid , $from, $ext);
        
        $data = array(
            'type' => 'txt',
            'txt_len' => strlen($txt)
        );
        return self::_add_log($uid , ML_ACTCODE_CONTENT_PUBLISH , $data);
    }
    static public function publish_pic($uid ,$rid, $txt , $at_num = 0,$from=0)
    {
        $ext = array(
            'filter' => 2,
            'at_num' => $at_num
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_PUBLISH
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,$rid , $from , $ext);
        
        $data = array(
            'type' => 'pic',
            'txt_len' => strlen($txt)
        );
        return self::_add_log($uid , ML_ACTCODE_CONTENT_PUBLISH , $data);
    }
    static public function del_content($uid , $rid,$from=0)
    {
        $ext = array(
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_DEL
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,$rid , $from , $ext);
    }
    
    static public function add_cmt($uid , $rid , $cmt ,$tags = array(), $from=0 , $at_num = 0)
    {
        $ext = array(
            'at_num' => $at_num,
            'tags' => implode(';' , $tags)
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_COMMENT
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , ml_tool_resid::resid2uid($rid),$rid , $from , $ext);
        
        $data = array(
            'rid' => $rid,
            'txt_len' => strlen($cmt)
        );
        return self::_add_log($uid , ML_ACTCODE_CMT_NEW, $data);
    }
    static public function del_cmt($uid , $rid ,$from=0)
    {
        $ext = array(
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_COMMENT_DEL
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,$rid , $from , $ext);
    }
    
    
    static public function add_attitude($uid , $rid ,$point = 0,$tags = array(), $from=0)
    {
        $ext['mood'] = $point;
        $ext['tags'] = implode(';' , $tags);
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_ATTITUDE
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , ml_tool_resid::resid2uid($rid),$rid , $from , $ext);
        
        $data = array(
            'rid' => $rid,
            'point' => $point
        );
        return self::_add_log($uid , ML_ACTCODE_ATTITUDE_NEW, $data);
    }
    static public function del_attitude($uid , $rid , $from=0)
    {
        $ext = array(
            
        );
        self::_add_userpath_log(ML_SINAACTCODE_CONTENT_ATTITUDE_DEL
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , ml_tool_resid::resid2uid($rid),$rid , $from , $ext);
    }
    
    static public function add_follow($uid , $dest_uid , $from=0)
    {
        $ext = array(
            
        );
        self::_add_userpath_log(ML_SINAACTCODE_RELATION_FOLLOW
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , $dest_uid,'0' , $from , $ext);
        

    }
    static public function del_follow($uid , $dest_uid , $from=0)
    {
        $ext = array(
            
        );
        self::_add_userpath_log(ML_SINAACTCODE_RELATION_FOLLOW
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 1 , $dest_uid,'0' , $from , $ext);
        

    }
    
    
    static public function _add_log($uid , $action_code , $data = array())
    {
        Tool_logger::baseActionLog($uid , $action_code , $data);
    }
    
    static public function view_index($uid,$from=0)
    {
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_INDEX
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' , $from , $ext);
    }
    static public function view_activity($uid,$from=0)
    {
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_ACTIVITY
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' ,  $from  , $ext);
    }
    static public function view_guang($uid , $aTarget,$from=0)
    {
        $ext = $aTarget;
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_GUANG
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , 0,'0' , $from , $ext);
    }
    static public function view_show_goods($uid , $rid , $catelog , $tags , $price ,$from=0)
    {
        $ext = array(
            'ctg' => $catelog,
            'tags' => implode(';',$tags),
            'price' => $price
        );
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_GOODSSHOW
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , ml_tool_resid::resid2uid($rid),$rid , $from , $ext);
    }
    static public function view_jump_goods($uid , $rid , $catelog , $tags , $price , $gd_url ,$from=0)
    {
        $a = parse_url($gd_url);
        $ext = array(
            'ctg' => $catelog,
            'tags' => implode(';',$tags),
            'price' => $price,
            'longurl' => urlencode($gd_url),
            'product_source' => $a['host'],
        );
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_GOODSSHOW_JUMP
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , ml_tool_resid::resid2uid($rid),$rid , $from , $ext);
    }
    static public function view_lookbook($uid ,$pagetype , $ext = array() , $from=0)
    {
        $ext['url'] = $_SERVER['SCRIPT_NAME'] ;
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_LOOKBOOK
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , $pagetype , 0,'0' , $from , $ext);
    }
    static public function view_search($uid , $searchwhat , $key , $ext,$from=0)
    {
        $key = str_replace(' ' , '_' , $key);
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_SEARCH
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , $searchwhat , 0,$key , $from , $ext);
    }
    static public function view_userfeed($uid , $dest_uid , $block=0 ,$from=0)
    {
        $ext = array(
            'block' => $block
        );
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_USERFEED
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , $dest_uid,0 , $from , $ext);
    }
    static public function view_userpic($uid , $dest_uid , $block=0 ,$from=0)
    {
        $ext = array(
            'block' => $block
        );
        self::_add_userpath_log(ML_SINAACTCODE_VIEW_USERPIC
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , $dest_uid,0 , $from , $ext);
    }
    
    static public function add_other_action($uid , $action_code, $dest_rid='' , $dest_uid=0 , $ext = array(),$from=0)
    {
        self::_add_userpath_log($action_code
                        , ML_SINAACTCODE_STATUS_OK 
                        ,$uid
                        , 0 , $dest_uid,$dest_rid , $from , $ext);
    }
    
    
    
    
    
    static private function _add_userpath_log($actcode , $op_status ,$op_uid, $op_type , $op_dest_uid = 0,$op_dest_id = '',$from = 0, $ext = array() , $path_ext = array())
    {
        //过滤非正常访问
        if(ml_tool_ua::is_fake_visitor())
            return false;
        if($actcode < ML_SINAACTCODE_VIEW_START)
            self::_add_sina_log($actcode , $op_status ,$op_uid, $op_type , $op_dest_uid,$op_dest_id,$from, $ext );
        
        $_controller = ml_factory::get_controller();
        $wbinfo = $_controller->loginProxy('getWeiboInfo');//->_login->getWeiboInfo();
            $wb_uid = $wbinfo['weiboID'];
        
        $ext['location'] = self::_format_referer();
        $ext['refer_sort'] = htmlspecialchars($_GET['frm']);
        $ext += $path_ext;
        $aLog = array(
            time(),
            Tool_ip::get_real_ip(),
            $op_uid?$op_uid:0,
            $wb_uid?$wb_uid:0,
            ml_tool_ua::get_usign(),
            $actcode,
            $from,
            $op_type,
            $op_status,
            $op_dest_id,
            $op_dest_uid,
            self::_format_ext($ext),
            Tool_ip::getLocalLastIp()
        );
        $sLog = implode("\t" , $aLog);
        
        Tool_logger::scrib_log('meila-debug_userpath' , $sLog);
        ml_tool_async::wx_action_log($sLog , $_SERVER['SCRIPT_NAME']);
    }
    
    static private function _add_sina_log($actcode , $op_status ,$op_uid, $op_type , $op_dest_uid = 0,$op_dest_id = '',$from = 0, $ext = array() )
    {
        
        $ext['location'] = self::_format_referer();
        $ext['refer_sort'] = htmlspecialchars($_GET['frm']);
        $ext['svrip'] = Tool_ip::getLocalLastIp(true);
        $aLog = array(
            time(),
            Tool_ip::get_real_ip(),
            $op_uid,
            $actcode,
            $from,
            $op_type,
            $op_status,
            $op_dest_id,
            $op_dest_uid,
            self::_format_ext($ext),
        );
        $sLog = implode("\t" , $aLog);
        
        Tool_logger::scrib_log('meila_action_log' , $sLog);
        Tool_logger::dataLog('sinaactionlog' , $sLog , true);
    }
    static private function _format_ext($arr_ext)
    {
        foreach ($arr_ext as $k => $v)
        {
            $a[] = $k.'=>'.$v;
        }
        return implode(',' , $a);
    }
    
    static private function _format_referer()
    {
        $a = parse_url($_SERVER['HTTP_REFERER']);
        if(in_array($a['host'] , array('meila.com' , 'www.meila.com')))
        {
            $ref = $a['path'];
            
            if(substr($ref , 0 , 11) == '/show_goods')
                $ref = 'show_goods';
        }
        else 
        {
            if(!in_array($a['host'] ,array('weibo.com' , 'qing.weibo.com')))
            {
                $ref = $a['host'];
            }
            else 
            {
                $ref = $a['host'].$a['path'];
            }
        }
        
        $ref = urldecode($ref);
        if(!Tool_string::isUtf8($ref))
            $ref = Tool_string::utf2gb($ref);
        return $ref;
    }
    
    
}