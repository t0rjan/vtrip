<?php
class ml_tool_isuid{
    
    public static function is_mluid($uid)
    {
        if (!is_numeric($uid)) return false;
        return pow(10, 11)> $uid && 0< $uid ? true: false;
    }
    
}