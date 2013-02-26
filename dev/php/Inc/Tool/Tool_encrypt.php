<?php
class Tool_encrypt
{
    public function md5($str)
    {
        $salt = substr($str , 0 , 2).strlen($str);
        return md5($str.$salt);
    }
}
?>