<?php
class Lib_imageResizer
{
    const RESIZE_GD = 0;
    const RESIZE_IMAGICK = 1;

    const MODE_MAXSIZE = 1;
    const MODE_MINSIZE = 2;
    const MODE_REGWIDTH = 3;
    const MODE_REGHEIGHT = 4;
    const MODE_CROP = 5;

    private $quality = 100;
    private $resize_method = self::RESIZE_IMAGICK  ;
    private $_width;
    private $_srcWidth;
    private $_height;
    private $_srcHeight;
    private $_singleSize;
    private $_src;
    private $_dst;
    private $_mime;

    private $mode;
    
    
    public function __construct()
    {
    }
    
    public function set_mode($mode)
    {
        $this->mode = $mode;
        return;
    }

    public function set_size($width = 0 , $height = 0)
    {
        if($width == 0 && $height == 0)
            return false;
            
        $this->_width = $width;
        $this->_height = $height;
        return true;
    }
    
    public function set_single_size($i)
    {
        $this->_singleSize = $i;
        return ;
    }
    

    public function set_source_image($path)
    {
        $this->_src = $path;
        $aImgInfo = getimagesize($this->_src);
        $this->_srcWidth = $aImgInfo[0];
        $this->_srcHeight = $aImgInfo[1];
        $this->_mime = $aImgInfo['mime'];
        return true;
    }
    public function set_dest_image($path)
    {
        $this->_dst = $path;
        return true;
    }
    
    
    public function resize()
    {
        //计算生成的大小
        $rs = $this->_calc_size($this->_srcWidth , $this->_srcHeight);
        list($width , $height) = $rs;
        
        
        
        if(class_exists('Imagick'))
            $this->_resize_imagick($width , $height);
        else
            $this->_resize_gd($width , $height);
        
        return true;
    }
    
    private function _resize_gd($width , $height)
    {
        //创建句柄
        
            $img = imagecreatetruecolor($width , $height);
        
        if ($this->_mime == 'image/jpeg')
            $src = imagecreatefromjpeg($this->_src);
        else if ($this->_mime == 'image/gif')
            $src = imagecreatefromgif($this->_src);
        else if ($this->_mime == 'image/png')
            $src = imagecreatefrompng($this->_src);
        


        
        imagecopyresampled($img, $src, 0,0,0, 0, $width, $height, $this->_srcWidth, $this->_srcHeight);
        

        if ($this->_mime == 'image/jpeg')
            imagejpeg($img , $this->_dst , $this->quality);
        else if ($this->_mime == 'image/gif')
            imagegif($img , $this->_dst , $this->quality);
        else if ($this->_mime == 'image/png')
            imagepng($img , $this->_dst , $this->quality);


if($this->mode == self::MODE_CROP)
{
$img = imagecreatetruecolor($this->_width , $this->_height);

        if ($this->_mime == 'image/jpeg')
            $src = imagecreatefromjpeg($this->_src);
        else if ($this->_mime == 'image/gif')
            $src = imagecreatefromgif($this->_src);
        else if ($this->_mime == 'image/png')
            $src = imagecreatefrompng($this->_src);
        


        if($this->mode == self::MODE_CROP)
        {


            $x = ($width-$this->_width)/2 *4;
            $y = ($height-$this->_height)/2 *4;

            //echo $x.'-'.$y;die;
        }
        
        imagecopyresampled($img, $src, 0,0,$x, $y, $width, $height, $this->_srcWidth, $this->_srcHeight);
        

        if ($this->_mime == 'image/jpeg')
            imagejpeg($img , $this->_dst , $this->quality);
        else if ($this->_mime == 'image/gif')
            imagegif($img , $this->_dst , $this->quality);
        else if ($this->_mime == 'image/png')
            imagepng($img , $this->_dst , $this->quality);
    }
     


        return ;


    }

    
    
    private function _resize_imagick($width , $height)
    {


        $imagick = new Imagick();
        
        $imagick->readImageBlob(file_get_contents($this->_src));
        $imagick->resizeImage($width, $height, Imagick::FILTER_CATROM, 1, true);
        if($this->mode == self::MODE_CROP)
        {
            
            $x = ($width-$this->_width)/2;
            $y = ($height-$this->_height)/2;
//echo $x.'-'.$y;die;

            $imagick->cropImage($this->_width , $this->_height , $x , $y);

        }
        $imagick->setImageFormat('JPEG');
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality($this->quality);
        $imagick->stripImage();
        $blob = $imagick->getImageBlob();
        $imagick->clear();
        $imagick->destroy();
        
        $fp = fopen($this->_dst , 'wb');
        fwrite($fp , $blob);
        fclose($fp);
        return ;
    }
    
    public function reset()
    {
        $this->_width = $this->_height = $this->_singleSize = 0;
        $this->_src = $this->_dst = $this->_mime = '';
        return true;
    }
    
    private function _calc_size($width , $height)
    {
        if($this->mode == self::MODE_MAXSIZE)
        {
            
            if($width >= $height)
            {
                $scale = $height / $width;
                $width = $this->_singleSize;
                $height = $width * $scale;
            }
            else if($width < $height)
            {
                $scale = $width / $height;
                $height = $this->_singleSize;
                $width = $height * $scale;
            }
        }
        else if($this->mode == self::MODE_MINSIZE || $this->mode == self::MODE_CROP)
        {

            $max = $this->_height > $this->_width ? $this->_height : $this->_width;
            $min = $this->_srcHeight > $this->_srcWidth ? $this->_srcWidth : $this->_srcHeight;
            if($max < $min)            
            {
                $scale = $max / $min;
                $width = $this->_srcWidth * $scale;
                $height = $this->_srcHeight * $scale;
            }

            
            //else{}//特殊情况
        }
        else if($this->mode == self::MODE_REGWIDTH)
        {


            $scale = $height / $width;
            
            $width = $this->_singleSize;
            $height = $width * $scale;


        }
        else if($this->mode == self::MODE_REGHEIGHT)
        {
            $scale = $width / $height;
            $height = $this->_singleSize;
            $width = $height * $scale;
        }
        

        
        return array(
            $width,
            $height
        );
    }
    
    private function _calc_xy()
    {


    }
}

/*
include('lib_imagemark.php');

$oImageResize = new lib_imageResize();
$oImageResize->set_source_image('1.jpg');
                    $oImageResize->set_dest_image('2.jpg');
                    $oImageResize->set_max_size(600);
                    $oImageResize->resize();
                    
$o = new lib_imageMark(10);
$rs = $o->mark('2.jpg' , 'data/watermark.gif' , '5.jpg' , 0 , 0);
*/
?>