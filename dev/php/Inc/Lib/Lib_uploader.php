<?php

class Lib_uploader
{
    private $key;
    private $dir;
    private $filename;
    
    public function is_set($key)
    {
        return $_FILES[$key]['tmp_name'] ? true : false;
    }
    
    public function start($key)
    {
        $this->reset();
        
        if(!isset($_FILES[$key]))
            return false;
        $this->key = $key;
        
        return true;
    }
    /**
     * 设置保存目录
     *
     * @param string $dir
     * @return bool
     */
    public function set_save_dir($dir)
    {

        if(!is_dir($dir))
        {

            if(!mkdir($dir , 0777 , true))
            {
                
                return false;
            }
        }
            
        $this->dir = $dir;
        return true;
    }
    /**
     * 检查文件类型
     *
     * @param array $aList  //mime 数组
     * @return bool
     */
    public function check_content_type($aList)
    {
        $mime = $_FILES[$this->key]['type'];
        return array_search($mime , $aList) === false ? false : true;
    }
    /**
     * 检查文件大小
     *
     * @param int $max  //k
     */
    public function check_file_size($max)
    {
        $size_k = $_FILES[$this->key]['size']/1024;
        if($size_k < 1 || $size_k > (int)$max)
            return false;
        
        return true;
        
    }
    /**
     * 设置保存文件名
     *
     * @param string $name
     * @return bool
     */
    public function set_file_name($name , $add_suffix = false)
    {
        $suffix = '';
        if($add_suffix)
        {
            $suffix = substr($_FILES[$this->key]['name'] , strrpos($_FILES[$this->key]['name'] , '.')) ;
        }
        
        $this->filename = $name.$suffix;
        return true;
    }
    public function get_file_name()
    {
        return $this->filename;
    }
    /**
     * 保存文件
     *
     * @param bool $is_overwritten
     * @return bool
     */
    public function save($is_overwritten = false)
    {
        
        $file_path = $this->dir.'/'.$this->filename;
        
        if(is_file($file_path) && !$is_overwritten)
        {
            return false;
        }
        $rs = move_uploaded_file($_FILES[$this->key]['tmp_name'] , $file_path);
        if($rs)
            return true;
        else 
            return false;
        
    }
    public function reset()
    {
        $this->key = '';
        $this->dir = '';
        $this->filename = '';
    }
}