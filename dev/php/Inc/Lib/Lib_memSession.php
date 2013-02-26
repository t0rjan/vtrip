<?php
/**
* SESSION操作类
*
* 使用memcache作为session存储应用的session操作类
* @author xiaozhen <xiaozhen@staff.sina.com.cn>
* @version 1.0 (beta) 2011-01-25
* @package SESS基本操作类
* 
* include_once('MemSession.class.php');
* $session = new MemSession();
* $session->setval('abc', 123);
* $session->save();
* 
* #other page
* $session = new MemSession();
* $session->getval('abc');
* */
define('MEILA_SESSION_NAME', 'SessionId');

ini_set('session.save_handler','memcache');
ini_set('session.save_path','tcp://127.0.0.1:11211?persistent=1');

class Lib_memSession{

    /**
    * PHPSESSID的值, PHPSESSID为php.ini配置的session.name的值
    * 
    * @var mixed
    */
    private $_sesId = '';
    
    /**
    * session的值
    * 
    * @var mixed
    */
    private $_sesVal = '';
    
    /**
    * 判断是否已经初始化过
    * 
    * @var bool
    */
    private $_sesStarted = '';
    
    /**
    * 判断是否已经解析过
    * 
    * @var bool
    */
    private $_sesParsed = '';
    
   
    
    /**
    * 初始化session处理函数
    *
    * @param string $name 设置的内部保存的session名称，默认为SessionID
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
     */
    public function __construct($name = MEILA_SESSION_NAME)
    {
        if(session_name()!= $name) {  
            session_name($name); 
        }
    
        $this->_start();
    }
   
    
    
   
    
    /**
    * 创建一个当前会话
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    private function _start()
    {
        
        if(FALSE == $this->_sesStarted) { 
            if (empty($_COOKIE[session_name()])) {
                session_id(md5(uniqid(microtime())));
            }
            session_set_cookie_params(0, "/", ".gulibaby.com");
            session_start(); 
            $this->_sesStarted = TRUE; 
        }
    }
    
    /**
    * 解析会话数据到内存数组
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    private function _parse()
    {
        $this->_sesParsed = TRUE;
        if(FALSE == $_SESSION['__Session_Val']){
            $this->_sesVal = FALSE;
        }else{
            $this->_sesVal = unserialize($_SESSION['__Session_Val']);
        }
    }
    
    /**
    * 获取当前的PHPSESSID的值
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function getSessId() {  
        $this->_sesId = session_id(); 
        return(session_id());  
    }
    
    /**
    * 获取保存在session中变量的值
    * 
    * @return string|boolean 返回session中变量的值
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function getVal($key)
    {
        if (FALSE == $this->_sesParsed)
        {
            $this->_parse();
        }
        return isset($this->_sesVal[$key]) ? $this->_sesVal[$key] : false;
    }
    
    /**
    * 获取session的全部数据数组
    * 
    * @return array|boolean 返回session数组
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function getValALL()
    {
        if (!$this->_sesParsed)
        {
            $this->_parse();
        }
        return $this->_sesVal;
    }
    
    /**
    * 设置session变量的值
    * 
    * @param string $key
    * @param mixed $val
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn 
    */
    public function setVal($key, $val)
    {
        if ( ! $this->_sesParsed)
        {
            $this->_parse();
        }
        $this->_sesVal[$key] = $val;
    }
    
    /**
    * 批量设置session变量
    * 
    * @param array $ary
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function setValMuti($ary)
    {
        $cnt      = count($ary);
        $keys     = array_keys($ary);
        for($i = 0; $i < $cnt ; $i++)
        {
            $this->_sesVal[$keys[$i]] = $ary[$keys[$i]];
        }
    }
    
    /**
    * 保存到session中。在$this->setVal 和 $this->unregister之后调用
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function save()
    {
        $this->_start();
        if(!empty($this->_sesVal))
        {
            $_SESSION['__Session_Val'] = @serialize($this->_sesVal);
        }
    }
    
    /**
    * 删除session变量 
    *     
    * @param string $key
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function unregister($key)
    {
        $this->_parse();
        if(isset($this->_sesVal[$key]))
        {
            unset($this->_sesVal[$key]);
        }
    }
    
    /**
    * 注销当前session会话
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn
    */
    public function destroy() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time()-3600);
    }
    
    /**
    * 析构函数
    * 
    * @author xiaozhen xiaozhen@staff.sina.com.cn     
    */
    public function __destruct()
    {
        
    }
}//EOC MemSession
?>