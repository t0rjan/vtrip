<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/validate.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Tue Feb 09 23:20:01 CST 2010
************************************/

include('./__global.php');


//ini_set('display_errors', 1);
//error_reporting(E_ALL);

class adm_validate extends admin_ctrl 
{
    private $_im;
    private $_img_width = 100;
    private $_img_height = 25;
    private $_vcode;
    private $_fontcolor;
    private $_ptcolor;
    private $_colornum = 9;
    
    public function __construct()
    {
        $this->need_login = false;
        
        parent::__construct();
    }
    
    
    public function run()
    {
        
        //初始化验证码
        $this->_vcode = mt_rand(10000 , 99999);
        $this->set_session('vcode' , $this->_vcode);
        
        
        //创建图片
        $this->_im = @imagecreate ($this->_img_width, $this->_img_height);
        //背景色
        $background_color = imagecolorallocate ($this->_im, 0, 0, 0);
    
        //初始化字体颜色
        $this->_fontcolor = array();
        for ($i=0;$i<=$this->_colornum;$i++)
        {
            $this->_fontcolor[] = imagecolorallocate ($this->_im , mt_rand(180,255), mt_rand(180,255), mt_rand(180,255));
        }

        // 雪花点的颜色
        $this->_ptcolor = array();
        for ($i=0;$i<=$this->_colornum;$i++)
        {
            $this->_ptcolor[] = imagecolorallocate ($this->_im, mt_rand(80,120), mt_rand(80,120), mt_rand(80,120));
        }
        
             
        //粗横线
        for ($i=0;$i<2;$i++)
        {
            $this->_write_a_line( mt_rand(0 , 20)
                                , mt_rand(0 , $this->_img_height) 
                                , mt_rand($this->_img_width-20, $this->_img_width)
                                , mt_rand(0 , $this->_img_height)
                                , mt_rand(2,3));
        }
        

        // 字体
        $font = SERVER_ROOT_PATH.'/include/bookos.ttf';
$this->_vcode = (string)$this->_vcode;
        for ($i = 0 ; $i < strlen($this->_vcode) ; $i++)
        {
            $num = $this->_vcode{$i};       //
            
            $size = rand(14,18);            //字体大小
            $angle = rand(-20, 20);            // 角度
            $lastpost = imagettfbbox($size, $angle, $font, $num);
            
            $nColor = mt_rand(0 , $this->_colornum);
            
            $x = 5+$i*18;
            $y = mt_rand(15,20);
            
            imagettftext($this->_im, $size, $angle, $x , $y, $this->_fontcolor[$nColor], $font, $num);
            $x += $inteval;
        }
        
        //粗横线
        for ($i=0;$i<2;$i++)
        {
            $this->_write_a_line( mt_rand(0 , 20)
                                , mt_rand(0 , $this->_img_height) 
                                , mt_rand($this->_img_width-20, $this->_img_width)
                                , mt_rand(0 , $this->_img_height)
                                , mt_rand(0,1));
        }
        

        imagesetthickness($this->_im , 1);
        ImageRectangle($this->_im, 0, 0, $this->_img_width-1, $this->_img_height-1, $this->_fontcolor[0]);

        header ("Content-type: image/png");
        imagepng($this->_im);
        imagedestroy($this->_im);
        die;
    }
    private function _write_a_line($x, $y, $x1, $y1, $thick=3)
    {
        imagesetthickness ($this->_im, $thick);
        
        
        $line_color = $this->_ptcolor[mt_rand(0,$this->_colornum)];
            
        imageline  ($this->_im, $x, $y, $x1, $y1, $line_color);
    }
    

}
new adm_validate();
?>