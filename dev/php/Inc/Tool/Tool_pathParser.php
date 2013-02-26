<?php
class Tool_pathParser
{
    static public function parse($path , $separator = '_')
    {
        $aPath = explode($separator , $path);
        $aRs['filename'] = array_pop($aPath);
        $aRs['path'] = count($aPath)>0 ? '/'.implode('/', $aPath).'/' : '/';
        
        return $aRs;
    }
}