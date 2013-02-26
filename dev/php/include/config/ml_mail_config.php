<?php
define('ML_SMTP_HOST' , 'smtp.exmail.sina.com');
define('ML_SMTP_PORT' , 25);
define('ML_SMTP_TIMEOUT' , 30);
define('ML_SMTP_SENDER_MAIL' , 'admin@meila.com');
define('ML_SMTP_SENDER_NAME' , '美啦');
define('ML_SMTP_PASSWORD' , '');
define('ML_SMTP_TITLE' , '欢迎来到新浪美啦,请验证EMAIL');
define('ML_SMTP_CONTENT' , '请将以下地址复制到地址栏并访问以找回您的美啦帐户密码：<br/>'.SITE_ROOT_URL.'/page/user/setpwd.php?code=');


$mailArr = array(
    'sina.com'=>'mail.sina.com.cn',
    'sina.com.cn'=>'mail.sina.com.cn',
    'sina.cn'=>'mail.sina.com.cn',
    '163.com'=>'mail.163.com',
    '126.com'=>'mail.126.com',
    'yahoo.com'=>'mail.cn.yahoo.com',
    'yahoo.cn'=>'mail.cn.yahoo.com',
    'yahoo.com.cn'=>'mail.cn.yahoo.com',
    'hotmail.com'=>'mail.live.com',
    'sohu.com'=>'mail.sohu.com',
    'qq.com'=>'mail.qq.com',
    'tom.com'=>'mail.tom.com',
    'gmail.com'=>'mail.google.com',
    
);
