<?php
class Tool_array
{
    /**
     * 只保留某字段
     *
     */
    const FORMAT_VALUE_ONLY = 0;
    /**
     * 二维数组格式化为一维，原key -> 某字段
     *
     */
    const FORMAT_KEY2VALUE = 1;
    /**
     * 某字段->原KEY
     *
     */
    const FORMAT_VALUE2KEY = 2;
    /**
     * ID主键->某字段
     *
     */
    const FORMAT_ID2VALUE = 3;
    /**
     * 某字段 -> id主键
     *
     */
    const FORMAT_VALUE2ID = 4;
    /**
     * 某字段->另一字段
     *
     */
    const FORMAT_VALUE2VALUE2 = 5;
    /**
     * 某字段->数组
     *
     */
    const FORMAT_FIELD2ROW = 6;

    static public function format_2d_array($array , $field , $format = self::FORMAT_VALUE_ONLY , $field2 = '')
    {
        if(is_array($array) && count($array)>0)
        {
            /**
             * @todo ID格式检查
             */
            foreach ($array as $key => $tmp)
            {

                switch ($format)
                {
                    case self::FORMAT_ID2VALUE :
                        $arrRs[$tmp['id']] = $tmp[$field]; break;
                    case self::FORMAT_KEY2VALUE :
                        $arrRs[$key] = $tmp[$field]; break;
                        
                    case self::FORMAT_VALUE2KEY :
                        $arrRs[$tmp[$field]] = $key; break;
                    case self::FORMAT_VALUE2ID :
                        $arrRs[$tmp[$field]] = $tmp['id']; break;
                    case self::FORMAT_VALUE2VALUE2 :
                        $arrRs[$tmp[$field]] = $tmp[$field2]; break;
                    case self::FORMAT_FIELD2ROW :
                        $arrRs[$tmp[$field]] = $tmp; break;
                            
                    case self::FORMAT_VALUE_ONLY :
                    default:
                        $arrRs[] = $tmp[$field]; break;
                            
                }

            }
        }
        return $arrRs;
    }
    /**
     * @todo 对二维数组进行排序
     * xinhua
     */
    static function array_sort($arr,$keys,$type='asc'){

        $keysvalue = $new_array = array();

        foreach ($arr as $k=>$v){

            $keysvalue[$k] = $v[$keys];

        }

        if($type == 'asc'){

            asort($keysvalue);

        }else{ arsort($keysvalue);

        }

        reset($keysvalue);//倒转键值指针，防止外部foreach的时候出错

        foreach ($keysvalue as $k=>$v){

            $new_array[$k] = $arr[$k];

        }

        return $new_array;

    }



}