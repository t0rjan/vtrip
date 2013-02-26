<?php
/**
 * Sina sso client
 * @package  Client
 * @filename Cookie.class.php
 * @author   lijunjie <junjie2@staff.sina.com.cn>
 * @date      2009-06-18
 * @version  1.3
 */

/**
 * @brief     set & get cookie for sina.com.cn
 *             cookie format:  [ uniqueid:userid:appgroup:displayname:gender:paysign ]
 */

 

class SSO_Cookie {
    var $COOKIE_SUE = 'SUE';   //sina user encrypt info
    var $COOKIE_SUP = 'SUP';   //sina user plain info

    var $COOKIE_KEY_FILE =  SINADEF_SSO_COOKIECONF_PATH ;

    var $_error;
    var $_errno = 0;
    var $_arrConf; // the infomation in cookie.conf
    var $_arrKeyMap = array("cv"=>"cookieversion","bt"=>"begintime","et"=>"expiredtime","uid"=>"uniqueid","user"=>"userid","ag"=>"appgroup","nick"=>"displayname","sex"=>"gender","ps"=>"paysign");

     function SSO_Cookie($config = NULL) {
        if ($config == NULL) $config = $this->COOKIE_KEY_FILE;
        if(!$this->_parseConfigFile($config)){
            die("parse config file failed");
        }
    }
     function getCookie(&$arrUserInfo) {
        $sup = $_COOKIE[$this->COOKIE_SUP];
        if(!$sup) return false;

        parse_str($sup,$arrSUP);
        $cookieVersion = $arrSUP['cv'];
        switch($cookieVersion) {
            case 1:
                return $this->_getCookieV1($arrUserInfo);
                break;
            default:
                return false;
        }
    }

    /**
     * delete cookie
     */
    function delCookie() {
        // 产品可以在这里删除自己的cookie
        return true;
    }

    function _getCookieV1(&$arrUserInfo) {
        // 不存在密文cookie或明文cookie视为无效
        if( !isset($_COOKIE[$this->COOKIE_SUE]) ||
            !isset($_COOKIE[$this->COOKIE_SUP])) {
                $this->_setError('not all cookie are exists ');
                return false;
            }
        parse_str($_COOKIE[$this->COOKIE_SUE],$arrSUE);
        parse_str($_COOKIE[$this->COOKIE_SUP],$arrSUP);
        foreach( $arrSUP as $key=>$val) {
            if(isset($this->_arrKeyMap[$key])) $key = $this->_arrKeyMap[$key];
            $arrUserInfo[$key] = iconv("UTF-8","GBk",$val);
        }
        // 判断是否超时
        if($arrUserInfo['expiredtime'] < time()) {
            $this->_setError("cookie is timeout ");
            return false;
        }

        // �??查加密cookie
        if($arrSUE['es2'] != md5(rawurlencode($_COOKIE[$this->COOKIE_SUP]) . $this->_arrConf[$arrSUE['ev']])) {
            $this->_setError("encrypt string error");
            return false;
        }
        return true;

    }

    /**
     * parse cookie config file.
     * @param $config: cookie config file
     */
    function _parseConfigFile($config) {
        $arrConf = @parse_ini_file($config);
        if(!$arrConf) {
            $this->_setError('parse file '.$config . ' error');
            return false;
        }
        $this->_arrConf = $arrConf;
        return true;
    }

    function _setError($error,$errno=0) {
        $this->_error = $error;
        $this->_errno = $errno;
    }

    function getError() {
        return $this->_error;
    }

    function getErrno() {
        return $this->_errno;
    }

}

?>
