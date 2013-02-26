<?php
class Tool_os
{
    static public function run_cmd($cmd)
    {
        $fp = popen($cmd , 'r');
        while (!feof($fp))
            $rs .= fgets($fp);
        fclose($fp);
        
        return $rs;
    }
}
