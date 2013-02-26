<?php
function page_findbykeys($adm)
{
?>

<table class="adminlist" width="100%">
<tr>
    <th><?php echo $adm['tag'].' 共'.$adm['total']; ?></th>
</tr>
<tr>
<td>
<form action="?" method="get">
<input type="hidden" name="page" value="findbykeys"/>
关键字:<input type="text" name="tag" value="<?php echo $adm['tag']; ?>"/>
地区:<input type="text" name="area" value="<?php echo $adm['area']; ?>"/>
商城：<input type="checkbox" name="mall_item" value=1 <?php echo $adm['mall_item']?'checked':''; ?>/>
价格:<input type="text" name="minprice" value="<?php echo $adm['minprice']; ?>"/>
~~<input type="text" name="maxprice" value="<?php echo $adm['maxprice']; ?>"/>
排序：<select name="sort">
<option value="price_desc"<?php echo $adm['sort']=='price_desc'?' selected':''; ?>>价格降序</option>
<option value="price_asc"<?php echo $adm['sort']=='price_asc'?' selected':''; ?>>价格升序</option>
<option value="credit_desc"<?php echo $adm['sort']=='credit_desc'?' selected':''; ?>>信用降序</option>
<option value="credit_asc"<?php echo $adm['sort']=='credit_asc'?' selected':''; ?>>信用升序</option>
<option value="commissionRate_desc"<?php echo $adm['sort']=='commissionRate_desc'?' selected':''; ?>>佣金比降序</option>
<option value="commissionRate_asc"<?php echo $adm['sort']=='commissionRate_asc'?' selected':''; ?>>佣金比升序</option>
<option value="commissionNum_desc"<?php echo $adm['sort']=='commissionNum_desc'?' selected':''; ?>>成交量降序</option>
<option value="commissionNum_asc"<?php echo $adm['sort']=='commissionNum_asc'?' selected':''; ?>>成交量升序</option>
<option value="delistTime_desc"<?php echo $adm['sort']=='delistTime_desc'?' selected':''; ?>>下架时间</option>

</select>

<input type="submit" value="查询"/>
</form>
</td>
</tr>
</table>
<br/>
<br/>
<br/>
<form action="?api=add_autofetch" method="post">
<table class="adminlist" width="100%">
<tr>
    <th width="150px">图片</th>
    <th width="300px">商品名</th>
    <th>价格</th>
    <th>返现</th>
    <th>返现率</th>
    <th>成交量</th>
    <th>所在地</th>
    <th>操作</th>
</tr>
<tr>
    <td colspan="5"><?php  echo ml_tool_admin_view::get_page($adm['total'] , 50 , $adm['page'] , $adm['pager_url']);  ?></td>
    <td colspan=4>
        <input type="submit" value="保存并跳一下页">
    </td>
</tr>
<?php
$i = 0;
    foreach($adm['goods'] as $gd)    
    {

?>

<tr class="tb_row">
    <td><img src="<?php echo $gd['pic_url']; ?>" width="250px"/></td>
    <td><a href="<?php echo $gd['click_url']; ?>" target="_blank"><?php echo $gd['title']; ?></a></td>
    <td><?php echo $gd['price']; ?></td>
    <td><?php echo $gd['commission']; ?></td>
    <td><?php echo ((int)$gd['commission_rate']/100); ?>%</td>
    <td><?php echo $gd['volume']; ?></td>
    <td><?php echo $gd['item_location']; ?></td>
    <td>
        <input type="hidden" name="v[<?php echo $i; ?>][url]" value="<?php echo $gd['click_url']; ?>">
        <input type="hidden" name="v[<?php echo $i; ?>][iid]" value="<?php echo $gd['num_iid']; ?>">
        <div bg="#ff0000">
        <label for="check_<?php echo $i; ?>">抓取</label><input id="check_<?php echo $i; ?>" type="checkbox" name="v[<?php echo $i; ?>][ischeck]" value="1"><br/>
        标签：<input type="text" name="v[<?php echo $i; ?>][tag]">
        </div>
    </td>
</tr>
<?php 
$i++;
}

 ?>
<tr>
    <td colspan="5"><?php  echo ml_tool_admin_view::get_page($adm['total'] , 50 , $adm['page'] , $adm['pager_url']);  ?></td>
    <td colspan=4>
        <input type="submit" value="保存并跳一下页">
    </td>
</tr>
</table>
</form>

<script type="text/javascript">
{
    $('.tb_row').click(function(){
        if(!$(this).find('input[type=checkbox]').attr('checked'))
        {
            $(this).find('input[type=checkbox]').attr('checked','true');
            $(this).css('background','pink');
        }
        else
        {
            $(this).find('input[type=checkbox]').removeAttr("checked");
            $(this).css('background','white');
        }
    })
}
</script>
<?php
}
?>