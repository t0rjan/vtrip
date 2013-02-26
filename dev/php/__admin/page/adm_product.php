<?php

include('../__global.php');


class adm_product extends admin_ctrl
{
    public function _construct()
    {
        
    }
    
    protected function page_new_product()
    {
        $data = array();
        
        $oArraytree = new lib_arraytree(array());
            
        $oArraytree->restart(k::config('product_class'));
        $this->module_data['product_class'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('product_map'));
        $this->module_data['product_map'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('material'));
        $this->module_data['material'] = $oArraytree->get_id2field('name' , ' └');
        
        $this->output($data);
    }
    
    protected function page_list_product($msg = '')
    {
        $data = array();
        
        $oArraytree = new lib_arraytree(array());
            
        $oArraytree->restart(k::config('product_class'));
        $this->module_data['product_class'] = $oArraytree->get_id2field('name');
        
        $oArraytree->restart(k::config('product_map'));
        $this->module_data['product_map'] = $oArraytree->get_id2field('name');
        
        $oArraytree->restart(k::config('material'));
        $this->module_data['material'] = $oArraytree->get_id2field('name');
        
        
        $oProduct = K::load_mod('product');
        
        $s_sn = $this->input('sn');
        if($s_sn)
        {
            if(!$this->input('like'))
            {
                $aSn = explode(',' , $s_sn);
                foreach ($aSn as $sn)
                {
                    if(!is_product_sn($sn))
                        die('sn 输入不合法');
                }
                $data['sn'] = $s_sn;
                $oProduct->list_by_sn($aSn) or die('eee');
            }
            else 
            {
                $data['sn'] = $s_sn;
                $data['like'] = 1;
                $oProduct->search_by_sn($s_sn) or die('fff');
            }
        }
        else 
        {
            $data['page']        = (int)$this->input('p') < 1 ? 1 : (int)$this->input('p');
            $data['class_id']    = (int)$this->input('class_id');
            $data['is_multi']    = (int)$this->input('is_multi') == 1 ? 1 : 0;
            $data['stat']        = (int)$this->input('stat');
        
            $condition = '1';
                $condition .= $data['class_id'] ? ' AND class_id='.$data['class_id'] : '';
                $condition .= $data['stat'] ? ' AND stat='.$data['stat'] : '';
                $condition .= $data['is_multi'] ? ' AND is_multi=1' : '';
            
            $oProduct->list_product($data['total'] , $data['page'] , 10 , $condition);
        }
        $data['products'] = $oProduct->get_data();
        $data['msg'] = $msg;
        $this->output($data);
    }
    
    protected function page_edit_product()
    {
        $id = (int)$this->input('id');
        $sn = $this->input('sn');
        if(!$id && !$sn)
        {
            $this->_redirect('?page=list_product');
        }
        
        $oArraytree = new lib_arraytree(array());
            
        $oArraytree->restart(k::config('product_class'));
        $this->module_data['product_class'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('product_map'));
        $this->module_data['product_map'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('material'));
        $this->module_data['material'] = $oArraytree->get_id2field('name' , ' └');
        
        $oProduct = K::load_mod('product');
        
        if($id)
        {
            $oProduct->get_by_id($id) or $this->busy();
        }
        elseif($sn)
        {
            $oProduct->get_by_sn($sn) or $this->busy();
        }
        $data['product'] = $oProduct->get_data();
        
        if(!$data['product'])
        {
            $this->_redirect('?page=list_product' , '查无此产品');
        }
            
        $cover_path = product2coverpath($id);
        if(is_file($cover_path))
            $data['cover_path'] = product2coverpath($id , true);
        
        $this->output($data);
    }
    
    protected function page_photo_manage()
    {
        $data['product_id'] = (int)$this->input('id');
        
        $oProduct = K::load_mod('product');
        $oProduct->get_by_id($data['product_id']) or $this->busy();
        $data['product'] = $oProduct->get_data();
        
        $this->output($data);
    }
    
    protected function page_tar_upload()
    {
        $tar_list = array();
        $dp = opendir(K5_ADMIN_DATA_PATH.'/tar_create');
        while ($file = readdir($dp)) {
            if(strpos($file , 'zip') !== false)
            {
                $size = filesize(K5_ADMIN_DATA_PATH.'/tar_create/'.$file);
                $tar_list[] = array(
                    'name' => $file,
                    'size' => helper_filesystem::format_size($size)
                );
            }
        }
        $data = array(
            'tar_list' => $tar_list
        );
        $this->output($data);
    }
    
    protected function page_tar_exec()
    {
        $keys = array('sn' , /*'name' , 'eng_name' , 'stock_num' , */'size'/* , 'material'*/);
        $n_keys = count($keys);
        
        $oArraytree = new lib_arraytree(array());
        $oArraytree->restart(k::config('material'));
        $aMaterial = array_flip($oArraytree->get_id2field_meta('name'));
        
        $file = $this->input('file');
        if(is_file(K5_ADMIN_DATA_PATH.'/tar_create/'.$file))
        {
            $cmd = 'cd '.K5_ADMIN_DATA_PATH.'/tar_create;unzip '.$file;
            helper_os_linux::run_cmd($cmd);
            
            $dir_name = substr($file , 0 , strpos($file , '.'));
            //$dir_name = $file;
            
            $dir_files = helper_filesystem::ls(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
            if (count($dir_files)<1)
            {
                rmdir(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
                $this->_redirect('?page=tar_upload' , '该数据包为空');
            }
            
            //找到索引文件
            /*
            $cmd = 'cd '.K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name.';mv *.txt index.txt';
            echo $cmd;
            die;
            helper_os_linux::run_cmd($cmd);
            */
            
            
            $index_file = '';
            foreach ($dir_files as $f)
            {
                $tmp = pathinfo($f);
                
                if($tmp['extension'] == 'txt')
                    $index_file = $f;
                    
                $n = substr_count($tmp['basename'],'-');
                if($n > 2)
                {
                    $tmp_1 = explode('-' , $tmp['basename']);
                    $pic_sn = $tmp_1[0].'-'.$tmp_1[1];
                }
                else 
                {
                    $pic_sn = $tmp['filename'];
                }
                
                $pics[strtolower($pic_sn)] = $tmp['basename'];
            }
            if(!$index_file)
            {
                rmdir(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
                $this->_redirect('?page=tar_upload' , '未找到数据文件');
            }
            

            
            //读取索引文件进行商品创建
            $dir = K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name;
            $fp = fopen($dir.'/'.$index_file , 'r');
            $i = 0;
            $fields_cnt = count($keys);
            
            
            while ($line = trim(fgets($fp))) {
                $line = K_str::gb2utf($line);
                $i++;
                if(empty($line))
                {
                    $out .= $i.' 空行'.'<br/>';
                    continue;
                }
                
                $tmp = explode("\t" , $line);
                $tmp = array_slice($tmp , 0 , $n_keys);
                
                /*
                if(count($tmp) != $fields_cnt)
                {
                    $out .= $i.' 数据不完整'.'<br/>';
                    continue;
                }*/
                
                $out .= $i.' ';
                
                $tmp = array_pad($tmp , 2 , '');
                $data = array_combine($keys , $tmp);
                list($data['width'],$data['height'],$data['deep']) = explode('-' , $data['size']);
                $data['material_id'] = $aMaterial[$data['material']];
                unset($data['size'] , $data['material']);
                $cmd = 'ls '.$dir.'/'.$data['sn'].'*';
                
                
                
                $rs = $this->_create_product($data , $product_id);
                if(!$rs)
                {
                    $out .= $i.''.$data['sn'].' 错误'.'<br/>';
                    continue;
                }
                
                $out .= $data['sn'].' 生成 '.$product_id;
                
                $pic = $dir.'/'.$pics[strtolower($data['sn'])];
                //echo $pic;
                
                $cmd = 'ls '.$dir.'/'.$data['sn'].'*';
                $pic = trim(helper_os_linux::run_cmd($cmd));
                if(is_file($pic))
                {
                    
                    $pid = $this->_calc_pid($product_id);
                    $dest = img2dir($product_id).$pid;
                    //echo $dest;
                    
                    $rs = copy($pic , $dest);
                    
                    
                    $this->_add_pic($product_id , $pid , $dest);
                    $out .= ' 完成图片';
                }
                $out .= '<br/>';
            }
            
            
        }
        
        $data = array(
            'rs' => $out,
            'file' => $file
        );
        $this->output($data);
    }
    
    protected function page_tar_exec_1()
    {
        $keys = array('sn' , 'name' , 'eng_name' , 'stock_num' , 'size');
        
        $file = $this->input('file');
        //if(is_file(K5_ADMIN_DATA_PATH.'/tar_create/'.$file))
        {
            //$cmd = 'cd '.K5_ADMIN_DATA_PATH.'/tar_create;tar -vxf '.$file;
            //helper_os_linux::run_cmd($cmd);
            
            //$dir_name = substr($file , 0 , strpos($file , '_'));
            $dir_name = $file;
            
            $dir_files = helper_filesystem::ls(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
            if (count($dir_files)<1)
            {
                rmdir(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
                $this->_redirect('?page=tar_upload' , '该数据包为空');
            }
            
            //找到索引文件
            $index_file = '';
            foreach ($dir_files as $f)
            {
                $tmp = pathinfo($f);
                
                if($tmp['extension'] == 'txt')
                    $index_file = $f;
                    
                $n = substr_count($tmp['basename'],'-');
                if($n > 2)
                {
                    $tmp_1 = explode('-' , $tmp['basename']);
                    $pic_sn = $tmp_1[0].'-'.$tmp_1[1];
                }
                else 
                {
                    $pic_sn = $tmp['filename'];
                }
                
                $pics[strtolower($pic_sn)] = $tmp['basename'];
            }
//            if(!$index_file)
//            {
//                rmdir(K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name);
//                $this->_redirect('?page=tar_upload' , '未找到数据文件');
//            }
            

            
            //读取索引文件进行商品创建
            $dir = K5_ADMIN_DATA_PATH.'/tar_create/'.$dir_name;
            $fp = fopen($dir.'/'.$index_file , 'r');
            $i = 0;
            $fields_cnt = count($keys);
            while ($line = trim(fgets($fp))) {
                $i++;
                if(empty($line))
                {
                    $out .= $i.' 空行'.'<br/>';
                    continue;
                }
                $tmp = explode("\t" , $line);
                if(count($tmp) != $fields_cnt)
                {
                    $out .= $i.' 数据不完整'.'<br/>';
                    continue;
                }
                
                $out .= $i.' ';
                
                $data = array_combine($keys , $tmp);
                list($data['width'],$data['height'],$data['deep']) = explode('-' , $data['size']);
                unset($data['size']);
                
                
                $this->_create_product($data , $product_id);
                
                $out .= '生成'.$product_id;
                
                $pic = $dir.'/'.$pics[strtolower($data['sn'])];
                echo $pic;
                
                if(is_file($pic))
                {
                    
                    $pid = $this->_calc_pid($product_id);
                    $dest = img2dir($product_id).$pid;
                    echo $dest;
                    
                    $rs = copy($pic , $dest);
                    
                    
                    $this->_add_pic($product_id , $pid , $dest);
                    $out .= ' 完成图片';
                }
                $out .= '<br/>';
            }
            
            
        }
        
        $data = array(
            'rs' => $out,
            'file' => $file
        );
        $this->output($data);
    }
    
    protected function page_bat_create()
    {
        $data = array();
        
        $oArraytree = new lib_arraytree(array());
            
        $oArraytree->restart(k::config('product_class'));
        $this->module_data['product_class'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('product_map'));
        $this->module_data['product_map'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('material'));
        $this->module_data['material'] = $oArraytree->get_id2field('name' , ' └');
        
        $oArraytree->restart(k::config('common_object'));
        $this->module_data['common_object'] = $oArraytree->get_id2field('name' , ' └');
        
        $this->output($data);
    }
    
    protected function page_bat_photo()
    {
        $aId = is_array($_POST['id']) ? $_POST['id'] : explode(',' , $this->input('id'));
        $oProduct = K::load_mod('product');
        $oProduct->list_by_id($aId , array('id' , 'sn' , 'name')) or $this->busy();
        $aProduct = $oProduct->get_data();
        
        $data = array(
            'rows' => $aProduct
        );
        $this->output($data);
    }
    
    protected function page_zip_photo()
    {
        $zip_photo = K5_ADMIN_DATA_PATH.'/zip_photo/doing.zip';
        $is_zip_photo = is_file($zip_photo) ? true : false;
        $data = array(
            'is_zip_photo' => $is_zip_photo
        );
        $this->output($data);
    }
    
    
    protected function api_new_product()
    {
        $data = array();
        $data['sn'] = $this->input('sn');
        $data['name'] = $this->input('name');
        $data['eng_name'] = $this->input('eng_name');
        $data['class_id'] = $this->input('class_id');
        $data['material_id'] = $this->input('material_id');
        $data['map_id'] = $this->input('map_id');
        $data['width'] = (int)$this->input('width');
        $data['height'] = (int)$this->input('height');
        $data['deep'] = (int)$this->input('deep');
        $data['intro'] = $this->input('intro');
        $data['stock_num'] = (int)$this->input('stock_num');
        $data['is_multi'] = $this->input('is_multi') == 1 ? 1 : 0;
        $data['eng_intro'] = $this->input('eng_intro');
        $data['price'] = (int)$this->input('price');
        
        $this->_create_product($data , $id);
        
        $this->_redirect('?page=photo_manage&id='.$id , '保存成功，继续上传图片');
    }
    
    protected function api_bat_create()
    {
        $oProduct = K::load_mod('product');
        $oArraytree = new lib_arraytree(array());
            
        $oArraytree->restart(k::config('common_object'));
        $aCommonObject = $oArraytree->get_id2field_meta();
        
        for ($i=0;$i<30;$i++)
        {
            if($this->input('sn_'.$i))
            {
                $data = array();
                $data['sn'] = $this->input('sn_'.$i);
                $pre_name = $this->input('pre_name_'.$i);
                
                $name = $this->input('name_'.$i);
                $eng_name = $this->input('eng_name_'.$i);
                $data['name'] = $name ? $name : $aCommonObject[$pre_name]['name'];
                $data['eng_name'] = $eng_name ? $eng_name : $aCommonObject[$pre_name]['eng_name'];
                
                $data['class_id'] = $this->input('class_id_'.$i);
                $data['material_id'] = $this->input('material_id_'.$i);
                $data['map_id'] = $this->input('map_id_'.$i);
                $data['width'] = (int)$this->input('width_'.$i);
                $data['height'] = (int)$this->input('height_'.$i);
                $data['deep'] = (int)$this->input('deep_'.$i);
                $data['intro'] = $this->input('intro_'.$i);
                $data['stock_num'] = $this->input('stock_num_'.$i);
                $data['is_multi'] = $this->input('is_multi_'.$i) == 1 ? 1 : 0;
                $data['eng_intro'] = $this->input('eng_intro_'.$i);
                $data['price'] = $this->input('price_'.$i);
                
                $oProduct->create_product($data) or $this->busy();
                $aId[] = $oProduct->get_insert_id();
            }
        }
        $this->_redirect('?page=bat_photo&id='.implode(',' , $aId) , '保存成功，继续上传图片');
    }
    
    protected function api_bat_photo()
    {
        $aProductId = explode(',' , $_POST['ids']);
        if(count($aProductId) < 1)
            $this->_redirect('?page=list_product');
            
        $oProduct = K::load_mod('product');
        $oProduct->list_by_id($aProductId , array('id' , 'sn')) or $this->busy();
        $aProduct = $oProduct->get_data();
            
        $aSn = array();
        
        foreach ($aProduct as $row)
        {
            $product_id = $row['id'];
            
            if(!is_file($_FILES['file_'.$product_id]['tmp_name']))
                continue;
            
                
            $aSn[] = $row['sn'];
            
            //save img
            $pid = $this->_calc_pid($product_id);
            $dest = img2dir($product_id).$pid;
            
            move_uploaded_file($_FILES['file_'.$product_id]['tmp_name'] , $dest) or $this->busy('move_pic error');;
            
            //resize the max size
            $a = getimagesize($dest);
            if($a[0] > 600)
            {
                $oImageResize = new lib_imageResize();
                $oImageResize->set_source_image($dest);
                $oImageResize->set_dest_image($dest);
                $oImageResize->set_max_size(600);
                $oImageResize->resize();
            }
            
            //add watermark
            $oImageMark = new lib_imageMark();
            $rs = $oImageMark->mark($dest , K5_DATA_PATH.'/watermark.gif' , $dest , 90 , 90);
            
            //add img to db record
            $oProduct->append_photo_list($product_id , array($pid)) or $this->busy('append_photo_list error');
            
            //set cover
            if(count($aProduct['photo_list']) < 1)
            {
                $cover_path = product2coverpath($product_id);
                $oProduct->set_photo_cover($product_id , $pid);
                $this->_make_cover($product_id , $pid);
            }
            $aSn[] = $sn;
        }
        
    }
    
    protected function api_zip_photo()
    {
        $doing_dir = K5_ADMIN_DATA_PATH.'/zip_photo';
        
        //接收上传图片包
        if($_FILES['zip']['tmp_name'])
        {
            move_uploaded_file($_FILES['zip']['tmp_name'] , $doing_dir.'/doing.zip') or $this->busy('文件处理失败!');
        }
        
        if(!is_file($doing_dir.'/doing.zip'))
            $this->busy('no_zip');
        
        //解压
        $cmd = 'cd '.$doing_dir.';unzip doing.zip';
        helper_os_linux::run_cmd($cmd);
        //兼容整个目录的压缩包
        if(is_dir($doing_dir.'/doing'))
        {
            $cmd = 'mv '.$doing_dir.'/doing/* '.$doing_dir;
            helper_os_linux::run_cmd($cmd);
            
            rmdir($doing_dir.'/doing');
        }
        
        
        $oProduct = K::load_mod('product');
        $aSn = array();
        $aError = array();
        
        $df = opendir($doing_dir);
        while ($file = readdir($df)) {
            if($file == '.' || $file == '..' || $file == 'doing.zip')
               continue;
               
            list($sn , $suffix) = explode('.' , $file);
            if(!is_product_sn($sn))
               $aError[$file] = 'sn';
            
            echo '开始处理商品-'.$sn."~~~~~~~~~~~~~~~~~~~~~~~~~~~~~<br/>";
            //get product info
            $oProduct->get_by_sn($sn) or $this->busy('数据库错误');
            $aProduct = $oProduct->get_data();
            if(!$aProduct['name'])
                $aError[] = $sn.' 产品不存在<br/>';
            
            //save img
            echo '保存图片中...<br/>';
            $product_id = $aProduct['id'];
            $pid = $this->_calc_pid($product_id);
            $dest = img2dir($product_id).$pid;
            $rs = rename($doing_dir.'/'.$file , $dest);
                
            
            
            //resize the max size
            $a = getimagesize($dest);
            if($a[0] > 600)
            {
                echo '重新调整图片大小...<br/>';
                $oImageResize = new lib_imageResize();
                $oImageResize->set_source_image($dest);
                $oImageResize->set_dest_image($dest);
                $oImageResize->set_max_size(600);
                $oImageResize->resize();
            }
            
            //add watermark
            $oImageMark = new lib_imageMark();
            $rs = $oImageMark->mark($dest , K5_DATA_PATH.'/watermark.gif' , $dest , 90 , 90);
            
            echo '追加图片到商品中...<br/>';
            //add img to db record
            $oProduct->append_photo_list($product_id , array($pid)) or $this->busy('append_photo_list error');
            
            //set cover
            echo '设置商品封面...<br/>';
            if(count($aProduct['photo_list']) < 1)
            {
                $cover_path = product2coverpath($product_id);
                $oProduct->set_photo_cover($product_id , $pid);
                $this->_make_cover($product_id , $pid);
            }
            echo '完成...<br/>';
            $aSn[] = $sn;
        }
        
        echo '清理中...<br/>';
        
        $this->_redirect('?page=list_product&sn='.urlencode(implode(',' , $aSn)) , '保存完成' , 3);
    }
    
    protected function api_edit_product()
    {
        $id = $this->input('id');
        
        $data = array();
        $data['sn'] = $this->input('sn');
        $data['name'] = $this->input('name');
        $data['eng_name'] = $this->input('eng_name');
        $data['class_id'] = $this->input('class_id');
        $data['material_id'] = $this->input('material_id');
        $data['map_id'] = $this->input('map_id');
        $data['width'] = (int)$this->input('width');
        $data['height'] = (int)$this->input('height');
        $data['deep'] = (int)$this->input('deep');
        $data['intro'] = $this->input('intro');
        $data['is_multi'] = $this->input('is_multi') == 1 ? 1 : 0;
        $data['eng_intro'] = $this->input('eng_intro');
        $data['price'] = (int)$this->input('price');
        
        $oProduct = K::load_mod('product');
        $oProduct->edit_product($id , $data) or $this->busy();
        $this->_redirect($_SERVER['HTTP_REFERER'] , '保存成功！');
    }
    
    protected function api_edit_stat()
    {
        $id = (int)$this->input('id');
        $stat = (int)$this->input('stat');
        
        $oProduct = K::load_mod('product');
        $oProduct->set_value_by_id($id , 'stat' , $stat) or $this->busy();
        $this->back();
    }
    
    protected function api_bat_edit_stat()
    {
        $aId = $_POST['id'];
        $stat = (int)$this->input('stat');
        
        $oProduct = K::load_mod('product');
        $oProduct->set_value_in_ids($aId , 'stat' , $stat) or $this->busy();
        $this->back();
    }
    
    protected function api_upload_photo()
    {
        $product_id = $this->input('product_id');
        
        $oUpload = new lib_uploader();
        $aPid = array();
        $save_dir = img2dir($product_id);
        for ($i=1;$i<6;$i++)
        {
            $key = 'file_'.$i;
            if($oUpload->is_set($key))
            {
                $oUpload->start($key);
                
                $oUpload->set_save_dir($save_dir) or $this->busy('save_dir_err');
                
                $pid = $this->_calc_pid($product_id);
                
                $oUpload->set_file_name($pid , false);
                
                $oUpload->save() or $this->busy('save_err '.$i);
                $aPid[] = $pid;
                $oUpload->reset();
                
                $img_src = $save_dir.$pid;
                $a = getimagesize($img_src);
                if($a[0] > 600)
                {
                    $oImageResize = new lib_imageResize();
                    $oImageResize->set_source_image($img_src);
                    $oImageResize->set_dest_image($img_src);
                    $oImageResize->set_max_size(600);
                    $oImageResize->resize();
                }
                
                //add watermark
                $oImageMark = new lib_imageMark();
                $rs = $oImageMark->mark($img_src , K5_DATA_PATH.'/watermark.gif' , $img_src , 90 , 90);
            }
        }
        
        if(count($aPid) > 0)
        {
            $oProduct = K::load_mod('product');
            $oProduct->append_photo_list($product_id , $aPid) or $this->busy();
        
            //取第一张图作封皮
            $pid = $aPid[0];
            $cover_path = product2coverpath($product_id);
            if(!is_file($cover_path))
            {
                $oProduct->set_photo_cover($product_id , $pid);
                $this->_make_cover($product_id , $pid);
            }
        }
        
        $this->back();
    }
    
    protected function api_delete_photo()
    {
        $product_id = $this->input('id');
        $pid = $this->input('pid');
        $path = pid2path($product_id , $pid);
        $dest = K5_UPLOAD_PATH.'/__delete/'.$pid;
        rename($path , $dest);
        
        $is_cover = false;
        
        $oProduct = K::load_mod('product');
        $oProduct->delete_photo_list($product_id , $pid , $is_cover) or $this->busy('delete');
        
        $this->back();
    }
    
    protected function api_set_cover()
    {
        $product_id = $this->input('id');
        $pid = $this->input('pid');
        
        $oProduct = K::load_mod('product');
        $oProduct->set_photo_cover($product_id , $pid) or $this->busy();
        
        $this->_make_cover($product_id , $pid);
        
        $this->back();
    }   
    
    protected function api_tar_upload()
    {
        for($i=0;$i<10;$i++)
        {
            if(!is_file($_FILES['tar_'.$i]['tmp_name']))
                continue;
            $a = pathinfo($_FILES['tar_'.$i]['name']);
            $filename = $a['filename'].'_'.time().'.tar';
                
            $file = K5_ADMIN_DATA_PATH.'/tar_create/'.$filename;

            move_uploaded_file($_FILES['tar_'.$i]['tmp_name'] , $file);
        }
        $this->_redirect('?page=tar_upload' , '上传完成');
    }
    
    protected function api_tar_delete()
    {
        $file = $this->input('file');
        if(is_file(K5_ADMIN_DATA_PATH.'/tar_create/'.$file))
        {
            unlink(K5_ADMIN_DATA_PATH.'/tar_create/'.$file);
            $this->_redirect('?page=tar_upload' , '删除成功');
        }
        $this->_redirect('?page=tar_upload' , '文件不存在');
    }
    
    
    
    private function _calc_pid($product_id)
    {
        return 'p'.$product_id.'_'.substr(md5($product_id.''.K::microtime().mt_rand(10000,99999)) , 0 , 8);
    }
    
    private function _make_cover($product_id , $pid)
    {
        $src = pid2path($product_id , $pid);
        $dest = product2coverpath($product_id);
        $dest_big = product2bigcoverpath($product_id);
        
        
        
        if(is_file($dest))
            @unlink($dest);
        if(is_file($dest_big))
            @unlink($dest_big);
        copy($src , $dest_big);
        
        
        $oImageResize = new lib_imageResize();
        $oImageResize->set_source_image($src);
        $oImageResize->set_dest_image($dest);
        $oImageResize->set_max_size(210);
        $oImageResize->resize();
//die;
        return true;
    }
    
    private function _create_product($data , &$id)
    {
        $oProduct = K::load_mod('product');
        $rs = $oProduct->create_product($data);
        if(!$rs)
            return false;
        $id = $oProduct->get_insert_id();
        return true;
    }
    
    private function _add_pic($product_id , $pid , $dest)
    {
        //resize the max size
        $a = getimagesize($dest);
        if($a[0] > 600)
        {
            $oImageResize = new lib_imageResize();
            $oImageResize->set_source_image($dest);
            $oImageResize->set_dest_image($dest);
            $oImageResize->set_max_size(600);
            $oImageResize->resize();
        }
        
        //add watermark
        $oImageMark = new lib_imageMark();
        $rs = $oImageMark->mark($dest , K5_DATA_PATH.'/watermark.gif' , $dest , 90 , 90);
        
        //add img to db record
        $oProduct = K::load_mod('product');
        $oProduct->append_photo_list($product_id , array($pid)) or $this->busy('append_photo_list error');
        
        //set cover
        if(count($aProduct['photo_list']) < 1)
        {
            $cover_path = product2coverpath($product_id);
            $oProduct->set_photo_cover($product_id , $pid);
            $this->_make_cover($product_id , $pid);
        }
    }
}

new adm_product();
?>
