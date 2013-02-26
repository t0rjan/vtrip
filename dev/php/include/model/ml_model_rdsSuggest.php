<?php
//include('../../__global.php');
class ml_model_rdsSuggest extends ml_model_redis  
{    

    const PFX_HASH_SUGGEST_TAGS_ID = 'tagID_';
    const FIELD_HASH_SUGGEST_TAGS_DATA = 'meila:suggest:tags-data';
    
    const PFX_SORTED_SUGGEST_TAGS_INDEX = 'meila:suggest:tags-index:';
    
    function __construct() {
        if(!$this->init_rds('meila_cache'))
            return false;
    }
    

    public function getMSuggestTagsData($aTagid)
    {
        foreach ($aTagid as &$tag_id)
        $aKey[] = self::PFX_HASH_SUGGEST_TAGS_ID .$tag_id;

        $aRs = $this->hMGet(self::FIELD_HASH_SUGGEST_TAGS_DATA, $aKey);
        
        $this->set_data( array_combine($aTagid , $aRs) );
        return true;
    }
    
    public function setSuggestTagsData($tag_id , $array)
    {
        $value = json_encode($array);
        $key = self::PFX_HASH_SUGGEST_TAGS_ID.$tag_id;
        return $this->hSet(self::FIELD_HASH_SUGGEST_TAGS_DATA, $key, $value);
    }

    public function setSuggestTagsIndex($key, $aTagScore){
        if (empty($key)) {
            return ;
        }
        
        $re = $this->zRemRangeByRank(self::PFX_SORTED_SUGGEST_TAGS_INDEX.$key, 0, -1);
        if (!$re){
            return false;
        }
        
        foreach ($aTagScore as $score => $member){
            if ($score == 11) {
                break;
            }
            $this->zAdd(self::PFX_SORTED_SUGGEST_TAGS_INDEX.$key, $score, $member);
        }
        
        return true;
    }
    
    public function getSuggestTagsIndex($key, $withscore=false){
        
        return $this->zRange(self::PFX_SORTED_SUGGEST_TAGS_INDEX.$key, 0, -1, $withscore);
    }
    
    public function getSuggestTagsIndexSorted($key){
        
        return $this->zRevRange(self::PFX_SORTED_SUGGEST_TAGS_INDEX.$key, 0, -1);
    }
    
}