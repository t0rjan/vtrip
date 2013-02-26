<?php
function page_list_new($adm)
{
    global $K_ARRAY_USER_LEVEL;
    $currency = K::config('currency_usd2cny');
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<?php if($stat==K_COMMON_NO){?>未报价<?PHP } 
      else if($stat==K_COMMON_YES){?>已报价<?PHP }
      else if($stat==K_COMMON_LOCK){?>已售出<?PHP } ?>
<?php     if(count($adm['order']) > 0)
    { ?>
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
    <th>会员名</th>
    <th>最后预订时间</th>
    <th>未报价商品数</th>
    <th>操作</th>
</tr>
<?php 

        foreach ($adm['order'] as $row)
        { 
            $user = $adm['user'][$row['uid']];
?>
<tr>
    <td><a href="adm_member.php?page=show_member&uid=<?php echo $user['id']; ?>"><?php echo $user['name'].'('.$K_ARRAY_USER_LEVEL[$user['level']].')'; ?></a></td>
    <td><?php echo date('Y-m-d H:i' , $row['ctime']); ?></td>
    <td><?php echo $row['n']; ?></td>
    <td><a href="?page=list_by_uid&uid=<?php echo $row['uid']; ?>">未报价商品</a>|<a href="?page=list_by_uid&uid=<?php echo $row['uid']; ?>&status=<?php echo K_COMMON_YES; ?>">已报价商品</a>|<a href="?page=list_by_uid&uid=<?php echo $row['uid']; ?>&is_all=1">全部商品</a></td>
</tr>
<?php } ?>
</table>

<?php
}else{echo '无';}



}
function page_list_by_uid($adm)
{
    global $K_ARRAY_USER_LEVEL;
    $currency = K::config('currency_usd2cny');
    
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<?php if(count($adm['order'])){ ?>

<?php 
    foreach ($adm['order'] as $uid=>$aOrder)
    { 
        $user = $adm['user'][$uid];
        
        if(count( $adm['bids'][$uid]) > 0)
            $bids = implode(',' , $adm['bids'][$uid]);
?>
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
    <th><a href="adm_member.php?page=show_member&uid=<?php echo $user['id']; ?>"><?php echo $user['name']; ?>(<?php echo $K_ARRAY_USER_LEVEL[$user['level']]; ?>)</a>
    <?php if(!$adm['is_all']){ ?><a href="?page=list_by_uid&is_all=1&uid=<?php echo $uid ?>">全部</a><?php }else{ echo '全部('.count($aOrder).')';} ?>
    <?php if($adm['status']!=K_COMMON_NO){ ?><a href="?page=list_by_uid&uid=<?php echo $uid ?>">未报价</a><?php }else{ echo '未报价('.count($aOrder).')';} ?>
    <?php if($adm['status']!=K_COMMON_YES){ ?><a href="?page=list_by_uid&uid=<?php echo $uid ?>&status=<?php echo K_COMMON_YES ?>">已报价</a><?php }else{ echo '已报价('.count($aOrder).')';} ?>
    <?php if($adm['status']!=K_COMMON_LOCK){ ?><a href="?page=list_by_uid&uid=<?php echo $uid ?>&status=<?php echo K_COMMON_LOCK ?>">已售出</a><?php }else{ echo '已售出('.count($aOrder).')';} ?>
    <?php if(!$adm['is_all']){ ?>
    <th><?php if($bids){ ?><a href="?page=quote_price&bids=<?php echo $bids; ?>"><font color="#ff6600"><b>报价</b></font></a>&nbsp;&nbsp;&nbsp;<?php } ?><a href="?page=print&type=<?php echo $adm['print']; ?>&key=<?php echo $uid; ?>&xls=1" target="_blank"><font color="#ff6600"><b>打印报表</b></font></a><a href="?page=print&type=<?php echo $adm['print']; ?>&key=<?php echo $uid; ?>" target="_blank"><font color="#ff6600"><b>[图]</b></font></a> <a href="?page=print&type=<?php echo $adm['print']; ?>&key=<?php echo $uid; ?>&pic=1"><font color="#ff6600"><b>[下载]</b></font></a></th></th>
    <?php } ?>
</tr>
<tr><td colspan="2">
<?php if(count($aOrder)>0){ ?>
    <table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
    <?php
    
        foreach ($aOrder as $row)
        {
            $bid = $row['id'];
            $price_alert = '';
            $product = $adm['product'][$row['pid']];
            $price_dol = round(($product['price']/$currency) , 2);
            
            $status = '未报价';
            if($row['stat'] == K_COMMON_YES)
            {
               if($row['quoted_price'] <> $product['price'])
                   $price_alert = '<font color="red"><b>!</b></font>';
               $quoted_price_dol = round(($row['quoted_price']/$currency) , 2);
               $status = $price_alert.'已报价: ￥'.$row['quoted_price'].' $'.$quoted_price_dol .' <a href="?page=done&bid='.$bid.'">售出</a>';
            }
    ?>
    <tr>
        <td><?php echo $product['sn']; ?> <a href="?page=list_by_pid&pid=<?php echo $row['pid']; ?>">全部</a></td>
        <td><img src="<?php echo $product['cover_url']; ?>" width="80"/></td>
        <td><?php echo '<b>￥'.$product['price'].'</b> $'.$price_dol; ?></td>
        <td><?php echo date('Y-m-d' , $row['ctime']); ?></td>
        <td><?php echo $status; ?></td>
        <td><a href="?api=cancel_order&bid=<?php echo $bid; ?>">取消</a></td>
    </tr>
    <?php } ?>
    </table>
<?php }else{echo '无';} ?>
</td></tr>
</table>
<?php
    }
}else{echo '无';}
}
function page_list_by_pid($adm)
{
    global $K_ARRAY_USER_LEVEL;
    $currency = K::config('currency_usd2cny');
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
<form method="POST" action="?page=list_by_pid">
    <td align="right">商品编号：</td><td><input name="sn" type="text" class="input01" onclick="if(this.value=='输入商品编号'){this.value=''}" value="<?php echo $adm['sn']?$adm['sn']:'输入商品编号'; ?>" size="30"/> <input type="submit" class="button01" value="查看"/>(逗号分隔)</td>
</form>
</tr>
</table>
<?php if(count($adm['order'])){ ?>
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<?php 
    foreach ($adm['order'] as $pid=>$aOrder)
    { 
        $product = $adm['product'][$pid];
        
        $price_dol = round(($product['price']/$currency) , 2);
        $bids = implode(',' , $adm['bids'][$pid]);
?>
<tr>
    <th><?php echo $product['sn']; ?>(<?php echo $product['sn'] ?>) 数量:<?php echo $product['stock_num']; ?> <a href="?page=quote_price&bids=<?php echo $bids; ?>"><font color="#ff6600"><b>报价</b></font></a> <a href="?page=print&type=buybypid&key=<?php echo $pid; ?>&xls=1" target="_blank">打印报表</a><a href="?page=print&type=buybypid&key=<?php echo $pid; ?>" target="_blank">[图]</a><a href="?page=print&type=buybypid&key=<?php echo $pid; ?>&img=1">[下载]</a></th>
    <th><?php echo '<b>￥'.$product['price'].'</b> $'.$price_dol; ?></th>
</tr>
<tr><td colspan="2">
    <table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
    <?php
        foreach ($aOrder as $row)
        {
            $bid = $row['id'];
            $price_alert = '';
            $user = $adm['user'][$row['uid']];
           
            $status = '未报价 <a href="?page=quote_price&bids='.$bid.'">报价</a>';
            if($row['stat'] == K_COMMON_YES)
            {
                if($row['quoted_price'] <> $product['price'])
                    $price_alert = '<font color="red"><b>!</b></font>';
                $quoted_price_dol = round(($row['quoted_price']/$currency) , 2);
                $status = $price_alert.'已报价: ￥'.$row['quoted_price'].' $'.$quoted_price_dol.' <a href="?page=done&bid='.$bid.'">售出</a>';
            }
            else if($row['stat'] == K_COMMON_DONE)
            {
                $price_dol = round(($row['done_price']/$currency) , 2);
                $status = '已售出:(￥'.$row['done_price'].' $'.$price_dol.') 数量:'.$row['buy_num'];
            }
    ?>
    <tr>
        <td width="150px"><a href="adm_member.php?page=show_member&uid=<?php echo $user['id']; ?>"><?php echo $user['name']; ?></a> <a href="?page=list_by_uid&uid=<?php echo $row['uid']; ?>">全部</a></td>
        <td><?php echo $K_ARRAY_USER_LEVEL[$user['level']]; ?> 累计购买：<?php echo $user['cnt_buy']; ?></td>
        <td><?php echo date('Y-m-d' , $row['ctime']); ?></td>
        <td><?php echo $status ?></td>
    </tr>
    <?php } ?>
    </table>
</td></tr>
<?php
    }
?>
</table>
<?php
}
else {echo '无';}
}

function page_quote_price($adm)
{
    global $K_ARRAY_USER_LEVEL;
    $currency = K::config('currency_usd2cny');
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<form method="POST" action="?api=quote_price">
<tr>
    <th>会员名</th>
    <th>商品(价格)</th>
    <th>图片</th>
    <th>邮件</th>
    <th>报价</th>
</tr>
<?php 
    foreach ($adm['order'] as $row)
    { 
        $user = $adm['user'][$row['uid']];
        $product = $adm['product'][$row['pid']];
        $price_dol = round(($product['price']/$currency) , 2);
        $bid = $row['id'];
?>
<input type="hidden" name="bids[]" value="<?php echo $bid; ?>"/>
<tr>
    <td><a href="adm_member.php?page=show_member&uid=<?php echo $user['id']; ?>"><?php echo $user['name'].'</a>'.'('.$K_ARRAY_USER_LEVEL[$user['level']].' 已购:'.$user['cnt_buy'].')'; ?></td>
    <td><?php echo $product['sn'].'(￥'.$product['price'].' $'.$price_dol.')'; ?></td>
    <td><img src="<?php echo $product['cover_url'] ?>" width="80px"/></td>
    <td><input type="text" name="email_<?php echo $bid; ?>" value="<?php echo $user['email'] ?>"/></td>
    <td><input type="text" name="price_<?php echo $bid; ?>" value="<?php echo $product['price'] ?>"/></td>
</tr>
<?php } ?>
<tr><td colspan="4"><input type="checkbox" name="email_chn" value="1" />中文邮件 <input type="submit" value="发送报价"/></td></tr>
</form>
</table>
<?php
}

function page_done($adm)
{
    $currency = K::config('currency_usd2cny');
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<form method="POST" action="?api=done&bid=<?php echo $adm['order']['id']; ?>">
<tr>
    <td>会员:</td>
    <td><a href="adm_member.php?page=show_member&uid=<?php echo $adm['user']['id']; ?>"><?php echo $adm['user']['name']; ?></a></td>
</tr>
<tr>
    <td>商品:</td>
    <td><?php echo $adm['product']['sn']; ?></td>
</tr>
<tr>
    <td>价格:</td>
    <td><input name="done_price" value="<?php echo $adm['order']['quoted_price'] ?>"/></td>
</tr>
<tr>
    <td>数量:</td>
    <td><input name="buy_num" type="text" value="1"/>(库存:<?php echo $adm['product']['stock_num']; ?>)</td>
</tr>
<tr>
    <td>卖光:</td>
    <td><input type="checkbox" name="empty" value="1"/></td>
</tr>
<tr><td colspan="2"><input type="submit" value="售出"/></td></tr>
</form>
</table>
<?php
}

function page_list_buy_by_uid($adm)
{
?>
<link href="../css.css" rel="stylesheet" type="text/css">
<div align="right"><a href="?page=print&type=buybyuid&key=<?php echo $adm['user']['uid']; ?>" target="_blank"><font color="#ff6600"><b>打印报表</b></font></a><a href="?page=print&type=buybyuid&key=<?php echo $adm['user']['uid']; ?>&xls=1" target="_blank"><font color="#ff6600"><b>[图]</b></font></a><a href="?page=print&type=buybyuid&key=<?php echo $adm['user']['uid']; ?>&pic=1"><font color="#ff6600"><b>[下载]</b></font></a></div>
    <?php if(count($adm['order']) > 0){ ?>
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
    <th>商品名</th>
    <th>价格</th>
    <th>数量</th>
    <th>售出时间</th>
</tr>
<?php foreach ($adm['order'] as $row){ ?>
<tr>
    <td><?php echo $adm['product'][$row['pid']]['sn']; ?></td>
    <td><?php echo $row['done_price']; ?></td>
    <td><?php echo $row['buy_num']; ?></td>
    <td><?php echo date('Y-m-d' , $row['done_time']); ?></td>
</tr>
<?php } ?>
</table>
<?php
    }else{echo '无';}
}

function page_print($adm)
{
?>
<table width="100%" border="0">
<tr>
    <td width="40%" align="center"><img src="/data/images/logo.gif"/></td>
    <td><h1>北京泰来古典家具采购定单</h1><h2>BEIJING TAILAI ANTIQUES PURCHASE LIST</h2>
    <h4>Address: &nbsp;&nbsp;北京朝阳区京沈高速豆各庄万科青青家园南50米<br/>TEL&FAX: &nbsp;&nbsp;8610-85302045<br/>Attn: &nbsp;&nbsp;Helen Zhou
    <br/>Email：&nbsp;&nbsp;info@tailaiantiquefurniture.com<br/>Web:&nbsp;&nbsp;www.tailaiantiquefurniture.com
    <br/>Tel:            
    <br/>Date：</h4></td>
</tr>
<tr>
<td colspan="2"><hr/></td>
</tr>
<tr>
    <td colspan="2">
    
    <table width="100%" border="1" cellspacing="0">
    <tr>
        <td bgcolor="dedede" align="center">Code<br/>工厂编号</td>
        <td bgcolor="dedede" align="center">Buyer'NO<br/>顾客编号</td>
        <td bgcolor="dedede" align="center" width="100px">Photo<br/>图片</td>
        <td bgcolor="dedede" align="center">Description<br/>货物名称</td>
        <td bgcolor="dedede" align="center">Q'ty<br/>数量</td>
        <td bgcolor="dedede" align="center">Unit Price<br/>报价</td>
        <td bgcolor="dedede" align="center">Subtotal<br/>小计</td>
        <td bgcolor="dedede" align="center" width="15%">Sepcial Request<br/>特殊需求</td>
        <td bgcolor="dedede" align="center" width="15%">Remarks<br/>备注</td>
    </tr>
    <?php
        foreach ($adm['buys'] as $row)
        {
    ?>
    <tr>
        <td align="center"><?php echo $adm['products'][$row['pid']]['sn']; ?></td>
        <td align="center"><?php echo $row['uid']; ?>(<a href="adm_member.php?page=show_member&uid=<?php echo $row['uid']; ?>"><?php echo $adm['user'][$row['uid']]['name']; ?></a>)</td>
        <td align="center"><img src="<?php echo $row['cover']; ?>" width="50%" height="50%"/></td>
        <td align="center"><?php echo $adm['products'][$row['pid']]['sn']; ?></td>
        <td align="center"><?php echo $adm['table_empty'] ? '' : $row['buy_num']; ?></td>
        <td align="center"><?php echo $adm['table_empty'] ? '' : $row['quoted_price']; ?></td>
        <td align="center"><?php echo $adm['table_empty'] ? '' : ($row['buy_num'] * $row['done_price']); ?></td>
        <td></td>
        <td></td>
    </tr>
    <?php } ?>
    </table>
    
    </td>
</tr>
</table>
<?php
}
?>