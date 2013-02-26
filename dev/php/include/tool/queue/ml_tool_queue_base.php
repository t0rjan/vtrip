<?php

class ml_tool_queue_base
{
    //~~以下为内部方法，一般不用改~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    
    /**
     * 当前使用的MC对象
     *
     * @var object
     */
    static private $oRedis;
    static protected function _init()
    {
        if(!is_object(self::$oRedis))
            self::$oRedis = new ml_model_rdsQueue;
            

        return true;
    }
    
    /**
     * 发送MQ数据
     *
     * @param string $key     队列KEY
     * @param array $data
     * @param string $mq_type
     * @return true
     */
    static protected function send_mq($key , $data , $mq_type = 'default')
    {
        $data['__mqmeta__'] = array(
            't' => time(),
            'sip' => Tool_ip::getLocalLastIp()
        );

        if(!self::_init())
        {
            Tool_logger::dataLog('MQ_FALSE' , serialize($data));
        }
        else
        {
            self::$oRedis->addQueue($key , $data);
        }
        return true;
    }
}