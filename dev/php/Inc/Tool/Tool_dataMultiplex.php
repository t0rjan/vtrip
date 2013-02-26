<?php
/**
 * @fileoverview      博文属性复用字段处理文件
 * @important        全局通用，计算X_RANK值，禁止下线
 * @date            2011-3-5
 */

/*
define('DATA_PRIVACY',1);         //私密    00 00 00 00 01
define('DATA_QUOTE',2);         //转载    00 00 00 00 10
define('DATA_PIC',4);             //图片    00 00 00 01 00
define('DATA_VIDEO',8);         //视频    00 00 00 10 00
*/
 
/**
 * 博文属性复用值计算类
 * 
 * @author 杨祥宇 <xiangyu1@staff.sina.com.cn>
 * @package 全局通用
 *
 */
class  Tool_dataMultiplex  {
 
    /**
     * 判断博文属性
     *
     * @param int $x_rank
     * @param int $attribute
     * @return boolean
     */
    public static function isAttribute($x_rank, $attribute) {
        if ($x_rank & $attribute) {
            return true; 
        }else {
            return false;
        }
        
    }
    
    /**
     * 添加博文属性
     *
     * @param int $x_rank
     * @param int $attribute
     * @return int
     */
    public static function addAttribute($x_rank, $attribute) {
        return ($x_rank | $attribute);
    }

    /**
     * 删除博文属性
     *
     * @param int $x_rank
     * @param int $attribute
     * @return int
     */
    public static function delAttribute($x_rank, $attribute) {
        $_temp = ~$attribute;
        return ($x_rank & $_temp);
    }
}