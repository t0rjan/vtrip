<?php
class ml_tool_time
{
    static function get_microtime($microtime='')
    {
        $microtime = empty($microtime) ? microtime() : $microtime;
        $array = explode(' ', $microtime);
        $time = $array[1].'.'.substr($array[0], 2, 6);
        return floatval($time);
    }
}
?>