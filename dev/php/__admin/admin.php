<?php
/************************************
*   熊店
*   @file:/server/bearshop/__admin/admin.php
*   @intro:
*   @author:new_shop
*   @email:new_shop@163.com    
*   @date:Wed Feb 10 08:32:36 CST 2010
************************************/

include('./__global.php');

class adm_admin extends admin_ctrl 
{
    public function run()
    {
        echo 'admin';
    }
}
new adm_admin();
?>