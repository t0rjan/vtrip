<?php


class Lib_cache_proxy implements Lib_cache_base 
{
    const MC = 'mc';
    private $gb_cache;
    
    public function __construct($cache='mc')
    {
        $class = 'Lib_cache_'.$cache;
        $this->gb_cache = new $class();
    }
    
    public function init_cache($u, $s)
    {
        return $this->gb_cache->init_cache($u, $s);
    }
    public function connect($server) 
    {
        return $this->gb_cache->connect($server );
    }
    
    public function get($key)
    {
        return $this->gb_cache->get($key);
    }
    public function set($key , $value)
    {
        return $this->gb_cache->set($key , $value);
    }
    
    public function delete($key)
    {
        return $this->gb_cache->delete($key);
    }
    
    public function  close()
    {
        return $this->gb_cache-> close();
    }
}

?>
