<?php
define(S3_PROJECT_DOMAIN, 'http://image{?}.meila.com/');



class ml_tool_picid
{
    const PORTRAIT_50 = 50;
    const PORTRAIT_30 = 30;
    const PORTRAIT_100 = 100;
    const PORTRAIT_200 = 200;

    const IMGSIZE_BIG = 'big';
    const IMGSIZE_PIN = 'pin';
    const IMGSIZE_FED = 'fed';
    const IMGSIZE_GDS = 'gds';
    const IMGSIZE_PIC = 'pic';
    const IMGSIZE_THM = 'thm';
    const IMGSIZE_SQR = 'sqr';
    
    //生成pid用 size是图片SIZE 也用来HASH PID
    public static function uid2pid($uid , $width , $height) {
        
        return  str_pad(base_convert($uid , 10 , 36) , 6 , 0 , STR_PAD_LEFT)
                .str_pad(base_convert($width , 10 , 32) , 3 , 0 , STR_PAD_LEFT)
                .str_pad(base_convert($height , 10 , 32) , 3 , 0 , STR_PAD_LEFT)   
                .substr(base_convert(microtime(1),10,36),1,8);
    }
    //根据UID HASH 存放的目录
    public static function hashUidDir($uid)
    {
        return Tool_sina::calc_hash_tbl($uid , ML_IMG_HASHNUM).'/'.$uid;

    }
    //根据PID 生成图片存放路径
    public static function pid2filepath($pid , $is_bigpic = true , $size = '')
    {
        return ($is_bigpic ? ML_IMG_DIR_BIGPIC : ML_IMG_DIR_SMALLPIC)
                .'/'.ml_tool_picid::hashUidDir(self::pid2uid($pid)).'/'.$pid
                .(($size && $size != 'big') ? '_'.$size : '');
    }
    //根据PID 得到其所属UID
    public static function pid2uid($pid)
    {
        return base_convert(substr($pid , 0 , 6), 36, 10);

    }
    //根据PID 生成图片访问URL
    public static function pid2url($pid , $size = 'pin')
    {
        return 'http://'.ML_CNF_DOMAIN_IMAGE.'/image/show.php?pid='.$pid.'&size='.$size;
        
    }
    public static function _pid2wh($pid)
    {
        return array(
            'width' => base_convert(substr($pid , 6,3),36,10),
            'height' => base_convert(substr($pid , 9,3),36,10)
        );
    }

    public static function pid2wh($pid , $size = 'pin')
    {
        $ML_IMG_SIZE_CONFIG = ml_factory::load_standard_conf('imageSize');

        $a = self::_pid2wh($pid);

        $conf = $ML_IMG_SIZE_CONFIG[$size];
        
        switch ($conf['type']) {
            case ML_IMG_TYPE_REGULARWIDTH:
                $rs = array(
                    'width' => $conf['width'],
                    'height' => intval(($conf['width']/$a['width'])*$a['height'])
                );
                break;

        }
        return $rs;
    }
    /**
     * 是否图片ID
     *
     * @param string $str
     * @return bool
     */
    public static function ispid($str)
    {
        return preg_match("/^[0-9a-z]{20}$/i", $str) ? true : false;
    }

    
    
    /**
     * 根据UID生成头像地址
     *
     * 30 50 100 200 
     * 4种尺寸
     * 
     * 
     * @param int $uid
     * @param int $size
     * @return string
     */
    public static function uid2portrait($uid , $size = '')
    {
        return '/image/show_portrait.php?uid='.$uid.'&size='.$size;
    }
    public static function uid2portraitPath($uid , $size = '')
    {
        return ML_IMG_DIR_PORTRAIT.'/'.Tool_sina::calc_hash_tbl($uid , ML_IMG_HASHNUM).'/up_'.$uid.($size && $size != 'big' ? '_'.$size : '');

    }
    
    
    private static function _hash_pic_domain($hashkey)
    {
        $i = Tool_sina::calc_hash_db($hashkey , 4)+1;
        return str_replace('{?}' , $i , S3_PROJECT_DOMAIN);
    }
}




?>