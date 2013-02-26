<?php
/**
 *@fileoverview: [群博客] 基础参数
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 05:03:20 GMT 2010
 *@copyright: sina
 */

define('ML_CNF_DOMAIN' , 'www.meila.com');        



define('ML_CNF_SECURE_CODE' , 'Gr0uPb!o9izN6');

define('ML_CNF_SSO_ENTRY' , 'qing');
define('ML_CNF_SSO_PIN' , '5b6e653128ca751da5384610fca3dda4');

define('ML_WEIBO_OFFICIAL_UID' , '2459731433');
define('ML_WEIBO_OFFICIAL_URL' , 'http://weibo.com/u/2459731433');
define('ML_WEIBO_APPKEY' , '1643354595');
define('ML_WEIBO_APPSECRET' , '722c79d6933cb4259b79a31e75f56b85');
define('ML_WEIBO_DUMMY_TOKEN' , '000497ea2767f743911627128333c40f');
define('ML_WEIBO_DUMMY_SECRET' , 'b6e177e0f3d8c20d45971276a239bff4');

//美啦
define('ML_TAOBAO_READAPI_APPKEY' , 12584922);
define('ML_TAOBAO_READAPI_APPSECRET' , 'b9298679627ec351899aa3aa8487e7fd');

define('ML_TAOBAO_APPKEY' , 21312923);
define('ML_TAOBAO_APPSECRET' , '0648cb878697f3a33b4b83706e171a89');

define('ML_TAOBAO_ALMM_PID' , 33121640);


define('ML_OFFICIAL_UID' , 5211519);
define('ML_CNF_PAGETITLE_SUFFIX' , ' - 美啦');
define('ML_CNF_PAGETITLE_SUFFIX2', " - 爱上美啦的衣橱");
define('ML_RIA_DEFAULT_VERSION', '201205301442');
define('ML_USERSTATUS_NOACTIVE' , 0);
define('ML_USERSTATUS_OK' , 1);
define('ML_USERSTATUS_THIRD' , 2);
define('ML_USERSTATUS_KILL' , 9);

define('ML_USER_ONLINE', 1);
define('ML_USER_OFFLINE', 0);

define('ML_PERMISSION_LOGOUT_ONLY', 1);
define('ML_PERMISSION_LOGIN_CANWRITE', 2);
define('ML_PERMISSION_LOGIN_ONLY', 3);
define('ML_PERMISSION_UNVERIFY_ONLY', 4);
define('ML_PERMISSION_UNVERIFY_CANREAD', 5);//激活页面可以返回到登录页面

define('ML_NOTVERIFY' , 0);
define('ML_VERIFYED' , 1);
define('ML_NOEMAIL', 2);

define('ML_CNF_VIEWS_DIR' , SERVER_ROOT_PATH.'/view');
define('ML_DATA_DIR' , '/data1/www/htdocs/dr.meila.com/__temp_data/'); //数据来源目录
define('ML_RIA_CSS', 'http://meila.com/static/css/');
define('ML_RIA_JS', 'http://sjs.sinajs.cn/meila/js/');
define('ML_RIA_IMG', 'http://meila.com/static/images/');

define('ML_TPL_ROOTPATH' , SERVER_ROOT_PATH.'/view/');
define('KEYWORD_FILE_PATH', SYSDEF_PRIVDATA_ROOT_PATH.'ml_rsync/sensitive/');

define('ML_3RD_NOEMAIL', 1);
define('ML_3RD_NOVERIFY', 2);

define('ML_CONTENT_XRANK_GOODS' , 1);
define('ML_CONTENT_XRANK_IMG' , 2);
define('ML_CONTENT_XRANK_IMGOODS', 3);

define('ML_RESID_TYPE_CONTENT' , 1);
define('ML_RESID_TYPE_USERFEED' , 2);
define('ML_RESID_TYPE_ATME' , 20);
define('ML_RESID_TYPE_COMMENT' , 21);
define('ML_RESID_TYPE_ATTITUDE' , 22);
define('ML_RESID_TYPE_COLLECT' , 23);
define('ML_RESID_TYPE_ALBUMFEED', 30);
define('ML_RESID_TYPE_ALBUMREPLY', 31);
define('ML_ATME_TYPE_IN_CONTENT' , 1);
define('ML_ATME_TYPE_IN_COLLECTION' , 2);
define('ML_ATME_TYPE_IN_NEWCOMMENT' , 3);
define('ML_ATME_TYPE_IN_REPLYCOMMENT' , 4);

define('ML_WEIBO_ACCESS_TOKEN', '2.00fuiDACRb2NnB63ab337b1csWwYIB');

//  feed类型
define('ML_FEED_TYPE_PUB_SHORT' , 1);       //  分享短语
define('ML_FEED_TYPE_PUB_DOTEY' , 2);       //分享宝贝
define('ML_FEED_TYPE_PUB_PIC', 3);         //分享图片
define('ML_FEED_TYPE_MOD_DEC', 8);         //修改宣言
define('ML_FEED_TYPE_ATTITUDE', 6);         //表态（读rid）

define('ML_FEED_TYPE_REPOST_SHORT', 4);         ///*转发短语
define('ML_FEED_TYPE_REPOST_PIC', 5);         ///*转发图片
define('ML_FEED_TYPE_MOD_TAG', 7);         ///修改标签
define('ML_FEED_TYPE_REPOST_COLLECTION', 9);         ///转采


//头像
define('ML_HEADER_PICSIZE_200' , 's200');    
define('ML_HEADER_PICSIZE_100' , 's100');      
define('ML_HEADER_PICSIZE_50', 's50');        
define('ML_HEADER_PICSIZE_30', 's30'); 

define('ML_USER_PICSIZE_PINBOARD' , 'W200');    
define('ML_USER_PICSIZE_FEED' , 'W150');      
define('ML_USER_PICSIZE_SHOW', 'w320');
define('ML_USER_PICSIZE_COLLECTION', 'S90');
define('ML_USER_PICSIZE_RECOMMEND', 'S90');
define('ML_USER_PICSIZE_LIKERECOMMEND', 'S200');
define('ML_BG_PICSIZE_ORIGINAL', 'original_pic');
define('ML_BG_PICSIZE_BIG', 'big_pic');        
define('ML_BG_PICSIZE_BIGMIDDLE', 'bmiddle_pic');    

define('ML_PICTYPE_HEADER', 'header');
define('ML_PICTYPE_CONTENT', 'content');
define('ML_PICTYPE_LOOKBOOK_PHOTO', 'lmphoto');
define('ML_PICTYPE_LOOKBOOK_COVER', 'lmcover');
define('ML_PICTYPE_LOOKBOOK_INDEX', 'lmindex');
define('ML_PICTYPE_LOOKBOOK_MARK', 'lmmark');

define('ML_FEED_PAGESIZE' , 40);   //   feed页每页显示40条记录
define('ML_FOLLOW_MAXNUM' , 200);   //   关注上限
define('ML_FOLLOW_MAX', "您关注的人已达200人上限！");
define('ML_FOLLOW_PAGESIZE' , 20);   //   关注每页20
define('ML_FANS_PAGESIZE' , 20);   //   关注每页20


define('ML_FROM_WEB' , 1);
define('ML_FROM_PHONE' , 2);

define('MLAPP_FROM_VDAPEI',100010);



define('ML_PAGE_SELFINDEX' , '/home');
//seo
define('ML_META_KEY_PUBLIC', "美啦,美啦美啦,Meila,时尚媒体,淘宝网,天猫,凡客诚品,梦芭莎,网购,购物分享,时尚搭配,服饰搭配");
define('ML_META_LB_DES', "美啦杂志~为你提供最专业的服饰搭配规则~让你的在任何场合的装扮永不出错~和我们一起美啦美啦吧");
define('ML_META_DES_PUBLIC',"美啦~年轻时尚女性的必关注之地~这有最新的时尚潮流资讯~各路时尚达人分享时尚搭配秘笈~更多时尚杂志为你提供不一样的购物乐趣~ 爱上美啦的衣橱，和我们一起美啦美啦吧！");


//shop
define('ML_GOOD_SHOP_TYPE',1);//好店类型
define('ML_BAD_SHOP_TYPE',9);//普通店类型

$recommendUids = array(1000000,1000010,1000033,1000014,1000011,1000012,1000017,1000019);

//    我的首页  如果没有关注人，则默认用这些用户uid
$defaultUids = array(
            '0'=>1000010,
            '1'=>1000011,
            '2'=>1000012,
            '3'=>1000013,
            '4'=>1000014,
            '5'=>1000015,
            '6'=>1000016,
            '7'=>1000017,
            '8'=>1000018,
            '9'=>1000019,
            '10'=>1000020,
            '11'=>1000021
    );


define('ML_IMG_DIR_ROOT' , SYSDEF_DATA_ROOT_PATH.'ml_image');
define('ML_IMG_DIR_BIGPIC' , ML_IMG_DIR_ROOT.'/bigpic');
define('ML_IMG_DIR_SMALLPIC' , ML_IMG_DIR_ROOT.'/smallpic');
define('ML_IMG_DIR_PORTRAIT' , ML_IMG_DIR_ROOT.'/portrait');
define('ML_IMG_DIR_TEMP' , ML_IMG_DIR_ROOT.'/temp');

define('ML_IMG_HASHNUM' , 32);
define('ML_IMG_MAXSIZE' , 5*1024*1024*1024);

define('ML_IMG_TYPE_REGULARWIDTH', 1);
define('ML_IMG_TYPE_CROP', 2);


define('ML_HTMLBLOCK_DIR' , SYSDEF_PRIVDATA_ROOT_PATH.'/htmlblock');
?>