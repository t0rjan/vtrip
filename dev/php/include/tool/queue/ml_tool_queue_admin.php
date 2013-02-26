<?php

class ml_tool_queue_admin extends ml_tool_queue_base
{
    static public function add_fakedata($id){

        $key = ML_ADMQUEUENAME_ADDFAKEDATA;
        $data['id'] = $id;
        $data['time'] = time();
        return self::send_mq($key , $data);
    }
}