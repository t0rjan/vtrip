<?php
define('SINADEF_SSO_COOKIECONF_PATH' , '/usr/local/sinasrv2/lib/php/cookie.conf');

define('SITE_ROOT_URL','http://trip.com');
//定义第三方登录头像上传接口地址
define('OPENAPI_HEAD_UPLOAD','http://upload.image.meila.com/interface/bg_ml_3rdheader.php');



//定义app回调地址
define('OPENAPI_CALLBACK_URL',SITE_ROOT_URL.'/page/callback.php');
//定义手机app回调地址
define('WAP_OPENAPI_CALLBACK_URL',SITE_ROOT_URL.'/vdapei/wap_callback.php');
//定义新浪微薄appkey
define('OPENAPI_WEIBO_APP_KEY','2962554693');
define('OPENAPI_WEIBO_APP_TOKEN','345e6c0d826918ba77462a1f708a0ab3');
//定义腾讯微薄appkey
define('OPENAPI_QQ_APP_KEY','801065201');
define('OPENAPI_QQ_APP_TOKEN','00562bd2bf58db8f93f8d7d8582f348e');
//定义人人网appkey
define('OPENAPI_RENREN_APP_KEY','ccac17a005bc49a485a83feabfe5dfd4');
define('OPENAPI_RENREN_APP_TOKEN','f69084e295ef4f8484f0b1e0bc171aca');

//定义淘宝网appkey
define('OPENAPI_TAOTAO_APP_AUTHURL','https://oauth.taobao.com/authorize');

//开心网定义
define('OPENAPI_KAIXIN_APP_KEY', '362455447029714ea2c0d98836c1a619');
define('OPENAPI_KAIXIN_APP_SECRET', '3e552103e90931ea6154b0d830c391de');

?>