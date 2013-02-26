<?php
/**
 *@fileoverview: [群博客] 群博客专用工厂
 *@author: 辛少普 <shaopu@staff.sina.com.cn>
 *@date: Sat Dec 11 18:31:42 GMT 2010
 *@copyright: sina
 */
class ml_factory
{
    private static $_configs;
    private static $_datamodels;    
    
    /**
     * 取标准化配置
     *
     * @param string $name
     * @return array
     */
    public static function load_standard_conf($name)
    {
        $aPath = Tool_pathParser::parse($name);
        
        if(!is_array(self::$_configs['stdConf'][$name]))
            self::$_configs['stdConf'][$name] = include(SERVER_ROOT_PATH.'/include/config'.$aPath['path'].'ml_stdConf_'.$name.'.php');
        
        return self::$_configs['stdConf'][$name];
    }
    /**
     * 加载页面模块
     *
     * @param int $mod_id
     * @return object
     */
    public static function load_page_module($mod_id)
    {
        $class_name = 'pg_pagemod_'.$mod_id;
        return new $class_name;
    }
    
    
      /**
     * 加载主题配置数据
     *
     * @param int $theme_id
     * @return array
     */
    public static function load_theme_config($cfgName)
    {
//        if(!is_array(self::$_configs['theme'][$theme_id]))
        
        self::$_configs['theme'][0] = include(SERVER_ROOT_PATH.'/include/config/'.$cfgName.'.php');
        
        return self::$_configs['theme'][0];
    }
    
    /**
     * 加载数据模型
     * 注意：调用微博接口的模型不通过此方法加载
     *
     * @param string $name
     * @param mix * n       默认传到模型构造函数中的参数
     * @return object
     */
    public static function load_datamodel($name /* , $param_1 , $param_2 ...*/)
    {
        $args = func_get_args();
        $mod_name = array_shift($args);                //第一个参数是类名
        $class_name = 'ml_model_'.$mod_name;            //类名
        
        $key = md5($mod_name.'_'.serialize($args));    //根据类名和参数生成缓存KEY
        //已缓存 直接返回
        if(isset(self::$_datamodels[$key]) && is_a(self::$_datamodels[$key] , $class_name))
            return self::$_datamodels[$key];
        
        
            
        //传入参数 生成对象
        $param = '';
        if(count($args) > 0)
            $param = '$args['.implode('] , $args[' , array_keys($args)).']';
        $cmd = '$o = new '.$class_name.'('.$param.');';
        eval($cmd);
        
        self::$_datamodels[$key] = $o;                //写入缓存
        
        return self::$_datamodels[$key];
    }

    /**
     * 将当前进程的control程序暂存供他处调用
     * 在CONTROLLOR构造时调用，其他地方不需要调用此方法
     *
     * @param object $o
     */
    public static function set_controller($o)
    {
        $GLOBALS['__CONTROLLER'] = $o;
        return ;
    }
    /**
     * 返回当前进程的CONTROLLOR
     *
     * @return object
     */
    public static function get_controller()
    {
        return $GLOBALS['__CONTROLLER'];
    }

}
