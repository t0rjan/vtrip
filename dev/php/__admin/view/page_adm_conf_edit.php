<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/view/page_adm_comment.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Sat Feb 20 22:21:24 CST 2010
************************************/
$_url = "?conf=".$adm['conf'];

function page_index($adm)
{
    global $_url;
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table width="100%" border="0" class="adminlist2" cellpadding="0" cellspacing="0">

<tr>
<td valign="top" align="left"><?php
if(count($adm['list'])>0)
{
?>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist">
    <?php
        foreach ($adm['list'] as $id =>$row)
        {
            $nSub = count($row['sub']);
    ?>
    <tr>
      <td>&nbsp;</td>
    <td><strong><?php echo $row['row']['name'] ?></strong> ( <span class="font12"><?php echo $row['row']['eng_name']; ?></span> )</td>
    <td width="114"><?php  ?><a href="<?php echo $_url; ?>&page=edit&id=<?php echo $id; ?>" target="op_iframe">[编辑]</a></td>
    <td width="114"><?php if($nSub == 0 ){ ?><a href="<?php echo $_url; ?>&api=del&id=<?php echo $id; ?>">[删除]</a><?php } ?></td>
    </tr>
    <?php
        if($nSub > 0){
            foreach ($row['sub'] as $id => $sub_row){
    ?>
    <tr>
      <td width="184">&nbsp;</td>
    <td width="729">└ <?php echo $sub_row['row']['name'] ?> (<span class="font12"><?php echo $sub_row['row']['eng_name']; ?></span>)</td>
    <td><?php  ?><a href="<?php echo $_url; ?>&page=edit&id=<?php echo $id; ?>" target="op_iframe">[编辑]</a></td>
    <td><a href="<?php echo $_url; ?>&api=del&id=<?php echo $id; ?>">[删除]</a></td>
    </tr>
    <?php
            }
        }
    ?>
    
    
    <?php
        }
    ?>
    </table>
<?php
}
else 
{
    echo '无';
}
?>
    
    
</td>
<td width="300px" valign="top" align="left">
<iframe name="op_iframe" id="op_iframe" height="300px" frameborder="0" width="100%" src="<?php echo $_url ?>&page=add"></iframe>
</td>
</tr>
<?php
}

function page_add($adm)
{
    global $_url;
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table class="adminlist" border="0" cellpadding="0" cellspacing="0" width="280">
<tr><td colspan="2" align="center" class="td_title"><strong>添加分类</strong></td>
</tr>
<form action="<?php echo $_url; ?>&api=add" method="POST" target="_parent">
<tr>
  <td width="80" align="right">父分类：</td>
  <td width="194" valign="top"><select name="parent_id" class="input01">
    <option value="0">顶级分类</option>
    <?php
if(count($adm['top_list'])>0)
{
    foreach ($adm['top_list'] as $k =>$row)
    {
?>
    <option value="<?php echo $k ?>"><?php echo $row['name']; ?></option>
    <?php
    }
}
?>
  </select></td>
</tr>

<tr>
  <td align="right">名称：</td>
  <td valign="top"><input name="name" type="text" class="input01" id="input_name" /></td>
</tr>
<tr>
  <td align="right">英文名称：</td>
  <td valign="top"><input name="eng_name" type="text" class="input01" id="input_eng_name" /></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" class="button01" value="保存"/></td>
</tr></form>
</table>
<?php
}

function page_edit($adm)
{
    global $_url;
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table class="adminlist" border="0" cellpadding="0" cellspacing="0" width="280">
<tr><td colspan="2" align="center" class="td_title"><strong>添加分类</strong></td>
  </tr><form action="<?php echo $_url; ?>&api=edit&id=<?php echo $adm['id'] ?>" method="POST" target="_parent">
<tr>
  <td align="right" width="80px">父分类：</td>
  <td><select name="parent_id" class="input01">
    <option value="0"<?php echo $adm['class_info']['parent_id'] == 0 ? ' selected' : ''; ?>>顶级分类</option>
    <?php
if(count($adm['top_list'])>0)
{
    foreach ($adm['top_list'] as $k =>$row)
    {
        if($k == $adm['id'])
            continue;
?>
    <option value="<?php echo $k ?>"<?php echo $adm['class_info']['parent_id'] == $k ? ' selected' : ''; ?>><?php echo $row['name']; ?></option>
    <?php
    }
}
?>
  </select></td>
</tr>
<tr>
  <td align="right">名称：</td>
  <td><input name="name" type="text" class="input01" id="input_name" value="<?php echo $adm['class_info']['row']['name']; ?>" /></td>
</tr>
<tr>
  <td align="right">英文名称：</td>
  <td><input name="eng_name" type="text" class="input01" id="input_eng_name" value="<?php echo $adm['class_info']['row']['eng_name']; ?>" /></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" class="button01" value="保存"/> 
  <a href="<?php echo $_url; ?>&page=add">取消</a>
</td>
</tr></form>
</table>
<?php
}
?>