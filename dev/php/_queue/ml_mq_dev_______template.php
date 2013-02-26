<?php
/**
 * @copyright meila.com
 * @author shaopu@
 * @name 
 * @param 
 *         $xxx = 作用
 * @static 
 *         XXX = 作用
 * 
 * 
 */
$dir = dirname(__FILE__);
include($dir.'/__queue_global.php');

class ml_mq_QUEUE________NAME extends MqClass{
    const QUEUE_NAME = ML_QUEUENAME_QUEUE________NAME;

    /**
     * 注释
     *
     * @param array $message_data
     * array(
     *                        所接收的参数说明
     *        )
     * )
     */
    public function run_job(){
        //接收的数据
        $arr = $this->src_data;
        
        /**
         * 这里写队列逻辑
         */
        
        return true;
    }

}

$xblog_obj = new ml_mq_QUEUE________NAME(new McQueue(ml_mq_QUEUE________NAME::QUEUE_NAME));
$argv[1]   = __FILE__;
$xblog_obj->setArgv($argv[1]);
$xblog_obj->execute();
?>