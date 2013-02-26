<?php
define('ML_DATARULE_BASE_URL', '/[http:\/\/]*.+\.\w+/');
define('ML_INTERFACE_FETCHIMG', 'http://upload.image.meila.com/interface/bg_ml_fetchImg.php'); //抓取图片的接口路径

define('ML_RID_LEN', 17); //rid的长度

define('ML_DATATYPE_DIGIT', 10); //数字
define('ML_DATATYPE_ALPHA', 11); //字母
define('ML_DATATYPE_ALNUM', 12); //数字字母
define('ML_DATATYPE_FLOAT', 13); //浮点数
define('ML_DATATYPE_INARRAY', 14); //是否在数组范围内

define('ML_DATATYPE_EMAIL', 20); //邮箱

define('ML_DATATYPE_URL',   30); //url
define('ML_DATATYPE_URL_WEIBO',   31); //微博的url

define('ML_DATATYPE_USER_NICK', 40); //昵称
define('ML_DATATYPE_USER_PASSWORD', 41); //密码

define('ML_DATATYPE_PREG',  90); //正则表达式
