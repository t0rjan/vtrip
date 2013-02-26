<?php
class ml_tool_template
{
    static public function parseModTpl($data , $tpl_name)
    {
        $aPath = Tool_pathParser::parse($tpl_name , '::');
        $tpl_path = SERVER_ROOT_PATH.'/view/module'.$aPath['path'].'tpl_mod_'.$aPath['filename'].'.php';
       
        if(is_array($data))
        extract($data);

        ob_start();
        include($tpl_path);
        $html = ob_get_clean();
        ob_end_clean();

        return $html;
    }

    
    
    
}

