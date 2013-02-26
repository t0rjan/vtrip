<?php
function page_notice($adm)
{
?>
<table class="adminlist" width="100%">
<form action="?api=notice" enctype="multipart/form-data" method="POST">
<tr>
    <th width="100%" style="text-align:center;" colspan="2">发布通知</th>
</tr>
<tr>
    <td align="center" width="25%"style="padding:10px"><img src="/data/notice.jpg?<?php echo time(); ?>"/><br/><input type="file" name="pic"/></td>
    <td width="75%" style="padding:10px">中文:<br/><textarea name="notice" style="width:100%" rows="4"><?php echo $adm['notice']; ?></textarea><br/>
        英文:<br/><textarea name="notice_eng" style="width:100%" rows="4"><?php echo $adm['notice_eng']; ?></textarea>
    </td>
</tr>
<tr><td align="center" colspan="2" width="100%"><input type="submit" value="保存    "/></td></tr>
</form>
</table>
<?php
}
?>


<?php
function page_suggestlist($adm)
{
?>
<table class="adminlist" width="100%">

<tr>
    <th width="50px">用户</th>
    <th>意见</th>
    <th width="70px">时间</th>
    <th width="100px">操作</th>
</tr>
<?php foreach ($adm['suggestlist'] as $row) {  ?>
<tr>
    <td></td>
    <td><?php echo Tool_string::un_html($row['content']); ?>(<a href="<?php echo $row['page_url']; ?>"><?php echo $row['page_url'] ?></a>)</td>
    <td><?php echo $row['ctime']; ?></td>
    <td>
        <form action="?api=suggestreply&id=<?php echo $row['id']; ?>" method="post">
            回复：<input type="text" name="reply" value="<?php echo Tool_string::un_html($row['adm_reply']); ?>"/>
            <input type="submit" name="回复"/>
        </form>
    </td>
</tr>
<?php } ?>
<tr>
    <td colspan=4><?php  echo ml_tool_admin_view::get_page($adm['total'] ,20 , $adm['page']);  ?></td>
</tr>
</table>
<?php
}


function page_commentlist($adm)
{
?>
<table class="adminlist" width="100%">

<tr>
    <th width="50px">用户</th>
    <th>意见</th>
    <th width="70px">时间</th>
    <th width="100px">操作</th>
</tr>
<?php foreach ($adm['list'] as $row) {  ?>
<tr>
    <td></td>
    <td><?php echo Tool_string::un_html($row['content']); ?></td>
    <td><?php echo $row['ctime']; ?></td>
    <td>
        <a href="http://meila.com/goods/<?php echo $row['rid']; ?>" target="_blank">查看</a>
        <!--<form action="?api=suggestreply&id=<?php echo $row['id']; ?>" method="post">
            回复：<input type="text" name="reply" value="<?php echo Tool_string::un_html($row['adm_reply']); ?>"/>
            <input type="submit" name="回复"/>
        </form>-->
    </td>
</tr>
<?php } ?>
<tr>
    <td colspan=4><?php  echo ml_tool_admin_view::get_page($adm['total'] ,20 , $adm['page']);  ?></td>
</tr>
</table>
<?php
}
