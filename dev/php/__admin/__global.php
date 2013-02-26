<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/__global.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Tue Feb 09 22:47:22 CST 2010
************************************/

include (dirname(dirname(__FILE__)).'/__global.php');
include(SERVER_ROOT_PATH.'/include/config/admin/ml_admin_queuename.php');

class admin_ctrl
{
    private $session;
    private $session_data = array();
    protected $need_login = true;
    protected $module_data = array();
    
    public function __construct()
    {
        $this->session = new Lib_memSession();
        $this->session_data = $this->session->getVal('__ADMIN');
        
        
        
        if(!isset($this->session_data['uid']) && $this->session_data['uid'] < 1 && $this->need_login)
        {
            
            $this->no_login();
        }
        
        $this->_construct();
        
        if($_GET['page'])
        {
            $method = 'page_'.$_GET['page'];
            if(method_exists($this , $method))
            {
                $this->$method();
            }
            else
                $this->_redirect();
        }
        else if($_GET['api'])
        {
            $method = 'api_'.$_GET['api'];
            if(method_exists($this , $method))
            {
                $this->$method();
            }
        }
        else 
        {
            
            $this->run();
        }
        
        
        $this->_over();
        return ;
    }
    /**
    * 准构造函数 需要在子类中被定义 在RUN之前执行
    * 
    */
    protected function _construct()
    {
    
        
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $key
    * @param mixed $value
    */
    protected function set_session($key , $value)
    {
     
        $this->session_data[$key] = $value;
        $this->session->setVal('__ADMIN' , $this->session_data);
        $this->session->save();
    }
    public  function get_session($key)
    {
        return $this->session_data[$key];
    }
    protected function del_session($key)
    {
        unset($this->session_data[$key]);
        $this->session->setVal('__ADMIN' , $this->session_data);
    }
    
    /**
     * get the input
     * caution!! could not get the array like this : ?a[]=1&a[]=2&a[]=3
     *
     * @param string $key
     * @param string $method  G/GET P/POST OTHER
     * @param mix $default    //default value when !isset
     * @return mix
     */
    protected function input($key , $method = 'all' , $default = null)
    {
        $method = strtoupper($method);
        if('G' == $method || 'GET' == $method)
        {
            $value = isset($_GET[$key]) ? $_GET[$key] : $default;
            $this->__input[$key] = trim($value);
        }
        if('P' == $method || 'POST' == $method)
        {
            $value = isset($_POST[$key]) ? $_POST[$key] : $default;
            $this->__input[$key] = trim($value);
        }
        else
        {
            $value = isset($_GET[$key]) 
                    ? $_GET[$key] 
                    : (isset($_POST[$key]) 
                        ? $_POST[$key] 
                        : $default);
            $this->__input[$key] = trim($value);
        }
        
        return trim($value);
    }
    
    protected function output_js($code , $data)
    {
        echo json_encode(array('code'=>$code , 'data'=>$data));
        $this->_over();
        die;
    }
    protected function output($data = array() , $tpl_name = '')
    {
        global $adm , $_url;
        $adm = array_merge($data , $this->module_data);
        $tpl_file = 'page_'.get_class($this);
        
        if($tpl_name)
            $page = 'page_'.$tpl_name;
        else
            $page = $_GET['page'] ? 'page_'.$_GET['page'] : 'page_index';    
        
        echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录管理后台</title>
<link href="../css.css" rel="stylesheet" type="text/css">
<script src="/static/js/jquery-1.8.0.min.js" type="text/javascript" ></script>
</head>
<body>';
        
        if(is_file(SERVER_ROOT_PATH.'/__admin'.'/view/'.$tpl_file.'.php'))
        {
            include(SERVER_ROOT_PATH.'/__admin'.'/view/'.$tpl_file.'.php');
            
            $page($adm);
        }
        else
            die('tpl_error');
        echo '</body>
</html>';

        $this->_over();
        die;
    }
    
    protected function run(){}
    /**
     * 未登录 跳转到登录页面
     *
     */
    protected function no_login()
    {
        echo '<script>window.top.location="/__admin/login.php";</script>';
        die;
    }
    protected function busy($msg = '')
    {
        die('BUSY!'.$msg);
    }
    
    protected function _redirect($url , $lazy_msg = '' , $sec = 1)
    {
        if(headers_sent() || $lazy_msg)
        {
            echo '<meta http-equiv="refresh" content="'.$sec.'; url='.$url.'" /><meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo $lazy_msg.'<br/>'.$sec.'秒后跳转...';
        }
        else 
        {
            header('Location:'.$url);
        }
        $this->_over();
        die;
    }
    protected function _parent_reload()
    {
        echo "<script>window.parent.histrory.reload();</script>";
    }
    protected function back($param)
    {
        $this->_redirect($_SERVER['HTTP_REFERER'].$param);
    }
    
    protected function _check_adm_level($access)
    {
        $level = $this->get_session('level');
        if($level == K_ADM_LV_ADMINISTRATOR)
            return true;
            
        if($level != $access)
            die('该功能只对管理员开放');
    }

    protected function _over()
    {
        Tool_logger::saveRunningLog();
    }
}




?>