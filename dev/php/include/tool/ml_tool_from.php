<?php
class ml_tool_from
{
    static function makeFrom($from){
        switch($from){
            case ML_FROM_WEB:
                return '来自美啦网站';
            case ML_FROM_PHONE:
                return '来自手机客户端';
            default:
                return '来自其他';
        }
    }
        
}
