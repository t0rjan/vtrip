<?php

class ml_model_openapi_baidumap extends Lib_datamodel_abstract
{
	private $_baidukey;
	public function __construct($baidukey)
	{
		$this->_baidukey = $baidukey;
	}

	public function lalong2place($latitade , $longtitude)
	{
		$url = "http://api.map.baidu.com/geocoder?output=json&location=".$latitade.",".$longtitude."&key=".$this->_baidukey;
		return $this->httpFetch($url);
	}

	private function httpFetch($url)
	{
		if(!$url)
			return false;

		$rs = Tool_http::get($url);
		if(!$rs)
			return false;
		$rs = json_decode($rs , 1);
		if($rs[status]!= 'OK')
			return false;
		else
		{
			$this->_data = $rs['result'];
			return true;
		}
	}
}