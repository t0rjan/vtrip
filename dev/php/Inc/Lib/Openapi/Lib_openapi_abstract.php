<?php
/**
 *@fileoverview: 第三方授权抽象类
 *@author: 徐冠群 <guanqun@staff.sina.com.cn>
 *@date: 2012-01-06
 *@copyright: sina
 */

abstract class Lib_openapi_abstract
{
    protected $_data;
    
    public function get_data()
    {
        return $this->_data;
    }
    protected function _set_data($data)
    {
        $this->_data = $data;
    }
    
    /* 获取第三方授权用url */
    public function get_auth_url()
    {
        
    }
    
    public function check_auth()
    {
        
    }
    public function get_user_info()
    {
        
    }
    public function sent_message()
    {
        
    }
}
?>