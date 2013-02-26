<?php

include('../__global.php');


class adm_product_buy extends admin_ctrl
{
    public function page_list_new()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $stat = $this->input('stat' , 'ALL' , K_COMMON_NO);
        
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_uid(array($stat),$total,1,50) or $this->busy('ls_order');
        $aOrder = $oBuy->get_key2map('uid');
        
        if(count($aOrder)>0)
        {
            $aUid = array_keys($aOrder);
            
            $aUser = array();
            $oMember = K::load_mod('member');
            $oMember->list_by_uid($aUid) or $this->busy();
            $aUser = $oMember->get_key2map();
        }
        
        
        $data = array(
            'stat' => $stat,
            'order' => $aOrder,
            'user' => $aUser,
        );
        $this->output($data);
    }
    
    public function page_list_by_uid()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $oBuy = K::load_mod('product_buy');
        $is_all = $this->input('is_all') == 1 ? true : false;
        $stats_one = $this->input('status');
        if($is_all)
            $stats = array(K_COMMON_NO , K_COMMON_YES , K_COMMON_DONE);
        else if($stats_one) 
            $stats = array($stats_one);
        else 
        {
            $stats = array(K_COMMON_NO);
            $stats_one = K_COMMON_NO;
        }
        
        if($stats_one == K_COMMON_NO)
            $print_page = 'orderbyuid';
        else if($stats_one == K_COMMON_YES)
            $print_page = 'quotebyuid';
        else if($stats_one == K_COMMON_LOCK)
            $print_page = 'buybyuid';
            
        $uid = $this->input('uid');
        
        $oBuy->list_by_uid(array($uid) , $stats , 0) or $this->busy();
        $aOrder = $oBuy->get_data();
        
        if(count($aOrder) > 0)
        {
            $aPid = $aUid = array();
            foreach ($aOrder as $row)
            {
                if($row['stat'] == K_COMMON_NO)
                    $aBid[$row['uid']][] = $row['id'];
                $aUidOrder[$row['uid']][] = $row;
                $aPid[$row['pid']] = 1;
                $aUid[$row['uid']] = 1;
            }
            unset($aOrder);
        
            
                
            $aPid = array_keys($aPid);
            $aProduct = array();
            $oProduct = K::load_mod('product');
            $oProduct->list_by_id($aPid , array('id' ,'sn', 'name','price')) or $this->busy('ls_product');
            $aProduct = $oProduct->get_key2map();
            foreach ($aProduct as &$row)
            {
                $cover_path = product2coverpath($row['id']);
                if(is_file($cover_path))
                    $row['cover_url'] = product2coverpath($row['id'] , true);
            }
            
            
            $aUid = array_keys($aUid);
            $aUser = array();
            $oMember = K::load_mod('member');
            $oMember->list_by_uid($aUid) or $this->busy();
            $aUser = $oMember->get_key2map();
        }
        

        $data = array(
            'order' => $aUidOrder,
            'user' => $aUser,
            'product' => $aProduct,
            'bids' => $aBid,
            'is_all' => $is_all,
            'status' => $stats_one,
            'print' => $print_page
        );
        $this->output($data);
    }
    
    
    public function page_list_by_pid()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $is_done = $this->input('over');
        $status = array(K_COMMON_NO , K_COMMON_YES);
        if($is_done)
            $status = array(K_COMMON_DONE);
        
        $sn = $this->input('sn');
        if($sn)
            $aSn = explode(',' , $sn);
        $pids = $this->input('pid');
        if($pids)
            $aPid = explode(',' , $pids);
        
        
        $oProduct = K::load_mod('product');
        $oBuy = K::load_mod('product_buy');
        
        if($aPid)
        {
            $oBuy->list_by_pid($aPid , $status) or $this->busy();
        }
        else if($aSn)
        {
            $oProduct->list_by_sn($aSn) or $this->busy();
            $aPid = array_keys($oProduct->get_key2map());
            $oBuy->list_by_pid($aPid , $status) or $this->busy();
            
        }
        else
        {
            $oBuy->list_pid(array(K_COMMON_NO , K_COMMON_YES) , $total) or $this->busy();
            $aPid = $oBuy->get_key2map('id' , 'pid');

            $oBuy->list_by_pid($aPid , $status) or $this->busy();
        }
        
        $aOrder = $oBuy->get_data();
        
        if(count($aOrder) > 0)
        {
            $aPid = $aUid = array();
            foreach ($aOrder as $row)
            {
                $aBid[$row['pid']][] = $row['id'];
                $aUidOrder[$row['pid']][] = $row;
                $aPid[$row['pid']] = 1;
                $aUid[$row['uid']] = 1;
            }
            unset($aOrder);
        
            
                
            $aPid = array_keys($aPid);
            $aProduct = array();

            $oProduct->list_by_id($aPid , array('id' , 'name','price','sn','stock_num')) or $this->busy('ls_product');
            $aProduct = $oProduct->get_key2map();
            
            $aUid = array_keys($aUid);
            $aUser = array();
            $oMember = K::load_mod('member');
            $oMember->list_by_uid($aUid) or $this->busy();
            $aUser = $oMember->get_key2map();
        }
        

        $data = array(
            'order' => $aUidOrder,
            'user' => $aUser,
            'product' => $aProduct,
            'bids' => $aBid,
            'sn' => $sn,
        );
        $this->output($data);
    }

    public function page_quote_price()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $aBid = explode(',' , $this->input('bids'));
        
        if(!$aBid)
            die('err');
        
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_by_id($aBid) or $this->busy();
        $aOrder = $oBuy->get_data();
        
//        foreach ($aOrder as $row)
//        {
            $aPid = $aUid = array();
            foreach ($aOrder as $row)
            {
                $aPid[$row['pid']] = 1;
                $aUid[$row['uid']] = 1;
            }
            
            $aPid = array_keys($aPid);
            $aProduct = array();
            $oProduct = K::load_mod('product');
            $oProduct->list_by_id($aPid , array('id' ,'sn', 'name','price')) or $this->busy('ls_product');
            $aProduct = $oProduct->get_key2map();
            foreach ($aProduct as &$row)
            {
                $cover_path = product2coverpath($row['id']);
                if(is_file($cover_path))
                    $row['cover_url'] = product2coverpath($row['id'] , true);
            }
            
            $aUid = array_keys($aUid);
            $aUser = array();
            $oMember = K::load_mod('member');
            $oMember->list_by_uid($aUid) or $this->busy();
            $aUser = $oMember->get_key2map();
//        }
        
        $data = array(
            'order' => $aOrder,
            'product' => $aProduct,
            'user' => $aUser
        );
        $this->output($data);
    }
    
    public function page_done()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $bid = $this->input('bid');
        if(!$bid)
            die('e');
            
        $oBuy = K::load_mod('product_buy');
        $oBuy->get_by_id($bid) or $this->busy();
        $order = $oBuy->get_data();
        
        $oProduct = K::load_mod('product');
        $oProduct->get_by_id($order['pid']);
        $product = $oProduct->get_data();
        
        $oMember = K::load_mod('member');
        $oMember->get_user($order['uid']);
        $user = $oMember->get_data();
        
        $data = array(
            'order' => $order,
            'product' => $product,
            'user' => $user
        );
        $this->output($data);
    }
    
    public function page_print()
    {
        $excel = $this->input('xls');
        $downpic = $this->input('pic');
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $type = $this->input('type');
        $key = $this->input('key');
        $table_empty = false;
            
        if($type == 'orderbyuid')
        {
            $table_empty = true;
            $rows = $this->_biz_print_buy_by_uid($key , K_COMMON_NO);
        }
        else if($type == 'quotebyuid')
        {
            $table_empty = true;
            $rows = $this->_biz_print_buy_by_uid($key , K_COMMON_YES);
        }
        else if($type == 'buybyuid')
            $rows = $this->_biz_print_buy_by_uid($key , K_COMMON_DONE);
        else if($type == 'buybypid')
            $rows = $this->_biz_print_buy_by_pid($key);
            
            
        if(count($rows) > 0)
        {
            foreach ($rows as $k => $row)
            {
                $aPid[] = $row['pid'];
                $aUid[] = $row['uid'];
                $rows[$k]['cover'] = product2coverpath($row['pid'],true);
            }
            
        }
        else 
            die('无');
        
            
        $oProduct = K::load_mod('product');
        $oProduct->list_by_id($aPid , array('id' , 'name','price' , 'sn','photo_list')) or $this->busy('ls_product');
        if($downpic)
        {
            $this->_print_down_pic($oProduct->get_data());
        }
        
        $aProduct = $oProduct->get_key2map();
        
        
        
        $oMember = K::load_mod('member');
        $oMember->list_by_uid(array_unique($aUid)) or $this->busy();
        $aUser = $oMember->get_key2map('id');
        
        $array = array(
            'table_empty' => $table_empty,
            'buys' => $rows,
            'user' => $aUser,
            'products' => $aProduct
        );
        
        if($excel)
        {
            $this->_print_excel($array);
            die;
        }
  
        $this->output($array);
    }
    
    private function _biz_print_buy_by_uid($uid , $stat = K_COMMON_YES)
    {
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_by_uid(array($uid) , array($stat)) or $this->busy();
        $rows = $oBuy->get_data();
        return $rows;
    }
    private function _biz_print_buy_by_pid($pid , $stat = K_COMMON_DONE)
    {
        
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_by_pid(array($pid) , array($stat)) or $this->busy();
        $rows = $oBuy->get_data();
        return $rows;
    }
    private function _print_excel($data)
    {
        $oExcel = new lib_excel_xml();
        
        //表头
        $field = array('Code 工厂编号' , 'Buyer\'NO 顾客编号' , 'Photo 图片' , 'Description 货物名称' , 'Q\'ty 数量' , 'Unit Price 报价' , 'Subtotal 小计' , 'Sepcial Request 特殊需求' , 'Remarks 备注');
        $oExcel->addRow($field);
        
        $excel_data = array();
        foreach ($data['buys'] as $row)
        {
            $excel_row = array(
                $data['products'][$row['pid']]['sn'],
                $data['user'][$row['uid']]['name'],
                '<img src="'. $row['cover'].'" width="50%" height="50%"/>',
                $data['products'][$row['pid']]['name'],
                ($data['table_empty'] ? '' : $row['buy_num']),
                ($data['table_empty'] ? '' : $row['quoted_price']),
                ($data['table_empty'] ? '' : ($row['buy_num'] * $row['done_price']))
            );
            $excel_data[] = $excel_row;
        }
        
        $oExcel->addArray($excel_data);
        $oExcel->generateXML();
    }
    private function _print_down_pic($data)
    {
        $dir = K5_ADMIN_DATA_PATH.'/zip_photo';
        $cmd = 'rm -f '.$dir.'/*';
        helper_os_linux::run_cmd($cmd);
        foreach ($data as $product)
        {
            
            $product['photo_list'] = unserialize($product['photo_list']);
            foreach ($product['photo_list'] as $photo_id => $row)
            {
                if($row['is_cover'] == 1)
                {
                    $path = pid2path($product['id'] , $photo_id);
                    $dest_path = $dir.'/'.$product['sn'].'.jpg';
                    copy($path , $dest_path);
                }
            }
        }
        
        $zip = K5_ADMIN_DATA_PATH.'/zip_photo.zip';
        $cmd = 'rm -f '.$zip;
        helper_os_linux::run_cmd($cmd);
        
        $cmd = 'cd '.K5_ADMIN_DATA_PATH.';zip -r photo.zip zip_photo';
        helper_os_linux::run_cmd($cmd);
        
        $this->_redirect('/__admin/data/photo.zip','',0);
    }
    
    public function page_list_buy_by_uid()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $uid = $this->input('uid');
        
        $oMember = K::load_mod('member');
        $oMember->get_user($uid) or $this->busy();
        $user = $oMember->get_data();
        if(empty($user))
            die('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
无');
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_by_uid(array($uid) , array(K_COMMON_DONE)) or $this->busy();
        $aOrder = $oBuy->get_data();
        $aPid = $oBuy->get_key2map('id' , 'pid');
        if(empty($aPid))
            die('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
无');
        $oProduct = K::load_mod('product');
        $oProduct->list_by_id($aPid , array('id' ,'sn', 'name')) or $this->busy();
        $aproduct = $oProduct->get_key2map();
        
        $data = array(
            'user' => $user,
            'order' => $aOrder,
            'product' => $aproduct,
        );
        $this->output($data);
    }
    
    public function api_quote_price()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $email_chn = $_POST['email_chn'];
        $aBids = $_POST['bids'];
        if(count($aBids) < 1)
            die('e');
            
        $oBuy = K::load_mod('product_buy');
        $oBuy->list_by_id($aBids) or $this->busy();
        $aBuy = $oBuy->get_data();
        
        foreach ($aBuy as $row)
        {
            $bid = $row['id'];
            $aUid[$row['uid']] = 1;
            $aPid[$row['pid']] = 1;
            $aUid2Product[$row['uid']][$bid] = $row;
        }
        
        $aUid = array_keys($aUid);
        $oMember = K::load_mod('member');
        $oMember->list_by_uid($aUid) or $this->busy();
        $aMember = $oMember->get_key2map();
        
        $aPid = array_keys($aPid);
        $oProduct = K::load_mod('product');
        $oProduct->list_by_id($aPid , array('id' , 'name' , 'eng_name' , 'price' , 'sn','width','height','deep')) or $this->busy();
        $aProduct = $oProduct->get_key2map();
        
        
        
        $currency = K::config('currency_usd2cny');

        
ob_start();
        foreach ($aUid2Product as $uid => $aOrder)
        {
            $user = $aMember[$uid];

            if($email_chn)
            {
?>
<?php echo $user['name']?>,您好!<br/>
&nbsp;&nbsp;&nbsp;&nbsp;很高兴您选择我们的商品。您所选的：<br/><br/>

<table border="1" width="400px">
<tr>
    <th bgcolor="#dedede" width="100px"><b>产品编号</b></th>
    <th bgcolor="#dedede" width="100px"><b>尺寸</b></th>
    <th bgcolor="#dedede" width="100px"><b>品名</b></th>
    <th bgcolor="#dedede"><b>单价</b></th>
</tr>
<?php
            }else{
?>
Deer,<?php echo $user['name']?>!<br/>
&nbsp;&nbsp;&nbsp;&nbsp;How are you!<br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;I'm very glad to heard news from you! your choosing<br/><br/>

<table border="1" width="400px">
<tr>
<th bgcolor="#dedede" width="100px"><b>sn</b></th>
    <th bgcolor="#dedede" width="100px"><b>size</b></th>
    <th bgcolor="#dedede" width="100px"><b>Product name</b></th>
    <th bgcolor="#dedede"><b>Price</b></th>
    
</tr>
<?php
            }
            
            $total_price = 0;
            foreach ($aOrder as $bid => $row)
            {
                $product = $aProduct[$row['pid']];
                $price = $this->input('price_'.$bid);
                $price = $price ? $price : $product['price'];
                $total_price+=$price;
                
                $product_url = 'http://'.K5_DOMAIN.'/product/show/'.$product['sn'];
                
                $email = $this->input('email_'.$bid);
                
?>
<tr>
    <td align="center"><?php echo $product['sn']; ?></td>
    <td align="center"><?php echo $product['width'].'cm *'.$product['deep'].'cm *'.$product['height']; ?>cm</td>
    <td align="center"><?php echo $email_chn ? $product['name'] : $product['eng_name']; ?></td>
    <td align="center">¥<?php echo $price; ?></td>
</tr>

<?php
                $oBuy->quote_price($bid , $price) or $this->busy();
            }//foreach
            
            if($email_chn){
?>
<tr bgcolor="#dedede">
<td align="center"><b>总计：</b></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="center">¥<?php echo $total_price ?></td>
</tr>
</table>
<br/>
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;如您有任何问题，邮件或电话联系我们<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
谢谢！
<?
            }else{
?>
<tr bgcolor="#dedede">
<td align="center"><b>Total：</b></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align="center">¥<?php echo $total_price ?></td>
</tr>
</table>
&nbsp;&nbsp;&nbsp;&nbsp;please pay me the amount at the latest rate on the day when you send the expense.<br/>
&nbsp;&nbsp;&nbsp;&nbsp;If you have any problum,please send email or call us!Thank you!<br/><br/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
best regards
<br/>
<?php
            }
            
        }
$mail = ob_get_contents();
ob_end_clean();
        
        echo '<a href="mailto:'.$email.'"/>撰写邮件 ('.$email.')</a>';
        echo '<br/>邮件内容：<br/><br/><br/><br/>';
        echo $mail;
        die();
    }
    
    public function api_done()
    {
        $this->_check_adm_level(K_ADM_LV_ADMINISTRATOR);
        
        $bid = $this->input('bid');
        if(!$bid)
            die('e');
        $done_price = $this->input('done_price');
        $is_empty = $this->input('empty') == 1 ? true : false;
        $buy_num = (int)$this->input('buy_num');
        $buy_num = $buy_num < 1 ? 1 : $buy_num;
        
        $oProduct = K::load_mod('product');
        $oProduct->get_by_id($order['pid']);
        $product = $oProduct->get_data();
        $stat_done = (!$product['is_multi'] || $is_empty == 1 ) ? true : false;
        
        $oBuy = K::load_mod('product_buy');
        $oBuy->get_by_id($bid) or $this->busy();
        $order = $oBuy->get_data();
        
        //lock other order
        if($stat_done)
        {
            $pid = $order['pid'];
            $oBuy->lock_by_pid($pid) or $this->busy();
        }
        //sell to user
        $oBuy->done_order($bid , $done_price ,true, $buy_num) or $this->busy();
        //改变产品状态
        $oProduct = K::load_mod('product');
        $oProduct->set_value_by_id($pid , 'stat' , ($is_empty?K_COMMON_LOCK:K_COMMON_YES)) or $this->busy();
        //增加会员产品购买量
        $oMember = K::load_mod('member');
        $oMember->increase_buy($order['uid']) or $this->busy();
        
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        $msg = '完成'.($stat_done ? ',该商品已下架' : '');
        die('<script>alert("'.$msg.'");window.close();</script>');
    }
    public function api_cancel_order()
    {
        $bid = $this->input('bid');
        $oBuy = K::load_mod('product_buy');
        $oBuy->cancel_order($bid);
        $this->back();
    }
}

new adm_product_buy();
?>