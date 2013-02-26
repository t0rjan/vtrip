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

define('ML_QUEUENAME_SENDMAIL' , 'mlq_sendmail');
define('ML_QUEUENAME_SENDSMS' , 'mlq_sendSMS');
define('ML_QUEUENAME_SENDINTERMAIL' , 'mlq_send_intermail');//发内部邮件到后台
define('ML_QUEUENAME_REGI_CHECK' , 'mlq_regiCheck');
define('ML_QUEUENAME_GOODS_ADDCONTENT' , 'mlq_gd_addcontent');
define('ML_QUEUENAME_GOODS_DELCONTENT' , 'mlq_gd_delcontent');
define('ML_QUEUENAME_GUANG_INSERT' , 'mlq_guang_insert');
define('ML_QUEUENAME_GUANG_DELETE' , 'mlq_guang_del');
define('ML_QUEUENAME_ACT2GOODS' , 'mlq_act2goods');
define('ML_QUEUENAME_UPNICK' , 'mlq_upnick');
define('ML_QUEUENAME_ADD_ATME' , 'mlq_add_atme');
define('ML_QUEUENAME_DEL_ATME' , 'mlq_del_atme');
define('ML_QUEUENAME_DEL_LIKE_FEED' , 'mlq_del_like_feed');
define('ML_QUEUENAME_ADD_SYS_NOTICE' , 'mlq_add_sysntc');
define('ML_QUEUENAME_ADD_CMT_NOTICE','mlq_add_cmtntc');
define('ML_QUEUENAME_DEL_CMT_NOTICE','mlq_del_cmtntc');
define('ML_QUEUENAME_GOODS_ANALYSE','mlq_goods_analyse');
define('ML_QUEUENAME_UPDATEGDURL','mlq_update_gd_url');
define('ML_QUEUENAME_UPDATETOKEN','mlq_update_token');

define('ML_QUEUENAME_SEND_3RDWEIBO','mlq_send_3rdweibo');

define('ML_QUEUENAME_SEARCH_CONTENT', 'mlq_search_content');
define('ML_QUEUENAME_SEARCH_USER', 'mlq_search_user');

define('ML_QUEUENAME_SUGGEST_NEWTAG', 'mlq_suggest_newtag');
define('ML_QUEUENAME_ATNICK_NEWNICK', 'mlq_atnick_newnick');
define('ML_QUEUENAME_ATNICK_NEWFOLLOW', 'mlq_atnick_newfollow');
define('ML_QUEUENAME_ATNICK_LATESTAT', 'mlq_atnick_latestat');

define('ML_QUEUENAME_EXTFOLLOW_NEW', 'mlq_extfollow_new');
define('ML_QUEUENAME_ADD_FEED', 'mlq_add_feed');
define('ML_QUEUENAME_DEL_FEED', 'mlq_del_feed');
define('ML_QUEUENAME_ADD_FOLLOW', 'mlq_add_follow');

define('ML_QUEUENAME_ADD_COLLECTION', 'mlq_add_collection');
define('ML_QUEUENAME_DEL_COLLECTION', 'mlq_del_collection');


define('ML_CRONPHP_GUANG_CLEANUP' , 'ml_cron_guang_cleanup');
define('ML_CRONPHP_GUANG_DATA24H_GEN' , 'ml_cron_guang_data24h_gen');
define('ML_CRONPHP_GUANG_DATA7D_GEN' , 'ml_cron_guang_data7d_gen');
define('ML_CRONPHP_GUANG_DATABYCTG_GEN' , 'ml_cron_guang_dataByCtg_gen');
define('ML_CRONPHP_GUANG_DATABYCTG2TAG_GEN' , 'ml_cron_guang_dataByCtg2tag_gen');
define('ML_CRONPHP_GUANG_DATABYTAG_GEN' , 'ml_cron_guang_dataByTag_gen');
define('ML_CRONPHP_GUANG_DATABYTAG2_GEN' , 'ml_cron_guang_dataByTag2_gen');
define('ML_CRONPHP_GUANG_DATA_RSYNC' , 'ml_cron_guang_data_rsync');
define('ML_CRONPHP_GUANG_UPDATEHOTRANK' , 'ml_cron_guang_updatehotrank');

define('ML_CRONPHP_VDAPEI_GEN' , 'ml_cron_vdapei_gen');

define('ML_QUEUENAME_INTRA_DEL_BY_RID' , 'mlq_intra_del_by_rid');

define('ML_QUEUENAME_COLLECT_COMMENT','mlq_collect_comment');
define('ML_QUEUENAME_ADD_APPLY_SHOP','mlq_add_shop');
define('ML_QUEUENAME_ADD_FOLLOW_MEILA', 'mlq_add_follow_meila');//关注美啦微博及主站美啦
define('ML_QUEUENAME_SUGGEST_COLLECT', 'mlq_suggest_collect');

define('ML_CRONPHP_ALBUM_COUNT' , 'ml_cron_album_count');

define('ML_QUEUENAME_ADD_LIKEFAN', 'mlq_add_like&fan');


define('ML_QUEUENAME_FK_ADDGOODS', 'mlq_fk_addgoods');

/**
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 * 定义队列过程
 * 1，定义队列名称常量
 * 2，将常量加入$mq_key_config数组
 * 3，COPY ml_mq_dev_______template.php ,并修改相应信息，加入自己的逻辑
 * 16 17 41行，共有4处修改
 * 增加自己的注释和程序
 * 完成
 * 
 * 
 * 
 */
$mq_key_config = array(
    ML_QUEUENAME_REGI_CHECK => 1,
    ML_QUEUENAME_SENDMAIL=>1,
    ML_QUEUENAME_GUANG_INSERT => 1,
    ML_QUEUENAME_GUANG_DELETE => 1,
    ML_QUEUENAME_ACT2GOODS=>1,
    ML_QUEUENAME_UPNICK=>1,
    ML_QUEUENAME_ADD_ATME=>1,
    ML_QUEUENAME_DEL_ATME=>1,
    ML_QUEUENAME_ADD_SYS_NOTICE=>1,
    ML_QUEUENAME_ADD_CMT_NOTICE=>1,
    ML_QUEUENAME_DEL_CMT_NOTICE=>1,
    ML_QUEUENAME_ADD_FEED=>1,
    ML_QUEUENAME_DEL_FEED=>1,
    ML_QUEUENAME_ADD_COLLECTION=>1,
    ML_QUEUENAME_DEL_COLLECTION=>1,
    ML_QUEUENAME_ADD_FOLLOW=>1,
    ML_QUEUENAME_SEARCH_CONTENT=>1,
    ML_QUEUENAME_SEARCH_USER=>1,
    ML_QUEUENAME_SUGGEST_NEWTAG=>1,
    ML_QUEUENAME_DEL_LIKE_FEED=>1,
    ML_QUEUENAME_INTRA_DEL_BY_RID=>1,
    ML_QUEUENAME_GOODS_ANALYSE=>1,
    ML_QUEUENAME_UPDATEGDURL=>1,
    ML_QUEUENAME_UPDATETOKEN=>1,
    ML_QUEUENAME_SEND_3RDWEIBO=>1,
    ML_QUEUENAME_COLLECT_COMMENT=>1,
    ML_QUEUENAME_ATNICK_NEWFOLLOW=>1,
    ML_QUEUENAME_ATNICK_NEWNICK=>1,
    ML_QUEUENAME_ATNICK_LATESTAT=>1,
    ML_QUEUENAME_ADD_APPLY_SHOP=>1,
    ML_QUEUENAME_ADD_FOLLOW_MEILA=>1,
    ML_QUEUENAME_EXTFOLLOW_NEW=>1,
    ML_QUEUENAME_ADD_LIKEFAN=>1,    
    ML_QUEUENAME_FK_ADDGOODS=>1,    
);

define('ML_SRVDEF_QUEUE_M','10.73.18.49');
define('ML_SRVDEF_QUEUE_S','10.73.18.46');
define('ML_SRVDEF_ADMIN_M','10.69.3.135');
define('ML_SRVDEF_ADMIN_S','10.69.2.107');

$mq_cron_run_ip = array(
    ML_QUEUENAME_REGI_CHECK     => ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_SENDMAIL        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_GUANG_INSERT    => ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_GUANG_DELETE     => ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ACT2GOODS        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_UPNICK        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_DEL_ATME        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_ATME        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_SYS_NOTICE        =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_CMT_NOTICE      =>ML_SRVDEF_QUEUE_M,      
    ML_QUEUENAME_ADD_FEED =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_DEL_FEED =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_COLLECTION =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_DEL_COLLECTION =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_GOODS_ANALYSE =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_UPDATEGDURL    =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_UPDATETOKEN       =>ML_SRVDEF_QUEUE_M,
    
    ML_QUEUENAME_SEND_3RDWEIBO    =>ML_SRVDEF_QUEUE_M,
    
    ML_CRONPHP_GUANG_CLEANUP    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATA24H_GEN=>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATA7D_GEN    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATABYCTG_GEN    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATABYCTG2TAG_GEN    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATABYTAG_GEN    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATABYTAG2_GEN    =>ML_SRVDEF_ADMIN_M,
    ML_CRONPHP_GUANG_DATA_RSYNC    =>ML_SRVDEF_ADMIN_M,
    ML_QUEUENAME_DEL_LIKE_FEED  =>ML_SRVDEF_QUEUE_M,
    ML_CRONPHP_GUANG_UPDATEHOTRANK    =>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_FOLLOW=>ML_SRVDEF_QUEUE_M,
    
    ML_QUEUENAME_SEARCH_CONTENT=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_SEARCH_USER=>ML_SRVDEF_QUEUE_M,
    ML_CRONPHP_SEARCH_USER=>ML_SRVDEF_QUEUE_M,
    ML_CRONPHP_SEARCH_CONTENT=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_INTRA_DEL_BY_RID=>ML_SRVDEF_QUEUE_M,
    
    ML_QUEUENAME_SUGGEST_NEWTAG=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ATNICK_NEWFOLLOW=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ATNICK_NEWNICK=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ATNICK_LATESTAT=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_COLLECT_COMMENT=>ML_SRVDEF_QUEUE_M,

    ML_QUEUENAME_ADD_APPLY_SHOP=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_ADD_FOLLOW_MEILA=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_EXTFOLLOW_NEW=>ML_SRVDEF_QUEUE_M,

    ML_CRONPHP_ALBUM_COUNT =>ML_SRVDEF_ADMIN_M,
    ML_QUEUENAME_ADD_LIKEFAN=>ML_SRVDEF_QUEUE_M,
    ML_QUEUENAME_FK_ADDGOODS=>ML_SRVDEF_QUEUE_M,);

function ml_run_queue_check($queuename)
{
    return true;
    if(SYSDEF_SERVER_TYPE == 'dev')
        return true;
    global $mq_cron_run_ip;
    $local_ip = Tool_ip::getLocalLastIp(true , false);
    if($mq_cron_run_ip[$queuename] != $local_ip)
        die('no run here!');
}