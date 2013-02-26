<script>

function cm2inch($what , $num)

{

    $oIpt = document.getElementById('ipt_'+$what+'_inch');

    $v = Math.round(($num/2.54)*100)/100;

    $oIpt.value=$v;

}

</script>

<?php



function page_new_product($adm)

{

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<form method="POST" action="?api=new_product">

<tr><td colspan="2" align="center" class="td_title"><strong>加入新商品</strong></td></tr>

<tr>

    <td align="right" width="200px">名称：</td><td><input name="name" type="text" class="input01"/></td>

</tr>

<tr>

    <td align="right">英文名称：</td><td><input name="eng_name" type="text" class="input01"/></td>

</tr>

<tr>

    <td align="right">商品编码：</td><td><input name="sn" type="text" class="input01"/></td>

</tr>

<tr>

    <td align="right">参考价格：</td><td><input name="price" type="text" class="input01"/></td>

</tr>

<tr>

    <td align="right">商品分类：</td><td><?php echo lib_htmlmaker::help_html_select('class_id' , $adm['product_class']); ?></td>

</tr>

<tr>

    <td align="right">商品材质：</td><td><?php echo lib_htmlmaker::help_html_select('material_id' , $adm['material']); ?></td>

</tr>

<tr>

    <td align="right">商品产地：</td><td><?php echo lib_htmlmaker::help_html_select('map_id' , $adm['product_map']); ?></td>

</tr>

<tr>

    <td align="right">存货数量：</td><td><input type="text" name="stock_num" value="1"/></td>

</tr>

<tr>

    <td align="right">可复制：</td><td><input type="checkbox" name="is_multi" value="1"/></td>

</tr>

<tr>

    <td align="right">宽：</td><td><input name="width" type="text" class="input01" id="ipt_width" onblur="cm2inch('width' , this.value)"/> 

      厘米         

        <input name="width_inch" type="text" disabled class="input01" id="ipt_width_inch"/> 

        英寸</td>

</tr>

<tr>

    <td align="right">高：</td><td><input name="height" type="text" class="input01" id="ipt_height" onblur="cm2inch('height' , this.value)"/> 

      厘米         

        <input name="height_inch" type="text" disabled class="input01" id="ipt_height_inch"/> 

        英寸</td>

</tr>

<tr>

    <td align="right">深：</td><td><input name="deep" type="text" class="input01" id="ipt_deep" onblur="cm2inch('deep' , this.value)"/> 

      厘米         <input name="deep_inch" type="text" disabled class="input01" id="ipt_deep_inch"/> 

      英寸</td>

</tr>

<tr>

    <td align="right">简介：</td><td><textarea name="intro" cols="40" rows="5" class="input03"></textarea></td>

</tr>

<tr>

    <td align="right">英文简介：</td><td><textarea name="eng_intro" cols="40" rows="5" class="input03"></textarea></td>

</tr>



<tr><td colspan="2" align="center" class="td_con"><input type="submit" class="button02" value="保存 >>上传图片"/></td></tr>

</form>

</table>

<?php

}



function page_bat_create($adm)

{

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<form action="?api=bat_create" method="POST">

<?php for($i = 0; $i<30;$i++){ ?>

<?php if($i%6==0){?>

<tr><td colspan="5" align="right"><input type="submit" value=" 全 页 保 存 "/></td></tr>

<tr>

    <th width="80px">编号</th>

    <th>名称</th>

    <th>属性</th>

    <th width="70px">尺寸</th>

    <th width="70px">价格</th>

</tr>

<?php } ?>

<tr>

    <td>编号:<input name="sn_<?php echo $i; ?>" maxlength="15" size="10"/><br/>

    数量:<input name="stock_num_<?php echo $i; ?>" type="text" value="1" size="3"/><br/>

    可复制:<input name="is_multi_<?php echo $i; ?>" type="checkbox" value="1"/></td>

    <td>参考:<?php echo lib_htmlmaker::help_html_select('pre_name_'.$i , $adm['common_object']); ?><br/>

    中文:<input name="name_<?php echo $i; ?>"/><br/>

    英文:<input name="eng_name_<?php echo $i; ?>"/></td>

    <td>分类:<?php echo lib_htmlmaker::help_html_select('class_id_'.$i , $adm['product_class']); ?><br/>

    材质:<?php echo lib_htmlmaker::help_html_select('material_id_'.$i , $adm['material']); ?><br/>

    产地:<?php echo lib_htmlmaker::help_html_select('map_id_'.$i , $adm['product_map']); ?></td>

    <td width="70px">宽:<input name="width_<?php echo $i; ?>" size="6"/><br/>高:<input name="height_<?php echo $i; ?>" size="6"/><br/>深:<input name="deep_<?php echo $i; ?>" size="6"/></td>

    <td><input name="price_<?php echo $i; ?>" size="6"/></td>

</tr>

<tr><td colspan="5" height="1px"><hr/></td></tr>

<?php } ?>

<tr><td colspan="4" align="right"><input type="submit" value=" 全 页 保 存 "/></td></tr>

</form>

</table>

<?php

}



function page_edit_product($adm)

{

    $product = $adm['product'];

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<form method="POST" action="?api=edit_product&id=<?php echo $product['id']; ?>">

<tr><td colspan="3" align="center" class="td_title"><strong>编辑商品</strong> <?php echo $product['sn'].' '.$product['name']; ?></td></tr>

<tr>

    <td align="right" width="200px">名称：</td><td><input name="name" type="text" class="input01" value="<?php echo $product['name']; ?>"/></td>

    <td rowspan="4" align="center"><?php if($adm['cover_path']){?><img src="<?php echo $adm['cover_path']; ?>" width="100px" /><?php }else{echo '未设置封面';} ?>

        <br/><a href="?page=photo_manage&id=<?php echo $product['id']; ?>">图片管理</a>

    </td>

</tr>

<tr>

    <td align="right">英文名称：</td><td><input name="eng_name" type="text" class="input01" value="<?php echo $product['eng_name']; ?>"/></td>

</tr>

<tr>

    <td align="right">商品编码：</td><td><input name="sn" type="text" class="input01" value="<?php echo $product['sn']; ?>"/></td>

</tr>

<tr>

    <td align="right">商品价格：</td><td><input name="price" type="text" class="input01" value="<?php echo $product['price']; ?>"/></td>

</tr>

<tr>

    <td align="right">商品分类：</td><td colspan="2"><?php echo lib_htmlmaker::help_html_select('class_id' , $adm['product_class'] , $product['class_id']); ?></td>

</tr>

<tr>

    <td align="right">商品材质：</td><td colspan="2"><?php echo lib_htmlmaker::help_html_select('material_id' , $adm['material'] , $product['material_id']); ?></td>

</tr>

<tr>

    <td align="right">商品产地：</td><td colspan="2"><?php echo lib_htmlmaker::help_html_select('map_id' , $adm['product_map'] , $product['map_id']); ?></td>

</tr>

<tr>

    <td align="right">数量：</td><td colspan="2"><input type="text" name="stock_num" value="<?php echo $product['stock_num']; ?>"/></td>

</tr>

<tr>

    <td align="right">可复制：</td><td colspan="2"><input type="checkbox" name="is_multi" value="1"<?php if($product['is_multi']){echo ' checked';} ?>/></td>

</tr>

<tr>

    <td align="right">宽：</td><td colspan="2"><input name="width" type="text" class="input01" id="ipt_width" onblur="cm2inch('width' , this.value)" value="<?php echo $product['width']; ?>"/> 

      厘米         

        <input name="width_inch" type="text" disabled class="input01" id="ipt_width_inch" value="<?php echo lib_math::length_cm2inch($product['width']); ?>"/> 

        英寸</td>

</tr>

<tr>

    <td align="right">高：</td><td colspan="2"><input name="height" type="text" class="input01" id="ipt_height" onblur="cm2inch('height' , this.value)" value="<?php echo $product['height']; ?>"/> 

      厘米         

        <input name="height_inch" type="text" disabled class="input01" id="ipt_height_inch" value="<?php echo lib_math::length_cm2inch($product['height']); ?>"/> 

        英寸</td>

</tr>

<tr>

    <td align="right">深：</td><td colspan="2"><input name="deep" type="text" class="input01" id="ipt_deep" onblur="cm2inch('deep' , this.value)" value="<?php echo $product['deep']; ?>"/> 

      厘米         

        <input name="deep_inch" type="text" disabled class="input01" id="ipt_deep_inch" value="<?php echo lib_math::length_cm2inch($product['deep']); ?>"/> 

        英寸</td>

</tr>

<tr>

    <td align="right">简介：</td><td colspan="2"><textarea name="intro" cols="40" rows="5" class="input03"><?php echo $product['intro']; ?></textarea></td>

</tr>

<tr>

    <td align="right">英文简介：</td><td colspan="2"><textarea name="eng_intro" cols="40" rows="5" class="input03"><?php echo $product['eng_intro']; ?></textarea></td>

</tr>

<tr><td colspan="3" align="center"><input type="submit" class="button01" value="保存"/> 

  |<a href="?page=photo_manage&id=<?php echo $product['id']; ?>" class="a"> 图片管理 </a>|<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="a"> 返回</a></td></tr>

</form>

</table>

<?php

}



function page_list_product($adm)

{

    $aStat = array(K_COMMON_NO=>'未发布' , K_COMMON_YES=>'已发布' , K_COMMON_LOCK=>'已售出');

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<script>

function checkAll($check) 

{ 

    var code_Values = document.getElementsByTagName("input"); 

    for(i = 0;i < code_Values.length;i++){ 

        if(code_Values[i].type == "checkbox") 

        { 

            code_Values[i].checked = $check; 

        } 

    } 

}

function checkFlip() 

{ 

    var code_Values = document.getElementsByTagName("input"); 

    for(i = 0;i < code_Values.length;i++){ 

        if(code_Values[i].type == "checkbox") 

        { 

            if(code_Values[i].checked == false )

                code_Values[i].checked = true; 

            else

                code_Values[i].checked = false; 

        } 

    } 

}

function batEditStat(stat)

{

    document.getElementById('hiddenStat').value=stat;

    document.getElementById('formBat').submit();

}

function batJump(action)

{

    document.getElementById('formBat').action=action;

    document.getElementById('formBat').submit();

}

</script>

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<?php if($adm['msg']){ ?>

<tr><td><?php echo $adm['msg']; ?></td></tr>

<?php } ?>

<tr>

<form method="GET" action=""><input type="hidden" name="page" value="list_product"/>

    <td align="right">商品编号：</td><td><input name="sn" type="text" class="input01" onclick="if(this.value=='输入商品编号'){this.value=''}" value="<?php echo $adm['sn']?$adm['sn']:'输入商品编号'; ?>" size="30"/> <input type="submit" class="button01" value="查看"/>(逗号分隔) <input type="checkbox" name="like" value="1"/>模糊搜索</td>

</form>

</tr>

<tr>

<form method="GET" action="">

    <td align="right">分类搜索：</td>

    <td>

    分类：<?php echo lib_htmlmaker::help_html_select('class_id' , $adm['product_class'] , $adm['class_id'] , true); ?>

    可复制:<input type="checkbox" name="is_multi" value="1" <?php if($adm['is_multi']){echo 'checked';} ?>/>

    状态：<?php echo lib_htmlmaker::help_html_select('stat' , $aStat  , $adm['stat'] , true); ?>

    <input type="hidden" name="page" value="list_product"/>

    <input type="submit" class="button01" value="查看"/>

    </td>

</form>

</tr>

</table>

<br/>

<?php

    if(count($adm['products']) > 0)

    {

?>



<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<form action="?api=bat_edit_stat" method="POST" id="formBat">

<tr>

    <td class="td_title" width="30px"><input type="checkbox" onclick="if(this.check==true){checkAll(false);this.check=false;}else{checkAll(true);this.check=true;}"></td>

    <td width="150" align="center" class="td_title"><strong>商品编号</strong></td>

    <td width="476" align="center" class="td_title"><strong>名称</strong></td>

    <td width="53" align="center" class="td_title"><strong>分类</strong></td>

    <td width="53" align="center" class="td_title"><strong>数量</strong></td>

    <td width="70" align="center" class="td_title"><strong>可复制</strong></td>

    <td width="120" align="center" class="td_title"><strong>图片</strong></td>

    <td width="200" align="center" class="td_title"><strong>状态</strong></td>

    <td width="120" align="center" class="td_title"><strong>价格</strong></td>

    <td width="53" align="center" class="td_title"><strong>详情</strong></td>

    <td width="53" align="center" class="td_title"><strong>预订</strong></td>

</tr>

<?php

    foreach ($adm['products'] as $row)

    { 

?>

<tr>

    <td align="center"><input type="checkbox" name="id[]" value="<?php echo $row['id']; ?>"/></td>

    <td align="center"><?php echo $row['sn']; ?></td>

    <td><?php echo $row['name']; ?></td>

    <td align="center"><?php echo $adm['product_class'][$row['class_id']]; ?></td>

    <td align="center"><?php echo $row['stock_num']; ?></td>

    <td align="center"><?php echo $row['is_multi']?'是':'否'; ?></td>

    <td align="center"><?php echo count($row['photo_list']); ?>张(<a href="?page=photo_manage&id=<?php echo $row['id']; ?>">管理</a>)</td>

    <td>

        <?php if($row['stat'] == K_COMMON_NO){?><font color="Maroon"><b>未发布</b></font><?php }else{ ?><a href="?api=edit_stat&id=<?php echo $row['id'];?>&stat=<?php echo K_COMMON_NO ?>">收回</a><?php } ?>|

        <?php if($row['stat'] == K_COMMON_YES){?><font color="Green"><b>已发布</b></font><?php }else{ ?><a href="?api=edit_stat&id=<?php echo $row['id'];?>&stat=<?php echo K_COMMON_YES ?>">发布</a><?php } ?>|

        <?php if($row['stat'] == K_COMMON_LOCK){?><font color="Gray"><b>已售出</b></font><?php }else{ ?><a href="?api=edit_stat&id=<?php echo $row['id'];?>&stat=<?php echo K_COMMON_LOCK ?>">售出</a><?php } ?>

    </td>

    <td align="center"><?php echo $row['price'] ?></td>

    <td align="center"><a href="?page=edit_product&id=<?php echo $row['id'] ?>">查看</a></td>

    <td align="center"><a href="/__admin/page/adm_product_buy.php?page=list_by_pid&pid=<?php echo $row['id'] ?><?php if($row['stat'] == K_COMMON_LOCK){echo '&over=1';} ?>" target="_blank">查看</a></td>

</tr>

<?php } ?>

<tr><td colspan="11" align="right"><?php echo get_page($adm['total'] , 10 , $adm['page']); ?><input type="hidden" name="stat" id="hiddenStat" value=""/>

<a href="#" onclick="batJump('?page=bat_photo');">上传图片</a>

<input type="button" style="color:Maroon;" onclick="batEditStat('<?php echo K_COMMON_NO; ?>');" value="收回"/>

<input type="button" style="color:Green;" onclick="batEditStat('<?php echo K_COMMON_YES; ?>');" value=" 发 布 "/>

<input type="button" style="color:Gray;" onclick="batEditStat('<?php echo K_COMMON_LOCK; ?>');" value="售出"/>



</td></tr>

</form>

</table>

<?php 

    }

 ?>

<?php

}



function page_photo_manage($adm)

{

    $product = $adm['product'];

    $aPhoto = $product['photo_list'];

    

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<tr><td colspan="2" class="td_title"><div style="float:right;">

<?php if($product['stat'] == K_COMMON_NO){ ?>

<a href="?api=edit_stat&id=<?php echo $product['id'];?>&stat=<?php echo K_COMMON_YES ?>"><b>发布</b></a>

<?php }else if($product['stat'] == K_COMMON_YES){ ?>

<a href="?api=edit_stat&id=<?php echo $product['id'];?>&stat=<?php echo K_COMMON_NO ?>">收回</a>

<?php } ?>

<a href="?page=edit_product&id=<?php echo $product['id']; ?>"> 返回查看产品信息</a> &nbsp;</div><b>上传照片 <?php echo $product['name'].' ('.$product['sn'].')'; ?></b></td></tr>

<form method="POST" enctype="multipart/form-data" action="?api=upload_photo&product_id=<?php echo $adm['product_id'] ?>">

<?php for($i=1;$i<=10;$i++){ ?>

<tr>

    <td align="center">#<?php echo $i; ?> <input name="file_<?php echo $i; ?>" type="file" class="input01"/></td>

    <?php $i++; ?>

    <td align="center">#<?php echo $i; ?> <input name="file_<?php echo $i; ?>" type="file" class="input01"/></td>

</tr>

<?php } ?>

<tr><td colspan="2" align="center"><input type="submit" class="button01" value="上传"/></td></tr>

</form>

</table>

<br />



<table width="98%" border="0" align="center" cellspacing="0" class="adminlist2">

  <?php 

    $iCount = count($aPhoto);

    if($iCount > 0)

    { 

?>

  <tr>

<?php

        $i = 1;

        foreach ($aPhoto as $photo_id => $row)

        {

?>

<td bgcolor="#FFFFFF" <?php if($row['is_cover'] == 1){echo 'class="border" border="1"';} ?>>

<img src="<?php echo pid2path($product['id'] , $photo_id , true) ?>" width="200px"/><br/>

<?php if($row['is_cover'] != 1){ ?>

    <a href="?api=delete_photo&id=<?php echo $adm['product_id']; ?>&pid=<?php echo $photo_id; ?>" class="a">删除 </a>| <a href="?api=set_cover&id=<?php echo $adm['product_id']; ?>&pid=<?php echo $photo_id; ?>" class="a">设置为封面</a>

<?php }else{ ?>

封面

<?php } ?>

|<a href="<?php echo pid2path($product['id'] , $photo_id , true) ?>" target="_blank" class="a"> 大图</a>

</td>

<?php

            if($i%3 == 0)

            {

                echo $i == $iCount ? "</tr>" : "<tr/><tr>";

            }

            $i++;

        }

    }

?>

</table>

<?php

}



function page_zip_photo($adm)

{

?>

<form method="POST" action="?api=zip_photo" enctype="multipart/form-data">

<input type="file" name="zip"/>

<input type="submit" value="上传"/>

</form>



<?php

if($adm['is_zip_photo']){

?>

<a href="?api=zip_photo">处理doing.zip</a>

<?php

}

}



function page_bat_photo($adm)

{

    foreach ($adm['rows'] as $row)

        $ids .= $row['id'].',';

        

    $ids = trim($ids , ',');

?>

<link href="../css.css" rel="stylesheet" type="text/css">

<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">

<form method="POST" enctype="multipart/form-data" action="?api=bat_photo">

<input type="hidden" name="ids" value="<?php echo $ids; ?>"/>

<tr>

    <th>产品编码</th>

    <th>名称</th>

    <th>图片</th>

</tr>

<?php

    foreach ($adm['rows'] as $product){

?>

<tr>

    <td><?php echo $product['sn']; ?></td>

    <td><?php echo $product['name']; ?></td>

    <td><input type="file" name="file_<?php echo $product['id']; ?>"/></td>

</tr>

<?php } ?>

<tr><td colspan="3"><input type="submit" value="上传"/></td></tr>

</form>

</table>

<?php

}



function page_tar_upload($adm)

{

?>

<link href="../css.css" rel="stylesheet" type="text/css">



<table class="adminlist" width="70%">

<tr>

<th>文件名</th>

<th>容量</th>

<th>操作</th>

</tr>

<?php if(count($adm['tar_list'])>0){ 

    foreach ($adm['tar_list'] as $row){?>

<tr>

<td><?php echo $row['name']?></td>

<td><?php echo $row['size']?></td>

<td><a href="?page=tar_exec&file=<?php echo $row['name'].'&'.time(); ?>">处理</a>|<a href="?api=tar_delete&file=<?php echo $row['name']; ?>">删除</a></td>

</tr>

<?php } }else{ ?>

<tr><td colspan="3">无</td></tr>

<?php } ?>

</table>

<br>

<br>

<hr>

<br>

<br>

<table class="adminlist">

<form enctype="multipart/form-data" action="?api=tar_upload" method="POST">

<?php for($i = 0;$i<3;$i++){ ?>

<tr>

<td>#<?php echo $i; ?></td>

<td><input type="file" name="tar_<?php echo $i; ?>"/></td>

</tr>

<?php } ?>

<tr><td><input type="submit" value="上传"/></td></tr>

</form>

</table>

<?php

}



function page_tar_exec($adm)

{

?>

运行结果:<br/>

<?php echo $adm['rs']; ?>

<table class="adminlist">

<tr><td>是否删除该数据包?</td></tr>

<tr><td><a href="?api=tar_delete&file=<?php echo $adm['file'];?>">现在删除</a> &nbsp;&nbsp;<a href="?page=tar_upload">返回</a></td></tr>

</table>

<?php

}

?>