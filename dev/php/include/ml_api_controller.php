<?php

class ml_api_controller
{
	private $start_time;

	public function __construct()
	{
        ml_factory::set_controller($this);
		$this->start_time = microtime(1);
		$this->initParam();
        $this->checkParam();
        $this->main();
	}
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
    public function check_user_permission($uid)
    {
    	/*
    	$code = $this->input('uh');//uid hash
    	return ml_tool_resid::apiUidEncrypt($uid , $code);
    	 */
    	return true;
    }
}