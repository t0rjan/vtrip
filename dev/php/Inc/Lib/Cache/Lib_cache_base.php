<?php

interface Lib_cache_base 
{
    public function init_cache($u , $s);
    public function connect($server);
    
    public function get($key);
    
    public function set($key , $value);
    
    public function delete($key);
    
    public function  close();
    
}

?>
