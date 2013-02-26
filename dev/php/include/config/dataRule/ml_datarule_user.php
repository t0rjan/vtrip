<?php
//define('ML_DATARULE_USER_NICK_PREG' , '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u');
define('ML_DATARULE_USER_NICK_PREG' , '/^[\x{4e00}-\x{9fa5}\x{3040}-\x{317f}\x{AC00}-\x{D7A3a}a-zA-Z0-9_]+$/u'); 
define('ML_DATARULE_USER_NICK_MINLEN' , 4);
define('ML_DATARULE_USER_NICK_MAXLEN' , 20);

define('ML_DATARULE_USER_EMAIL_MINLEN' , 5);
define('ML_DATARULE_USER_EMAIL_MAXLEN' , 50);

define('ML_DATARULE_USER_GENDER_BOY' , 0);
define('ML_DATARULE_USER_GENDER_GIRL' , 1);

define('ML_DATARULE_USER_DECLARATION_MAXLEN', 280);
define('ML_DATARULE_USER_SKILLTAGS_MAXLEN', 20);
define('ML_DATARULE_USER_OCCUPATION_MAXLEN', 20);

define('ML_DATARULE_USER_WEIBO_ADDRESS', '/^http[s]?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/\?%&=]*)?$/u');
define('ML_DATARULE_USER_PASSWORD_PREG' , '/[0-9a-zA-Z]{6,15}/');
?>