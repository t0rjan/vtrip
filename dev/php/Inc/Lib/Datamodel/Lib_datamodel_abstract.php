<?php
/**
 *@fileoverview: 数据模型抽象类[群博客] 
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Wed Apr 20 10:24:24 GMT 2011
 *@copyright: sina
 */

abstract class Lib_datamodel_abstract
{
    protected $_data;
    
    public function get_data()
    {
        return $this->_data;
    }
    public function set_data($data)
    {
        $this->_data = $data;
    }
}
?>