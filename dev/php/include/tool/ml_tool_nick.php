<?php

class ml_tool_nick
{
    static private $oRdsModel;
    static public function uids2nicks($aUid)
    {
        if(!is_array($aUid))
            return false;

        $aUid = array_unique($aUid);
            
        $oRds = self::_getRdsModel();
        $rs = $oRds->getNicksByUids($aUid);
        /**
         * @todo 
         */
        return $oRds->get_data();
    }
    static public function nicks2uids($aNicks)
    {
        if(!is_array($aNicks))
            return false;
            
        $oRds = self::_getRdsModel();
        $rs = $oRds->getUidsByNicks($aNicks);
        /**
         * @todo 
         */
        return $oRds->get_data();
    }
    
    /**
     * 匹配所有 @昵称 形式
     *
     * @param string $str
     * @return array
     */
    static public function findAllAtNick($str)
    {
        preg_match_all("/@([\x{4e00}-\x{9fa5}a-zA-Z0-9-_]+)/u",$str,$rs);
        return $rs[1];
    }
    
    static private function _getRdsModel()
    {
        if(!is_object(self::$oRdsModel))
        {
            self::$oRdsModel = new ml_model_rdsHash();
        }    
        return self::$oRdsModel;
    }
}