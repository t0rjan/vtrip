<?php
/**
 * @copyright meila.com
 * @author wangtao5@
 * @name 
 * @param 
 *         $xxx = 作用
 * @static 
 *         XXX = 作用
 * 
 * 
 */

class ml_tool_getAstro
{
    static function astro($date) {
        $datetime = strtotime($date);
        if(!$datetime) return false;
        $astro = ml_factory::load_standard_conf('astro');
        list($year, $month, $day) = explode('-', $date);
        $start = strtotime($year.'-01-21');
        $end = strtotime($year.'-12-22');
        if($datetime<$start || $datetime>=$end){
            return array_merge(array(1),$astro[1]);
        }
        unset($astro[1]);
        foreach($astro as $key=>$val){
            if($datetime>=strtotime($year.'-'.$val[0]) && $datetime<strtotime($year.'-'.$val[1]))
                return array_merge(array($key), $val);
        }
        return false;
    }
}
?>