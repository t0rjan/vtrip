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
include_once(SERVER_ROOT_PATH.'/include/config/ml_queue_name.php');

class ml_tool_sendMq extends ml_tool_queue_base
{


    /**
     * 注册后续任务队列
     *
     * @param int $uid
     * @return bool
     */
    static public function completeRegister($uid)
    {
        $key = ML_QUEUENAME_REGI_CHECK;
        $data = array(
            'uid' => $uid
        );
        return self::send_mq($key , $data);
    }
    /**
     * 找回密码的时候发送邮件  投队列
     *
     * @param unknown_type $to         要发送的邮箱
     * @param unknown_type $content    要发送的内容
     * @param unknown_type $title      邮件标题
     * @param unknown_type $nick       接受邮件的昵称
     * @return unknown
     */
    static public function sendMail($to , $title, $type ,$url,$nick = ' ')
    {
        $key = ML_QUEUENAME_SENDMAIL;
        $data = array('email'=>$to,'title'=>$title,'type'=>$type,'url'=>$url,'nick'=>$nick);
        return self::send_mq($key , $data);
    }
    /**
     * 发送手机飞信
     * 需要先添加飞信好友
     *
     * @param unknown_type $to         要发送的用户
     * @param unknown_type $content    要发送的内容
     * @return unknown
     */
    static public function sendSMS($to , $content)
    {
        $mobilelist=include_once(SERVER_ROOT_PATH.'/include/config/ml_stdConf_mobilelist.php');
        $key = ML_QUEUENAME_SENDSMS;
        if(isset($mobilelist[$to])){
            $data = array('sentto'=>$mobilelist[$to],'content'=>$content);
            return self::send_mq($key , $data);
        }else
        return false;
    }


    /**
     * 发送内部邮件到后端的队列
     * @param unknown_type $times 限额
     * @param unknown_type $flag 标识
     * @return Ambigous <true, boolean>|boolean
     */
    static public function send_inter_mail($times, $flag)
    {
        $ip = Tool_ip::get_real_ip();
        $key = ML_QUEUENAME_SENDINTERMAIL;
        if(isset($ip)&&isset($times)&&isset($flag)&&$ip!="218.30.115.151"){
            $data = array('ip'=>$ip,'times'=>$times,'flag'=>$flag,'date'=>$_SERVER['REQUEST_TIME']);
            return self::send_mq($key , $data);
        }else
        return false;
    }

    /**
     * 宝贝标签、分类分析队列
     *
     * @param rid $content_rid      内容资源ID
     * @param title $title       宝贝名
     * @return bool
     */
    static public function goods_title_analyse($content_rid , $title_ext = '')
    {
        $key = ML_QUEUENAME_GOODS_ANALYSE;
        $data = array(
            'rid' => $content_rid,
            'title_ext' => $title_ext
        );
        return self::send_mq($key , $data);
    }

    /**
     * 利用原有商品链接，增加返利链接
     * @gaojian3
     * @param unknown_type $content_rid
     * @param unknown_type $gd_info
     * @return unknown
     */
    static public function update_gd_url($content_rid)
    {
        $key = ML_QUEUENAME_UPDATEGDURL;
        $data = array(
            'rid' => $content_rid,
        );
        return self::send_mq($key , $data);
    }

    /**
     * 将token值修改成最新，保证可用
     *
     * @param unknown_type $uid
     * @param unknown_type $service_id
     * @param unknown_type $access_token
     * @return unknown
     */
    static public function update_token($uid,$service_id,$access_token)
    {
        $key= ML_QUEUENAME_UPDATETOKEN;

        $data=array(
            'uid' => $uid,
            'server_id' => $service_id,
            'access_token'=>$access_token,
        );
        return self::send_mq($key , $data);
    }

    /**
     * 第三方发微博
     *
     * @param unknown_type $service_id
     * @param unknown_type $data
     * @return unknown
     */

    static public function send_3rdweibo($service_id,$data)
    {
        $key= ML_QUEUENAME_SEND_3RDWEIBO;

        $arr=array(
            'service_id' => $service_id,
            'data' => $data,
        );
        return self::send_mq($key , $arr);
    }


    /**
     * 进入逛宝贝
     *
     * @param rid $content_rid      内容资源ID
     * @param title $title       宝贝名
     * @return bool
     */
    static public function guang_insert($content_rid)
    {
        $key = ML_QUEUENAME_GUANG_INSERT;
        $data = array(
            'rid' => $content_rid,
        );
        return self::send_mq($key , $data);
    }

    /**
     * 更新热度
     *
     * @param rid   资源ID
     * @param string   动作
     * @return bool
     */
    static public function userAction2GoodsRank($rid , $action = 'like' , $score = 1)
    {
        $key = ML_QUEUENAME_ACT2GOODS;
        $data = array(
            'rid' => $rid,
            'action' => $action,
            'score' => $score
        );
        return self::send_mq($key , $data);
    }
    /**
     * 增加内容中@某人
     *
     * @param int $uid
     * @param string $rid
     * @param array $aDestUid    //目标人的uid
     * @return bool
     */
    static public function atme_in_content($uid , $rid , $aDestUid)
    {
        $key = ML_QUEUENAME_ADD_ATME;
        $data = array(
            'type' => ML_ATME_TYPE_IN_CONTENT,
            'act_uid' => $uid,
            'rid' => $rid,
            'dest_uids' => $aDestUid
        );
        return self::send_mq($key , $data);
    }
    /**
     * 转采时@某人
     *
     * @param int $uid
     * @param rid $rid
     * @return bool
     */
    static public function atme_in_collect($uid , $rid , $col_id)
    {
        $key = ML_QUEUENAME_ADD_ATME;
        $data = array(
            'type' => ML_ATME_TYPE_IN_COLLECTION,
            'act_uid' => $uid,
            'rid' => $rid,
            'col_id' => $col_id
        );
        return self::send_mq($key , $data);
    }
    /**
     * 评论时 @某人
     *
     * @param int $act_uid 发评论的人
     * @param string $rid 针对什么发的评论
     * @param int $cmt_id 评论的ID
     * @param array $aAtUids 被@人的UID
     * @return bool
     */
    static public function atme_in_newcomment($act_uid , $rid , $cmt_id , $aAtUids)
    {
        $key = ML_QUEUENAME_ADD_ATME;
        $data = array(
            'type' => ML_ATME_TYPE_IN_NEWCOMMENT,
            'act_uid' => $act_uid,
            'rid' => $rid,
            'cmt_id' => $cmt_id,
            'at_uids' => $aAtUids
        );
        return self::send_mq($key , $data);
    }
    /**
     * 回复他人评论时
     *
     * @param int $act_uid 发评论人UID
     * @param string $rid
     * @param int $cmt_id
     * @param int $dest_uid    回复谁
     * @param int $old_cmt_id    回复哪条评论
     * @param array $aAtUids
     * @return bool
     */
    static public function atme_in_replycomment($act_uid , $rid , $cmt_id , $dest_uid , $old_cmt_id, $aAtUids)
    {
        $key = ML_QUEUENAME_ADD_ATME;
        $data = array(
            'type' => ML_ATME_TYPE_IN_NEWCOMMENT,
            'act_uid' => $uid,
            'src_rid' => $rid,
            'cmt_id' => $cmt_id,
            'old_cmt_id' => $old_cmt_id,
            'dest_uid' => $dest_uid,
            'at_uids' => $aAtUids
        );
        return self::send_mq($key , $data);
    }

    /**
     * 增加内容中@某人
     *
     * @param int $uid
     * @param string $rid
     * @param array $aDestUid    //目标人的uid
     * @return bool
     */
    static public function del_atme_in_content($rid)
    {
        $key = ML_QUEUENAME_DEL_ATME;
        $data = array(
            'type' => ML_ATME_TYPE_IN_CONTENT,
            'rid' => $rid
        );
        return self::send_mq($key , $data);
    }
    /**
     * 删除我的最爱和首页中的pin
     *
     * @param string $rid
     * @return bool
     */
    static public function del_like_feed($rid)
    {
        $key = ML_QUEUENAME_DEL_LIKE_FEED;
        $data = array(
            'rid' => $rid
        );
        return self::send_mq($key , $data);
    }

    static public function add_comment_me($uid)
    {
        $key=ML_QUEUENAME_ADD_CMT_NOTICE;
        $data=array(
            'uid'=>$uid
        );
        return self::send_mq($key , $data);
    }

    static public function del_comment_me($uid)
    {
        $key=ML_QUEUENAME_DEL_CMT_NOTICE;
        $data=array(
            'uid'=>$uid
        );
        return self::send_mq($key , $data);
    }

    static public function add_suggest_tag($tag, $tag_id)
    {
        $key=ML_QUEUENAME_SUGGEST_NEWTAG;
        $data=array(
            'tag'=>$tag,
            'tag_id'=>$tag_id,
        );
        return self::send_mq($key , $data);
    }

    static public function add_atnick_nick($nick, $uid)
    {
        $key=ML_QUEUENAME_ATNICK_NEWNICK;
        $data=array(
            'nick'=>$nick,
            'uid'=>$uid,
            'type'=>'new',
        );
        return self::send_mq($key , $data);
    }

    static public function update_atnick_nick($nick, $uid)
    {
        $key=ML_QUEUENAME_ATNICK_NEWNICK;
        $data=array(
            'nick'=>$nick,
            'uid'=>$uid,
            'type'=>'update',
        );
        return self::send_mq($key , $data);
    }

    static public function del_atnick_nick($nick, $uid)
    {
        $key=ML_QUEUENAME_ATNICK_NEWNICK;
        $data=array(
            'nick'=>$nick,
            'uid'=>$uid,
            'type'=>'del',
        );
        return self::send_mq($key , $data);
    }


    static public function add_atnick_follow($uid, $fuid)
    {
        $key=ML_QUEUENAME_ATNICK_NEWFOLLOW;
        $data=array(
            'fuid'=>$fuid,
            'uid'=>$uid,
            'type'=>'new',
        );
        return self::send_mq($key , $data);
    }

    static public function del_atnick_follow($uid, $fuid)
    {
        $key=ML_QUEUENAME_ATNICK_NEWFOLLOW;
        $data=array(
            'fuid'=>$fuid,
            'uid'=>$uid,
            'type'=>'del',
        );
        return self::send_mq($key , $data);
    }

    static public function add_atnick_latestAt($uid, $atUid)
    {
        $key=ML_QUEUENAME_ATNICK_LATESTAT;
        $data=array(
            'atUid'=>$atUid,
            'uid'=>$uid,
        );
        return self::send_mq($key , $data);
    }

    /**
     *
     * @param array $data ['userfeed_rid']
     */
    static public function del_feed($data)
    {
        $key = ML_QUEUENAME_DEL_FEED;
        $data['sys_time'] = time();
        return self::send_mq($key , $data);

    }

    static public function del_atme_in_collect($uid , $col_id)
    {
        $key = ML_QUEUENAME_DEL_ATME;
        $rid = ml_tool_resid::make_resid($uid , ML_RESID_TYPE_COLLECT , $col_id);
        $data = array(
            'type' => ML_ATME_TYPE_IN_COLLECTION,
            'rid' => $rid
        );
        return self::send_mq($key , $data);
    }
    static public function del_atme_in_comment($uid , $cmt_id)
    {
        $key = ML_QUEUENAME_DEL_ATME;
        $rid = ml_tool_resid::make_resid($uid , ML_RESID_TYPE_COMMENT , $cmt_id);
        $data = array(
            'type' => ML_ATME_TYPE_IN_COLLECTION,
            'rid' => $rid
        );
        return self::send_mq($key , $data);
    }
    
    static public function add_new3rd_extfollow($meila_uid, $trd_id, $type_id){
        $key = ML_QUEUENAME_EXTFOLLOW_NEW;
        $data = array(
            'meila_uid' => $meila_uid,
            '3rd_uid' => $trd_id,
            'type_id' => $type_id,
        );
        return self::send_mq($key , $data);
    }

    /**
     * 宝贝和图片队列
     *
     * @param data   内容数据
     * @return bool
     */
    static public function goods_add_content($data)
    {
        return ;
        $key = ML_QUEUENAME_GOODS_ADDCONTENT;
        return self::send_mq($key , $data);
    }
    /**
     * 内容删除队列
     *
     * @param data  内容资源ID
     * @return bool
     */
    static public function goods_del_content($rid)
    {
        $key = ML_QUEUENAME_GUANG_DELETE;
        $data = array('rid' => $rid);
        self::send_mq($key , $data);


        $key = ML_QUEUENAME_GOODS_DELCONTENT;
        return self::send_mq($key , $data);
    }

    /**
     * 更改nick 队列
     *
     * @param data  用户uid  nick
     * @return bool
     */
    static public function update_nick($data)
    {
        $key = ML_QUEUENAME_UPNICK;
        return self::send_mq($key , $data);
    }


    static public function search_content($type, $rid)
    {
        $key = ML_QUEUENAME_SEARCH_CONTENT;
        $data = array(
                       'rid' => $rid,
                       'dealtype' => $type,
                       'sys_time' => time(),
        );
        return self::send_mq($key , $data);
    }

    static public function search_user($type, $uid)
    {
        $key = ML_QUEUENAME_SEARCH_USER;
        $data = array(
                       'uid' => $uid,
                       'dealtype' => $type,
                       'sys_time' => time(),
        );
        //Tool_logger::dataLog('SEND_SEARCHUSER' , serialize($data));
        return self::send_mq($key , $data);
    }


    static public function add_feed($data) {

        $key = ML_QUEUENAME_ADD_FEED;
        $data['sys_time'] = time();
        return self::send_mq($key , $data);
    }

    static public function intra_publish($data){
        $key = ML_ADD_FAKE_ATTITUDE;
        $data['time'] = time();
        return self::send_mq($key, $data);
    }

    /**
     * 加关注
     * @param array $data
     */
    static public function add_follow($data) {

        $key = ML_QUEUENAME_ADD_FOLLOW;
        $data['sys_time'] = time();
        return self::send_mq($key , $data);
    }

    /**
     * 给后台收集评论信息
     * @param array $data
     */
    static public function collect_comment($data) {

        $key = ML_QUEUENAME_COLLECT_COMMENT;
        $data['sys_time'] = time();
        return self::send_mq($key , $data);
    }

    /**
     * 投送申请店铺信息给后台
     */
    static public function add_apply_shop($data){

        $key = ML_QUEUENAME_ADD_APPLY_SHOP;
        $data['time'] = time();
        return self::send_mq($key , $data);
    }
    /**
     * 默认关注美啦微博和美啦
     * @static
     * @param $data
     * @return true
     */
    static public function add_follow_meila($data){

        $key = ML_QUEUENAME_ADD_FOLLOW_MEILA;
        $data['time'] = time();
        return self::send_mq($key , $data);
    }
    /**
     * 意见收集队列
     * @static
     * @param $data
     * @return true
     */
    static public function suggest_collect($data){

        $key = ML_QUEUENAME_SUGGEST_COLLECT;
        return self::send_mq($key , $data);
    }
    
    static public function add_likeandfan($uid, $rid){
        
        $key = ML_QUEUENAME_ADD_LIKEFAN;
        $data = array(
                       'uid' => $uid,
                       'rid' => $rid,
                       'sys_time' => time(),
        );
        return self::send_mq($key, $data);
    }
    

    
}