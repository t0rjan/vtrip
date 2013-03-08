<?php

class ml_api_controller
{
    const PERMISSION_USER = 1;
    const PERMISSION_ALL = 0;
	private $start_time;
    private $permission;

	public function __construct()
	{
        ml_factory::set_controller($this);
		$this->start_time = microtime(1);
        if ($this->permission == PERMISSION_USER) {
            $this->_check_permission();
        }
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
    public function getOperateUid()
    {
        


    }
    private function _check_permission()
    {


    }
}