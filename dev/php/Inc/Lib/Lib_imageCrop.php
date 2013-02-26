<?php

class Lib_imageCrop
{
    private $_src;
    private $_dst;

    public function set_src_path($src_path){
        $this->_src = $src_path;
    }
    public function set_dest_path($dest_path){
        $this->_dst = $dest_path;
    }
    public function crop($x,$y,$w,$h)
    {
        $imagick = new Imagick();
        
        $imagick->readImageBlob(file_get_contents($this->_src));
        $imagick->cropImage($w , $h , $x , $y);

        
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

}