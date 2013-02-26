<?PHP
/**
 * Sina sso client
 *
 * @package SSOClient
 * @author lijunjie <junjie2@staff.sina.com.cn>
 * @author liuzhiyu <zhiyu@staff.sina.com.cn>
 * @copyright Copyright (c) 2011 SINA R&D Centre
 * @version $Id: $
 */


!defined('SSO_CLIENT_ROOT') && define('SSO_CLIENT_ROOT', dirname(__FILE__));
include_once SSO_CLIENT_ROOT . '/json.php';
include_once SSO_CLIENT_ROOT . '/SSOBase.php';
include_once SSO_CLIENT_ROOT . '/sessionmanager.php';
include_once SSO_CLIENT_ROOT . '/ssoapc.php';
include_once SSO_CLIENT_ROOT . '/SSOCookie.php';
include_once SSO_CLIENT_ROOT . '/SSOConfig.php';

@header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

//由于非sina.com.cn域和历史原因，部分产品使用时会修改SSOCookie.php文件名
//但类名仍然为SSOCookie，所以这里先检查，将来可规范化，并去除检查
if (!class_exists('SSOCookie', false)) {
    include_once SSO_CLIENT_ROOT . '/SSOCookie.php';
}

class SSO_Client extends SSOBase {
    const E_SYSTEM            = 9999;                    // 系统错误，用户不需要知道错误的原因

    const LOGIN_URL            = 'http://login.sina.com.cn/sso/login.php';    //登录接口
    const VALIDATE_URL        = 'http://ilogin.sina.com.cn/sso/validate.php';    //验证接口
    const GETSSO_URL        = 'http://ilogin.sina.com.cn/api/getsso.php';    //获取用户详细信息接口

    //配置信息
    private $_arrConfig = array(
                                //如果session距离过期时间小于这个阈值，那么就发送cookie给session server，尝试续时
                                'cookie_renew_threshold' => 3600, 
                                //如果连续这个次数以上发生因session server故障而验证失败，那么在一定时间以内总返回验证成功
                                'session_server_fail_limit' => 10, 
                                //apc中存储连续因session server或网络原因发生验证失败的次数的变量名称
                                'session_server_fail_limit_name' => 'sso_ss_ssfln',
                                //apc中存储连续因session server或网络原因发生验证失败的时间窗的变量名称
                                'session_server_fail_time_name' => 'sso_ss_ssftn',
                                //向session server发送请求的超时时间
                                'session_server_time_out' => 300,
                                //是否验证session
                                'use_session' => false,
                                'userinfo_cache_expire' => 5,
                                //cookie的配置信息在apc缓存中的过期时间
                                'cookie_config_apc_expiration_time' => 300,
    );

    private $_cookie        = null;
    private $_loginType        = '';
    private $_returnType    = 'META';
    private $_uid            = '';
    private $_userInfo        = array();
    private $_arrCookie;
    private $_arrLoginQuery = array();

    private $_cookieCheckLevel = 0; // cookie 验证级别

    private $_arrUserInfoCache = array();

    private static $_allowReEntrantIsLogined = false; // 默认判断登录状态的函数是不能重入的，第二次调用时直接返回第一次调用的结果
    private static $_arrStatic = array(
        'checkResult'=>array(
            'checked'=>false,
            'result'=>false
    ),
        'instance'=>array(
            '_cookie'=>'',
            '_loginType'=>'',
            '_uid'=>'',
            '_userInfo'=>array(),
            '_arrCookie'=>array(),
            '_arrUserInfoCache'=>array(),
            'error'=>'',
            'errno'=>0,
    )
    );

    private $_serviceId        = SSOConfig::SERVICE;    //应用产品标识, 一般和下面entry相同
    private $_entry            = SSOConfig::ENTRY;        //应用产品标识, 获取用户详细信息使用，由统一注册颁发的
    private $_pin            = SSOConfig::PIN;         //应用产品pin, 获取用户详细信息使用，由统一注册颁发的

    private $_need_validate_session    = true;         //是否需要验证session

    public function __construct() {
        if (!class_exists('SSOCookie', false)) {
            include_once SSO_CLIENT_ROOT . '/SSOCookie.php';
        }

        $this->_arrCookie    = $_COOKIE;
        $this->_cookie        = new SSOCookie();
        $this->_cookie->set_apc_expiration_time($this->_arrConfig['cookie_config_apc_expiration_time']);
        $this->_cookie->setCookieDomain(SSOConfig::COOKIE_DOMAIN);
        $this->_cookie->setCookieCheckLevel($this->_cookieCheckLevel);
    }

    /**
     * 退出，清除cookie
     * @return void
     */
    public function logout() {
        $this->_cookie->delCookie();    //实际上暂时不会做任何操作
        // 下面这两个删除cookie主要是针对非sina.com.cn域写的，对sina.com.cn域没有影响
        setcookie('SSOLoginState', 'deleted', 1, '/', SSOCookie::$COOKIE_DOMAIN);
        setcookie('ALF', 'deleted', 1, '/', SSOCookie::$COOKIE_DOMAIN);
        unset($_COOKIE['SSOLoginState']);
        unset($_COOKIE['ALF']);

        if (defined("SSOCookie::COOKIE_ALC")) {
            //兼容微博曾经有自己的ALC的用法
            setcookie(SSOCookie::COOKIE_ALC, 'deleted', 1, '/', SSOCookie::$COOKIE_DOMAIN);
            unset($_COOKIE[SSOCookie::COOKIE_ALC]);
        }
        if (@$_COOKIE["SUS"]) {
            $this->_destroySession($_COOKIE["SUS"]);
            setcookie(SSOCookie::$COOKIE_SUS, 'deleted', 1, '/', SSOCookie::$COOKIE_DOMAIN);
            unset($_COOKIE["SUS"]);
        }
    }

    /**
     * 设置cookie，用于无法在header中取到cookie的情况
     *
     * @param array $arrCookie
     * @return bool
     */
    public function setCustomCookie($arrCookie) {
        if (!$this->_cookie->setCustomCookie($arrCookie)) {
            $this->_setError($this->_cookie->getError(), $this->_cookie->getErrno());
            return false;
        }
        $this->_arrCookie = array_merge($this->_arrCookie, $arrCookie);
        return true;
    }

    public function setCookieCheckLevel($level) {
        $this->_cookieCheckLevel = $level;
        $this->_cookie->setCookieCheckLevel($this->_cookieCheckLevel);
    }

    /**
     * 用于IP验证
     */
    public function setCookieIp($ip) {
        $this->_cookie->setCookieIp($ip);
    }

    // 该方法未经会员平台允许，不得擅自外部使用，否则后果自负
    public function resetState($key, $val) {
        self::$_arrStatic[$key] = $val;
    }

    // 设置isLogined函数是否允许重入,该方法未经会员平台允许，不得擅自外部使用，否则后果自负
    public static function allowReEntrantIsLogined($bool) {
        self::$_allowReEntrantIsLogined = $bool;
    }

    /**
     * 检查用户是否登录
     *
     * @param bool $noRedirect        是否允许在需要的时候访问sso，js判断用户登录状态时，
     *                                该参数可以设置为true，然后js自己去访问sso，避免使用iframe时浏览器兼容问题
     * @return bool
     */
    public function isLogined($noRedirect = false) {
        // 防止方法重入, 如果已经验证过了，就直接返回结果
        if (self::$_arrStatic['checkResult']['checked']) {
            $this->_restoreInstance();
            return self::$_arrStatic['checkResult']['result'];
        }

        $arrQuery = $this->_getQueryArray();

        //    使用票据（st）登录
        if (SSOConfig::USE_SERVICE_TICKET && isset($arrQuery['ticket'])) {

            $query_result = array();
            if (!$this->_isValidateST($arrQuery['ticket'], $query_result)) {
                $this->logout();  // 删除可能存在的用户身份
                return $this->_checkResult(false);
            }
            //如果query返回的数据中有sid，则种下（作为Cookie）sid
            if(isset($query_result["sid"]) && !empty($query_result["sid"])) {
                $this->_setSession($query_result["sid"]);
            }

            //如果返回cookie，则种下cookie
            if (isset($query_result['cookie']) && $query_result['cookie']) {
                $this->_setCookie($query_result['cookie']);
                $this->_parseCookieStr($query_result["cookie"], $_COOKIE); //更新$_COOKIE数组
            }

            $this->_cookie->setCustomCookie($_COOKIE); // 重新初始化cookie类
            $this->_cookie->getCookie($userInfo); // 从$_COOKIE数组中解析用户信息

            $this->_arrCookie = $_COOKIE; // 更新类属性cookie数组
            $this->_userInfo = $userInfo;

            if (SSOCookie::$COOKIE_DOMAIN != ".sina.com.cn") {
                if (!@$arrQuery['ssosavestate']) { // delete ALF
                    $arrQuery['ssosavestate'] = 1;
                }
                setcookie(SSOCookie::$COOKIE_ALF, intval($arrQuery['ssosavestate']), intval($arrQuery['ssosavestate']), '/', SSOCookie::$COOKIE_DOMAIN);
            }

            //如果此时有ticket但无loginState则将来需要设置loginstate,针对非sina.com.cn域
            if (!isset($this->_arrCookie['SSOLoginState'])) {
                setcookie('SSOLoginState', time(), 0, '/', SSOCookie::$COOKIE_DOMAIN);
            }
            $this->_loginType = "st";
            return $this->_checkResult(true);

        } else if( isset($this->_arrCookie[SSOCookie::$COOKIE_SUE])) {
            //    使用cookie登录
            $use_rsa_sign = defined('SSOConfig::USE_RSA_SIGN') ? SSOConfig::USE_RSA_SIGN : false;
            if ($this->_cookie->getCookie($userinfo, $use_rsa_sign)) {
                /**************************************验证session********************************************************/
                $use_session = $this->_arrConfig['use_session'];
                if ($use_session && !$this->_validateSession($userinfo, $arr_result)) {
                    $this->logout();
                    return $this->_checkResult(false);
                }

                //如果返回数据中有设置新cookie的串，那么就种下新cookie(续时后的)
                if(isset($arr_result['cookie']) && is_string($arr_result['cookie'])) {
                    $this->_cookie->headerCookie($arr_result['cookie']);
                }
                /***************************************验证session完******************************************************/
                $this->_userInfo    = $userinfo;
                $this->_uid            = $this->_userInfo['uniqueid'];
                $this->_loginType    = 'cookie';

                return $this->_checkResult(true);
            }

            $this->_setError($this->_cookie->getError(), $this->_cookie->getErrno());
            // 无效的cookie试图删除
            $this->_cookie->delCookie();
        }

        if (@$arrQuery["retcode"] != 0) { // 这个必须写在检查SSOLoginState、ALF之前，否则就死循环了
            $this->_setError(@$arrQuery['reason'], $arrQuery['retcode']);
            $this->logout();
            return $this->_checkResult(false);
        }
        //only redirect to sso server when SSOLoginState or ALF is set
        if (isset($this->_arrCookie['SSOLoginState']) || isset($this->_arrCookie['ALF'])) {
            // 为了方便js判断用户状态
            if ($noRedirect) return $this->_checkResult(true);
            $this->_redirectToLoginUrl();
            exit();
        }

        // 对于外域才参考retcode
        $this->_setError(@$arrQuery['reason'], @$arrQuery['retcode']);
        $this->logout();  // 对于外域也一定要logout
        return $this->_checkResult(false);
    }

    /**
     * 获取用户详细信息,必须保证用户已登录或指定$uid 参数
     */
    public function getUserInfoByUniqueid($uid) {
        $query = array(
            'user'    => $uid,
            'ag'    => 0,
            'entry'    => $this->_entry,
            'm'        => md5($uid . 0 .$this->_pin)
        );
        $url = self::GETSSO_URL;
        $ret = $this->_query($url, $query);

        if($ret === false){
            $this->_setError('call '.$url.' error', self::E_SYSTEM);
            return false;
        }
        parse_str($ret,$arr);
        if ($arr['result'] != 'succ') {
            $this->_setError('call ' .$url ." error \n".$ret."\n".$arr['reason'], self::E_SYSTEM);
            return false;
        }
        return $arr;
    }
    /**
     * 获取用户信息
     */
    public function getUserInfo() {
        return $this->_userInfo;
    }
    /**
     * 获取登录方式
     */
    public function getLoginType() {
        return $this->_loginType;
    }
    /**
     * 获取用户唯一ID
     */
    public function getUniqueid() {
        return $this->_uid;
    }
    /**
     * 允许给login.php 自定义参数
     */
    public function setLoginQuery($arrQuery) {
        $this->_arrLoginQuery = $arrQuery;
        return true;
    }
    /**
     * 设置检查是否登录时的返回值类型
     */
    public function setReturntype($returntype){
        $this->_returnType = $returntype;
    }

    /**
     * 设置用户信息缓存时间
     */
    public function setUserInfoCacheExpire($userInfoCacheExpire){
        $this->_arrConfig['userinfo_cache_expire'] = $userInfoCacheExpire;
    }

    private function _parseCookieStr($str_cookie, &$arr_cookie) {
        $sue = $sup = array();
        preg_match('|SUE=(.*);|U', $str_cookie, $sue);
        preg_match('|SUP=(.*);|U', $str_cookie, $sup);

        if (!$sue || !$sup) {
            return false;
        }

        $arr_cookie[SSOCookie::$COOKIE_SUE] = rawurldecode($sue[1]);
        $arr_cookie[SSOCookie::$COOKIE_SUP] = rawurldecode($sup[1]);
        return true;
    }
    /**
     * 种cookie（header输出cookie）
     */
    private function _setCookie($str_cookie) {
        if (!$this->_cookie->headerCookie($str_cookie)) {
            $this->_setError($this->_cookie->getError(), $this->_cookie->getErrno());
            return false;
        }
        return true;
    }

    private function _setSession($sid) {
        setcookie('SUS', $sid, 0, SSOCookie::COOKIE_PATH, SSOCookie::$COOKIE_DOMAIN);
    }

    private function _destroySession($sid) {
        try {
            $result = SessionManager::destroy_by_sid(SSOConfig::ENTRY, $sid, SSOConfig::PIN);
        } catch(Exception $e) {
            $this->_setError($e->getMessage(), $e->getCode());
            return false;
        }
        return true;
    }

    private function _validateSession($arr_cookie_info, &$arr_result = array()) {
        // 如果用户没有session，则直接返回false
        if($arr_cookie_info['us'] != 1) return true;

        //如果连续发生n次（可配置）以上因服务器或网络原因而验证不通过，
        //那么在一个时间窗（可配置）内直接验证通过
        $limit = $this->_arrConfig['session_server_fail_limit'];
        $cur_time = time();
        if($this->_get_vft() > $limit) {
            if($cur_time <= $this->_get_vft_timestamp()) {
                return true;
            } else {
                $this->_clear_vft();
            }
        }

        //如果距离cookie过期的时间小于阈值，那么就将cookie发送给session server尝试进行续时
        $cookie_renew_threshold = $this->_arrConfig['cookie_renew_threshold'];
        $cookie_is_need_renew = ($arr_cookie_info['expiredtime'] - time()) < $cookie_renew_threshold;

        // 如果cookie 不需要续时，并且也不需要验证session，则直接返回false
        if(!$cookie_is_need_renew && !$this->_need_validate_session) return true;

        $cookie_str = null;
        if($cookie_is_need_renew) {
            $cookie_str = 'SUE=' . urlencode($this->_arrCookie[SSOCookie::$COOKIE_SUE]) .
                ';SUP=' . urlencode($this->_arrCookie[SSOCookie::$COOKIE_SUP]);
        }
        try {
            SessionManager::settimeout($this->_arrConfig['session_server_time_out']);
            $arr_result = SessionManager::validate(SSOConfig::ENTRY, //entry
            $this->_arrCookie[SSOCookie::$COOKIE_SUS],            //sid
            SSOCookie::$COOKIE_DOMAIN,                            //domain
            SSOCookie::getIp(),                                        //ip
            SSOConfig::PIN,                                        //signkey
            $cookie_str);                                        //cookie
        } catch(SessionException $e) {
            $errno = $e->getCode();
            if($errno != 30022) {
                $delaytime = $e->getDelayTime();
                $this->_increase_vft();
                $this->_set_vft_timestamp($delaytime);
                //非确定session不存在，都给验证通过。
                return true;
            } else {
                $this->_clear_vft();
            }
                
            $this->_setError($e->getMessage(), $e->getCode());
            return false;
        }

        $this->_clear_vft();
        return true;
    }

    /**
     * 检查ST是否有效,成功则通过$uid返回用户唯一ID
     *
     * @param    string        $ticket 票据
     *             &std_class    一个对象的引用，保存query返回的数据
     * return bool
     */
    private function _isValidateST($ticket, &$query_result) {
        //    登录成功后分发到的ST再到SSO服务器端确认
        $url = self::VALIDATE_URL;
        $ret = $this->_query($url, array('service'=>$this->_serviceId,
                                         'ticket'=>$ticket, 
                                         'domain'=>SSOCookie::$COOKIE_DOMAIN,
                                         'ip'=>SSOCookie::getIp(),
                                         'agent'=>$_SERVER['HTTP_USER_AGENT']));
        if($ret === false){
            $this->_setError('call ' .$url. 'error', self::E_SYSTEM);
            return false;
        }
        $arr = json_decode($ret, true);

        if ($arr["retcode"] != 0) {
            $this->_setError('call ' .$url ." error \n".$ret, self::E_SYSTEM);
            return false;
        }
        $this->_uid = $arr["uid"];

        //将decode出来的对象保存到引用参数的目标中
        $query_result = $arr;
        return true;
    }

    /**
     * 获取登录后的返回信息
     *
     * @return array
     */
    private function _getQueryArray() {
        $arrQuery = array();
        //为了避免rewrite丢掉url的参数，这里从$_SERVER['REQUEST_URI'] 中分析参数
        if (preg_match('/\?(.*)$/', $_SERVER['REQUEST_URI'], $matches)) {
            parse_str($matches[1], $arrQuery);
        }
        return array_merge((array)$arrQuery, $_POST);
    }

    /**
     * 获取跳转地址
     *
     * @return string
     */
    private function _getReturnUrl() {
        //redirect to sso server ,then user will send a new request with ST
        $scheme    = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $host    = $_SERVER['HTTP_HOST'];

        //由于7层做了内容转发，导致此处取到的HTTP_HOST可能与用户访问的地址不同，所以设置了一个修正机制
        if (property_exists('SSOConfig', 'HOST_MAPPING') && !empty(SSOConfig::$HOST_MAPPING) && isset(SSOConfig::$HOST_MAPPING[$host])) {
            $host = SSOConfig::$HOST_MAPPING[$host];
        }

        return $scheme . '://' . $host . $_SERVER['REQUEST_URI'];
    }

    /**
     * 重定向到登录地址去登录
     */
    private function _redirectToLoginUrl() {
        $arrRequest = array(
            'url'        => $this->_getReturnUrl(),
            'gateway'    => 1,
            'service'    => $this->_serviceId,
            'useticket'    => SSOConfig::USE_SERVICE_TICKET ? 1 : 0,
            'returntype'=> $this->_returnType,
            '_version_'    => self::VERSION,
        );
        $arrRequest = array_merge($arrRequest, $this->_arrLoginQuery);
        $loginURL    = self::LOGIN_URL;
        header("Cache-Control: no-cache, no-store");
        header("Pragma: no-cache");
        header('Location: '. $loginURL .'?'. http_build_query($arrRequest));
    }

    /**
     * 带版本号的发送请求
     *
     * @param string $url host
     * @param array $param        请求参数
     * @return mixed
     */
    private function _query($url, $param) {
        $param['_version_'] = self::VERSION;
        $query = http_build_query($param);
        return @file_get_contents($url.'?'.$query);
    }


    /**
     * 设置是否需要验证session。若session需要续时，则不受此限制。
     * @param    bool    $need 若为true，则需要验证
     *                        若为false， 则不需要验证
     */
    public function need_validate_session($need) {
        $this->_need_validate_session = $need ? true : false;
    }

    /**
     * 该函数为了避免isLogined函数重入
     * @param type $bool
     * @return type
     */
    private function _checkResult($bool) {
        if (self::$_allowReEntrantIsLogined) return $bool;
        self::$_arrStatic['checkResult'] = array(
                'checked' =>  true,
                'result' =>  $bool,
        );
        $arr = &self::$_arrStatic['instance'];
        foreach($arr as $key=>$val) {
            if ($key == "error" || $key == "errno") continue;
            $arr[$key] = $this->$key;
        }
        $arr["error"] = $this->getError();
        $arr["errno"] = $this->getErrno();
        return $bool;
    }
    private function _restoreInstance() {
        $arr = self::$_arrStatic['instance'];
        foreach($arr as $key=>$val) {
            if ($key == "error" || $key == "errno") continue;
            $this->$key = $val;
        }
        if ($arr["error"]) {
            $this->_setError($arr["error"], $arr["errno"]);
        }
    }

    /**
     * 清零"验证失败计数器"变量（self::VALIDATE_FAIL_CNT）
     */
    private function _clear_vft() {
        SSOApc::getInstance()->set($this->_arrConfig['session_server_fail_limit_name'], 0);
    }

    /**
     * 将"验证失败计数器"增1。（没有这个变量相当于变量为0）
     */
    private function _increase_vft() {
        $cnt = SSOApc::getInstance()->get($this->_arrConfig['session_server_fail_limit_name']);
        if($cnt === false) { //第一次读取此变量，它还不存在，初始化它
            SSOApc::getInstance()->set($this->_arrConfig['session_server_fail_limit_name'], 1);
        } else {
            SSOApc::getInstance()->set($this->_arrConfig['session_server_fail_limit_name'], $cnt+1);
        }
    }

    /**
     * 取得"验证失败计数器"。（没有这个变量相当于变量为0）
     */
    private function _get_vft() {
        $cnt = SSOApc::getInstance()->get($this->_arrConfig['session_server_fail_limit_name']);
        if($cnt === false) { //第一次读取此变量，它还不存在，初始化它
            $cnt = 0;
            SSOApc::getInstance()->set($this->_arrConfig['session_server_fail_limit_name'], $cnt);
        }
        return $cnt;
    }

    /**
     * 设置第一次连续发生n次（可配置）因为服务器或网络故障而验证失败的时间
     *
     * @param    int    $delaytime 延迟时间
     */
    private function _set_vft_timestamp($delaytime) {
        SSOApc::getInstance()->set($this->_arrConfig['session_server_fail_time_name'], time()+$delaytime);
    }

    /**
     * 取得第一次连续发生n次（可配置）因为服务器或网络故障而验证失败的时间
     */
    private function _get_vft_timestamp() {
        return SSOApc::getInstance()->get($this->_arrConfig['session_server_fail_time_name']);
    }

    /**
     * 设置配置信息
     */
    public function setConfig($name, $value) {
        if(!array_key_exists($name, $this->_arrConfig))
        return false;
        $this->_arrConfig[$name] = $value;
    }
}
