<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/index.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Sun Feb 14 22:31:54 CST 2010
************************************/

include('__global.php');

class adm_index extends admin_ctrl
{
    public function run()
    {
        
        
    }
}
$o = new adm_index();
$is_admin = true;//$o->get_session('level') == K_ADM_LV_ADMINISTRATOR;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录管理后台</title>
<link href="./css.css" rel="stylesheet" type="text/css">
<script>
function hideMenu()
{
    if(document.getElementById('tdMenu').style.display=='none')
        document.getElementById('tdMenu').style.display='';
    else
        document.getElementById('tdMenu').style.display='none';
}
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist">
<!-- header -->
<tr>
  <th height="72" colspan="3" class="fonttitle"> <span style="float:right"><a href="/__admin/logout.php" class="a">退出系统</a></span><span class="f18">网站后台管理系统</span><br>
    <span class="f10"> Web background management system</span></th>
  </tr>

<tr>
  <td width="158" valign="top" id="tdMenu">
  
  <table width="150" border="0" align="center" cellpadding="0" cellspacing="0" class="adminlist">
    
  <tr><td width="912" align="center" class="td_title"><strong>首页</strong></td></tr>
  <tr><td align="center" class="td_title"><strong>产品管理</strong></td></tr>
  <tr><td align="center">
    <table width="100%" border="0">
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_taobao.php?page=findbykeys" target="admin_iframe">淘宝</a></td></tr>
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_tags.php" target="admin_iframe">标签管理</a></td></tr>
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_goodsaudit.php" target="admin_iframe">商品审核</a></td></tr>
      
      </table>
  </td></tr>
  
  <tr><td align="center" class="td_title"><strong>用户管理</strong></td></tr>
  <tr><td align="center">
    <table width="100%" border="0">
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_member.php?page=apply_list" target="admin_iframe">处理会员申请</a></td></tr>
      </table>
  </td></tr>


  <tr><td align="center" class="td_title"><strong>基本运营管理</strong></td></tr>
  <tr><td align="center">
    <table width="100%" border="0">
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_op.php?page=htmlblock_list" target="admin_iframe">表态页面片段</a></td></tr>
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_home.php?page=suggestlist" target="admin_iframe">用户反馈收集</a></td></tr>
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_home.php?page=commentlist" target="admin_iframe">评论收集</a></td></tr>
      </table>
  </td></tr>
  
    <tr><td align="center" class="td_title"><strong>服务状态</strong></td></tr>
  <tr><td align="center">
    <table width="100%" border="0">
      <tr><td width="100%" align="center"><a href="/__admin/page/adm_home.php?page=queueStat" target="admin_iframe">队列状态</a></td></tr>
      </table>
  </td></tr>
  
   
  </table>
  </td>
  <td bgcolor="#ff0000;" width="10" style="background:url(./data/image/adm_sep.gif) repeat-y;"><a href="#" onclick="hideMenu();"><img src="./data/image/adm_sep.gif"/></a></td>
  <td><iframe name="admin_iframe" id="admin_iframe" src="/__admin/page/adm_home.php" width="100%" height="800px" frameborder="0"></iframe></td>
</tr>

</table>
</body>
</html>