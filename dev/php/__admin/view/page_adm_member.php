<script language="javascript">
function show_create_user_iframe($id)
{
    var oTd = document.getElementById('td_cuser_'+$id);
    if(oTd.style.display=='none')
    {
        oTd.innerHTML = '<iframe frameborder="0" width="100%" height="300px" src="/__admin/page/adm_member.php?page=create_member&id='+$id+'"></iframe>';
        oTd.style.display='';
    }
    else
        oTd.style.display='none';
}
</script>
<?php


/**
 * 申请列表
 *
 * @param array $adm
 */
function page_apply_list($adm)
{
    if(count($adm['apply_list']) > 0)
    {
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<form method="POST" action="?api=op_apply">
<table class="adminlist" border="0" cellspacing="0" width="800px">
<tr>
    <td width="716">&nbsp;
      <?php if($adm['stat'] == K_COMMON_NO){?>
        新申请 | <a href="?page=apply_list&stat=<?php echo K_COMMON_YES ?>">待审核</a>
    <?php }else{?>
        <a href="?page=apply_list&stat=<?php echo K_COMMON_NO ?>">新申请</a> | 待审核
    <?php } ?></td>
    <td align="center" width="80"><input type="submit" class="button01" value="全部保存"/></td>
</tr>
</table>
<?php
    $i = 0;
        foreach ($adm['apply_list'] as $row)
        {
            if($i != 0)
                echo '<br/>';
            $i = 1;
?>

<table class="adminlist" width="800px" border="0" cellspacing="0" >
<tr>
    <td width="100%" align="right"><a href="#" onclick="show_create_user_iframe(<?php echo $row['id']; ?>);return false;">创建会员帐号</a><input type="radio" id="del_<?php echo $row['id'] ?>" name="op[<?php echo $row['id'] ?>]" value="0" checked><label for="del_<?php echo $row['id'] ?>">删除</label><?php if($adm['stat'] == K_COMMON_NO){?> <input type="radio" id="bak_<?php echo $row['id'] ?>" name="op[<?php echo $row['id'] ?>]" value="1"><label for="bak_<?php echo $row['id'] ?>">以后处理</label><?php } ?></td>
</tr>
<tr>
<td>
    <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
        <td width="200px"><b>称呼：</b><?php echo K_str::un_html($row['name']) ?></td>
        <td><b>电话：</b><?php echo K_str::un_html($row['mobile']) ?></td>
    </tr>
    <tr>
        <td><b>传真：</b><?php echo K_str::un_html($row['fax']) ?></td>
        <td><b>邮件：</b><?php echo K_str::un_html($row['email']) ?></td>
    </tr>
    <tr>
        <td colspan="2"><b>公司：</b><?php echo K_str::un_html($row['company']) ?></td>
    </tr>
    <tr>
        <td colspan="2"><b>留言：</b><?php echo K_str::un_html($row['message']) ?></td>
    </tr>
    </table>
</td>
</tr>
<tr>
    <td colspan="2" id="td_cuser_<?php echo $row['id']; ?>" style="display:none">&nbsp;</td>
</tr>
    <table>
<?php
        }
?>
<table class="adminlist2" border="0" cellspacing="0" width="800px">
<tr>
    <td width="717">&nbsp;</td>
    <td width="79"><input type="submit" class="button01" value="全部保存"/></td>
</tr>
</table>
</form>
<?php
    }
    else 
    {
?>
<table class="adminlist" border="0" cellspacing="0" width="800px">
<tr>
    <td><?php if($adm['stat'] == K_COMMON_NO){?>
        新申请 | <a href="?page=apply_list&stat=<?php echo K_COMMON_YES ?>">待审核</a>
    <?php }else{?>
        <a href="?page=apply_list&stat=<?php echo K_COMMON_NO ?>">新申请</a> | 待审核
    <?php } ?></td>
    <td align="right" width="20"></td>
</tr>
<tr><td height="100px" align="center"><b>无数据</b></td>
<td></td>
</tr>
</table>
<?php
    }
}
/**
 * 创建用户表单
 *
 * @param unknown_type $adm
 */
function page_create_member($adm)
{
    global $K_ARRAY_USER_LEVEL;
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table border="0" width="100%">
<form method="POST" action="/__admin/page/adm_member.php?page=do_create_member">
<tr><td valign="top" width="50%">

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist2">
    <tr>
        <td align="right">登录名：</td>
        <td><input name="username" type="text" class="input01" value="<?php echo $adm['username']; ?>"/></td>
    </tr>
    <tr>
        <td align="right">密码：</td>
        <td><input name="password" type="text" class="input01" value="<?php echo $adm['password']; ?>"/></td>
    </tr>
    <tr>
        <td align="right">级别：</td>
        <td>
            <select name="level" class="input01">
                <?php foreach ($K_ARRAY_USER_LEVEL as $key => $value){ ?>
                <option value="<?php echo $key ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr><td align="right">即时启用：</td><td><input type="checkbox" name="stat" value="1" checked/></td></tr>
</table>

</td>
<td width="50%" valign="top">

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist2">
<tr><td height="30" align="right">称呼：</td><td><input name="name" type="text" class="input01" value="<?php echo $adm['userinfo']['name'] ?>"/></td></tr>
<tr><td height="30" align="right">电话：</td><td><input name="mobile" type="text" class="input01" value="<?php echo $adm['userinfo']['mobile'] ?>"/></td></tr>
<tr><td height="30" align="right">邮件：</td><td><input name="email" type="text" class="input01" value="<?php echo $adm['userinfo']['email'] ?>"/></td></tr>
<tr><td height="30" align="right">传真：</td><td><input name="fax" type="text" class="input01" value="<?php echo $adm['userinfo']['fax'] ?>"/></td></tr>
<tr><td height="30" align="right">公司：</td><td><input name="company" type="text" class="input01" value="<?php echo $adm['userinfo']['company'] ?>"/></td></tr>
<tr><td height="54" align="right">备注：</td><td><textarea name="intro" rows="3" class="input03" ></textarea></td></tr>
</table>
<input type="hidden" name="apply_id" value="<?php echo $adm['apply_id']; ?>"/>
</td>
</tr>
<tr><td height="39" colspan="2" align="center"><input type="submit" class="button01" value="保存"/></td></tr>
</form>
</table>
<?php
}
/**
 * 用户列表
 *
 * @param unknown_type $adm
 */
function page_list($adm)
{
    global $K_ARRAY_USER_LEVEL;
    
    if(count($adm['user_list'])>0)
    {
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table width="98%" border="0" align="center" cellspacing="0" class="adminlist">
<tr>
<td width="6%" align="center" class="td_title"><strong>#</strong></td>
<td width="18%" align="center" class="td_title"><strong>称呼</strong></td>
<td width="18%" align="center" class="td_title"><strong>登录名</strong></td>
<td width="13%" align="center" class="td_title"><strong>级别</strong></td>
<td width="80px" align="center" class="td_title"><strong>详细信息</strong></td>
<td width="60px" align="center" class="td_title"><strong>禁用</strong></td>
</tr>
<?php
        foreach ($adm['user_list'] as $user)
        {
?>
<tr>
    <td align="center"><?php echo $user['id']; ?></td>
    <td align="center"><?php echo $user['name']; ?></td>
    <td align="center"><?php echo $user['username']; ?></td>
    <td align="center"><?php echo $K_ARRAY_USER_LEVEL[$user['level']]; ?></td>
    <td align="center"><a href="?page=show_member&uid=<?php echo $user['id']; ?>"><b>查看</b></a> <a href="/__admin/page/adm_product_buy.php?page=list_by_uid&uid=<?php echo $user['id']; ?>">预订</a>(<?php echo $user['cnt_order'] ?>) <a href="/__admin/page/adm_product_buy.php?page=list_buy_by_uid&uid=<?php echo $user['id']; ?>">购买</a>(<?php echo $user['cnt_buy'] ?>)</td>
    <td align="center">
      <?php if($user['stat'] == K_COMMON_NO){ ?>
        <font color="Red">封杀</font>[<a href="?api=edit_member_stat&id=<?php echo $user['id']; ?>&v=<?php echo K_COMMON_YES ?>">启用</a>]
      <?php }else{ ?>
        <font color="Green">正常</font>[<a href="?api=edit_member_stat&id=<?php echo $user['id']; ?>&v=<?php echo K_COMMON_NO ?>">禁用</a>]
      <?php } ?>
    </td>
    
</tr>
<?php
        }
?>
<tr>
<?php echo get_page($adm['total'] , 50 , $adm['page']); ?>
</tr>
</table>
<?php
    }
}
/**
 * 修改用户表单
 *
 * @param unknown_type $adm
 */
function page_show_member($adm)
{
    global $K_ARRAY_USER_LEVEL;
?>
<link href="../css.css" rel="stylesheet" type="text/css" />
<table width="38%" height="350" border="0" cellspacing="0" class="adminlist">
<form method="POST" action="?api=edit_member&uid=<?php echo $adm['userinfo']['uid']; ?>">
<tr>
    <td width="23%" align="right">称呼：</td>
    <td width="77%"><input name="name" type="text" class="input01" value="<?php echo $adm['userinfo']['name']; ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?page=show_email&uid=<?php echo $adm['userinfo']['uid'] ?>">查看邮件</a></td>
</tr>
<tr>
    <td align="right">登录名：</td>
    <td><input type="text" name="username" class="input01" value="<?php echo $adm['userinfo']['username']; ?>"/>(<?php echo $adm['userinfo']['_meta_password']; ?>)</td>
</tr>
<tr>
    <td align="right">级别：</td>
    <td><select name="level" class="input01">
                <?php foreach ($K_ARRAY_USER_LEVEL as $key => $value){ ?>
                <option value="<?php echo $key ?>"<?php echo $key == $adm['userinfo']['level']?' selected':''; ?>><?php echo $value; ?></option>
                <?php } ?>
            </select></td>
</tr>

<tr>
    <td align="right">电话：</td>
    <td><input name="mobile" type="text" class="input01" value="<?php echo $adm['userinfo']['mobile']; ?>"/></td>
</tr>
<tr>
    <td align="right">传真：</td>
    <td><input name="fax" type="text" class="input01" value="<?php echo $adm['userinfo']['fax']; ?>"/></td>
</tr>
<tr>
    <td align="right">邮件:</td>
    <td><input name="email" type="text" class="input01" value="<?php echo $adm['userinfo']['email']; ?>"/></td>
</tr>
<tr>
    <td align="right">公司：</td>
    <td><input name="company" type="text" class="input01" value="<?php echo $adm['userinfo']['company']; ?>"/></td>
</tr>
<tr>
    <td align="right">备注：</td>
    <td><textarea name="intro" class="input03" ><?php echo $adm['userinfo']['intro'] ?></textarea></td>
</tr>
<tr>
    <td align="right">启用：</td>
    <td><input type="checkbox" name="stat"<?php echo $adm['userinfo']['stat'] == K_COMMON_YES ? ' checked' : ''; ?> value="<?php echo K_COMMON_YES; ?>"></td>
</tr>
<tr>
    <td colspan="2" align="center">
    最后登录IP：<?php echo $adm['userinfo']['last_login_ip']; ?> <a href="http://www.ip138.com/ips.asp?ip=<?php echo $adm['userinfo']['last_login_ip']; ?>&action=2" target="_blank">查看</a>
    
    <input type="hidden" name="referer" value="<?php echo $_SERVER['HTTP_REFERER'] ?>"/>
    </td>
</tr>
<tr>
    <td colspan="2" align="center"><input type="submit" class="button01" value="保存"/> <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">取消</a></td>
</tr>
</form>
</table>
<br />

<table width="38%" border="0" cellpadding="0" cellspacing="0" class="adminlist">
<form method="POST" action="?api=edit_password&uid=<?php echo $adm['userinfo']['uid']; ?>">
<tr>
<td align="right">设置密码：</td><td><input name="password" type="password" class="input01"/> <input type="submit" class="button01" value="保存"/></td>
</tr>
</form>
</table>
<br />
<!--
<hr/>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist">
<tr><td class="td_title"><strong>产地 : 分类 : 材质</strong></td><td class="td_title"><strong>产品</strong></td></tr>
<tr>
<td><?php echo is_array($adm['history']['type']) ? implode('<br/>' , $adm['history']['type']) : '无'; ?></td>
<td><?php echo is_array($adm['history']['product']) ? implode('<br/>' , $adm['history']['product']) : '无'; ?></td>
</tr>
</table>
-->
<?php
}
?>