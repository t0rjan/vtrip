<?php
function page_htmlblock_list($adm)
{
?>
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
    <th>#</th>
    <th>名称(英文)</th>
    <th>内容</th>
    <th>页面</th>
    <th>说明</th>
    <th>操作</th>
</tr>
<?php foreach ($adm['list'] as $row) { ?>
<tr>
    <form action="?api=htmlblock_update" method="post">
    <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo $row['id']; ?>"/></td>
    <td><input type="text" name="name" value="<?php echo $row['name']; ?>"/></td>
    <td><textarea name="content"><?php echo $row['content']; ?></textarea></td>
    <td><input type="text" name="page" value="<?php echo $row['page']; ?>"/></td>
    <td><input type="text" name="comment" value="<?php echo $row['comment']; ?>"/></td>
    
    <td><input type="submit" value="保存"/> <a href="?api=htmlblock_publish&id=<?php echo $row['id']; ?>"><font color="red">发布到前端</font></a></td>
    </form>
</tr>
<?php } ?>
<tr><td colspan="5">--------新建---------</td></tr>
<tr>
    <form action="?api=htmlblock_add" method="post">
    <td>#</td>
    <td><input type="text" name="name" value=""/></td>
    <td><textarea name="content"></textarea></td>
    <td><input type="text" name="page" value=""/></td>
    <td><input type="text" name="comment" value=""/></td>
    <td><input type="submit" value="保存"/></td>
    </form>
</tr>
</table>
<?php

}

?>