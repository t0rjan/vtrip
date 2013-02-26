<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/admin.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Wed Feb 10 08:32:36 CST 2010
************************************/

include('../__global.php');

class adm_admin extends admin_ctrl 
{
    public function run()
    {
    }
}
new adm_admin();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录管理后台</title>
<link href="./css.css" rel="stylesheet" type="text/css">
<link href="/data/css/superfish.css" rel="stylesheet" type="text/css">
<link href="/data/css/superfish-vertical.css" rel="stylesheet" type="text/css">
<link href="/data/css/superfish-navbar.css" rel="stylesheet" type="text/css">

</head>
<body>
<table width="100%" height="100%">
    <tr>
        <td>
            
        </td>
        <td width="200px" valign="top" align="center">
            <table border="1px" width="100%">
                <tr>
                    <th>服务器信息</th>
                </tr>
                <tr>
                    <td>PHP:<?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <td>MYSQL:<?php echo phpversion(); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>