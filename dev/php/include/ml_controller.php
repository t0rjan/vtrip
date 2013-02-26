<?php
/**
 *@fileoverview: [群博客] controller 基类
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Tue Nov 30 02:42:20 GMT 2010
 *@copyright: sina
 */
require_once(dirname(dirname(__FILE__)).'/Inc/Common.inc.php');
include_once(SERVER_ROOT_PATH.'/__global.php');
include_once(SERVER_ROOT_PATH.'/include/tool/ml_tool_jsoutput.php');            //接口标准输出
include_once(SERVER_ROOT_PATH.'/include/tool/ml_tool_httpheader.php');          //http头输出

define('ML_LOGIN_NEEDVCODE', 'needvcode');

abstract class ml_controller_base
{
    //~~需要应用程序重定的方法~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * @develop_template
     * 接收并初始化参数
     */
    abstract function initParam();
    /**
     * @develop_template
     * 检查参数合法性
     */
    abstract function checkParam();
    /**
     * @develop_template
     * 核心业务逻辑
     */
    abstract function main();
}

class ml_controller extends ml_controller_base
{
    const ACCESS_PUBLIC = 0;
    const ACCESS_LOGIN = 1;
    const ACCESS_SELF = 2;
    const NOACCESS_NOACT = 0;
    const NOACCESS_REDIRECT = 1;
    const NOACCESS_APIOUTPUT = 2;


    protected $_login;

    private $__access = self::ACCESS_PUBLIC ;
    private $__noaccess_act = self::NOACCESS_NOACT ;
    protected  $__visitor = array();
    protected  $__owner = array();
    protected  $__isself = false;
    private $_needWBrsync = false;
    private $_page_title;
    private $_meta_keyword = array();                    //页面关键字
    private $_meta_description = array();                //页面关键字
    private $_nav = array();   //导航信息数组
    private $_scope = array(
        '$PRODUCT_NAME' => 'meila',
        '$pageid' => '',
        '$notice' => ''
        );
    protected $_option = array(); //接收参数的集合

    /**
     * @var object session对象单例
     */
    private static $_sessInstance = NULL;


    public function init(){}
    public function initParam(){}
    public function checkParam(){}
    public function main(){}


    public function __construct(){
        
        $this->start_time = microtime(1);

        //在这里301定向到meila
/*
        $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['SCRIPT_FILENAME']);
        $url = $_SERVER['REQUEST_URI'];
        $Aurl = explode("/", $url);
        $Apath = explode("/", $path);
        if($_SERVER['HTTP_HOST']=='www.meila.com'&&$Apath[0]=="index.php"||
           $_SERVER['HTTP_HOST']=='www.meila.com'&&$Apath[0]=="page"||
           $_SERVER['HTTP_HOST']=='www.meila.com'&&$Apath[0]=="activity"){//活动估计以后得搬到page下
           Header( "HTTP/1.1 301 Moved Permanently" );
           $this->redirect("http://meila.com".$url);
        }
*/            
        
        if (SYSDEF_DEBUG && function_exists('xhprof_enable')){
            xhprof_enable(XHPROF_FLAGS_MEMORY);
        }
        $this->_scope['$pageid'] = get_class($this);
        ml_factory::set_controller($this);
/*
        $this->_login = ml_biz_login::get_instance();


        $this->init();

        $this->_check_vistor();
        ml_tool_ua::add_usid();
        if (!$this->__visitor['online']) {
            $this->_login->cookie_login();

            if (!$this->__visitor['online']) {
                
                if($this->__noaccess_act == self::NOACCESS_REDIRECT )
                $this->redirect(ML_PAGE_LOGIN);
                else if($this->__noaccess_act == self::NOACCESS_APIOUTPUT)
                $this->api_output(ML_RCODE_NOLOGIN);
            }
        }
        if(!$this->check_permission(ML_PERMISSION_LOGIN_CANWRITE)) {
            if($this->__noaccess_act == self::NOACCESS_REDIRECT )
            $this->redirect(ML_PAGE_ACTIVE);
            else if($this->__noaccess_act == self::NOACCESS_APIOUTPUT)
            $this->api_output(ML_RCODE_NOACTIVE);

        }
 */
        //投放微博用户访问路径
        $this->_wb_visit_log();

        $this->initParam();
        $this->checkParam();
        $this->main();
    }

    /**
     * 为了调试用
     *
     */
    public function __destruct(){
        if (SYSDEF_DEBUG && function_exists('xhprof_disable')){
            $xhprof_data = xhprof_disable();
            $filepath = SYSDEF_LOG_DEBUG_PATH . '/xhprof/' . get_class($this);
            if (!file_exists($filepath)){
                mkdir($filepath, 0755, true);
            }
            $filename = $filepath . '/' . date('Y-m-d_H_i_s') . '__' . substr(uniqid(), -4) . '.xhprof';
            file_put_contents($filename, serialize($xhprof_data));
        }
    }
    //公用~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    /**
     * 接收数据参数
     *
     * @param string $key
     * @param string $method
     * @param mix $default
     * @return mix
     */
    public function input($key , $method = '' , $default = null)
    {
        $method = strtoupper($method);
        if('G' == $method || 'GET' == $method)
        {
            $value = isset($_GET[$key]) ? $_GET[$key] : $default;
        }
        if('P' == $method || 'POST' == $method)
        {
            $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        }
        else
        {
            $value = isset($_GET[$key])
            ? $_GET[$key]
            : (isset($_POST[$key])
            ? $_POST[$key]
            : $default);
        }

        return trim($value);
    }
    /**
     * @检测参数
     */
    public function check_option(){
        if(!is_array($this->_param_check))
            return true;
        // 检查并处理处理参数
        foreach($this->_param_check as $name=>$option){
            if(!empty($option['call'])){
                if(empty($this->_option[$name])){
                    if($option['is_must'])
                        $rs_param = false;
                    else
                        $rs_param = true;
                }else{
                    if('callback' == $option['call']){
                        $fun_name = '_param_'.$name;
                        $rs_param = $this->$fun_name();
                    }else{
                        $fun_name = $option['call'];
                        $rs_param = $fun_name($this->_option[$name]);
                    }
                }
                
            }else{
                $rs_param = Tool_input::param_base($this->_option[$name], $option);
            }
            if(!$rs_param)
                $this->api_output(ML_RCODE_PARAM, '', $this->_error_msg[$name]);
        }
        return true;
    }
    /**
     * 接口标准输出
     *
     * @param string $code          //状态码
     * @param array $data           //数据    如果为array()    则DATA也会输出为[]
     * @return void
     */
    public function api_output($code , $data = null , $msg = '')
    {
        $out_data = array(
        'code' => $code,
        );
        if($data || is_array($data))
        $out_data['data'] = $data;

        if($msg)
        $out_data['msg'] = $msg;

        $this->_over();
        ml_tool_jsoutput::output($out_data , $this->input('format') , $this->input('varname') , $this->input('jsonp'));
    }
    /**
     * 页面输出
     *
     * @param string $tpl_name      //模块地址
     * @param array $data    //数据
     */
    public function page_output($tpl_name , $data = array())
    {
        if($this->__access == self::ACCESS_SELF )
        $this->__isself = true;
         
        $aPath = Tool_pathParser::parse($tpl_name , '::');
        $tpl_path = $aPath['path'].'/tpl_'.$aPath['filename'].'.php';
        if(is_array($data)) extract($data);
        $this->set_scope_var('$site_root_url', 'http://'.$_SERVER['HTTP_HOST']);
        if($this->__visitor['uid'])
        {
            $aVisitor = array(
                'uid' => $this->__visitor['uid'],
                'nickname' => $this->__visitor['nickname'],
                'headPic' => ml_tool_picid::uid2portrait($this->__visitor['uid'],'sml'),
            );
            $this->set_scope_var('$visitor' , $aVisitor);
        }
        if($this->__owner['uid'])
        {
            $aOwner = array(
                'uid' => $this->__owner['uid'],
                'nickname' => $this->__owner['nickname'],
                'headPic' => ml_tool_picid::uid2portrait($this->__visitor['uid'],'sml'),
            );
            $this->set_scope_var('$owner' , $aOwner);
        }
        $page_id = $this->_scope['$pageid'];
        $scope = json_encode($this->_scope);
        $scope = '<script type="text/javascript">
var scope = '.$scope.'
</script>
';

        $isLogin = $this->__visitor['online']==ML_USER_ONLINE ? true : false;
        $ifme = $this->__isself;
        $page_title = $this->_page_title;

        if(count($this->_meta_keyword)>0)
            $meta['keywords'] = implode(',' , array_unique($this->_meta_keyword));
        if(count($this->_meta_description)>0)
            $meta['description'] = implode(',' , array_unique($this->_meta_description));

        $this->add_page_nav('classes', ml_tool_getdata::lbJsonData('class'));
        $this->add_page_nav('catelog', ml_factory::load_standard_conf('catelog'));
        if(count($this->_nav)>0)
            $nav = $this->_nav;
        //输出禁用缓存HEADER
        ml_tool_httpheader::no_cache();
        $this->_over();
        include_once(SERVER_ROOT_PATH.'/view/'.$tpl_path);
        if ($this->_needWBrsync) {
            $sd = session_id();
            //$sd = Tool_encrypt::stingCode($sd.'cc', 'E');
            include_once(SERVER_ROOT_PATH.'/view/wbrsync/_tpl_wbrsync.php');
        }
    }
    /**
     * 页面重定向
     *
     * @param string $url
     */
    public function redirect($url)
    {
        $url = str_replace(array("\n", "\r"), '', $url);
        if (!headers_sent())
        {
            header("Content-Type:text/html; charset=utf-8");
            header("Location: ".$url);
            exit;
        }
        else
        {
            ml_tool_httpheader::no_cache();
            $str = "<meta http-equiv='Refresh' content='0;URL={$url}'>";
            exit($str);
        }
    }
    /**
     * 设置页面ID
     *
     * @param string $pageid
     */
    public function set_page_id($pageid)
    {
        $this->_scope['$pageid'] = $pageid;
        return ;
    }
    /**
     * 设置页面SCOPE变量
     *
     * @param unknown_type $key
     * @param unknown_type $value
     */
    public function set_scope_var($key , $value)
    {
        $this->_scope[$key] = $value;
        return ;
    }

    public function set_page_title($title){
        $this->_page_title = $title.ML_CNF_PAGETITLE_SUFFIX;
        return ;
    }

    public function set_page_title2($title){
        $this->_page_title = $title.ML_CNF_PAGETITLE_SUFFIX2;
        return ;
    }
    /**
     * 增加页面 导航配置信息
     * 
     * @param key $string
     * @param value
     * wangtao5@
     */
    public function add_page_nav($key , $value){
        $this->_nav[$key] = $value;
        return ;
    }
    /**
     * 设置页面 SSO描述
     *
     * @param string $string
     * @return unknown
     */
    public function add_page_description($string)
    {
        $this->_meta_description[] = $string;
    }
    /**
     * 增加页面 SSO关键字
     *
     * @param unknown_type $string
     */
    public function add_page_keyword($string)
    {
        $this->_meta_keyword[] = $string;
        return ;
    }
    /**
     * login相关若干方法
     * Enter description here ...
     * @param unknown_type $funcName
     */

    public function loginProxy($funcName) {
         
        $p = func_get_args();
        array_shift($p);

        return call_user_func_array(array($this->_login, $funcName), $p);
    }
    

    public function init_owner($uid) {
         
        if (!ml_tool_isuid::is_mluid($uid)) return false;
         
        if($this->__visitor['online'] == ML_USER_ONLINE && $this->__visitor['uid'] == $uid)
        $this->__isself = true;
        else
        {
            $oAccount = new ml_model_dbUserAccount();
            $userinfo = $oAccount->getAccountById($uid);

            if($userinfo)
            {
                $this->__owner['uid'] = $userinfo['uid'];
                $this->__owner['nickname'] = $userinfo['nickname'];
                $this->__owner['email'] = $userinfo['email'];
                $this->__owner['verifyE'] = $userinfo['verify_email'];
                $this->__owner['status'] = $userinfo['status'];
            }

        }
    }



    public function set_access($acc , $noacc_act = self::NOACCESS_NOACT)
    {
        $this->__access = $acc;
        $this->__noaccess_act = $noacc_act;
    }
    /**
     * 只管页面访问权限
     * 返回bool
     */
    public function check_permission($permission) {
        switch ($permission) {
            case ML_PERMISSION_LOGOUT_ONLY:
                return $this->__visitor['online']==ML_USER_OFFLINE?true:false;
                break;
            case ML_PERMISSION_LOGIN_CANWRITE:
                if ($this->__visitor['online']== ML_USER_OFFLINE) return false;
                $tmp = $this->__visitor['status']== ML_USERSTATUS_OK && $this->__visitor['verifyE'];
                if ($this->__visitor['status']== ML_USERSTATUS_THIRD || $tmp) {
                    return true;
                }
                break;
            case ML_PERMISSION_UNVERIFY_ONLY:
                if ($this->__visitor['online']== ML_USER_OFFLINE) return false;
                $tmp = $this->__visitor['status']== ML_USERSTATUS_OK && !$this->__visitor['verifyE'];
                return $tmp?true:false;
                break;
            case ML_PERMISSION_LOGIN_ONLY:
                return $this->__visitor['online']==ML_USER_ONLINE?true:false;
                break;
            case ML_PERMISSION_UNVERIFY_CANREAD:
                return $this->__visitor['verifyE']==ML_NOTVERIFY?true:false;
                break;
            default:
                return false;
        }
    }

    public function get_visitor()
    {
        return $this->__visitor;
    }
    public function get_owner()
    {
        return $this->__owner;
    }

    /**
     * 检查访问访问者登录状态
     * session
     * @return int or true
     * -1 未登录
     * -2 未开通博客
     * -9 错误
     *
     */
    public function _check_vistor()
    {

        //$this->set_scope_var('weiboinfo', $a);
        $userinfo = $this->_login->is_ml_login();
        if($userinfo)
        {
            $this->__visitor['uid'] = $userinfo['uid'];
            $this->__visitor['nickname'] = $userinfo['nickname'];
            $this->__visitor['email'] = $userinfo['email'];
            $this->__visitor['verifyE'] = $userinfo['verify_email'];
            $this->__visitor['status'] = $userinfo['status'];
            $this->__visitor['online'] = ML_USER_ONLINE;

            if ($this->__visitor['status'] == ML_USERSTATUS_THIRD) {
                if ($this->__visitor['verifyE'] == ML_NOEMAIL) {
                    $this->_scope['$notice'] = ML_3RD_NOEMAIL;
                }
                elseif ($this->__visitor['verifyE'] == ML_NOTVERIFY) {

                    //拼写邮件
                    $domain = strstr($this->__visitor['email'], '@');
                    $domain = substr($domain, 1);
                    global $mailArr;
                    $mail_url = $mailArr[$domain];
                    if (empty($mail_url)) {
                        $mail_url = 'http://mail'. $domain;
                    }


                    $this->_scope['$notice'] = ML_3RD_NOVERIFY;
                    $this->_scope['mail_url'] = 'http://'.$mail_url;
                    $this->_scope['email'] = $this->__visitor['email'];
                }
            }
        }
        else
        {
            $a = $this->_login->getWeiboInfo();
            if (empty($a)|| $a['timeout'] < time() ||$a['isLogin'] === false) {
                $this->_needWBrsync = true;
            }
            $this->__visitor['online'] = ML_USER_OFFLINE;

            //未登录用户是否需要验证码
            $oSession = $this->getSession();
            $count = $oSession->getVal('login_fail_count');
            if (2 < $count) {
                $this->_scope['$loginStatus'] = ML_LOGIN_NEEDVCODE;
            }
        }

        $this->_scope['$online'] = $this->__visitor['online'];
    }



    /**
     * 获取对象唯一实例
     *
     * @param string $configFile 配置文件路径
     * @return object 返回本对象实例
     */

    public static function getSession($name = "SessionID") {
         
        if(!isset(self::$_sessInstance))
        {
            self::$_sessInstance = new Lib_memSession($name);
        }

        /*
         if (!(self::$_sessInstance instanceof self)){
         self::$_sessInstance = new Lib_memSession($name);
         }
         */
         
        return self::$_sessInstance;
    }

    private function _wb_visit_log()
    {
        return ;
        $wbinfo = $this->_login->getWeiboInfo();
        $wb_uid = $wbinfo['weiboID'];
        $oSess = $this->getSession();
        $key = 'wbTinfo';
        if($_GET['Tuid'])
        {
            $tinfo = array();
            $_GET['Tuid'] ? $tinfo['Tuid'] =$_GET['Tuid'] : null;
            $_GET['Tmid'] ? $tinfo['Tmid'] =$_GET['Tmid'] : null;
            $_GET['Ttag'] ? $tinfo['Ttag'] =$_GET['Ttag'] : null;
            $_GET['Tfrom'] ? $tinfo['Tfrom'] =$_GET['Tfrom'] : null;
            $tinfo['Tsign'] = array_sum(explode(' ' , microtime()));
            setcookie('mlWbTinfo' , serialize($tinfo),0,'/','.meila.com');
        }
        else
        {
            $tinfo = unserialize($_COOKIE['mlWbTinfo']);
        }

        //if($wb_uid || !empty($tinfo))
        //ml_tool_async::tuiguang_log($wb_uid , $_SERVER['REQUEST_URI'] , $this->__visitor['uid'] , $tinfo);

        return ;
    }
    private function _over()
    {
        Tool_logger::debugLog('time' , (microtime(1)-$this->start_time) , 1);

        if(SYSDEF_DEBUG)
        {
            Tool_logger::saveRunningLog();
        }

        if(SYSDEF_DEBUG && extension_loaded('xhprof'))
        {
            $arr = xhprof_disable();
            $filename = get_class($this).'.xhprof';
            Tool_logger::oneLog('xhprof' , $filename , serialize($arr) );
        }
        return ;
    }
    protected function check_referer(){

return;
            $referer = $_SERVER['HTTP_REFERER'];
            $parts = parse_url($referer);
            //if(!empty($referer)){

            //$accessList = array('meila.com','www.meila.com','dr.meila.com','lookbook.meila.com');


            if (!preg_match("/meila.com$/", $parts['host'])){
                $this->api_output(ML_RCODE_HACK, 'referer');
            }
            //}
             
        }
}
