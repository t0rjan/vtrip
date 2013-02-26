<?php
/**
 * Sina sso base class , 2008-07-02
 * @author lijunjie <junjie2@staff.sina.com.cn>
 * @package SSO_Base.php
 * @version 1.0
 */

class SSO_Base {
    
    /**
     * 标识是否出错
     */
    var $_is_error    = false;
    /**
     * 错误描述
     */
    var $_err_str    = '';    
    /**
     * 错误代码
     */
    var $_err_no    = '';    

    //============== method about error ==========//
    /**
     * 设置出错信息
     */
    function _setError($str,$num = '') {
        $this->_is_error = true;
        $this->_err_str = $str;
        $this->_err_no = $num;
        return true;
    }
    /**
     * 判断是否出错
     */
    function isError() {
        return $this->_is_error;
    }
    /**
     * 返回出错信息
     */
    function getError() {
        return $this->_err_str;
    }
    /**
     * 返回错误号
     */
    function getErrno() {
        return $this->_err_no;
    }
    /**
     * 清除出错信息
     */
    function clearError() {
        $this->_is_error = false;
        $this->_err_str = '';
        $this->_err_no = '';
        return true;
    }

}
?>
